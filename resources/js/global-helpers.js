/* global tcp */

const typeArray = [];
typeArray.Rejected = 'caseReminderRed';
typeArray['Aborted Case'] = 'caseReminderRed';
typeArray['Chase Prospect'] = 'caseReminderGreen';
typeArray['Chase Comparison Quote'] = 'caseReminderGreen';
typeArray['New Lead'] = 'caseReminderYellow';
typeArray['Solicitor Office Contact'] = 'lightblue-color';
typeArray['Complaint Follow Up'] = 'lightred-color';
typeArray['New Solicitor Office'] = 'orange-color';
typeArray['Chase Tm Set Up'] = 'orange-color';
typeArray['Panel Manager Audit'] = 'light-green';
typeArray['Solicitor Office Activated'] = 'caseReminderGreen';
typeArray['Solicitor Office Sent To Tm'] = 'caseReminderYellow';
typeArray.Branch = 'branchReminderBlack';

function getAlertResponse(selector, data) {
    if ($(selector).length) {
        $(selector).replaceWith(data);
    } else {
        let elemClass = selector;
        if (elemClass.charAt(0) === '.') {
            elemClass = elemClass.slice(1);
        }
        $('#page-header').append(`<div data-alert class="alresp col-sm-23 p-1 m-2 ${elemClass}"><p>${data}</p></div>`);
        $('.alresp').fadeOut(6000);
    }
}

window.getOnboardingCount = () => {
    const ajaxRequest = window.tcp.xhr.get('/solicitors/onboarding/get-onboarding/');
    ajaxRequest.done((responseData) => {
        $('#onboard-count').text(responseData.count);
    });

    ajaxRequest.fail((error) => {
        getAlertResponse('warning-box error', error);
    });
};

/**
 * Check if an input checkbox is ticked or not by providing the element's ID.
 */
window.isCheckboxTicked = (elementId) => {
    let value = 0;

    if ($(`#${elementId}`).is(':checked')) {
        value = 1;
    }

    return value;
};

let currentlyEditing = null;
let doneEditingCallback = null;

// this checks if both the Email and Mobile fields are empty if any of the two fields are being edited
function propertyReportNotificationsFields() {
    const elementName = $(currentlyEditing).find('input').attr('name');

    if (elementName === 'email' || elementName === 'mobile') {
        if ($("input[name='email']").val() === '' && $("input[name='mobile']").val() === '') {
            const confirmation = window.windowConfirm("As the Email and Mobile fields are empty you won't be able to send a property report and any other notifications to the client.\n\nDo you want to proceed?");

            if (confirmation) {
                $('#newPropertyReportOption').hide();
            }
            return true;
        }
        return true;
    }

    return true;
}

function isCurrentlyEditing(tableCell) {
    if (tableCell) {
        return $(currentlyEditing).is(tableCell);
    }

    return currentlyEditing !== null;
}


function doneEditing() {
    const caseForm = $('#caseForm').length;
    if (caseForm) {
        propertyReportNotificationsFields();
    }

    if (currentlyEditing) {
        $(currentlyEditing).off('keypress.edit');

        const result = doneEditingCallback(currentlyEditing);

        currentlyEditing = null;

        return result;
    }

    console.error('Called done editing when we weren\'t editing.');

    return false;
}

window.setEditing = (tableCell, onDoneEditing) => {
    if (isCurrentlyEditing()) {
        if (isCurrentlyEditing(tableCell)) {
            // We're editing this cell. Do nothing.
            return;
        }

        doneEditing();
    }

    currentlyEditing = tableCell;
    doneEditingCallback = onDoneEditing;

    $(currentlyEditing).on('keypress.edit', ':input', (e) => {
        if (e.which === 13) {
            doneEditing();
        }
    });
};

$(document.body).click((ev) => {
    if (isCurrentlyEditing()) {
        if (!$(ev.target).is(currentlyEditing) && $(ev.target).closest(currentlyEditing).length === 0) {
            doneEditing();
        }
    }

    return true;
});

