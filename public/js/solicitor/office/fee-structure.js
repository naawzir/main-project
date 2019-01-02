$(document).ready(() => {
    $('#salePurchase .delete-btn:first').hide();
    $('#sale .delete-btn:first').hide();
    $('#purchase .delete-btn:first').hide();

    $('.unlimited-checkbox').on('change', function () {
        const row = $(this).closest('tr');
        if ($(this).is(':checked')) {
            row.find('.price_to').val('9999999.99').addClass('hidden');
        } else {
            row.find('.price_to').val('').removeClass('hidden');
        }
    });

    $('#feeScaleType').on('change', function () {
        const option = $(this).val();
        $('.fee-section').hide();
        $(`#${option}`).show();
    });

    $('.add-more').on('click', function (e) {
        // alert('clicked');
        e.preventDefault();
        const oldPT = $(this).parent().parent().parent()
            .parent()
            .find('input.price_to:last')
            .val();
        const priceFrom = Number(oldPT) + 1;
        const legalFee = $(this).parent().parent().parent()
            .parent()
            .find('input.legal_fee:last')
            .val();
        const caseType = $(this).parent().parent().parent()
            .parent()
            .find('input.case_type:last')
            .val();

        if (oldPT === '' || legalFee === '') {
            $('p#addRowMessage').removeClass('hidden');
            $('#content-container').removeClass('hidden');
        } else {
            $(this).parent().parent().parent()
                .parent()
                .find('tbody tr:last')
                .clone(true, true)
                .attr('data-id', '')
                .find('input:text, input:hidden')
                .val('')
                .end()
                .find('.price_from')
                .val(priceFrom)
                .end()
                .find('.case_type')
                .val(caseType)
                .end()
                .find('.delete-btn')
                .show()
                .end()
                .appendTo($(this)
                    .parent()
                    .parent()
                    .parent()
                    .parent()
                    .find('tbody'))
                .removeClass('duplicate');
            if (!$('p#addRowMessage').hasClass('hidden')) {
                $('p#addRowMessage').addClass('hidden');
                $('#content-container').addClass('hidden');
            }
        }
    });

    $('input[type="text"]').on('keydown', (e) => {
        if (e.keyCode === 188) {
            e.preventDefault();
        }
    });

    $('input[type="text"]').on('input', function () {
        const value = $(this).val();
        const row = $(this).closest('tr');
        const priceTo = row.find('.price_to').val();
        const priceFrom = row.find('.price_from').val();
        const nexttr = $(this).closest('tr').next('tr');
        const nextPriceToVal = Number(priceTo) + 1;
        let err = 0;

        if (!$.isNumeric(value) && value !== '') {
            err += 1;
            $('p#valueNumberMessage').removeClass('hidden');
        } else {
            $('p#valueNumberMessage').addClass('hidden');
            nexttr.find('.price_from').val(nextPriceToVal);
        }

        if (priceFrom && priceTo) {
            if (priceFrom >= priceTo) {
                err += 1;
                $('p#currentRowMessage').removeClass('hidden');
            } else {
                $('p#currentRowMessage').addClass('hidden');
            }
        }

        if (err > 0) {
            $('#content-container').removeClass('hidden');
        } else {
            $('p#addRowMessage').addClass('hidden');
            $('#content-container').addClass('hidden');
        }
    });

    $('#tbodySalePurchase .legal_fee').on('change', function () {
        if ($(this).val() !== '') {
            const row = $(this).closest('tr');
            row.find('.case_type_sale_purchase').val('salePurchase');
        }
    });

    $('.delete-btn').on('click', function (ev) {
        ev.preventDefault();
        const id = $(this).parent().parent().attr('data-id');
        const row = $(this).closest('tr');

        if (typeof id !== 'undefined' && id !== '') {
            $.confirm({
                title: 'DELETE!',
                content: 'Are you sure you want to delete this fee structure?',
                buttons: {
                    confirm: {
                        btnClass: 'btn-success',
                        action: () => {
                            $.ajax({
                                url: `/solicitors/office/fee-structure/${id}/destroy/`,
                                method: 'get',
                                dataType: 'json',
                                success: () => {
                                    // console.log('Record Deleted');
                                    if (!row.hasClass('duplicate')) {
                                        row.remove();
                                    }
                                },
                                error: () => {
                                    // console.log('Could Not Delete Record');
                                },
                            });
                        },
                    },
                    cancel: {
                        btnClass: 'btn-red',
                    },
                },
            });
        } else if (!$(this).closest('tr').hasClass('duplicate')) row.remove();
    });
});
