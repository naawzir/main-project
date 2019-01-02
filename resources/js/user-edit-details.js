/* global updateFormInput, saveForUndo, setEditing */
function processUserData(field, model, inputElement/* , originalValue */) {
    const value = $(inputElement).val();

    $('#save-btn').hide();
    $('#undo-btn').show();

    updateFormInput(field, value);

    $(inputElement).parent().addClass('table-cell');

    $(inputElement).hide();

    $(`[data-field=${field}] span`)
        .text(value)
        .show();
}

$('table').on('click', '.table-cell', function onClickTableCell() {
    $(this).removeClass('table-cell');

    const field = $(this).attr('data-field');
    const spanElem = $(`[data-field=${field}] span`);
    const value = spanElem.text();

    $(`[name='${field}']`).val(value).show();
    spanElem.hide();
    $('#save-btn').show();

    const inputElement = $(`[name="${field}"]`);

    saveForUndo(field, value);

    setEditing(this, () => {
        processUserData(field, 'User', inputElement, value);
    });
});

