/* global tcp, getAlertResponse, saveForUndo, setEditing */
$(document).ready(() => {
    $('.delete').click(function onClickDelete(ev) {
        ev.preventDefault();

        $.confirm({
            content: 'Are you sure you want to delete this record?',
            buttons: {
                confirm: () => {
                    const link = $(this).attr('href');
                    const ajaxRequest = tcp.xhr.get(link);

                    ajaxRequest.done(() => {
                        window.location.reload();
                    });
                },
            },
        });
    });

    $('.additional').click(function onClickAdditional(ev) {
        ev.preventDefault();

        $('#createRecordBtns').hide();

        const id = $(this).attr('data-id-additional');

        const tr = $(this).parent().parent();

        const data = {
            id,
        };

        const ajaxRequest = tcp.xhr.get('/admin/userroles/additional', data);

        ajaxRequest.done((responseData) => {
            tr.addClass('active-row');

            $('#updateBtn').attr('data-id-update', id);

            let select = '';
            select += "<select id='basePermissions' multiple='multiple' name='base_permissions[]'>";

            for (let i = 0; i < responseData.selectedPermissions.length; i += 1) {
                select += `<option value="${responseData.selectedPermissions[i].id}" selected>${responseData.selectedPermissions[i].name}</option>`;
            }

            // var notSelected = '';
            for (let i = 0; i < responseData.notSelectedPermissions.length; i += 1) {
                select += `<option value="${responseData.notSelectedPermissions[i].id}">${responseData.notSelectedPermissions[i].name}</option>`;
            }

            select += '</select>';

            $('#basePermissionTd').html(select);

            $('#additionalFields').show();

            $('#additionalFieldsBtns').show();
        });

        ajaxRequest.fail(() => {
            $('.error').fadeOut(2500);
        });
    });

    $('#updateBtn').click(function onClickUpdateButton(ev) {
        ev.preventDefault();

        const id = $(this).attr('data-id-update');

        const basePermissions = $('#basePermissions').val();

        const data = {

            id,
            base_permissions: basePermissions,

        };

        const ajaxRequest = tcp.xhr.post('/admin/userroles/updateAdditional', data);

        ajaxRequest.done((responseData) => {
            $('#additionalFieldsBtns').hide();

            $('#additionalFields').hide();

            $('#createRecordBtns').show();

            $('tr.active-row').removeClass('active-row');

            getAlertResponse('success-box', responseData.message);
        });

        ajaxRequest.fail((error) => {
            getAlertResponse('warning-box error', error);
        });
    });

    $('#createBtn').click(() => {
        $('#createRecord').show();
        $('#createBtn').hide();
        $('#removeBtn').show();
        $('#saveBtn').show();
    });

    $('#removeBtn').click(() => {
        $('#createRecord').hide();
        $('#removeBtn').hide();
        $('#saveBtn').hide();
        $('#createBtn').show();
    });

    $('#closeBtn').click(() => {
        $('#additionalFieldsBtns').hide();
        $('#additionalFields').hide();
        $('#createRecordBtns').show();

        $('tr.active-row').removeClass('active-row');
    });

    $('#saveBtn').click(() => {
        let success = true;

        $('.required').each(function forEachRequired() {
            if ($(this).val() === '') {
                const fieldNameCapitalized =
                    $(this)
                        .attr('id')
                        .charAt(0)
                        .toUpperCase() + $(this)
                        .attr('id')
                        .slice(1) // this will capitalize the first letter e.g. leadSource will become LeadSource
                        .replace(/([a-z])([A-Z])/g, '$1 $2'); // this will add a space after the capital letter e.g. LeadSource will become Lead Source

                $.alert(`${fieldNameCapitalized} is a required field.`);

                success = false;
            }
        });

        if (success === true) {
            const title = $('#title').val();
            const description = $('#description').val();
            const superUser = $('#superUser').val();
            const active = $('#active').val();
            const basePermissions = $('#createBasePermissions').val();

            const data = {

                title,
                description,
                super_user: superUser,
                active,
                base_permissions: basePermissions,

            };

            const ajaxRequest = tcp.xhr.post('/admin/userroles/create', data);

            ajaxRequest.done(() => {
                window.location.reload();
            });

            ajaxRequest.fail((error) => {
                console.error(error);
                $('.error').fadeOut(2500);
            });
        }
    });

    function processData(field, model, inputElement, originalValue, id, tableCell) {
        const value = $(inputElement).val();

        $('#save-btn').hide();
        $('#undo-btn').show();

        const data = {

            id,
            field,
            title: value,
            description: value,
            super_user: value,
            active: value,
            value,

        };

        const ajaxRequest = tcp.xhr.post('/admin/userroles/update', data);

        ajaxRequest.done((responseData) => {
            getAlertResponse('success-box', responseData.message);

            $(inputElement).parent().addClass('table-cell');

            $(inputElement).hide();

            $(tableCell).find('span')
                .text(responseData.value)
                .show();

            $('.success').fadeOut(2500);
        });

        ajaxRequest.fail(() => {
            $(inputElement).hide();

            $(inputElement).parent().addClass('table-cell');

            $(tableCell).find('span')
                .text(originalValue)
                .show();

            $('#failedToSaveMessage').fadeIn().fadeOut(5000);

            $('.error').fadeOut(2500);
        });
    }

    $('table').on('click', '.table-cell', function onClickCell() {
        const tableCell = $(this);

        tableCell.removeClass('table-cell');

        const id = tableCell.parent().attr('data-id');

        const field = tableCell.attr('data-field');

        let inputElement;

        let value = tableCell.find('span').text();

        if (tableCell.find('input').length === 1) {
            tableCell.find('input')
                .val(value)
                .show();

            inputElement = tableCell.find('input');
        } else if (tableCell.find('select').length === 1) {
            if (value === 'Active') {
                value = 1;
            } else if (value === 'Not active') {
                value = 0;
            }

            if (value === 'Yes') {
                value = 1;
            } else if (value === 'No') {
                value = 0;
            }

            tableCell.find('select')
                .val(value)
                .show();

            inputElement = tableCell.find('select');
        }

        tableCell.find('span')
            .hide();

        $('#save-btn').show();

        saveForUndo(field, value);

        setEditing(this, () => {
            processData(field, 'UserRole', inputElement, value, id, tableCell);
        });
    });

    $('#userRoleTable').DataTable({
        /* "columnDefs":
         [
         {
         "orderable": false,
         "targets": [2, 5, 6, 7]
         }
         ],*/
        initComplete() {
            this.api().columns('.filter').each(function forEachFilter() {
                const column = this;
                const select = $('<select><option value=""></option></select>')
                    .appendTo($(column.header()).empty())
                    .on('change', function onChange() {
                        const val = $.fn.dataTable.util.escapeRegex($(this).val());

                        column
                            .search(val ? `^${val}$` : '', true, false)
                            .draw();
                    });

                column.data().unique().sort().each((d) => {
                    select.append(`<option value="${d}">${d}</option>`);
                });
            });
        },
    });
});
