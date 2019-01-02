$(document).ready(() => {
    const manualaddress = $('#manual-address');

    function populateAddressDropDown(postcodeInput) {
        // Unwrap the jQuery element to access the HTMLInputElement.validity proerty
        const DOMInput = postcodeInput[0];
        if ((DOMInput && DOMInput.validity) && DOMInput.validity.valid === false) {
            // HTML5 validation is available and the input is invalid, so return
            return;
        }
        if (postcodeInput.val().length >= 5 && $('#enter-address').html() === 'Or Enter Address Manually') {
            $('#solicitorOfficeAddress').hide();
            $('#address-list').empty().removeClass('hidden');
            window.getFullAddress(postcodeInput, '#address-list');
        } else {
            $('#address-list').empty().addClass('hidden');
        }

        if (postcodeInput.val().length < 1) {
            $('#address-list').empty().addClass('hidden');
        }
    }

    $('#postcodeLookupBtn').on('click', () => {
        populateAddressDropDown($('#postcode'));
    });

    $('#postcode').on('keypress', (e) => {
        const searchSpecial = [0, 8, 9, 13, 37, 39, 16, 46]; // Allow arrows/delete/backsapce/tab etc
        const exclude = ["'", '%']; // for chrome exclude these from being accepted. do not remove!
        const regex = new RegExp('^[a-zA-Z0-9 ]+$');
        const str = String.fromCharCode(!e.charCode ? e.which : e.charCode);

        if (searchSpecial.indexOf(e.keyCode) < 1) {
            if (regex.test(str)) {
                return true;
            }

            e.preventDefault();
            return false;
        } else if (e.key !== exclude[0] && e.key !== exclude[1]) {
            return true;
        }

        return false;
    }).on('blur', () => {
        populateAddressDropDown($('#postcode'));
    });

    $('#enter-address').on('click', function (e) {
        e.preventDefault();
        if (manualaddress.hasClass('hidden')) {
            manualaddress.removeClass('hidden');
            $('#postcode').attr('placeholder', 'Postcode');
            $(this).html('Hide Address Fields');
        } else {
            manualaddress.addClass('hidden');
            $('#postcode').attr('placeholder', 'Postcode Search*');
            $(this).html('Or Enter Address Manually');
        }
    });

    $('#address-list').on('change', function () {
        $('#building_name').val($(this).find(':selected').data('building-name'));
        $('#building_number').val($(this).find(':selected').data('building-number'));
        $('#address_line_1').val($(this).find(':selected').data('line1'));
        $('#address_line_2').val($(this).find(':selected').data('line2'));
        $('#town').val($(this).find(':selected').data('post-town'));
        $('#county').val($(this).find(':selected').data('county'));
    });
});
