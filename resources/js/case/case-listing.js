/* global tcp */
window.minimisePanels = () => {
    // add in the side panel
    $('section.full').removeClass('col-sm-24').addClass('col-sm-18');
    $('aside.not-full').removeClass('hidden').addClass('col-sm-5');
};

window.restorePanels = () => {
    // hide the side panel
    $('section.full').removeClass('col-sm-18').addClass('col-sm-24');
    $('aside.not-full').removeClass('col-sm-5').addClass('hidden');
    $('tr.highlight-tr').removeClass('highlight-tr');
};

window.highlightRow = (target) => {
    $('tr.highlight-tr').removeClass('highlight-tr');
    target.addClass('highlight-tr');
};

window.populateOverview = (caseData) => {
    const warningBox = $('.warning-box');
    if (warningBox.length) {
        warningBox.remove();
    }

    $('#quick_glance span').empty();


    const {
        slug: caseId,
        date_created: createdAt,
        reference,
        transaction,
        Solicitor: solicitor,
    } = caseData;

    let {
        TransactionAddress: transAddress,
    } = caseData;

    transAddress = transAddress.replace(/,/g, '<br/>');

    const data = {
        caseId,
    };

    const ajaxRequest = tcp.xhr.post('/cases/case-overview', data);

    ajaxRequest.done((requestData) => {
        const {
            customer,
            corr_address: correspondenceAddress,
            last_milestone: lastMilestone,
        } = requestData;
        // let vendor = '';
        let corrAddress = '';

        let vendor = null;

        if (customer != null) {
            if (customer.title && customer.forename && customer.surname) {
                vendor = `${customer.title} ${customer.forename} ${customer.surname}`;
            }
            const email = customer.email ? customer.email : '';
            // var telephone = customer. THIS PART WILL NEED TO BE ADDED IN WHEN THE MULTIPLE USERS TABLES ARE UPDATED

            $('#quick_glance #vendor').html(vendor);
            $('#quick_glance #email').html(email);
        } else {
            $('#quick_glance #vendor').html(vendor);
        }

        if (correspondenceAddress != null) {
            corrAddress = `${correspondenceAddress.building_number}<br/>${correspondenceAddress.address_line_1}<br/>${correspondenceAddress.town}<br/>${correspondenceAddress.postcode}`;
            $('#quick_glance #corresp_address').html(corrAddress);
        } else {
            corrAddress = '';
            $('#quick_glance #corresp_address').html(corrAddress);
        }

        if (lastMilestone !== null) {
            // WE DON'T CURRENTLY HAVE THE CONFIG VARIABLE STUFF WE USED TO
            $('#quick_glance #last_milestone').html(lastMilestone);
        }
    });

    ajaxRequest.fail((error) => {
        $('#content-container').prepend(`<div data-notification="warning" data-hover="Dismiss Notification?" class="col-sm-23 warning-box"><p>${error}</p></div>`);
    });

    $('#quick_glance #created_at').html(createdAt);
    $('#quick_glance #reference').html(reference);
    $('#quick_glance #transaction').html(transaction);
    $('#quick_glance #trans_address').html(transAddress);
    $('#quick_glance #solicitor').html(solicitor);
    $('#quick_glance #view-edit').attr('href', `/cases/${caseId}`);
    $('#quick_glance #update-case').attr('href', `/cases/${caseId}/request-update/`);

    // Get the list of documents
    $('#documents-list').html('');
    const ajaxRequestCaseDocuments = tcp.xhr.get(`/document/${caseId}/get-documents-for-case/`);
    ajaxRequestCaseDocuments.done((responseData) => {
        if (responseData.documents.length) {
            responseData.documents.forEach((row) => {
                $('#documents-list').append(`${row.title}: ${row.file_name} <a href="/document/${row.slug}">Download</a><br />`);
            });
        } else {
            $('#documents-list').html('No Documents');
        }
    });

    ajaxRequestCaseDocuments.fail((error) => {
        $('#content-container').prepend(`<div data-notification="warning" data-hover="Dismiss Notification?" class="col-sm-23 warning-box"><p>${error}</p></div>`);
        $('#documents-list').html('No Documents');
    });
};
