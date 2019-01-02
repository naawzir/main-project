/**
 * Initalise the the select filters (for Datatables)
 *
 * @param inputIds - Object Array with the id's of the filters and data columns to attach them too. Uses the following format:
 [{
    input_id: '#filterStatuses', // String - The id of the filter
    col_ref: 1, // Integer - The column number it references (starts from 0)
    type: 'default', // String - The type of filter: default, alpha, alpha_numeric, numeric
    make_options: false, // Boolean - Should we get the options from datatables, optional, only used on default.
    dataTableID: '#casesTable', // String - The Table to send data to eg #MyTable
    stateSave: true, // Boolean - Save the state of this filter on page leave
    search_first: false, // Boolean. - search the first char of the column only. Default false.
    function: myfunction // Function name without brackets - Optional function to be ran after filter change.
}]
 */
window.initalizeSelectBoxItems = (inputIds) => {
    inputIds.forEach((inputItem) => {
        const item = inputItem;
        const dataTable = $(item.dataTableID).DataTable();
        const colNumber = item.col_ref;
        let colItems = [];
        let items = [];
        const options = [];
        const searchFirst = item.search_first !== undefined ? item.search_first : false;

        if (item.stateSave === undefined || item.stateSave === '') {
            item.stateSave = false;
        }

        switch (item.type) {
        case 'default':
            if (item.make_options) {
                colItems = dataTable.rows().column(colNumber).data();

                $.each(colItems, (i, nitem) => {
                    const isHTML = RegExp.prototype.test.bind(/(<([^>]+)>)/i);
                    let text = nitem;
                    if (text === null || text === '') {
                        text = 'No Data';
                    }
                    if (isHTML(nitem)) {
                        items.push($(text).text());
                    } else {
                        items.push(text);
                    }
                });

                if (items.length) {
                    items = $.unique(items).sort();
                    $.each(items, (i, singleItem) => {
                        options.push(`<option value="${singleItem}">${singleItem}</option>`);
                    });
                }
            }
            break;
        case 'text':

            break;
        case 'alpha':
            for (let i = 0; i < 26; i += 1) {
                const letter = String.fromCharCode(65 + i);
                options.push(`<option value="${letter}">${letter}</option>`);
            }
            break;
        case 'numeric':
            for (let i = 0; i < item.max; i += 1) {
                options.push(`<option value="${i}">${i}</option>`);
            }
            break;
        case 'alpha_numeric':
            for (let i = 0; i < 26; i += 1) {
                const letter = String.fromCharCode(65 + i);
                options.push(`<option value="${letter}">${letter}</option>`);
            }

            for (let i = 0; i < item.max; i += 1) {
                options.push(`<option value="${i}">${i}</option>`);
            }
            break;
        default:
        }

        if (options.length) {
            $(item.input_id).append(`<option value="">Please Select...</option>${options}`);
        }

        $(inputItem.input_id).on(inputItem.type === 'text' ? 'keyup' : 'change', (ev) => {
            if (searchFirst === true) {
                dataTable.column(colNumber).search(`^${$(ev.currentTarget).val()}`, true).draw();
            } else {
                const search = $(ev.currentTarget).val() ? `${$(ev.currentTarget).val()}` : '';
                dataTable.column(colNumber).search(search, true, false).draw();
            }

            if (item.stateSave) {
                dataTable.state.save();
                sessionStorage.setItem(`${inputItem.input_id.substring(1)}_value`, $(inputItem.input_id).val());
            }

            if (item.function !== undefined && item.function !== '') {
                item.function();
            }
        });

        const inputSavedVal = sessionStorage.getItem(`${inputItem.input_id.substring(1)}_value`);
        if (inputItem.stateSave && (inputSavedVal !== null && inputSavedVal !== '')) {
            $(inputItem.input_id).val(inputSavedVal);
            if (searchFirst === true) {
                dataTable.column(colNumber).search(`^${inputSavedVal}`, true);
            } else {
                dataTable.column(colNumber).search(inputSavedVal ? `^${inputSavedVal}$` : '', true, false);
            }
        }
    });
};
