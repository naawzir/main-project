window.minimisePanels = function () {
    // add in the side panel
    $('section.full').removeClass('col-sm-24').addClass('col-sm-18');
    $('aside.not-full').removeClass('hidden').addClass('col-sm-5');
};

window.restorePanels = function () {
    // hide the side panel
    $('section.full').removeClass('col-sm-18').addClass('col-sm-24');
    $('aside.not-full').removeClass('col-sm-5').addClass('hidden');
    $('tr.highlight-tr').removeClass('highlight-tr');
};

window.highlightRow = function (target) {
    $('tr.highlight-tr').removeClass('highlight-tr');
    target.addClass('highlight-tr');
};

window.populateOverview = function (caseData) {
    var warningBox = $('.warning-box');
    if (warningBox.length) {
        warningBox.remove();
    }

    $('#quick_glance span').empty();

    var caseId = caseData.slug,
        createdAt = caseData.date_created,
        reference = caseData.reference,
        transaction = caseData.transaction,
        solicitor = caseData.Solicitor,
        office = caseData.Office;
    var transAddress = caseData.TransactionAddress;

    transAddress = transAddress.replace(/,/g, '<br/>');

    var data = {
        caseId: caseId
    };

    var ajaxRequest = tcp.xhr.post('/cases/case-overview', data);

    ajaxRequest.done(function (requestData) {
        var customer = requestData.customer,
            correspondenceAddress = requestData.corr_address,
            lastMilestone = requestData.last_milestone;

        var corrAddress = '';

        if (customer != null) {
            if (customer.title && customer.forename && customer.surname) {
                vendor = customer.title + " " + customer.forename + " " + customer.surname;
            }
            var email = customer.email ? customer.email : '';
            // var telephone = customer. THIS PART WILL NEED TO BE ADDED IN WHEN THE MULTIPLE USERS TABLES ARE UPDATED

            $('#quick_glance #vendor').html(vendor);
            $('#quick_glance #email').html(email);
        } else {
            $('#quick_glance #vendor').html(vendor);
        }

        if (correspondenceAddress != null) {
            corrAddress = correspondenceAddress.building_number + "<br/>" + correspondenceAddress.address_line_1 + "<br/>" + correspondenceAddress.town + "<br/>" + correspondenceAddress.postcode;
            $('#quick_glance #corresp_address').html(corrAddress);
        } else {
            corrAddress = '';
            $('#quick_glance #corresp_address').html(corrAddress);
        }

        $('#quick_glance #phone').html(customer.phone);

        if (lastMilestone !== null) {
            // WE DON'T CURRENTLY HAVE THE CONFIG VARIABLE STUFF WE USED TO
            $('#quick_glance #last_milestone').html(lastMilestone);
        }
    });

    ajaxRequest.fail(function (error) {
        $('#content-container').prepend("<div data-notification=\"warning\" data-hover=\"Dismiss Notification?\" class=\"col-sm-23 warning-box\"><p>" + error + "</p></div>");
    });

    $('#quick_glance #created_at').html(createdAt);
    $('#quick_glance #reference').html(reference);
    $('#quick_glance #transaction').html(transaction);
    $('#quick_glance #trans_address').html(transAddress);
    $('#quick_glance #solicitor').html(solicitor + ' (' + office + ')');
    $('#quick_glance #view-edit').attr('href', "/cases/" + caseId);
    $('#quick_glance #update-case').attr('href', "/cases/" + caseId + "/request-update/");

    // Get the list of documents
    $('#documents-list').html('');
    var ajaxRequestCaseDocuments = tcp.xhr.get("/document/" + caseId + "/get-documents-for-case/");
    ajaxRequestCaseDocuments.done(function (responseData) {
        if (responseData.documents.length) {
            responseData.documents.forEach(function (row) {
                $('#documents-list').append('<p class="row grouped col-sm-22"><span class="col-sm-12">' + row.file_name + "</span><span class=\"col-sm-12\"><a href=\"/document/" + row.slug + "\">Download</a></span></p>");
            });
        } else {
            $('#documents-list').html('No Documents');
        }
    });

    ajaxRequestCaseDocuments.fail(function (error) {
        $('#content-container').prepend("<div data-notification=\"warning\" data-hover=\"Dismiss Notification?\" class=\"col-sm-23 warning-box\"><p>" + error + "</p></div>");
        $('#documents-list').html('No Documents');
    });
};
