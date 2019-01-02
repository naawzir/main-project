/* global tcp */
/*
    This is the javaScript that updates the Staff Performance page statistics
*/
function getUnix(date) {
    const dateParts = date.split('-');
    const dateObj = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
    return Date.parse(dateObj) / 1000;
}

function updateValues(data) {
    const jsonData = JSON.stringify(data);
    const formData = {
        jsonData,
    };

    const ajaxRequest = tcp.xhr.post('/staff/update-stats', formData);

    ajaxRequest.done((/* response*/) => {
        // probably update the statistics right?
        // console.log(response);
    });

    ajaxRequest.fail((/* error*/) => {
        // console.error(error);
    });
}

$('.staff-performance-datepicker button').click(() => {
    const date = $('.datepicker').val();
    const dateUnix = getUnix(date);

    let staff = $('#account-managers').val();
    staff = (staff === 'Please Select') ? false : staff;

    const data = [];

    data.push({
        date: dateUnix,
    });

    if (staff) {
        data.push({
            user: staff,
        });
    }

    updateValues(data);
});

$('#account-managers').change(() => {
    let staff = $('#account-managers').val();
    staff = (staff === 'Please Select') ? false : staff;

    const date = $('.datepicker').val();
    let dateUnix = false;

    const data = [];

    data.push({
        user: staff,
    });

    if (date) {
        dateUnix = getUnix(date);
        data.push({
            date: dateUnix,
        });
    }

    updateValues(data);
});
