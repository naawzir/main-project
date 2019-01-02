const { expect } = require('chai');

const Assert = function assert(nightmare, cleanupCallback) {
    const textEquals = (text, selector, doneCallback) => () => {
        nightmare
            .evaluate(query => document.querySelector(query).innerText, selector)
            .end()
            .then((actualText) => {
                expect(actualText).to.equal(text);
                cleanupCallback(doneCallback);
            })
            .catch((err) => {
                cleanupCallback(doneCallback, err);
            });
    };

    return { textEquals };
};

module.exports = Assert;
