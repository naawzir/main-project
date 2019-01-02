/* global tcp, isCheckboxTicked, userId, getAlertResponse, setEditing, getFullAddress */

$(document).ready(() => {
    const form = $('form');

    function notify(message, title) {
        $.alert({
            title,
            content: message,
        });
    }

    function getLeadSource(value) {
        const leadSources = {

            seller_sstc: 'Seller SSTC',
            buyer_sstc: 'Buyer SSTC',
            seller_new_take_on: 'Seller New Take On',
            seller_welcome_call: 'Seller Welcome Call',
            seller_already: 'Seller Already on the Market',
            none: 'None',

        };

        const keys = Object.keys(leadSources);
        for (let i = 0; i < keys.length; i += 1) {
            if (leadSources[keys[i]] === value) {
                return keys[i];
            }
        }

        return null;
    }

    function addNote(notified) {
        $('.noteAdded').remove();

        const caseId = $('#caseId').val();

        const note = $('#note').val();

        const data = {

            caseId,
            note,
            notified,

        };

        const ajaxRequest = tcp.xhr.post('/cases/addnote', data);

        ajaxRequest.done((responseData) => {
            $('#note').val('');
            const tr = $('#notesTable tr:last').clone();
            $('#notesTable tbody').append(tr);

            $('#notesTable tr:last td:nth-child(1)')
                .text(responseData.note_date_created);
            $('#notesTable tr:last td:nth-child(2)')
                .text(responseData.note_staff);
            $('#notesTable tr:last td:nth-child(3)')
                .text(responseData.note_content);
            $('#notesTable tr:last td:nth-child(4)')
                .text(responseData.notified);

            getAlertResponse('success-box', responseData.message);
        });

        ajaxRequest.fail((error) => {
            getAlertResponse('warning-box error', error);
            $('.error').fadeOut(2500);
        });
    }

    function processInstructionNoteData(inputElement, originalValue) {
        const value = $(inputElement).val();
        const caseId = $('#caseId').val();
        const instructionNoteId = $('#instructionNoteId').val();

        const data = {
            instructionNoteId,
            caseId,
            instructionNote: value,
        };

        const ajaxRequest = tcp.xhr.post('/cases/saveinstructionnote', data);

        ajaxRequest.done((responseData) => {
            $(inputElement).hide();

            $('#instructionNoteText')
                .text(responseData.note)
                .show();

            $(inputElement).parent().attr('id', 'instructionNoteEditableTd');


            if (instructionNoteId === 'create') {
                $('#instructionNoteId').val(responseData.noteId);
            }

            $('#savedMessage').fadeIn().fadeOut(5000);
        });

        ajaxRequest.fail(() => {
            $(inputElement)
                .val(originalValue)
                .hide();

            $('#instructionNoteText')
                .text(originalValue)
                .show();

            $(inputElement).parent().attr('id', 'instructionNoteEditableTd');

            $('#failedToSaveMessage').fadeIn().fadeOut(5000);
        });
    }

    function updateAgency() {
        const agencyIdElem = $('#agencyIdSpan');
        const originalValue = agencyIdElem.attr('data-selected-agency-id');
        $('#agencyId')
            .val(originalValue)
            .show();

        agencyIdElem.hide();
    }

    function updateBranch() {
        const agencyBranchIdElem = $('#agencyBranchIdSpan');
        const originalValue = agencyBranchIdElem.attr('data-selected-branch-id');
        $('#agencyBranchId')
            .val(originalValue)
            .show();

        agencyBranchIdElem.hide();
    }

    function updateAgent() {
        const userIdAgent = $('#userIdAgentSpan');
        const originalValue = userIdAgent.attr('data-selected-user-id-agent');
        $('#userIdAgent')
            .val(originalValue)
            .show();

        userIdAgent.hide();
    }

    function propertyReportNotificationsFieldsPostSubmission(field, currentnum) {
        if (field === 'email' || field === 'mobile') {
            if ($(`#email_${currentnum}`).val() === '' && $(`#mobile_${currentnum}`).val() === '') {
                $('#newPropertyReportOption').hide();
            } else {
                $('#newPropertyReportOption').show();
            }
        }
    }

    function processData(field, model, inputElement, currentnum, originalValue) {
        const value = $(inputElement).val();

        const id = $(`#userId_${currentnum}`).val();

        const data = {

            id,
            model,
            field,
            title: $(`#title_${currentnum}`).val(),
            forenames: $(`#forenames_${currentnum}`).val(),
            surname: $(`#surname_${currentnum}`).val(),
            email: $(`#email_${currentnum}`).val(),
            phone: $(`#phone_${currentnum}`).val(),
            mobile: $(`#mobile_${currentnum}`).val(),
            value,

        };

        const ajaxRequest = tcp.xhr.post('/cases/update', data);

        ajaxRequest.done(() => {
            $(inputElement).parent().addClass('table-cell');

            $(inputElement).hide();

            $(`#${field}Span_${currentnum}`)
                .text(value)
                .show();

            if (field === 'title' || field === 'forenames' || field === 'surname') {
                const clientName =
                    `${$(`#titleSpan_${currentnum}`).text()} ${
                        $(`#forenamesSpan_${currentnum}`).text()} ${
                        $(`#surnameSpan_${currentnum}`).text()}`;

                $(`.client-fullname_${currentnum}`).text(clientName);
            }

            propertyReportNotificationsFieldsPostSubmission(field, currentnum);

            $('#savedMessage').fadeIn().fadeOut(5000);
        });

        ajaxRequest.fail(() => {
            $(inputElement).parent().addClass('table-cell');

            $(inputElement)
                .val(originalValue)
                .hide();

            $(`#${field}Span_${currentnum}`)
                .text(originalValue)
                .show();

            propertyReportNotificationsFieldsPostSubmission(field, currentnum);

            $('#failedToSaveMessage').fadeIn().fadeOut(5000);
        });
    }

    function processCaseData(
        field,
        fieldCamelCase,
        inputElement,
        originalValue,
    ) {
        const value = $(inputElement).val();

        const id = $('#caseId').val();

        const data = {
            id,
            model: 'Cases',
            field,
            type: $('#type').val(),
            lead_source: $('#leadSource').val(),
            price: $('#price').val(),
            tenure: $('#tenure').val(),
            mortgage: $('#mortgage').val(),
            searches_required: $('#searchesRequired').val(),
            user_id_staff: $('#userIdStaff').val(),
            value,
        };

        const ajaxRequest = tcp.xhr.post('/cases/update', data);

        ajaxRequest.done((responseData) => {
            $(inputElement).parent().addClass('table-cell-case');

            $(inputElement).hide();

            $(`#${fieldCamelCase}Span`)
                .text(responseData.value)
                .show();

            if (field === 'user_id_staff') {
                $('#userIdStaffSpan').attr('data-selected-user-id-staff', value);

                // this is to update the Account Manager span in the Case Summary at the top
                $('#accountManager').text($('#userIdStaffSpan').text());
            }

            $('#savedMessage').fadeIn().fadeOut(5000);
        });

        ajaxRequest.fail(() => {
            $(inputElement).parent().addClass('table-cell-case');

            $(inputElement).hide();

            $(`#${fieldCamelCase}Span`)
                .text(originalValue)
                .show();

            $('#failedToSaveMessage').fadeIn().fadeOut(5000);
        });
    }

    $('#notesTableToggle').click(() => {
        $('#notesTable').toggle();
    });

    $('#agencyId').change(function onChangeAgencyId() {
        $('#agencyEmailAddress').text('');
        $('#agencyBranchEmailAddress').text('');
        $('#agentEmailAddress').text('');

        const agencyId = $(this).val();

        const caseId = $('#caseId').val();

        if (agencyId) {
            const data = {
                agencyId,
                caseId,
            };

            const ajaxRequest = tcp.xhr.post('/cases/getbranchesforagency', data);

            ajaxRequest.done((requestData) => {
                $('#solicitorId').html(requestData.solicitor_options);
                $('#agencyBranchId').html(requestData.options);
                $('#agencyEmailAddress').text(requestData.email);

                $('#agencyIdSpan')
                    .text($('#agencyId option:selected').text())
                    .attr('data-selected-agency-id', agencyId);

                $(this).parent().addClass('table-cell-agency');
            });

            ajaxRequest.fail((error) => {
                console.error(error);
                $('.error').fadeOut(2500);
            });

            $('#userIdAgent option').remove();
            $('#userIdAgent').append('<option value="">Please select</option>');
        } else {
            $('#agencyBranchId option').remove();
            $('#agencyBranchId').append('<option value="">Please select</option>');

            $('#userIdAgent option').remove();
            $('#userIdAgent').append('<option value="">Please select</option>');
        }
    });

    $('#agencyBranchId').change(function onBranchIdChange() {
        const agencyBranchId = $(this).val();

        const caseId = $('#caseId').val();

        const data = {

            agencyBranchId,
            caseId,

        };

        const ajaxRequest = tcp.xhr.post('/cases/getusersforbranch', data);

        ajaxRequest.done((requestData) => {
            $('#userIdAgent').html(requestData.options);
            $('#agencyBranchEmailAddress').text(requestData.email);

            $('#agencyBranchIdSpan')
                .text($('#agencyBranchId option:selected').text())
                .attr('data-selected-branch-id', agencyBranchId);

            $(this).parent().addClass('table-cell-agency');
        });

        ajaxRequest.fail((error) => {
            console.error(error);
            $('.error').fadeOut(2500);
        });
    });

    $('#userIdAgent').change(function onUserIdAgentChange() {
        const userIdAgent = $(this).val();

        const caseId = $('#caseId').val();

        const data = {
            userIdAgent,
            caseId,
        };

        const ajaxRequest = tcp.xhr.post('/cases/getagentsemailaddress', data);

        ajaxRequest.done((requestData) => {
            $('#agentEmailAddress').text(requestData.email);

            $('#userIdAgentSpan')
                .text($('#userIdAgent option:selected').text())
                .attr('data-selected-user-id-agent', userIdAgent);

            $(this).parent().addClass('table-cell-agency');
        });

        ajaxRequest.fail((error) => {
            console.error(error);
            $('.error').fadeOut(2500);
        });
    });

    $('#solicitorId').change(function onSolicitorIdChange() {
        const solicitorId = $(this).val();

        if (solicitorId) {
            const data = {
                solicitorId,
            };

            const ajaxRequest = tcp.xhr.get('/solicitors/getofficesforsolicitor', data);

            ajaxRequest.done((requestData) => {
                $('#solicitorOfficeId').html(requestData.options);
                $('#solicitorIdSpan')
                    .text($('#solicitorId option:selected').text())
                    .attr('data-selected-solicitor-id', solicitorId);
            });

            ajaxRequest.fail((error) => {
                console.error(error);
                $('.error').fadeOut(2500);
            });

            $('#solicitorUserId option.ajax-options').remove();
            $('#solicitorUserId').val('');
        } else {
            $('#solicitorOfficeId option.ajax-options').remove();
            $('#solicitorOfficeId').val('');
            $('#solicitorUserId option.ajax-options').remove();
            $('#solicitorUserId').val('');
        }
    });

    $('#solicitorOfficeId').change(function onSolicitorOfficeIdChange() {
        const solicitorOfficeId = $(this).val();

        const data = {
            solicitorOfficeId,
        };

        const ajaxRequest = tcp.xhr.get('/solicitors/offices/getusersforoffice', data);

        ajaxRequest.done((requestData) => {
            $('#solicitorUserId').html(requestData.options);
            $('#solicitorOfficeIdSpan')
                .text($('#solicitorOfficeId option:selected').text())
                .attr('data-selected-solicitor-office-id', solicitorOfficeId);
        });

        ajaxRequest.fail((error) => {
            console.error(error);
            $('.error').fadeOut(2500);
        });
    });

    $('.checkboxElement').click(function onCheckboxClick() {
        const field = $(this).attr('id');
        let value = $(this).val();
        let data;
        let ajaxRequest;
        const caseId = $('#caseId').val();

        if (field === 'aml_fees_paid') {
            $.confirm({
                content: 'Are you sure AML fees have been paid?',
                buttons: {
                    confirm: () => {
                        data = {
                            model: 'Cases',
                            id: caseId,
                            field,
                            value: 1,
                        };

                        ajaxRequest = tcp.xhr.post('/cases/update', data);

                        ajaxRequest.done(() => {
                            $('#aml_fees_paid').prop('disabled', true);
                            $('#savedMessage').fadeIn().fadeOut(5000);
                        });

                        ajaxRequest.fail((error) => {
                            console.error(error);
                            $('#aml_fees_paid').prop('disabled', false);
                            $('#failedToSaveMessage').fadeIn().fadeOut(5000);
                        });
                    },
                },
            });
        } else if (field === 'all_aml_searches_complete') {
            value = isCheckboxTicked(field);

            data = {
                caseId,
                field,
                value,
            };

            ajaxRequest = tcp.xhr.post('/cases/update', data);

            ajaxRequest.done(() => {
                $('#savedMessage').fadeIn().fadeOut(5000);
            });

            ajaxRequest.fail((error) => {
                console.error(error);
                $('#failedToSaveMessage').fadeIn().fadeOut(5000);
            });
        }
        return true;
    });

    form.on('click', '.clear-client-td', function onClearClientClick() {
        $.confirm({
            content: 'Are you sure you want to clear this client\'s details and re-enter a new client?',
            buttons: {
                confirm: () => {
                    const currentnum = parseInt($(this).attr('id').match(/\d+/g), 10);

                    const clientTypeElem = $(`#clientType_${currentnum}`);
                    const userIdElem = $(`#userId_${currentnum}`);

                    const clientType = clientTypeElem.text();

                    const userId = userIdElem.val();
                    const caseIdValue = $('#caseId').val();
                    const caseId = (caseIdValue === 'create') ? null : caseIdValue;

                    const elements = [
                        'title',
                        'forenames',
                        'surname',
                        'email',
                        'phone',
                        'mobile',
                    ];

                    if (clientType === 'Existing') {
                        clientTypeElem.text('New');
                        userIdElem.val('create');

                        $(`#saveClientBtn_${currentnum}`).show();

                        $(`#clientPersonalDetailsTr_${currentnum}`).find('.table-cell').removeClass('table-cell');

                        elements.forEach((element) => {
                            $(`#${element}_${currentnum}`).val('').show();
                            $(`#${element}Span_${currentnum}`).hide();
                        });

                        $(`#clientPostcode_${currentnum}`).val('').show();
                        $(`#clientPostcodeSpan_${currentnum}`).text('').hide();
                        // $("#clientPostcodeTd_" + currentnum).addClass("table-cell-postcode");

                        $(`#findClientAddressBtn_${currentnum}`).show();

                        $(`#changeAddress_${currentnum}`).hide();

                        $(`#clientResults_${currentnum}`).show();
                        $(`#clientAddr_${currentnum}`).html('');

                        $(`#clientBuildingNumber_${currentnum}`).val('').show();
                        $(`#clientBuildingNumberSpan_${currentnum}`).text('').hide();

                        $(`#clientBuildingName_${currentnum}`).val('').show();
                        $(`#clientBuildingNameSpan_${currentnum}`).text('').hide();

                        $(`#clientAddrline1_${currentnum}`).val('').show();
                        $(`#clientAddrline1Span_${currentnum}`).text('').hide();

                        $(`#clientAddrtown_${currentnum}`).val('').show();
                        $(`#clientAddrtownSpan_${currentnum}`).text('').hide();

                        $(`#clientCounty_${currentnum}`).val('').show();
                        $(`#clientCountySpan_${currentnum}`).text('').hide();

                        $(`.client-fullname_${currentnum}`).remove();
                    } else if (userId === 'create') {
                        userIdElem.val('create');

                        $(`#clientPersonalDetailsTr_${currentnum}`).find('.table-cell').removeClass('table-cell');

                        elements.forEach((element) => {
                            $(`#${element}_${currentnum}`).val('').show();
                            $(`#${element}Span_${currentnum}`).hide();
                        });

                        $(`.client-fullname_${currentnum}`).remove();
                    } else {
                        const data = {
                            userId,
                            caseId,
                            newClient: 1,
                        };

                        const ajaxRequest = tcp.xhr.get('/cases/removeclient', data);

                        ajaxRequest.done(() => {
                            $(`#userId_${currentnum}`).val('create');

                            $(`#clientPersonalDetailsTr_${currentnum}`).find('.table-cell').removeClass('table-cell');

                            elements.forEach((element) => {
                                $(`#${element}_${currentnum}`).val('').show();
                                $(`#${element}Span_${currentnum}`).hide();
                            });

                            $(`.client-fullname_${currentnum}`).remove();
                        });

                        ajaxRequest.fail((error) => {
                            console.error(error);
                            $('#failedToSaveMessage').fadeIn().fadeOut(5000);
                        });
                    }
                },
            },
        });
    });

    // this will remove spaces if any are entered whilst the user is entering the mobile number
    form.on('keyup', '.mobile', function onKeyUpInMobileField() {
        $(this).val($(this).val().replace(/ +?/g, ''));
    });

    form.on('click', '.save-btn', function onClickOfSaveButton(ev) {
        ev.preventDefault();

        const currentnum = parseInt($(this).attr('id').match(/\d+/g), 10);

        const success = true;

        if (success) {
            const title = $(`#title_${currentnum}`).val();
            const titles = [];
            $('.title').each(function forEachTitle() {
                titles.push($(this).val());
            });
            const forenames = $(`#forenames_${currentnum}`).val();
            const surname = $(`#surname_${currentnum}`).val();
            const email = $(`#email_${currentnum}`).val();
            const phone = $(`#phone_${currentnum}`).val();
            const mobile = $(`#mobile_${currentnum}`).val();
            let addressId;
            let clientPostcode;
            let clientBuildingNumber;
            let clientBuildingName;
            let clientAddrline1;
            let clientAddrtown;
            let clientCounty;

            const clientAddressIdElem = $(`#clientAddressId_${currentnum}`);
            const customerNumberElem = $(`#customerNumber_${currentnum}`);
            const clientTypeElem = $(`#clientType_${currentnum}`);

            const clientAddressLength = clientAddressIdElem.length;
            if (clientAddressLength) {
                addressId = clientAddressIdElem.val();
                clientPostcode = $(`#clientPostcode_${currentnum}`).val();
                clientBuildingNumber = $(`#clientBuildingNumber_${currentnum}`).val();
                clientBuildingName = $(`#clientBuildingName_${currentnum}`).val();
                clientAddrline1 = $(`#clientAddrline1_${currentnum}`).val();
                clientAddrtown = $(`#clientAddrtown_${currentnum}`).val();
                clientCounty = $(`#clientCounty_${currentnum}`).val();
            }

            const customerNumber = customerNumberElem.val();

            let newClient = clientTypeElem.text();
            if (newClient === 'New') {
                newClient = 1;
            } else {
                newClient = 0;
            }

            const caseId = $('#caseId').val();

            const data = {
                titles,
                forenames,
                surname,
                email,
                phone,
                mobile,
                /* addressId : addressId,
                clientPostcode : clientPostcode,
                clientBuildingNumber : clientBuildingNumber,
                clientBuildingName : clientBuildingName,
                clientAddrline1 : clientAddrline1,
                clientAddrtown : clientAddrtown,
                clientCounty : clientCounty,
                caseId : caseId,
                customerNumber : customerNumber*/

            };

            if (customerNumber === '1') {
                // if (userId === 'create' && newClient == '1') {
                if (userId === 'create') {
                    const ajaxRequest = tcp.xhr.post('/cases/user/create', data);

                    ajaxRequest.done((responseData) => {
                        $(`#userId_${currentnum}`).val(responseData.user_id);

                        /* var clientName = '<p data-user-id="' + data.user_id + '" class="client-fullname client-fullname_' + currentnum + '">';
                        clientName += data.title + ' ' + data.forenames + ' ' + data.surname;
                        clientName += '</p>';
                        $("#clientNamesAddressTd_" + currentnum).append(clientName);*/

                        $(`#title_${currentnum}`).val(responseData.title).hide();
                        $(`#titleSpan_${currentnum}`).text(responseData.title).show();
                        $(`#titleTd_${currentnum}`).addClass('table-cell');

                        $(`#forenames_${currentnum}`).val(responseData.forenames).hide();
                        $(`#forenamesSpan_${currentnum}`).text(responseData.forenames).show();
                        $(`#forenamesTd_${currentnum}`).addClass('table-cell');

                        $(`#surname_${currentnum}`).val(responseData.surname).hide();
                        $(`#surnameSpan_${currentnum}`).text(responseData.surname).show();
                        $(`#surnameTd_${currentnum}`).addClass('table-cell');

                        $(`#email_${currentnum}`).val(responseData.email).hide();
                        $(`#emailSpan_${currentnum}`).text(responseData.email).show();
                        $(`#emailTd_${currentnum}`).addClass('table-cell');

                        $(`#phone_${currentnum}`).val(responseData.phone).hide();
                        $(`#phoneSpan_${currentnum}`).text(responseData.phone).show();
                        $(`#phoneTd_${currentnum}`).addClass('table-cell');

                        $(`#mobile_${currentnum}`).val(responseData.mobile).hide();
                        $(`#mobileSpan_${currentnum}`).text(responseData.mobile).show();
                        $(`#mobileTd_${currentnum}`).addClass('table-cell');

                        $(`#clientType_${currentnum}`).text('New');

                        /* $("#clientPostcode_" + currentnum)
                            .val(data.postcode)
                            .hide();

                        $("#clientPostcodeSpan_" + currentnum)
                            .text(data.postcode)
                            .show();

                        $("#clientPostcodeTd_" + currentnum)
                            .addClass("table-cell-postcode");

                        $("#clientResults_" + currentnum).hide();

                        $("#clientBuildingNumber_" + currentnum).val(data.building_number).hide();
                        $("#clientBuildingNumberSpan_" + currentnum).text(data.building_number).show();

                        $("#clientBuildingName_" + currentnum).val(data.building_name).hide();
                        $("#clientBuildingNameSpan_" + currentnum).text(data.building_name).show();

                        $("#clientAddrline1_" + currentnum).val(data.address_line_1).hide();
                        $("#clientAddrline1Span_" + currentnum).text(data.address_line_1).show();

                        $("#clientAddrtown_" + currentnum).val(data.town).hide();
                        $("#clientAddrtownSpan_" + currentnum).text(data.town).show();

                        $("#clientCounty_" + currentnum).val(data.county).hide();
                        $("#clientCountySpan_" + currentnum).text(data.county).show();*/

                        $(`#saveBtnTd_${currentnum}`).html('');

                        // $("#clientAddressId_" + currentnum).val(data.address_id);

                        $(`#saveBtn_${currentnum}`).hide();

                        $('#clientAddressDetails').fadeIn();

                        /* $("#transactionDetails").fadeIn();
                        $("#transactionAddressDetails").fadeIn();*/

                        $('#savedMessage').fadeIn().fadeOut(5000);
                    });

                    ajaxRequest.fail(() => {
                        $('#failedToSaveMessage').fadeIn().fadeOut(5000);
                    });
                } else if (userId !== 'create' && newClient === 0) { // existing client
                    const ajaxRequest = tcp.xhr.post(`/cases/user/${userId}`, data);

                    ajaxRequest.done((responseData) => {
                        let clientName = `<p data-user-id="${userId}" class="client-fullname client-fullname_${currentnum}">`;
                        clientName += `${title} ${forenames} ${surname}`;
                        clientName += '</p>';
                        $(`#clientNamesAddressTd_${currentnum}`).append(clientName);

                        $(`#title_${currentnum}`).val(title).hide();
                        $(`#titleSpan_${currentnum}`).text(title).show();
                        $(`#titleTd_${currentnum}`).addClass('table-cell');

                        $(`#forenames_${currentnum}`).val(forenames).hide();
                        $(`#forenamesSpan_${currentnum}`).text(forenames).show();
                        $(`#forenamesTd_${currentnum}`).addClass('table-cell');

                        $(`#surname_${currentnum}`).val(surname).hide();
                        $(`#surnameSpan_${currentnum}`).text(surname).show();
                        $(`#surnameTd_${currentnum}`).addClass('table-cell');

                        $(`#email_${currentnum}`).val(email).hide();
                        $(`#emailSpan_${currentnum}`).text(email).show();
                        $(`#emailTd_${currentnum}`).addClass('table-cell');

                        $(`#phone_${currentnum}`).val(phone).hide();
                        $(`#phoneSpan_${currentnum}`).text(phone).show();
                        $(`#phoneTd_${currentnum}`).addClass('table-cell');

                        $(`#mobile_${currentnum}`).val(mobile).hide();
                        $(`#mobileSpan_${currentnum}`).text(mobile).show();
                        $(`#mobileTd_${currentnum}`).addClass('table-cell');

                        $(`#clientPostcode_${currentnum}`).val(clientPostcode).hide();
                        $(`#clientPostcodeSpan_${currentnum}`).text(clientPostcode).show();
                        $(`#clientPostcodeTd_${currentnum}`)
                            .addClass('table-cell-postcode');

                        $(`#findClientAddressBtn_${currentnum}`).hide();

                        $(`#changeAddress_${currentnum}`).show();

                        $(`#clientResults_${currentnum}`).hide();

                        $(`#clientBuildingNumber_${currentnum}`).val(clientBuildingNumber).hide();
                        $(`#clientBuildingNumberSpan_${currentnum}`).text(clientBuildingNumber).show();

                        $(`#clientBuildingName_${currentnum}`).val(clientBuildingNumber).hide();
                        $(`#clientBuildingNameSpan_${currentnum}`).text(clientBuildingNumber).show();

                        $(`#clientAddrline1_${currentnum}`).val(clientAddrline1).hide();
                        $(`#clientAddrline1Span_${currentnum}`).text(clientAddrline1).show();

                        $(`#clientAddrtown_${currentnum}`).val(clientAddrtown).hide();
                        $(`#clientAddrtownSpan_${currentnum}`).text(clientAddrtown).show();

                        $(`#clientCounty_${currentnum}`).val(clientCounty).hide();
                        $(`#clientCountySpan_${currentnum}`).text(clientCounty).show();

                        $(`#clientType_${currentnum}`).text('Existing');

                        $(`#clientAddressId_${currentnum}`).val(responseData.address_id);

                        $(`#saveBtn_${currentnum}`).hide();

                        $('#savedMessage').fadeIn().fadeOut(5000);

                        $('#clientAddressDetails').fadeIn();

                        /*                        $("#transactionDetails").fadeIn();
                                                $("#transactionAddressDetails").fadeIn();*/
                    });

                    ajaxRequest.fail(() => {
                        $('#failedToSaveMessage').fadeIn().fadeOut(5000);
                    });
                }
            } else if (userId === 'create' && newClient === 1) {
                clientPostcode = $('#clientPostcode_1').val();
                clientBuildingNumber = $('#clientBuildingNumber_1').val();
                clientBuildingName = $('#clientBuildingName_1').val();
                clientAddrline1 = $('#clientAddrline1_1').val();
                clientAddrtown = $('#clientAddrtown_1').val();
                clientCounty = $('#clientCounty_1').val();

                addressId = $('#clientAddressId_1').val();

                const ajaxRequest = tcp.xhr.post('/cases/user/create', {
                    title,
                    forenames,
                    surname,
                    email,
                    phone,
                    mobile,
                    addressId,
                    caseId,
                    customerNumber,
                });

                ajaxRequest.done((responseData) => {
                    $(`#userId_${currentnum}`).val(responseData.user_id);

                    let clientName = `<p data-user-id="${responseData.user_id}" class="client-fullname client-fullname_${currentnum}">`;
                    clientName += `${responseData.title} ${responseData.forenames} ${responseData.surname}`;
                    clientName += '</p>';
                    $('.client-names-address-td').append(clientName);

                    $(`#clientNamesAddressTd_1 .client-fullname_${currentnum}`).show();

                    $(`#title_${currentnum}`).val(responseData.title).hide();
                    $(`#titleSpan_${currentnum}`).text(responseData.title).show();
                    $(`#titleTd_${currentnum}`).addClass('table-cell');

                    $(`#forenames_${currentnum}`).val(responseData.forenames).hide();
                    $(`#forenamesSpan_${currentnum}`).text(responseData.forenames).show();
                    $(`#forenamesTd_${currentnum}`).addClass('table-cell');

                    $(`#surname_${currentnum}`).val(responseData.surname).hide();
                    $(`#surnameSpan_${currentnum}`).text(responseData.surname).show();
                    $(`#surnameTd_${currentnum}`).addClass('table-cell');

                    $(`#email_${currentnum}`).val(responseData.email).hide();
                    $(`#emailSpan_${currentnum}`).text(responseData.email).show();
                    $(`#emailTd_${currentnum}`).addClass('table-cell');

                    $(`#phone_${currentnum}`).val(responseData.phone).hide();
                    $(`#phoneSpan_${currentnum}`).text(responseData.phone).show();
                    $(`#phoneTd_${currentnum}`).addClass('table-cell');

                    $(`#mobile_${currentnum}`).val(responseData.mobile).hide();
                    $(`#mobileSpan_${currentnum}`).text(responseData.mobile).show();
                    $(`#mobileTd_${currentnum}`).addClass('table-cell');

                    $(`#clientType_${currentnum}`).text('New');

                    $(`#clientAddressId_${currentnum}`).val(responseData.address_id);

                    $(`#saveBtn_${currentnum}`).hide();

                    $('#clientAddressDetails').fadeIn();

                    /*                      $("#transactionDetails").fadeIn();
                                          $("#transactionAddressDetails").fadeIn();*/

                    $('#savedMessage').fadeIn().fadeOut(5000);
                });

                ajaxRequest.fail((error) => {
                    console.error(error);
                    $('#failedToSaveMessage').fadeIn().fadeOut(5000);
                });
            } else if (userId !== 'create' && newClient === 0) { // existing client
                const ajaxRequest = tcp.xhr.post(`/cases/user${userId}`, data);

                ajaxRequest.done((responseData) => {
                    $(`#title_${currentnum}`).val(title).hide();
                    $(`#titleSpan_${currentnum}`).text(title).show();
                    $(`#titleTd_${currentnum}`).addClass('table-cell');

                    $(`#forenames_${currentnum}`).val(forenames).hide();
                    $(`#forenamesSpan_${currentnum}`).text(forenames).show();
                    $(`#forenamesTd_${currentnum}`).addClass('table-cell');

                    $(`#surname_${currentnum}`).val(surname).hide();
                    $(`#surnameSpan_${currentnum}`).text(surname).show();
                    $(`#surnameTd_${currentnum}`).addClass('table-cell');

                    $(`#email_${currentnum}`).val(email).hide();
                    $(`#emailSpan_${currentnum}`).text(email).show();
                    $(`#emailTd_${currentnum}`).addClass('table-cell');

                    $(`#phone_${currentnum}`).val(phone).hide();
                    $(`#phoneSpan_${currentnum}`).text(phone).show();
                    $(`#phoneTd_${currentnum}`).addClass('table-cell');

                    $(`#mobile_${currentnum}`).val(mobile).hide();
                    $(`#mobileSpan_${currentnum}`).text(mobile).show();
                    $(`#mobileTd_${currentnum}`).addClass('table-cell');

                    $(`#clientType_${currentnum}`).text('Existing');

                    $(`#clientPostcode_${currentnum}`)
                        .addClass('required')
                        .addClass('client-address-detail')
                        .hide();

                    $(`#clientPostcodeSpan_${currentnum}`)
                        .text(clientPostcode)
                        .show();

                    $(`#clientPostcodeTd_${currentnum}`)
                        .addClass('table-cell-postcode');

                    $(`#findClientAddressBtn_${currentnum}`).hide();
                    $(`#changeAddress_${currentnum}`).show();

                    $(`#clientAddr_${currentnum}`).hide();

                    $(`#clientBuildingNumber_${currentnum}`)
                        .addClass('required')
                        .addClass('client-address-detail')
                        .hide();

                    $(`#clientBuildingNumberSpan_${currentnum}`)
                        .text(clientBuildingNumber)
                        .show();

                    $(`#clientBuildingNameSpan_${currentnum}`)
                        .text(clientBuildingName)
                        .show();

                    $(`#clientAddrline1_${currentnum}`)
                        .addClass('client-address-detail')
                        .hide();

                    $(`#clientAddrline1Span_${currentnum}`)
                        .text(clientAddrline1)
                        .show();

                    $(`#clientAddrtown_${currentnum}`)
                        .addClass('client-address-detail')
                        .hide();

                    $(`#clientAddrtownSpan_${currentnum}`)
                        .text(clientAddrtown)
                        .show();

                    $(`#clientCounty_${currentnum}`)
                        .addClass('client-address-detail')
                        .hide();

                    $(`#clientCountySpan_${currentnum}`)
                        .text(clientCounty)
                        .show();

                    $('.save-client-address-btn').hide();
                    $(`#saveBtn_${currentnum}`).hide();

                    $(`#clientAddressId_${currentnum}`).val(responseData.address_id);

                    $('#savedMessage').fadeIn().fadeOut(5000);

                    $('#clientAddressDetails').fadeIn();

                    /*                        $("#transactionDetails").fadeIn();
                                            $("#transactionAddressDetails").fadeIn();*/
                });

                ajaxRequest.fail((error) => {
                    console.error(error);
                    $('#failedToSaveMessage').fadeIn().fadeOut(5000);
                });
            }
        }
    });

    form.on('change', '.transaction-address-detail', () => {
        $('#transactionAddressRepeat').prop('checked', false);
    });

    form.on('click', '#saveCaseBtn', (ev) => {
        ev.preventDefault();

        let success = true;

        $('.case-detail.required').each(function forEachRequiredField() {
            if ($(this).val() === '') {
                const fieldNameCapitalized =
                    $(this)
                        .attr('id')
                        .charAt(0)
                        .toUpperCase() + $(this)
                        .attr('id')
                        .slice(1) // this will capitalize the first letter e.g. leadSource will become LeadSource
                        .replace(/([a-z])([A-Z])/g, '$1 $2'); // this will add a space after the capital letter e.g. LeadSource will become Lead Source

                notify(`${fieldNameCapitalized} is a required field.`);

                success = false;
            }
        });

        const transactionAddressBuildingNumberElem = $('#transactionAddressBuildingNumber');
        if (transactionAddressBuildingNumberElem.val() === '' && transactionAddressBuildingNumberElem.val() === '') {
            notify('You must enter a Building Number and/or Building Name.');

            success = false;
        }

        if (success) {
            const caseId = $('#caseId').val();
            const leadSource = $('#leadSource').val();
            const type = $('#type').val();
            const transactionPostcode = $('#transactionPostcode').val();
            const transactionAddressBuildingNumber = transactionAddressBuildingNumberElem.val();
            const transactionAddressBuildingName = $('#transactionAddressBuildingName').val();
            const transactionAddressAddrLine1 = $('#transactionAddressAddrLine1').val();
            const transactionAddressTown = $('#transactionAddressTown').val();
            const transactionAddressCounty = $('#transactionAddressCounty').val();
            const price = $('#price').val().replace(/,/g, '');
            const tenure = $('#tenure').val();

            let mortgage = $('#mortgage').val();
            if (mortgage === '1') {
                mortgage = 'Yes';
            } else {
                mortgage = 'No';
            }

            let searchesRequired = $('#searchesRequired').val();
            if (searchesRequired === '1') {
                searchesRequired = 'Yes';
            } else {
                searchesRequired = 'No';
            }

            const userIdsLength = $('.userIds').length;
            $('#countUsers').val(userIdsLength);

            const userIds = [];
            for (let i = 1; i <= userIdsLength; i += 1) {
                userIds.push($(`#userId_${i}`).val());
            }

            const transactionAddressRepeat = isCheckboxTicked('transactionAddressRepeat');

            const clientPostcode = $('#clientPostcode_1').val();
            const clientBuildingNumber = $('#clientBuildingNumber_1').val();
            const clientBuildingName = $('#clientBuildingName_1').val();
            const clientAddrline1 = $('#clientAddrline1_1').val();
            const clientAddrtown = $('#clientAddrtown_1').val();
            const clientCounty = $('#clientCounty_1').val();

            if (
                clientPostcode !== transactionPostcode ||
                clientBuildingNumber !== transactionAddressBuildingNumber ||
                clientBuildingName !== transactionAddressBuildingName ||
                clientAddrline1 !== transactionAddressAddrLine1 ||
                clientAddrtown !== transactionAddressTown ||
                clientCounty !== transactionAddressCounty
            ) {
                $('#transactionAddressRepeat').prop('checked', false);
            }

            const requestData = {
                caseId,
                type,
                lead_source: leadSource,
                transactionPostcode,
                transactionAddressBuildingNumber,
                transactionAddressBuildingName,
                transactionAddressAddrLine1,
                transactionAddressTown,
                transactionAddressCounty,
                price,
                tenure,
                mortgage,
                searches_required: searchesRequired,
                userIds,
            };

            if (caseId === 'create') {
                const ajaxRequest = tcp.xhr.post('/cases/case/create', requestData);

                ajaxRequest.done((responseData) => {
                    let url = '';

                    const userTypeLoggedIn = $('#userType').text();
                    if (userTypeLoggedIn === 'admin') {
                        url = `<a target="_blank" href="cases/${responseData.reference}">${responseData.reference}</a>`;
                    } else {
                        url = `<a target="_blank" href="cases/${responseData.reference}">${responseData.reference}</a>`;
                    }

                    $('#caseReference').html(url);
                    $('#caseType').text(responseData.type);
                    $('#caseStatus').text(responseData.status);
                    $('#accountManager').text(responseData.accountManager);
                    $('#caseSummary').fadeIn();
                    $('#caseId').val(responseData.case_id);

                    $('#type').val(responseData.type).hide();
                    $('#typeSpan').text(responseData.type).show();
                    $('#typeTd').addClass('table-cell-case');

                    $('#leadSource').val(responseData.lead_source).hide();
                    $('#leadSourceSpan').text(responseData.lead_source).show();
                    $('#leadSourceTd').addClass('table-cell-case');

                    $('#price')
                        .val(parseFloat(price).toFixed(2)).digits()
                        .hide();

                    $('#priceSpan')
                        .text(parseFloat(price).toFixed(2)).digits()
                        .show();

                    $('#priceTd').addClass('table-cell-case');

                    $('#tenure').val(tenure).hide();
                    $('#tenureSpan').text(tenure).show();
                    $('#tenureTd').addClass('table-cell-case');

                    $('#mortgage').val(mortgage).hide();
                    $('#mortgageSpan').text(mortgage).show();
                    $('#mortgageTd').addClass('table-cell-case');

                    $('#searchesRequired').val(searchesRequired).hide();
                    $('#searchesRequiredSpan').text(searchesRequired).show();
                    $('#searchesRequiredTd').addClass('table-cell-case');

                    $('#transactionPostcode')
                        .val(transactionPostcode)
                        .hide();

                    $('#transactionPostcodeSpan')
                        .text(transactionPostcode)
                        .show();

                    $('#transactionPostcodeTd').addClass('table-cell-transaction-postcode');

                    $('#transactionAddressRepeatCheckboxDiv').hide();

                    if (transactionAddressRepeat) {
                        $('#transactionAddressRepeatSpan').show();
                    } else {
                        $('#transactionAddressRepeatSpan').hide();
                    }

                    $('#transactionAddressResults').hide();

                    $('#transactionAddressBuildingNumber').val(transactionAddressBuildingNumber).hide();
                    $('#transactionAddressBuildingNumberSpan').text(transactionAddressBuildingNumber).show();

                    $('#transactionAddressBuildingName').val(transactionAddressBuildingName).hide();
                    $('#transactionAddressBuildingNameSpan').text(transactionAddressBuildingName).show();

                    $('#transactionAddressAddrLine1').val(transactionAddressAddrLine1).hide();
                    $('#transactionAddressAddrLine1Span').text(transactionAddressAddrLine1).show();

                    $('#transactionAddressTown').val(transactionAddressTown).hide();
                    $('#transactionAddressTownSpan').text(transactionAddressTown).show();

                    $('#transactionAddressCounty').val(transactionAddressCounty).hide();
                    $('#transactionAddressCountySpan').text(transactionAddressCounty).show();

                    $('#saveCaseBtn').hide();

                    $('#notesTable tr td:nth-child(1)')
                        .text(responseData.note_date_created);
                    $('#notesTable tr td:nth-child(2)')
                        .text(responseData.note_staff);
                    $('#notesTable tr td:nth-child(3)')
                        .text(responseData.note_content);
                    $('#notesTable tr td:nth-child(4)')
                        .text('Yes');

                    $('#notesTable').show();

                    $('#agencyDetails').fadeIn();

                    $('#savedMessage').fadeIn().fadeOut(5000);
                });

                ajaxRequest.fail((error) => {
                    console.error(error);
                    $('#failedToSaveMessage').fadeIn().fadeOut(5000);
                });
            } else {
                const ajaxRequest = tcp.xhr.post('/cases/updatecaseaddress', {
                    caseId,
                    transactionPostcode,
                    transactionAddressBuildingNumber,
                    transactionAddressBuildingName,
                    transactionAddressAddrLine1,
                    transactionAddressTown,
                    transactionAddressCounty,
                });

                ajaxRequest.done(() => {
                    $('#transactionPostcode')
                        .val(transactionPostcode)
                        .hide();

                    $('#transactionPostcodeSpan')
                        .text(transactionPostcode)
                        .show();

                    $('#transactionPostcodeTd').addClass('table-cell-transaction-postcode');

                    $('#transactionAddressRepeatCheckboxDiv').hide();

                    if (transactionAddressRepeat) {
                        $('#transactionAddressRepeatSpan').show();
                    } else {
                        $('#transactionAddressRepeatSpan').hide();
                    }

                    $('#transactionAddressResults').hide();

                    $('#transactionAddressBuildingNumber').val(transactionAddressBuildingNumber).hide();
                    $('#transactionAddressBuildingNumberSpan').text(transactionAddressBuildingNumber).show();

                    $('#transactionAddressBuildingName').val(transactionAddressBuildingName).hide();
                    $('#transactionAddressBuildingNameSpan').text(transactionAddressBuildingName).show();

                    $('#transactionAddressAddrLine1').val(transactionAddressAddrLine1).hide();
                    $('#transactionAddressAddrLine1Span').text(transactionAddressAddrLine1).show();

                    $('#transactionAddressTown').val(transactionAddressTown).hide();
                    $('#transactionAddressTownSpan').text(transactionAddressTown).show();

                    $('#transactionAddressCounty').val(transactionAddressCounty).hide();
                    $('#transactionAddressCountySpan').text(transactionAddressCounty).show();

                    $('#saveCaseBtn').hide();

                    $('#agencyDetails').fadeIn();

                    $('#savedMessage').fadeIn().fadeOut(5000);
                });

                ajaxRequest.fail((error) => {
                    console.error(error);
                    $('#failedToSaveMessage').fadeIn().fadeOut(5000);
                });
            }
        }
    });


    form.on('click', '.selectElement', function onSelectClick() {
        const currentnum = parseInt($(this).attr('id').match(/\d+/g), 10);
        const userElem = $(`#userId_${currentnum}`);
        const user = userElem.val();
        if (user !== 'create') {
            const value = $(this).val();
            const field = $(this).prop('name');
            const table = $(this).attr('data-table');
            const id = userElem.val();

            const data = {

                id,
                table,
                field,
                value,

            };

            const ajaxRequest = tcp.xhr.post('/cases/update', data);

            ajaxRequest.done(() => {
                $('#savedMessage').fadeIn().fadeOut(5000);
            });

            ajaxRequest.fail((error) => {
                console.error(error);
                $('#failedToSaveMessage').fadeIn().fadeOut(5000);
            });
        }
    });

    form.on('click', '.table-cell-transaction-postcode', function onPostcodeClick() {
        let value;

        $(this).removeClass('table-cell-transaction-postcode');

        $('#transactionAddressResults').show();

        $('#transactionAddress').show();

        const postcodeSpan = $('#transactionPostcodeSpan');
        value = postcodeSpan.text();
        $('#transactionPostcode').val(value).show();
        postcodeSpan.hide();

        $('#transactionAddressRepeatSpan').hide();
        $('#transactionAddressRepeatCheckboxDiv').show();

        const buildingNumber = $('#transactionAddressBuildingNumberSpan');
        value = buildingNumber.text();
        $('#transactionAddressBuildingNumber').val(value).show();
        buildingNumber.hide();

        const buildingName = $('#transactionAddressBuildingNameSpan');
        value = buildingName.text();
        $('#transactionAddressBuildingName').val(value).show();
        buildingName.hide();

        const addrLine1 = $('#transactionAddressAddrLine1Span');
        value = addrLine1.text();
        $('#transactionAddressAddrLine1').val(value).show();
        addrLine1.hide();

        const addrTown = $('#transactionAddressTownSpan');
        value = addrTown.text();
        $('#transactionAddressTown').val(value).show();
        addrTown.hide();

        const addrCounty = $('#transactionAddressCountySpan');
        value = addrCounty.text();
        $('#transactionAddressCounty').val(value).show();
        addrCounty.hide();

        $('#saveCaseBtn').show();
    });

    form.on('click', '.table-cell-postcode', function onPostcodeClick() {
        $(this).removeClass('table-cell-postcode');

        const currentnum = parseInt($(this).attr('id').match(/\d+/g), 10);
        let value;

        $('.save-btn').hide();
        $('.save-client-address-btn').hide();

        $('.save-address')
            .attr('id', `saveAddressBtn_${currentnum}`)
            .show();

        $(`#clientResults_${currentnum}`).show();

        const postcode = $(`#clientPostcodeSpan_${currentnum}`);
        value = postcode.text();
        $(`#clientPostcode_${currentnum}`).val(value).show();
        postcode.hide();

        const buildingNumber = $(`#clientBuildingNumberSpan_${currentnum}`);
        value = buildingNumber.text();
        $(`#clientBuildingNumber_${currentnum}`).val(value).show();
        buildingNumber.hide();

        const buildingName = $(`#clientBuildingNameSpan_${currentnum}`);
        value = buildingName.text();
        $(`#clientBuildingName_${currentnum}`).val(value).show();
        buildingName.hide();

        const addrLine1 = $(`#clientAddrline1Span_${currentnum}`);
        value = addrLine1.text();
        $(`#clientAddrline1_${currentnum}`).val(value).show();
        addrLine1.hide();

        const addrTown = $(`#clientAddrtownSpan_${currentnum}`);
        value = addrTown.text();
        $(`#clientAddrtown_${currentnum}`).val(value).show();
        addrTown.hide();

        const clientCounty = $(`#clientCountySpan_${currentnum}`);
        value = clientCounty.text();
        $(`#clientCounty_${currentnum}`).val(value).show();
        clientCounty.hide();

        // show the select element which have the clients name in them
        const userIds = [];
        $('.userIds').each(function forEachUserId() {
            userIds.push($(this).val());
        });

        const displayedUserIds = [];
        const clientFullNameElem = $(`#clientNamesAddressTd_${currentnum} .client-fullname`);
        clientFullNameElem.each(function forEachClientFullName() {
            if ($(this).is(':visible')) {
                displayedUserIds.push($(this).attr('data-user-id'));
            }
        });

        // hide paragraphs which have the clients name in them
        clientFullNameElem.hide();

        const match = '';
        let selectElement = '<select multiple name="clients[]" id="clientsMulti" class="required client-address-detail">';
        for (
            let index = 0,
                clientFullName = 1;
            clientFullName <= userIds.length;
            clientFullName += 1,
            index += 1
        ) {
            selectElement += `<option ${match} value="${userIds[index]}">${$(`#clientNamesAddressTd_${currentnum} .client-fullname_${clientFullName}`).text()}</option>`;
        }
        selectElement += '</select>';


        $(`#clientNamesAddressTd_${currentnum}`).append(selectElement);

        $('#clientsMulti option').each(function forEachClientOption() {
            if (jQuery.inArray($(this).val(), displayedUserIds) !== -1) {
                $(this).prop('selected', true);
            }
        });
    });

    form.on('click', '#saveSolicitorDetailsBtn', () => {
        const caseId = $('#caseId').val();
        const solicitorId = $('#solicitorId').val();
        const solicitorOfficeId = $('#solicitorOfficeId').val();
        const solicitorUserId = $('#solicitorUserId').val();

        const data = {

            caseId,
            solicitor_id: solicitorId,
            solicitor_office_id: solicitorOfficeId,
            solicitor_user_id: solicitorUserId,

        };

        const ajaxRequest = tcp.xhr.post('/cases/savesolicitor', data);

        ajaxRequest.done(() => {
            const solicitorIdElem = $('#solicitorId');
            const solicitorOfficeIdElem = $('#solicitorOfficeId');
            const solicitorUserIdElem = $('#solicitorUserId');

            // hide the select input elements
            solicitorIdElem.hide();
            solicitorOfficeIdElem.hide();
            solicitorUserIdElem.hide();

            // display the span text elements
            $('#solicitorIdSpan').show();
            solicitorIdElem.parent().addClass('table-cell-solicitor');

            $('#solicitorOfficeIdSpan').show();
            solicitorOfficeIdElem.parent().addClass('table-cell-solicitor');

            $('#solicitorUserIdSpan')
                .text($('#solicitorUserId option:selected').text())
                .attr('data-selected-solicitor-user-id', solicitorUserIdElem.val())
                .show();

            solicitorUserIdElem.parent().addClass('table-cell-solicitor');

            $('#amlDetails').fadeIn();

            $('#disbursementDetails').fadeIn();

            $('#instructionNotes').fadeIn();

            $('#savedMessage').fadeIn().fadeOut(5000);
        });
    });

    form.on('click', '#saveAgencyDetailsBtn', () => {
        const caseId = $('#caseId').val();
        const agencyId = $('#agencyId').val();
        const branchId = $('#agencyBranchId').val();
        const userIdAgent = $('#userIdAgent').val();
        const userIdStaff = $('#userIdStaff').val();

        const data = {

            caseId,
            agency_id: agencyId,
            agency_branch_id: branchId,
            user_id_agent: userIdAgent,
            user_id_staff: userIdStaff,

        };

        const ajaxRequest = tcp.xhr.post('/cases/saveagency', data);

        ajaxRequest.done(() => {
            // hide the select input elements
            const agencyIdElem = $('#agencyId');
            const agencyBranchIdElem = $('#agencyBranchId');
            const userIdAgentElem = $('#userIdAgent');
            const userIdStaffElem = $('#userIdStaff');
            const userIdStaffSpanElem = $('#userIdStaffSpan');
            agencyIdElem.hide();
            agencyBranchIdElem.hide();
            userIdAgentElem.hide();
            userIdStaffElem.hide();

            // display the span text elements
            $('#agencyIdSpan').show();
            agencyIdElem.parent().addClass('table-cell-agency');

            $('#agencyBranchIdSpan').show();
            agencyBranchIdElem.parent().addClass('table-cell-agency');

            $('#userIdAgentSpan').show();
            userIdAgentElem.parent().addClass('table-cell-agency');

            userIdStaffSpanElem
                .text($('#userIdStaff option:selected').text())
                .attr('data-selected-user-id-staff', userIdStaffElem.val())
                .show();

            userIdStaffElem.parent().addClass('table-cell-case');

            // this is to update the Account Manager span in the Case Summary at the top
            $('#accountManager').text(userIdStaffSpanElem.text());

            $('#solicitorDetails').fadeIn();

            $('#savedMessage').fadeIn().fadeOut(5000);
        });
    });

    form.on('click', '#instructionNoteEditableTd', function onInstructionNoteEditableClick() {
        let instructionNote;

        const instructionNoteInputElem = $('#instructionNoteInput');
        const instructionNoteTextElem = $('#instructionNoteText');

        $('#instructionNoteEditableTd').attr('id', '');

        instructionNoteTextElem.hide();
        instructionNote = instructionNoteTextElem.text();

        if ($(this).hasClass('empty')) {
            instructionNote = '';
            $(this).removeClass('empty');
        }

        instructionNoteInputElem.val(instructionNote).show();

        const inputElement = instructionNoteInputElem;
        const dataStatus = instructionNoteTextElem.attr('data-status');

        setEditing(this, () => {
            processInstructionNoteData(inputElement, instructionNote, dataStatus);
        });
    });

    form.on('click', '.table-cell-agency', function onClickAgencyCell() {
        $(this).removeClass('table-cell-agency');

        const id = $(this).attr('data-id');

        if (id === 'agencyId') {
            updateAgency();
            updateBranch();
            updateAgent();
        } else if (id === 'agencyBranchId') {
            updateBranch();
            updateAgent();
        } else {
            updateAgent();
        }
    });

    function updateSolicitor() {
        const solicitorId = $('#solicitorIdSpan');
        const originalValue = solicitorId.attr('data-selected-solicitor-id');
        $('#solicitorId')
            .val(originalValue)
            .show();

        solicitorId.hide();
    }

    function updateOffice() {
        const solicitorOfficeId = $('#solicitorOfficeIdSpan');
        const originalValue = solicitorOfficeId.attr('data-selected-solicitor-office-id');
        $('#solicitorOfficeId')
            .val(originalValue)
            .show();

        solicitorOfficeId.hide();
    }

    function updateOfficeUser() {
        const solicitorUserId = $('#solicitorUserIdSpan');
        const originalValue = solicitorUserId.attr('data-selected-solicitor-user-id');
        $('#solicitorUserId')
            .val(originalValue)
            .show();

        solicitorUserId.hide();
    }

    form.on('click', '.table-cell-solicitor', function onClickSolicitorCell() {
        $(this).removeClass('table-cell-solicitor');

        const id = $(this).attr('data-id');

        if (id === 'solicitorId') {
            updateSolicitor();
            updateOffice();
            updateOfficeUser();
        } else if (id === 'solicitorOfficeId') {
            updateOffice();
            updateOfficeUser();
        } else {
            updateOfficeUser();
        }
    });


    form.on('click', '.table-cell-case', function onClickCaseCell() {
        $(this).removeClass('table-cell-case');

        const field = $(this).attr('data-field');

        const fieldCamelCase = $(this).attr('data-id');
        const fieldSpanElem = $(`#${fieldCamelCase}Span`);
        const fieldElem = $(`#${fieldCamelCase}`);

        let originalValue = fieldSpanElem.text();

        if (field === 'user_id_staff') {
            originalValue = $('#userIdStaffSpan').attr('data-selected-user-id-staff');
        }

        if (field === 'lead_source') {
            originalValue = getLeadSource(originalValue);
        }

        if (field === 'mortgage' || field === 'src_required') {
            if (originalValue === 'Yes') {
                originalValue = 1;
            } else {
                originalValue = 0;
            }
        }

        fieldElem.val(originalValue).show();
        fieldSpanElem.hide();

        const inputElement = fieldElem;

        setEditing(this, () => {
            processCaseData(
                field,
                fieldCamelCase,
                inputElement,
                originalValue,
            );
        });
    });

    form.on('click', '.table-cell', function onClickTableCell() {
        $(this).removeClass('table-cell');

        const field = $(this).attr('data-field');
        const model = $(this).attr('data-model');
        const currentnum = parseInt($(this).attr('id').match(/\d+/g), 10);
        const currentFieldSpanElem = $(`#${field}Span_${currentnum}`);
        const currentFieldElem = $(`#${field}_${currentnum}`);
        const value = currentFieldSpanElem.text();

        currentFieldElem.val(value).show();
        currentFieldSpanElem.hide();

        const inputElement = currentFieldElem;

        setEditing(this, () => {
            processData(field, model, inputElement, currentnum, value);
        });
    });

    form.on('click', '#saveAddressBtn', (ev) => {
        ev.preventDefault();

        const id = $('#caseId').val();

        const postcode = $('#transactionPostcode').val();
        const buildingNumber = $('#transactionAddressBuildingNumber').val();
        const addressLine1 = $('#transactionAddressAddrLine1').val();
        const town = $('#transactionAddressTown').val();
        const county = $('#transactionAddressCounty').val();

        const data = {
            id,
            postcode,
            building_number: buildingNumber,
            address_line_1: addressLine1,
            town,
            county,
        };

        const ajaxRequest = tcp.xhr.post('/cases/updatecaseaddress', data);

        ajaxRequest.done(() => {
            $('#transactionPostcode').hide();
            $('#findTransactionAddressBtn').hide();
            $('#saveAddressBtn').hide();
            $('#transactionAddressResults').hide();
            $('#transactionAddressBuildingNumber').hide();
            $('#transactionAddressAddrLine1').hide();
            $('#transactionAddressTown').hide();
            $('#transactionAddressCounty').hide();

            $('#transactionPostcodeSpan')
                .text(postcode)
                .show();

            $('#changeAddress').show();
            $('#transactionPostcodeTd').addClass('table-cell-transaction-postcode');

            $('#transactionAddressBuildingNumberSpan')
                .text(buildingNumber)
                .show();

            $('#transactionAddressAddrLine1Span')
                .text(addressLine1)
                .show();

            $('#transactionAddressTownSpan')
                .text(town)
                .show();

            $('#transactionAddressCountySpan')
                .text(county)
                .show();

            $('#savedMessage').fadeIn().fadeOut(5000);
        });

        ajaxRequest.fail(() => {
            $('#failedToSaveMessage').fadeIn().fadeOut(5000);
        });
    });

    form.on('click', '.save-address', function onClickSaveAddress(ev) {
        ev.preventDefault();

        const currentnum = parseInt($(this).attr('id').match(/\d+/g), 10);

        /** If the user in the select element list is not selected then we need to check if they are assigned to another address.
         * If they are assigned to another address then that's no problem.
         * If they are not assigned to another address then they will not be able to save this change
         * as every client needs to be assigned to at least one address.
         */
        const nonSelectedOptions = [];
        $('#clientsMulti option:not(:selected)').each(function forEachOption() {
            nonSelectedOptions.push($(this).val());
        });

        // get row count of client addresses
        const clientAddressRowCount = $('.client-address-row').length;

        // need to do a check based on if there's only one address row
        if (clientAddressRowCount === 1) {
            if (nonSelectedOptions.length > 0) {
                notify('All clients must be assigned to at least one address.\n\nPlease select the relevant client/s from the dropdown list and try saving again or create another address and assign the relevant client/s to it.');
                return false;
            }
        } else if (clientAddressRowCount > 1 && nonSelectedOptions.length > 0) {
            let count = 0;

            const forEachField = function forEachField() {
                if ($(this).is(':visible')) {
                    count += 1;
                }
            };

            for (let i = 0; i <= nonSelectedOptions.length; i += 1) {
                $(`[data-user-id="${nonSelectedOptions[i]}"]`).each(forEachField);
            }

            if (!count) {
                notify('All clients must be assigned to at least one address.\n\nPlease select the relevant client/s from the dropdown list and try saving again or create another address and assign the relevant client/s to it.');
                return false;
            }

            return true;
        }

        const userIds = $('#clientsMulti').val();

        const addressId = $(`#clientAddressId_${currentnum}`).val();

        const postcode = $(`#clientPostcode_${currentnum}`).val();
        const buildingNumber = $(`#clientBuildingNumber_${currentnum}`).val();
        const addressLine1 = $(`#clientAddrline1_${currentnum}`).val();
        const town = $(`#clientAddrtown_${currentnum}`).val();
        const county = $(`#clientCounty_${currentnum}`).val();

        let success = true;

        $(`#clientPersonalDetailsTr_${currentnum} .client-personal-details.required`).each(function forEachClientDetails() {
            if ($(this).val() === '') {
                let fieldNameCapitalized =
                    $(this)
                        .attr('id')
                        .charAt(0)
                        .toUpperCase() +
                    $(this)
                        .attr('id')
                        .slice(1);

                fieldNameCapitalized =
                    fieldNameCapitalized
                        .slice(0, -2)
                        .replace(/([a-z])([A-Z])/g, '$1 $2'); // this will add a space after the capital letter e.g. LeadSource will become Lead Source;

                notify(`${fieldNameCapitalized} is a required field.`);

                success = false;
            }
        });

        // this is for the postcode and building number
        $('.client-address-details.required').each(function forEachRequiredField() {
            if ($(this).val() === '') {
                let fieldNameCapitalized =
                    $(this)
                        .attr('id')
                        .charAt(0)
                        .toUpperCase() +
                    $(this)
                        .attr('id')
                        .slice(1);

                fieldNameCapitalized =
                    fieldNameCapitalized
                        .slice(0, -2)
                        .replace(/([a-z])([A-Z])/g, '$1 $2'); // this will add a space after the capital letter e.g. LeadSource will become Lead Source;

                notify(`${fieldNameCapitalized} is a required field.`);

                success = false;
            }
        });

        if (success) {
            const data = {

                addressId,
                userIds,
                postcode,
                building_number: buildingNumber,
                address_line_1: addressLine1,
                town,
                county,

            };

            const ajaxRequest = tcp.xhr.post('/cases/updateclientaddress', data);

            ajaxRequest.done(() => {
                $(`#clientPostcode_${currentnum}`).hide();
                $(`#findClientAddressBtn_${currentnum}`).hide();
                $(`#saveAddressBtn_${currentnum}`).hide();
                $(`#clientResults_${currentnum}`).hide();
                $(`#clientBuildingNumber_${currentnum}`).hide();
                $(`#clientAddrline1_${currentnum}`).hide();
                $(`#clientAddrtown_${currentnum}`).hide();
                $(`#clientCounty_${currentnum}`).hide();

                $(`#clientPostcodeSpan_${currentnum}`).show();
                $(`#changeAddress_${currentnum}`).show();
                $(`#clientPostcodeTd_${currentnum}`).addClass('table-cell-postcode');

                $(`#clientBuildingNumberSpan_${currentnum}`)
                    .text(buildingNumber)
                    .show();

                $(`#clientAddrline1Span_${currentnum}`)
                    .text(addressLine1)
                    .show();

                $(`#clientAddrtownSpan_${currentnum}`)
                    .text(town)
                    .show();

                $(`#clientCountySpan_${currentnum}`)
                    .text(county)
                    .show();

                // show the p elements where the data-user-id attribute value matches the value in the selected options
                $(`#clientNamesAddressTd_${currentnum} .client-fullname`).each(function forEachFullName() {
                    if (jQuery.inArray($(this).attr('data-user-id'), userIds) !== -1) {
                        $(this).show();
                    }
                });

                $('#clientsMulti').remove();

                $('#savedMessage').fadeIn().fadeOut(5000);
            });

            ajaxRequest.fail((error) => {
                console.error(error);
                $('#failedToSaveMessage').fadeIn().fadeOut(5000);
            });
        }

        return true;
    });

    form.on('click', '#addClientBtn', (ev) => {
        ev.preventDefault();

        /*        var userId1 = $("#userId_1").val();
                if(userId1 === 'create') {

                    notify('You need to add the first client before being able to add another.');
                    return false;

                 }*/

        // get the last DIV which ID starts with ^= "clientRow"
        // var tr = $('tr[id^="clientRow"]:last');
        const tr = $('tr[id^="clientPersonalDetailsTr_"]:last');
        // var table = $('table[id^="clientPersonalDetailsTable_"]:last');

        // Read the number from that TR's ID and increment that number by 1
        const currentnum = parseInt(tr.attr('id').match(/\d+/g), 10);
        const num = parseInt(tr.attr('id').match(/\d+/g), 10) + 1;

        // Clone the TR and assign the new ID
        const nextCustomer = tr.clone().attr('id', `clientPersonalDetailsTr_${num}`);

        nextCustomer
            .removeClass(`client-row_${currentnum}`)
            .addClass(`client-row_${num}`);

        nextCustomer.find(`#clientName_${currentnum}`)
            .attr('id', `clientName_${num}`);

        nextCustomer.find(`#clientName_${num}`)
            .text('');

        nextCustomer.find(`#userId_${currentnum}`)
            .attr('id', `userId_${num}`);

        nextCustomer.find(`#userId_${num}`)
            .prop('name', `userId_${num}`);

        nextCustomer.find(`#userId_${num}`)
            .val('create');

        nextCustomer.find(`#autosuggest_${currentnum}`)
            .attr('id', `autosuggest_${num}`);

        nextCustomer.find(`#customerNumber_${currentnum}`)
            .attr('id', `customerNumber_${num}`);

        nextCustomer.find(`#customerNumber_${num}`)
            .val(num);

        nextCustomer.find(`#customerNumber_${num}`)
            .prop('name', `customerNumber_${num}`);

        const elements =
            [
                'title',
                'forenames',
                'surname',
                'email',
                'phone',
                'mobile',
            ];

        elements.forEach((element) => {
            nextCustomer.find(`#${element}Td_${currentnum}`).attr('id', `${element}Td_${num}`);
            nextCustomer.find(`#${element}Td_${num}`).removeClass('table-cell');
            nextCustomer.find(`#${element}_${currentnum}`).attr('id', `${element}_${num}`);
            nextCustomer.find(`#${element}_${num}`).val('');
            nextCustomer.find(`#${element}Span_${currentnum}`).attr('id', `${element}Span_${num}`);
            nextCustomer.find(`#${element}Span_${num}`).text('');
            nextCustomer.find(`#${element}_${num}`).show();
        });

        nextCustomer.find(`#newClientTd_${currentnum}`).attr('id', `newClientTd_${num}`);
        nextCustomer.find(`#clientType_${currentnum}`).attr('id', `clientType_${num}`);
        nextCustomer.find(`#clientType_${num}`).text('New');
        nextCustomer.find(`#clearClientBtn_${currentnum}`).attr('id', `clearClientBtn_${num}`);


        nextCustomer.find(`#removeClientBtnTd_${currentnum}`)
            .attr('id', `removeClientBtnTd_${num}`)
            .text('Remove');

        nextCustomer.find(`#removeClientBtnTd_${num}`)
            .removeClass('clear-client-td')
            .addClass('remove-client-td');

        nextCustomer.find('.remove-btn').css('display', 'inline-block');

        $('.save-btn')
            .attr('id', `saveBtn_${num}`)
            .show();

        $(tr).after(nextCustomer);
    });

    form.on('click', '#addClientAddressBtn', (ev) => {
        ev.preventDefault();

        const userId1 = $('#userId_1').val();
        if (userId1 === 'create') {
            notify('You need to add a Client before being able to add a Client Address.');
            return false;
        }

        // get the last DIV which ID starts with ^= "clientRow"
        const tr = $('tr[id^="clientAddressTr_"]:last');

        // Read the number from that TR's class (i.e: 1 from "client-row_") and increment that number by 1
        const currentnum = parseInt(tr.attr('id').match(/\d+/g), 10);
        const num = parseInt(tr.attr('id').match(/\d+/g), 10) + 1;

        // Clone the TR and assign the new ID (i.e: from num 4 to ID)
        const newAddress = tr.clone().attr('id', `clientAddressTr_${num}`);

        newAddress.find(`#clientNamesAddressTd_${currentnum}`).attr('id', `clientNamesAddressTd_${num}`);
        newAddress.find(`#clientNamesAddressTd_${num} .client-fullname`).css('display', 'none');

        const userIds = [];
        $('.userIds').each(function forEachUserId() {
            userIds.push($(this).val());
        });

        let selectElement = '<select multiple name="clients[]" id="clientsMulti" class="required client-address-detail">';
        for (let index = 0, clientFullName = 1; clientFullName <= userIds.length; clientFullName += 1, index += 1) {
            selectElement += `<option value="${userIds[index]}">${$(`#clientNamesAddressTd_1 .client-fullname_${clientFullName}`).text()}</option>`;
        }
        selectElement += '<select>';

        newAddress.find(`#clientNamesAddressTd_${num}`).append(selectElement);

        newAddress.find(`#clientAddressId_${currentnum}`).attr('id', `clientAddressId_${num}`);
        newAddress.find(`#clientAddressId_${num}`).val('create');

        newAddress.find(`#saveBtnTd_${currentnum}`).attr('id', `saveBtnTd_${num}`);
        newAddress.find(`#saveBtn_${currentnum}`).attr('id', `saveBtn_${num}`);

        newAddress.find(`#clientPostcodeTd_${currentnum}`).attr('id', `clientPostcodeTd_${num}`);
        newAddress.find(`#clientPostcodeTd_${num}`).removeClass('table-cell-postcode');
        newAddress.find(`#clientPostcode_${currentnum}`).attr('id', `clientPostcode_${num}`);
        newAddress.find(`#clientPostcode_${num}`).show();
        newAddress.find(`#clientPostcode_${num}`).val('');
        newAddress.find(`#clientPostcode_${num}`)
            .addClass('required')
            .addClass('postcode')
            .addClass('client-address-detail');
        newAddress.find(`#clientPostcodeSpan_${currentnum}`).attr('id', `clientPostcodeSpan_${num}`);
        newAddress.find(`#clientPostcodeSpan_${num}`)
            .addClass('postcode-span')
            .hide();

        newAddress.find(`#changeAddress_${currentnum}`).attr('id', `changeAddress_${num}`);
        newAddress.find(`#changeAddress_${num}`).hide();
        newAddress.find(`#saveAddressBtn_${currentnum}`).attr('id', `saveAddressBtn_${num}`);
        newAddress.find(`#saveAddressBtn_${num}`).hide();

        newAddress.find(`#clientBuildingNumberNameTd_${currentnum}`).attr('id', `clientBuildingNumberNameTd_${num}`);
        newAddress.find(`#clientBuildingNumberSpan_${currentnum}`).attr('id', `clientBuildingNumberSpan_${num}`);
        newAddress.find(`#clientBuildingNumberSpan_${num}`).hide();
        newAddress.find(`#clientBuildingNameSpan_${currentnum}`).attr('id', `clientBuildingNameSpan_${num}`);
        newAddress.find(`#clientBuildingNameSpan_${num}`).hide();

        newAddress.find(`#clientAddrLine1Td_${currentnum}`).attr('id', `clientAddrLine1Td_${num}`);
        newAddress.find(`#clientAddrline1Span_${currentnum}`).attr('id', `clientAddrline1Span_${num}`);
        newAddress.find(`#clientAddrline1Span_${num}`).hide();

        newAddress.find(`#clientAddrTownTd_${currentnum}`).attr('id', `clientAddrTownTd_${num}`);
        newAddress.find(`#clientAddrtownSpan_${currentnum}`).attr('id', `clientAddrtownSpan_${num}`);
        newAddress.find(`#clientAddrtownSpan_${num}`).hide();

        newAddress.find(`#clientCountyTd_${currentnum}`).attr('id', `clientCountyTd_${num}`);
        newAddress.find(`#clientCountySpan_${currentnum}`).attr('id', `clientCountySpan_${num}`);
        newAddress.find(`#clientCountySpan_${num}`).hide();

        newAddress.find(`#findClientAddressBtn_${currentnum}`).attr('id', `findClientAddressBtn_${num}`);
        newAddress.find(`#findClientAddressBtn_${num}`).show();
        newAddress.find(`#clientResults_${currentnum}`).attr('id', `clientResults_${num}`);
        newAddress.find(`#clientResults_${num}`).show();

        newAddress.find(`#clientAddr_${currentnum}`).attr('id', `clientAddr_${num}`);
        newAddress.find(`#clientAddr_${num}`)
            .removeClass(`find-address-results-client_${currentnum}`)
            .addClass(`find-address-results-client_${num}`)
            .html('');

        newAddress.find(`#additionalAddressDetails_${currentnum}`).attr('id', `additionalAddressDetails_${num}`);

        newAddress.find(`#clientBuildingNumber_${currentnum}`).attr('id', `clientBuildingNumber_${num}`);
        newAddress.find(`#clientBuildingNumber_${num}`).show();
        newAddress.find(`#clientBuildingNumber_${num}`).val('');
        newAddress.find(`#clientBuildingNumber_${num}`)
            .addClass('client-address-detail');

        newAddress.find(`#clientBuildingName_${currentnum}`).attr('id', `clientBuildingName_${num}`);
        newAddress.find(`#clientBuildingName_${num}`).show();
        newAddress.find(`#clientBuildingName_${num}`).val('');
        newAddress.find(`#clientBuildingName_${num}`)
            .addClass('client-address-detail');

        newAddress.find(`#clientAddrline1_${currentnum}`).attr('id', `clientAddrline1_${num}`);
        newAddress.find(`#clientAddrline1_${num}`).show();
        newAddress.find(`#clientAddrline1_${num}`).val('');

        newAddress.find(`#clientAddrtown_${currentnum}`).attr('id', `clientAddrtown_${num}`);
        newAddress.find(`#clientAddrtown_${num}`).show();
        newAddress.find(`#clientAddrtown_${num}`).val('');

        newAddress
            .find(`#clientCounty_${currentnum}`)
            .attr('id', `clientCounty_${num}`);

        newAddress.find(`#clientCounty_${num}`).show();
        newAddress.find(`#clientCounty_${num}`).val('');

        newAddress
            .find(`#saveBtnTd_${num}`)
            .attr('id', `saveClientAddressBtnTd_${num}`);

        newAddress
            .find(`#removeClientAddressTd_${currentnum}`)
            .attr('id', `removeClientAddressTd_${num}`);

        newAddress
            .find(`#removeClientAddressTd_${num}`)
            .addClass('action-field')
            .text('Remove');

        newAddress
            .find(`#saveClientAddressBtnTd_${num}`)
            .html(`<button class="btn btn-primary btn-block save-client-address-btn" id="saveClientAddressBtn_${num}">Save</button>`);

        newAddress
            .find('.remove-btn')
            .css('display', 'inline-block');

        $(tr).after(newAddress);

        // count how many Client Address rows there are
        const clientAddressRowLength = $('.client-address-row').length;

        $('.save-btn').hide();

        $('.save-client-address-btn')
            .attr('id', `saveClientAddressBtn_${clientAddressRowLength}`)
            .show();

        return true;
    });


    form.on('click', '.save-client-address-btn', function onClickSaveClientAddressButton(ev) {
        ev.preventDefault();

        const currentnum = parseInt($(this).attr('id').match(/\d+/g), 10);

        let success = true;

        if ($('#clientsMulti option:selected').index() < 0) {
            notify('Please select a Client/s');
            success = false;
        }

        $('.client-address-detail.required').each(function forEachClientAddressDetail() {
            if ($(this).val() === '') {
                let fieldNameCapitalized =
                    $(this)
                        .attr('id')
                        .charAt(0)
                        .toUpperCase() +
                    $(this)
                        .attr('id')
                        .slice(1);

                fieldNameCapitalized =
                    fieldNameCapitalized
                        .slice(0, -2)
                        .replace(/([a-z])([A-Z])/g, '$1 $2'); // this will add a space after the capital letter e.g. LeadSource will become Lead Source;

                notify(`${fieldNameCapitalized} is a required field.`);

                success = false;
            }
        });

        if ($(`#clientBuildingNumber_${currentnum}`).val() === '' && $(`#clientBuildingName_${currentnum}`).val() === '') {
            notify('You must enter a Building Number and/or Building Name.');

            success = false;
        }

        if (success) {
            const userIds = $('#clientsMulti').val();

            const clientPostcode = $(`#clientPostcode_${currentnum}`).val();
            const clientBuildingNumber = $(`#clientBuildingNumber_${currentnum}`).val();
            const clientAddrline1 = $(`#clientAddrline1_${currentnum}`).val();
            const clientAddrtown = $(`#clientAddrtown_${currentnum}`).val();
            const clientCounty = $(`#clientCounty_${currentnum}`).val();

            const data = {

                userIds,
                clientPostcode,
                clientBuildingNumber,
                clientAddrline1,
                clientAddrtown,
                clientCounty,

            };

            const ajaxRequest = tcp.xhr.post('/cases/createnewclientaddress', data);

            ajaxRequest.done((responseData) => {
                $(`#clientPostcode_${currentnum}`).val(responseData.postcode).hide();
                $(`#clientPostcodeSpan_${currentnum}`)
                    .text(responseData.postcode)
                    // .addClass("postcode-span")
                    .show();

                $(`#clientPostcodeTd_${currentnum}`)
                    .addClass('table-cell-postcode');

                $(`#findClientAddressBtn_${currentnum}`).hide();

                $(`#changeAddress_${currentnum}`).show();

                $(`#clientResults_${currentnum}`).hide();

                $(`#clientBuildingNumber_${currentnum}`).val(responseData.building_number).hide();
                $(`#clientBuildingNumberSpan_${currentnum}`).text(responseData.building_number).show();

                $(`#clientAddrline1_${currentnum}`).val(responseData.address_line_1).hide();
                $(`#clientAddrline1Span_${currentnum}`).text(responseData.address_line_1).show();

                $(`#clientAddrtown_${currentnum}`).val(responseData.town).hide();
                $(`#clientAddrtownSpan_${currentnum}`).text(responseData.town).show();

                $(`#clientCounty_${currentnum}`).val(responseData.county).hide();
                $(`#clientCountySpan_${currentnum}`).text(responseData.county).show();

                $(`#saveBtnTd_${currentnum}`).html('');

                $(`#clientAddressId_${currentnum}`).val(responseData.address_id);

                // show the p elements where the data-user-id attribute value matches the value in the selected options
                $(`#clientNamesAddressTd_${currentnum} .client-fullname`).each(function forEachClientFullName() {
                    if (jQuery.inArray($(this).attr('data-user-id'), userIds) !== -1) {
                        $(this).show();
                    }
                });

                $('#clientsMulti').remove();

                $(`#saveClientAddressBtn_${currentnum}`).hide();
                $(`#removeClientAddressBtn_${currentnum}`).show();

                $('#transactionDetails').fadeIn();
                $('#transactionAddressDetails').fadeIn();

                $('#savedMessage').fadeIn().fadeOut(5000);
            });

            ajaxRequest.fail((error) => {
                console.error(error);
                $('#failedToSaveMessage').fadeIn().fadeOut(5000);
            });
        }
    });

    // If user clicks one of the Remove Client Address buttons
    form.on('click', '.remove-client-address-td', function onClickRemoveClientAddress(ev) {
        ev.preventDefault();

        const currentnum = parseInt($(this).attr('id').match(/\d+/g), 10);

        const clientAddressId = $(`#clientAddressId_${currentnum}`).val();

        $.confirm({
            content: 'Are you sure you want to delete this Client Address?\n\nDeleting this address will assign any users to it to the Primary Client Address.',
            buttons: {
                confirm: () => {
                    // create an array which will contain the user IDs
                    const userIds = [];
                    $(`#clientNamesAddressTd_${currentnum} .client-fullname`).each(function forEachClientFullname() {
                        if ($(this).is(':visible')) {
                            userIds.push($(this).attr('data-user-id'));
                        }
                    });

                    if (clientAddressId === 'create') {
                        $(`#clientAddressTr_${currentnum}`).remove();

                        $('.save-client-address-btn').hide();
                        $('.save-btn').show();
                        return;
                    }

                    const addressIdPrimaryClient = $('#clientAddressId_1').val();
                    const addressIdToDelete = clientAddressId.val();

                    const data = {

                        addressIdPrimaryClient,
                        addressIdToDelete,
                        userIds,

                    };

                    const ajaxRequest = tcp.xhr.post('/cases/deleteclientaddress', data);

                    ajaxRequest.done(() => {
                        // show the correct clients in the first Client Address Details row
                        $('#clientNamesAddressTd_1 .client-fullname').each(function forEachClientFullName() {
                            if (jQuery.inArray($(this).attr('data-user-id'), userIds) !== -1) {
                                $(this).show();
                            }
                        });

                        $(`#clientAddressTr_${currentnum}`).remove();

                        $('.save-client-address-btn').hide();
                        $('.save-btn').show();

                        $('#savedMessage').fadeIn().fadeOut(5000);
                    });

                    ajaxRequest.fail((error) => {
                        console.error(error);
                        $('#failedToSaveMessage').fadeIn().fadeOut(5000);
                    });
                },
            },
        });
    });

    form.on('keydown', '.postcode', function onKeyDownInPostcodeField(ev) {
        const currentnum = parseInt($(this).attr('id').match(/\d+/g), 10);

        let addressSelect = '';
        if (typeof currentnum !== 'undefined' && !Number.isNaN(currentnum)) {
            addressSelect = $(`.find-address-results-client_${currentnum}`);
        } else {
            addressSelect = $('#transactionAddress');
        }

        if (ev.which === 9 || ev.which === 13) {
            const postcode = $(this).val();

            if (!postcode) {
                notify('Please enter a postcode.');
                return false;
            }

            if (postcode.length < 5) {
                notify('Please enter a valid postcode.');
                return false;
            }

            const tableRow = $(this).parent().parent();
            $(tableRow).find('.building-number').val('');
            $(tableRow).find('.building-name').val('');
            $(tableRow).find('.address-line-one').val('');
            $(tableRow).find('.town').val('');
            $(tableRow).find('.county').val('');

            const that = this;

            const l = tableRow.find('.address-select');
            const t = tableRow.find('.address-results');

            getFullAddress(that, postcode, l, t, addressSelect);
        } else {
            $(addressSelect).fadeOut();
        }
        return true;
    });


    // If the user changes the address in the dropdown complete the following
    // we now need to do this for each address
    form.on('change', '.address-select', function onAddressChange() {
        const tableRow = $(this).parent().parent().parent(); // I'm sorry I know this isn't good practise. I'll come round to amending this Riz :)
        const that = this;

        /* This is to prevent the issue in which the user selects a valid option but then returns to the default,
         the previously selected address remains in the address bar given the user the wrong information. */
        if (that.selectedIndex !== 0) {
            const addr = $.parseJSON($(that).val());

            tableRow
                .find('.postcode-span')
                .text(addr.postcode);

            if (addr.building_number) {
                tableRow
                    .find('.building-number')
                    .val(addr.premise);
            }

            if (addr.building_name) {
                tableRow
                    .find('.building-name')
                    .val(addr.premise);
            }

            tableRow
                .find('.address-line-one')
                .val(addr.thoroughfare);

            tableRow
                .find('.town')
                .val(addr.post_town);

            tableRow
                .find('.county')
                .val(addr.county);

            tableRow
                .find('.postcode')
                .val(addr.postcode);

            return false;
        }

        tableRow
            .find('.postcode-span')
            .text('');

        tableRow
            .find('.building-number')
            .val('');

        tableRow
            .find('.building-name')
            .val('');

        tableRow
            .find('.address-line-one')
            .val('');

        tableRow
            .find('.town')
            .val('');

        tableRow
            .find('.county')
            .val('');

        tableRow
            .find('.postcode')
            .val('');

        return false;
    });

    form.on('click', '.remove-client-td', function onClickRemoveClient(ev) {
        ev.preventDefault();

        $.confirm({
            content: 'Are you sure you want to remove this client?',
            buttons: {
                confirm: () => {
                    const currentnum = parseInt($(this).attr('id').match(/\d+/g), 10);

                    const caseIdValue = $('#caseId').val();
                    const caseId = (caseIdValue === 'create') ? null : caseIdValue;

                    const newClientValue = $(`#clientType_${currentnum}`).text();
                    const newClient = (newClientValue === 'New') ? 1 : 0;

                    const userId = $(`#userId_${currentnum}`).val();

                    // a current client and no case has been created yet
                    // if(!newClient && !caseId) {
                    if ((userId === 'create' || !newClient) && !caseId) {
                        $(`.client-fullname_${currentnum}`).remove();
                        $(`.client-row_${currentnum}`).remove();
                    } else if (userId !== 'create') {
                        /**
                         * delete records from tcp_users and tcp_user_customers but only if the address
                         * they were assigned to has been removed and they were the only user assigned to the address
                         */
                        const data = {
                            userId,
                            caseId,
                            newClient,
                        };

                        const ajaxRequest = tcp.xhr.get('/cases/removeclient', data);

                        ajaxRequest.done(() => {
                            $(`tr.client-row_${currentnum}`).remove();

                            $(`.client-fullname_${currentnum}`).remove();

                            $('#savedMessage').fadeIn().fadeOut(5000);
                        });

                        ajaxRequest.fail((error) => {
                            console.error(error);
                            $('#failedToSaveMessage').fadeIn().fadeOut(5000);
                        });
                    } else {
                        $(`.client-row_${currentnum}`).remove();
                    }
                },
            },
        });

        return false;
    });

    $('#transactionAddressRepeat').click(() => {
        if (isCheckboxTicked('transactionAddressRepeat')) {
            const clientPostcode = $('#clientPostcode_1').val();
            const clientBuildingNumber = $('#clientBuildingNumber_1').val();
            const clientBuildingName = $('#clientBuildingName_1').val();
            const clientAddrline1 = $('#clientAddrline1_1').val();
            const clientAddrtown = $('#clientAddrtown_1').val();
            const clientCounty = $('#clientCounty_1').val();

            $('#transactionPostcode').val(clientPostcode);
            $('#transactionAddressBuildingNumber').val(clientBuildingNumber);
            $('#transactionAddressBuildingName').val(clientBuildingName);
            $('#transactionAddressAddrLine1').val(clientAddrline1);
            $('#transactionAddressTown').val(clientAddrtown);
            $('#transactionAddressCounty').val(clientCounty);
        } else {
            $('#transactionAddress').val('');
            $('#transactionPostcode').val('');
            $('#transactionAddressBuildingNumber').val('');
            $('#transactionAddressBuildingName').val('');
            $('#transactionAddressAddrLine1').val('');
            $('#transactionAddressTown').val('');
            $('#transactionAddressCounty').val('');
        }
    });

    $('#checkBox').click(function onCheckBoxClick() {
        if ($(this).is(':checked')) {
            $('#actionDetails').fadeIn();
        } else {
            $('#actionDetails').fadeOut();
        }
    });

    $('#sendPropertyReportBtn').click(() => {
        const userIdEncrypted = $('#userId1').val();

        const data = {
            userIdEncrypted,
        };

        const ajaxRequest = tcp.xhr.get('/users/send-user-activation-email', data);

        ajaxRequest.done(() => {
            $('#savedMessage').fadeIn().fadeOut(5000);
        });
    });

    $(document).bind('click', (e) => {
        if (!$(e.target).is('.forenames')) {
            $('.autosuggest').hide();
        }
    });

    $('#form')
        .find('div input.forenames:first-child, div input.forenames:last-child')
        .bind('keydown', (e) => {
            if (e.which === 9) {
                // e.preventDefault();
                $('.autosuggest').hide();
            }
        });

    form.on('keyup', '.surname', function onSurnameKeyUp() {
        const currentnum = parseInt($(this).attr('id').match(/\d+/g), 10);

        const title = $(`#title_${currentnum}`).val();
        const forenames = $(`#forenames_${currentnum}`).val();
        const surname = $(this).val();

        if (surname.length > 2) {
            // $("#surname_" + currentnum).empty();
            $(`#autosuggest_${currentnum}`).show();

            const data = {
                title,
                forenames,
                surname,
                customerNumber: currentnum,
            };

            const ajaxRequest = tcp.xhr.get('/cases/users/find', data);

            ajaxRequest.done((requestData) => {
                $(`#autosuggest_${currentnum}`).html(requestData.html);

                $('li.auto-suggest-client:odd').css('background-color', '#f0f1f4');
                $('li.auto-suggest-client:even').css('background-color', '#c5c9d3');
            });
        } else {
            $(`#autosuggest_${currentnum}`).hide();
        }
    });

    form.on('click', '.autosuggest li', function onAutoSuggestClick() {
        const currentnum = parseInt($(this).parent().attr('id').match(/\d+/g), 10);

        const selectedOption = $(this).attr('data-id');
        const selectedEncryptedId = $(this).attr('id');

        const titleCapitalized = $(`#autoTitle_${selectedOption}`).text().charAt(0).toUpperCase() + $(`#autoTitle_${selectedOption}`).text().slice(1);

        $(`#title_${currentnum}`).val(titleCapitalized);

        const forenames = $(`#autoForenames_${selectedOption}`).text();
        $(`#forenames_${currentnum}`).val(forenames);

        const surname = $(`#autoSurname_${selectedOption}`).text();
        $(`#surname_${currentnum}`).val(surname);

        const email = $(`#autoEmail_${selectedOption}`).text();
        $(`#email_${currentnum}`).val(email);

        const mobile = $(`#autoMobile_${selectedOption}`).text();
        $(`#mobile_${currentnum}`).val(mobile);

        const phone = $(`#autoPhone_${selectedOption}`).text();
        $(`#phone_${currentnum}`).val(phone);

        const postcode = $(`#autoPostcode_${selectedOption}`).text();
        $(`#clientPostcode_${currentnum}`).val(postcode);

        const clientBuildingNumber = $(`#autoBuildingNumber_${selectedOption}`).text();
        $(`#clientBuildingNumber_${currentnum}`).val(clientBuildingNumber);

        const custBuildingName = $(`#autoBuildingName_${selectedOption}`).text();
        $(`#custHousename_${currentnum}`).val(custBuildingName);

        const clientAddrline1 = $(`#autoAddrLine1_${selectedOption}`).text();
        $(`#clientAddrline1_${currentnum}`).val(clientAddrline1);

        const clientAddrtown = $(`#autoTown_${selectedOption}`).text();
        $(`#clientAddrtown_${currentnum}`).val(clientAddrtown);

        const clientCounty = $(`#autoCounty_${selectedOption}`).text();
        $(`#clientCounty_${currentnum}`).val(clientCounty);

        if (currentnum > 1) {
            // get the last tr which ID starts with ^= "clientAddressTr_"
            const tr = $('tr[id^="clientAddressTr_"]:last');

            const usersCount = $('.userIds').length;

            let clientName = `<p class="hidden" data-user-id="${selectedEncryptedId}" class="client-fullname client-fullname_${usersCount}">`;
            clientName += `${titleCapitalized} ${forenames} ${surname}`;
            clientName += '</p>';
            tr.find('.client-names-address-td').append(clientName);

            // Read the number from that TR's ID and increment that number by 1
            const currentNum = parseInt(tr.attr('id').match(/\d+/g), 10);
            const num = parseInt(tr.attr('id').match(/\d+/g), 10) + 1;

            // Clone the TR and assign the new ID (i.e: from num 4 to ID)
            const newAddress = tr.clone().attr('id', `clientAddressTr_${num}`);

            newAddress.find(`#clientNamesAddressTd_${currentNum}`).attr('id', `clientNamesAddressTd_${num}`);

            newAddress.find('.client-fullname').hide();

            clientName = `<p data-user-id="${selectedEncryptedId}" class="client-fullname client-fullname_${usersCount}">`;
            clientName += `${titleCapitalized} ${forenames} ${surname}`;
            clientName += '</p>';
            newAddress.find(`.client-names-address-td [data-user-id='${selectedEncryptedId}']`).show();

            newAddress.find(`#clientAddressId_${currentNum}`).attr('id', `clientAddressId_${num}`);
            newAddress.find(`#clientAddressId_${num}`).val('create'); // insert address id in this field

            newAddress.find(`#saveBtnTd_${currentNum}`).attr('id', `saveBtnTd_${num}`);
            newAddress.find(`#saveBtn_${currentNum}`).attr('id', `saveBtn_${num}`);

            newAddress.find(`#clientPostcodeTd_${currentNum}`).attr('id', `clientPostcodeTd_${num}`);
            newAddress.find(`#clientPostcodeTd_${num}`).removeClass('table-cell-postcode');
            newAddress.find(`#clientPostcode_${currentNum}`).attr('id', `clientPostcode_${num}`);
            // newAddress.find("#clientPostcode_" + num).hide();
            newAddress.find(`#clientPostcode_${num}`)
                .val(postcode)
                .addClass('required')
                .addClass('client-address-detail')
                .show();

            newAddress.find(`#clientPostcodeSpan_${currentNum}`).attr('id', `clientPostcodeSpan_${num}`);
            newAddress.find(`#clientPostcodeSpan_${num}`)
                .text(postcode)
                .addClass('postcode-span')
                .hide();

            newAddress.find(`#changeAddress_${currentNum}`).attr('id', `changeAddress_${num}`);
            newAddress.find(`#changeAddress_${num}`).hide();

            newAddress.find(`#saveAddressBtn_${currentNum}`).attr('id', `saveAddressBtn_${num}`);
            newAddress.find(`#saveAddressBtn_${num}`).hide();

            // newAddress.find("#clientResultsTd_" + currentNum).attr("id", "clientResultsTd_" + num);

            // Client Building Number
            newAddress.find(`#clientBuildingNumberTd_${currentNum}`).attr('id', `clientBuildingNumberTd_${num}`);
            newAddress.find(`#clientBuildingNumber_${currentNum}`).attr('id', `clientBuildingNumber_${num}`);
            newAddress.find(`#clientBuildingNumber_${num}`)
                .val(clientBuildingNumber)
                .addClass('client-address-detail')
                .show();

            newAddress.find(`#clientBuildingNumberSpan_${currentNum}`).attr('id', `clientBuildingNumberSpan_${num}`);
            newAddress.find(`#clientBuildingNumberSpan_${num}`)
                .text(clientBuildingNumber)
                .hide();

            // Client Building Name
            newAddress.find(`#clientBuildingNameTd_${currentNum}`).attr('id', `clientBuildingNameTd_${num}`);
            newAddress.find(`#clientBuildingName_${currentNum}`).attr('id', `clientBuildingName_${num}`);
            newAddress.find(`#clientBuildingName_${num}`)
                .val(clientBuildingNumber)
                .addClass('client-address-detail')
                .show();

            newAddress.find(`#clientBuildingNameSpan_${currentNum}`).attr('id', `clientBuildingNameSpan_${num}`);
            newAddress.find(`#clientBuildingNameSpan_${num}`)
                .text(clientBuildingNumber)
                .hide();

            // Client Address Line 1
            newAddress.find(`#clientAddrLine1Td_${currentNum}`).attr('id', `clientAddrLine1Td_${num}`);
            newAddress.find(`#clientAddrline1Span_${currentNum}`).attr('id', `clientAddrline1Span_${num}`);
            newAddress.find(`#clientAddrline1Span_${num}`)
                .text(clientAddrline1)
                .hide();
            newAddress.find(`#clientAddrline1_${currentNum}`).attr('id', `clientAddrline1_${num}`);
            newAddress.find(`#clientAddrline1_${num}`)
                .val(clientAddrline1)
                .show();

            // Client Town
            newAddress.find(`#clientAddrTownTd_${currentNum}`).attr('id', `clientAddrTownTd_${num}`);
            newAddress.find(`#clientAddrtownSpan_${currentNum}`).attr('id', `clientAddrtownSpan_${num}`);
            newAddress.find(`#clientAddrtownSpan_${num}`)
                .text(clientAddrtown)
                .hide();

            newAddress.find(`#clientAddrtown_${currentNum}`).attr('id', `clientAddrtown_${num}`);
            newAddress.find(`#clientAddrtown_${num}`)
                .val(clientAddrtown)
                .show();

            // Client County
            newAddress.find(`#clientCountyTd_${currentNum}`).attr('id', `clientCountyTd_${num}`);
            newAddress.find(`#clientCountySpan_${currentNum}`).attr('id', `clientCountySpan_${num}`);
            newAddress.find(`#clientCountySpan_${num}`)
                .text(clientCounty)
                .hide();

            newAddress.find(`#clientCounty_${currentNum}`).attr('id', `clientCounty_${num}`);
            newAddress.find(`#clientCounty_${num}`)
                .val(clientCounty)
                .show();

            newAddress.find(`#findClientAddressBtn_${currentNum}`).attr('id', `findClientAddressBtn_${num}`);
            newAddress.find(`#findClientAddressBtn_${num}`).show();
            newAddress.find(`#clientResults_${currentNum}`).attr('id', `clientResults_${num}`);
            newAddress.find(`#clientResults_${num}`).show();

            newAddress.find(`#clientAddr_${currentNum}`).attr('id', `clientAddr_${num}`);
            newAddress.find(`#clientAddr_${num}`)
                .removeClass(`find-address-results-client_${currentNum}`)
                .addClass(`find-address-results-client_${num}`)
                .html('');

            newAddress.find(`#additionalAddressDetails_${currentNum}`).attr('id', `additionalAddressDetails_${num}`);

            newAddress
                .find(`#removeClientAddressTd_${currentNum}`)
                .attr('id', `removeClientAddressTd_${num}`);

            newAddress
                .find(`#removeClientAddressTd_${num}`)
                .addClass('action-field')
                .text('Remove');

            $(tr).after(newAddress);
        }

        $(`#autosuggest_${currentnum}`).hide();

        $(`#userId_${currentnum}`).val(selectedEncryptedId);

        // $("#existingClient_" + currentnum).val(1);
        $(`#clientType_${currentnum}`).text('Existing');

        $(`#clearClientBtn_${currentnum}`).show();

        const caseId = $('#caseId').val();

        if (caseId !== 'create') {
            // update the 'customer_id' field in transaction_customers
            const data = {
                caseId,
                userId: selectedEncryptedId,
                customerNumber: currentnum,
            };
            const ajaxRequest = tcp.xhr.post('/cases/updatetransactioncustomers', data);

            ajaxRequest.done(() => {
                const clientName =
                    `${$(`#title_${currentnum}`).text()} ${
                        $(`#forenames_${currentnum}`).text()} ${
                        $(`#surname_${currentnum}`).text()}`;

                $(`#clientName_${currentnum}`).html(clientName);

                const clientNames = [];
                $('.client-name').each(function forEachClientName() {
                    clientNames.push($(this).text());
                });

                let text = '<div>';
                for (let i = 0; i < clientNames.length; i += 1) {
                    text += `<p>${clientNames[i]}</p>`;
                }
                text += '</div>';
                $('#clientNamesAddressText_1').html(text);

                $('#savedMessage').fadeIn().fadeOut(5000);
            });

            ajaxRequest.fail((error) => {
                console.error(error);
                $('#failedToSaveMessage').fadeIn().fadeOut(5000);
            });
        }
    });

    form.on('click', '.forenames', () => {
        $('.autosuggest').hide();
    });

    // This is what happens when you click the 'Add note' button under the textbox in which the note is entered
    $('#addNote').click((e) => {
        e.preventDefault();

        const notified = null;

        addNote(notified);
    });

    $('#vendor-contact-text').keyup((e) => {
        e.preventDefault();

        const notified = null;

        if (e.which === 13) {
            addNote(notified);
        }
    });

    // This is what happens when you click the 'Add note & notify' button under the textbox in which the note is entered
    $('#addNoteAndNotify').click((e) => {
        e.preventDefault();

        const notified = true;

        addNote(notified);
    });
});

// $(document).foundation();