function updateFormInput(input, value) {
    const data = {

        tblField: input,
        tblValue: value,

    };

    const ajaxRequest = tcp.xhr.post('/home/update', data);

    ajaxRequest.done((responseData) => {
        getAlertResponse('success-box', responseData);
    });

    ajaxRequest.fail((error) => {
        getAlertResponse('warning-box error', error);
    });
}

window.saveForUndo = (field, value) => {
    const input = field;
    const lastValue = value;
    $('#undo-btn').on('click', () => {
        $(`[data-field=${field}] span`).text(lastValue);
        updateFormInput(input, lastValue);
        $('#undo-btn').hide();
    });
};

window.updateFormAjaxValidation = (data) => {
    if (typeof data.responseJSON.errors !== 'undefined') {
        $.each(data.responseJSON.errors, (index, value) => {
            $.alert(value);
        });
    }
};

// this function runs when trying to add a client
window.clientDetailsValidationCreating = (element) => {
    if (element.val() === '') {
        let fieldNameCapitalized =
            element
                .attr('id')
                .charAt(0)
                .toUpperCase() +
            element
                .attr('id')
                .slice(1);
        fieldNameCapitalized =
            fieldNameCapitalized
                .slice(0, -2)
                .replace(/([a-z])([A-Z])/g, '$1 $2'); // this will add a space after the capital letter e.g. LeadSource will become Lead Source;
        $.alert(`${fieldNameCapitalized} is a required field.`);
        // return false;
    }

    return true;
};

/**
 * Insert a comma in a price if there's at least 3 digits in it.
 * You'll need to add .digits() at the end of the selector jquery code if you want the comma to appear.
 */
$.fn.digits = function getDigits() {
    return this.each(function forEachElem() {
        $(this).text($(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,'));
    });
};

/**
 * Strip out HTML from a string
 *
 * @param html - The html string
 * @returns {((string | null) | string) | string} - Returned clean string
 */
window.strip = (html) => {
    const tmp = document.createElement('DIV');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
};

/**
 * Title Case A String
 *
 * @param str - The string to Title Case
 * @returns string - Returned Title Case string4
 */
window.toTitleCase = str => str.replace(/\w\S*/g, txt => txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase());

// Function to obtain address details and populate these in a dropdown for selection by the user
window.getFullAddress = (postCode, inputId) => {
    const postcode = $(postCode).val().toUpperCase().replace(/\s+/g, ' ');
    // Get the list of addresses
    $(inputId).toggleClass('hidden', true);
    $(inputId).empty();
    $(inputId).append('<option data-building-name="" data-building-number="" data-line1="" data-line2="" data-post-town="" data-county="" value="">Please Select Your Address</option>');

    const ajaxRequest = $.get(`/global/find-houses-by-postcode/?p=${postcode}`);
    ajaxRequest.done((responseData) => {
        $(inputId).empty();
        $(inputId).append('<option data-building-name="" data-building-number="" data-line1="" data-line2="" data-post-town="" data-county="" value="">Please Select Your Address</option>');
        if (responseData.resultcount > 0) {
            $('#content-container').html('').hide();
            responseData.results.forEach((row) => {
                $(inputId).append(`<option data-building-name="${row.building_name}"  data-building-number="${row.building_number}" data-line1="${row.thoroughfare}" data-line2="${row.line_2}" data-post-town="${row.district}" data-county="${row.county}"
                    value="row.line_1">${row.line_1}, ${row.district}</option>`);
            });
        } else {
            getAlertResponse('warning-box error', `No Data for Postcode: ${postcode}`);
        }
    });
    $(inputId).removeClass('hidden');

    ajaxRequest.fail((error) => {
        getAlertResponse('warning-box error', `${error.responseJSON.message}`);
    });
};
