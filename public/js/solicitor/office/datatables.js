$(document).ready(() => {
    const dataTablesInfo =
        [{
            url: `/solicitors/office/${window.OfficeId.value}/get-fee-structures`,
            dataTableID: '#pricingStructureTable',
            ordering: [[3, 'asc']],
            stateSave: false,
            displaylength: -1,
            dom: '<r>Bt<i>',
            cols:
                [{
                    data: 'price_from',
                    name: 'price_from',
                    render: (data, type, full) => `<span class="pound-symbol">&pound;</span>${full.price_from}`,
                },
                {
                    data: 'price_to',
                    name: 'price_to',
                    render: (data, type, full) => `<span class="pound-symbol">&pound;</span>${full.price_to}`,
                },
                {
                    data: 'legal_fee',
                    name: 'legal_fee',
                    render: (data, type, full) => `<span class="pound-symbol">&pound;</span>${full.legal_fee}`,
                },
                {
                    data: 'case_type',
                    name: 'case_type',
                }],
        },
        {
            url: `/solicitors/office/${window.OfficeId.value}/get-users-for-office/`,
            dataTableID: '#staffTable',
            ordering: [[3, 'asc'], [0, 'asc']],
            stateSave: false,
            displaylength: -1,
            dom: '<r>Bt<i>',
            cols:
                [{
                    data: 'forenames',
                    name: 'forenames',
                    render: (data, type, full) => `${full.forenames} ${full.surname}`,
                },
                {
                    data: 'phone',
                    name: 'phone',
                },
                {
                    data: 'email',
                    name: 'email',
                },
                {
                    data: 'id',
                    name: 'id',
                    render: (data, type, full) => `<a href="/solicitors/office/user/${full.slug}/edit/"><button class="success-button col-sm-24">Edit</button></a>`,
                }],
        }];
    window.makeDatatable(dataTablesInfo);

    $('.nav-link').click(function (e) {
        e.preventDefault();

        $('.nav-link').removeClass('active');
        $(this).addClass('active');
        $('section.panel-body').not(`#${$(this).attr('data-toggle')}`).addClass('hidden');
        $(`#${$(this).attr('data-toggle')}`).removeClass('hidden');
    });

    $('#panelManagerSubmission').click((e) => {
        e.preventDefault();
    });

    $('#exit')
        .click(() => {
            window.location = '/solicitors/';
        });

    $('#panelManagerSubmissionBtn')
        .click(() => {
            const ajaxRequest = window.tcp.xhr.get(`/solicitors/office/${window.OfficeId.value}/panel-manager-submission/`);

            ajaxRequest.done((data) => {
                window.getAlertResponse('success-box ', data.message);
                $('#panelManagerDiv').hide();
                window.getOnboardingCount();
            });

            ajaxRequest.fail((error) => {
                window.getAlertResponse('warning-box error', error);
            });
        });

    $('#TMSubmissionBtn')
        .click(() => {
            const ajaxRequest = window.tcp.xhr.get(`/solicitors/office/${window.OfficeId.value}/tm-submission/`);

            ajaxRequest.done((data) => {
                window.getAlertResponse('success-box ', data.message);
                $('#tmDiv').addClass('hidden');
                $('#marketDiv').removeClass('hidden');
                $('#tm_ref').text(`TM Ref: ${data.tm_ref}`);
            });

            ajaxRequest.fail((error) => {
                window.getAlertResponse('warning-box error', error);
            });
        });

    $('#addToMarketSubmissionBtn')
        .click(() => {
            const ajaxRequest = window.tcp.xhr.get(`/solicitors/office/${window.OfficeId.value}/add-to-market/`);

            ajaxRequest.done((data) => {
                window.getAlertResponse('success-box ', data.message);
                $('#marketDiv').addClass('hidden');
                window.getOnboardingCount();
            });

            ajaxRequest.fail((error) => {
                window.getAlertResponse('warning-box error', error);
            });
        });
});
