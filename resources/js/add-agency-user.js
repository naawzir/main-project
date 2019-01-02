$(document).ready(() => {
    const userLoggedInPosition = $('#userLoggedInPosition').text();

    if (userLoggedInPosition === 'Business Owner') {
        const positionElement = $('#positionElement');

        if (positionElement.val() === 'Branch Manager' || positionElement.val() === 'Agent') {
            $('#branches').show();
        } else {
            $('#branches').hide();
        }

        positionElement.change(() => {
            const position = $(this).val();

            if (position === 'Agent' || position === 'Branch Manager') {
                $('#branches').fadeIn();
            } else {
                $('#branches').fadeOut();
            }
        });
    }
});
