/* global updateFormAjaxValidation */
/* eslint no-underscore-dangle: ["error", { "allow": ["_token"] }] */
window.tcp = (($) => {
    const api = {};

    /**
     * XHR: Handle sending http requests to the server, and parse their responses.
     * Actually acts as a wrapper around jquery's $.ajax.
     */
    api.xhr = (() => {
        const xhrApi = {};
        const translations = {
            serverBadResponse: 'The server did not respond correctly to the request.',
            serverRequestFailed: 'The server server reported a failure, but provided no more information.',
            serverResponseNotJson: 'The server did not respond as expected.',
            requestTimedOut: 'The server did not respond in time.',
            requestAborted: 'The request was aborted by the client.',
            unknownError: 'An unknown error occurred during processing.',
        };

        function doAjaxRequest(config) {
            // Wrap the request.
            const wrapper = $.Deferred((deferred) => {
                const request = $.ajax(config);

                request
                    .done((data) => {
                        // Success! Check the status.

                        if (data.success === undefined) {
                            // Fail - no success reported.
                            deferred.reject(translations.serverBadResponse, 'serverBadResponse');
                            return;
                        }

                        if (data.success !== true) {
                            // Fail - Did not succeed.
                            // See if we can discern the error.
                            if (data.error === undefined || !data.error) {
                                deferred.reject(translations.serverRequestFailed, 'serverRequestFailed');
                            } else {
                                deferred.reject(data.error, 'serverRequestFailed');
                            }
                            return;
                        }

                        // Success!
                        if (data.data === undefined) {
                            deferred.resolve();
                        } else {
                            deferred.resolve(data.data);
                        }
                    })
                    .fail((jqXHR, textStatus, errorThrown) => {
                        switch (textStatus) {
                        case 'parsererror':
                            deferred.reject(translations.serverResponseNotJson, 'serverResponseNotJson');
                            break;
                        case 'error':
                            if (errorThrown) {
                                updateFormAjaxValidation(jqXHR);

                                deferred.reject(errorThrown, 'serverError');
                            } else {
                                deferred.reject(translations.unknownError, 'unknownError');
                            }
                            break;
                        case 'timeout':
                            deferred.reject(translations.requestTimedOut, 'requestTimedOut');
                            break;
                        case 'abort':
                            deferred.reject(translations.requestAborted, 'requestAborted');
                            break;
                        default:
                            deferred.reject(translations.unknownError, 'unknownError');
                            break;
                        }
                    });
            });

            return wrapper.promise();
        }


        function sendAjaxRequest(endpoint, type, data) {
            const config = {
                url: endpoint,
                method: type.toUpperCase(),
                data,
                dataType: 'json',
                timeout: 30000, // 30 second timeout by default.
            };

            return doAjaxRequest(config);
        }

        xhrApi.post = (endpoint, data) => {
            const requestData = data;
            requestData._token = $('meta[name="csrf-token"]').attr('content');
            return sendAjaxRequest(endpoint, 'post', requestData);
        };

        xhrApi.get = (endpoint, data) => sendAjaxRequest(endpoint, 'get', data);

        return xhrApi;
    })($);

    /**
     * Find address details based on a house number and postcode.
     *
     * @param {string} houseNumber
     * @param {string} postCode
     * @return {jQuery.promise} A jQuery promise object.
     */
    api.findAddress = (houseNumber, postCode) =>
        api.xhr.get('/ajax/find-houses-by-postcode', {
            hn: houseNumber,
            p: postCode,
        });

    api.translate = {};

    /**
     * Translate a number into a human readable string.
     * @param {int} number
     * @returns {string}
     */
    api.translate.numberToString = (number) => {
        // Copy of the original number is decreased as this function iterates.
        let numberLeft = Math.floor(Math.abs(number));
        // Fail safe to stop the loop from running more than 10 times.
        let failsafe = 0;
        // The output, in the form of a multidimensional array.
        const output = [];
        let outputString = '';
        let groupKey;
        let useGroup;
        let groupAmount;
        let replacePos;
        let outputKey;
        let joinStr;
        let currentNumber;
        let currentString;

        let joinKey;

        const numbers = {
            1: 'one',
            2: 'two',
            3: 'three',
            4: 'four',
            5: 'five',
            6: 'six',
            7: 'seven',
            8: 'eight',
            9: 'nine',
            10: 'ten',
            11: 'eleven',
            12: 'twelve',
            13: 'thirteen',
            14: 'fourteen',
            15: 'fifteen',
            16: 'sixteen',
            17: 'seventeen',
            18: 'eighteen',
            19: 'nineteen',
            20: 'twenty',
            30: 'thirty',
            40: 'forty',
            50: 'fifty',
            60: 'sixty',
            70: 'seventy',
            80: 'eighty',
            90: 'ninety',
        };

        const groups = {
            10: '%teen',
            100: '% hundred',
            1000: '% thousand',
            1000000: '% million',
            1000000000: '% billion',
            1000000000000: 'Please double-check the amount entered',
        };

        const joins = {
            1: ' and %',
            100: ', %',
        };

        while (numberLeft > 0 && failsafe <= 10) {
            failsafe += 1;

            // Exact number match?
            if (numbers[numberLeft] !== undefined) {
                output.push([numberLeft, numbers[numberLeft]]);
                numberLeft -= numberLeft;
            } else if (numberLeft >= 20 && numberLeft <= 99) {
                // ): Wish there was a way I could make this generic.
                // Special rule case for 20-99, as these numbers are often suffixed with single digits.
                const ty = api.translate.numberToString(Math.floor(numberLeft / 10) * 10);
                const single = api.translate.numberToString(numberLeft % 10);
                // Trim so if the singles is 0, we strip the leading space.
                output.push([numberLeft, (`${ty} ${single}`).trim()]);
                numberLeft = 0;
            } else {
                // Otherwise, find the group this number falls in and start breaking the number down.
                useGroup = false;
                const groupKeys = Object.keys(groups);
                for (let i = 0; i < groupKeys.length; i += 1) {
                    groupKey = groupKeys[i];
                    if (numberLeft >= groupKey) {
                        useGroup = groupKey;
                    }
                }

                if (useGroup) {
                    replacePos = groups[useGroup].search('%');

                    if (replacePos === -1) {
                        // Basically, for 1 trillion or higher, don't even bother.
                        output.push([numberLeft, groups[useGroup]]);
                        numberLeft = 0;
                    } else {
                        groupAmount = Math.floor(numberLeft / useGroup);
                        numberLeft -= groupAmount * useGroup;
                        output.push([groupAmount * useGroup, groups[useGroup].replace('%', api.translate.numberToString(groupAmount))]);
                    }
                }
            }
        }

        if (output.length === 0) {
            return outputString;
        }

        for (outputKey = 0; outputKey < output.length; outputKey += 1) {
            [currentNumber, currentString] = output[outputKey];

            if (outputKey === 0) {
                outputString += currentString;
            } else {
                joinStr = ' %';
                const joinKeys = Object.keys(joins);
                for (let i = 0; i < joinKeys.length; i += 1) {
                    joinKey = joinKeys[i];
                    if (currentNumber >= joinKey) {
                        joinStr = joins[joinKey];
                    }
                }

                outputString += joinStr.replace('%', currentString);
            }
        }

        if (number < 0) {
            outputString = `negative ${outputString}`;
        }

        return outputString;
    };

    /**
     * Timer objects allow for slightly more granular control
     * over timing-based callbacks. This class is particularly
     * useful for instant searching, so you can add delays between
     * key strokes before hitting the server.
     * @param {int} timeToExpire Time in MS before the callback is fired.
     * @param {function} callback
     * @constructor
     */
    api.Timer = function timer(timeToExpire, callback) {
        let timeout = null;
        const time = timeToExpire;

        const startTimer = () => {
            if (!timeout) {
                timeout = setTimeout(() => {
                    callback();
                }, time);
            }
        };

        const stopTimer = () => {
            if (timeout) {
                clearTimeout(timeout);
                timeout = null;
            }
        };

        this.reset = () => {
            stopTimer();
            startTimer();
        };

        this.end = () => {
            if (timeout) {
                stopTimer();
                callback();
            }
        };

        this.stop = stopTimer;
    };

    return api;
})(window.jQuery);
