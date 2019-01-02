/**
 * Refresh the list
 *
 * @param dataTableItems - Object Array with the datatable options. Uses the following format:
 [{
    url: '/solicitors/getmarketrecords',
    dataTableID: '#solicitorTable',
    ordering: [[0, "asc"]],
    stateSave: true,
    deferLoading: 0, // Remove if you do not want to defer loading of ajax
    dom: `<B>l<r>t<i<"solicitorTable-page-button"p>>`, // Remove if you want the default dom
    lengthmenu: [[10, 25, 50, 100], [10, 25, 50, 100]], // Remove if you want the default length menu
    displaylength: 10, // Remove if you want the default display length
    cols :
    [{
        data: 'Solicitor',
        name: 'Solicitor',
        render: function(data, type, full, meta)
        {
            return full.Solicitor + '<br />' + full.Location;
        }
    },
    {
        data: 'AverageCompletion',
        name: 'AverageCompletion'
    },
    {
        data: 'AgentRating',
        name: 'AgentRating'
    },
    {
        data: 'SolicitorID',
        name: 'SolicitorID',
        render: function(data, type, full, meta)
        {
            return '<a href="'+ full.SolicitorID +'">View More</a>' ;
        }
    },
    {
        data: 'Postcode',
        name: 'Postcode'
    }],
    buttons: [ // add your report buttons here, otherwise delete if you are not using exports
        $.extend(
            true, {}, {
                exportOptions: {
                    format: {
                        body(data) {
                            return strip(data);
                        },
                    },
                },
            },
            {
                extend: 'copyHtml5',
            },
        ),
        $.extend(
            true, {}, {
                exportOptions: {
                    format: {
                        body(data) {
                            return strip(data);
                        },
                    },
                },
            },
            {
                extend: 'excelHtml5',
            },
        ),
        $.extend(
            true, {}, {
                exportOptions: {
                    format: {
                        body(data) {
                            return strip(data);
                        },
                    },
                },
            },
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {columns: [0, 1, 2, 3]},
                customize(doc) {
                    let document = doc;
                    document.content[1].table.widths = ['35%', '15%', '35%', '15%'];
                    document.content[1].alignment = 'center';
                },
            },
        ),
    ]
}];
 */

window.makeDatatable = (dataTableItems) => {
    dataTableItems.forEach((dataTableItem) => {
        if (!dataTableItem.url) {
            return; // No data
        }

        const paginationClass = `.${dataTableItem.dataTableID.substring(1)}-page-button`;
        let noDraw = false;
        const deferLoadingVar = dataTableItem.deferLoading !== undefined ? dataTableItem.deferLoading : null;
        const domVar = dataTableItem.dom !== undefined ? dataTableItem.dom : `l<r>t<i<"${paginationClass}"p>>`;
        const buttonsVar = dataTableItem.buttons !== undefined ? dataTableItem.buttons : [];
        const defaultOrder = dataTableItem.ordering !== undefined ? dataTableItem.ordering : [[0, 'asc']];
        const defaultLengthMenu = dataTableItem.lengthmenu !== undefined ? dataTableItem.lengthmenu : [[10, 25, 50, 100], [10, 25, 50, 100]];
        const defaultdisplayLength = dataTableItem.displaylength !== undefined ? dataTableItem.displaylength : 10;

        const dataTable = $(dataTableItem.dataTableID).DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            stateDuration: -1,
            stripeClasses: '',
            lengthMenu: defaultLengthMenu,
            pageLength: defaultdisplayLength,
            order: defaultOrder,
            ajax: dataTableItem.url,
            columns: dataTableItem.cols,
            stateSave: dataTableItem.stateSave,
            dom: domVar,
            buttons: buttonsVar,
            drawCallback() {
                if (dataTableItem.stateSave) {
                    $(paginationClass).find('a.paginate_button').on('click', () => {
                        noDraw = true;
                        const dtInfo = dataTable.page.info();
                        sessionStorage.setItem(`${dataTableItem.dataTableID.substring(1)}page`, dtInfo.page);
                    });
                }
            },
            initComplete() {
                if (dataTableItem.stateSave) {
                    const dtPage = parseInt(sessionStorage.getItem(`${dataTableItem.dataTableID.substring(1)}page`), 10);
                    if ((!noDraw && deferLoadingVar === null) && (dtPage !== null && dtPage !== false && !Number.isNaN(dtPage))) {
                        dataTable.page(dtPage).draw('page');
                    }
                }
            },
            deferLoading: deferLoadingVar,
        });
    });
};
