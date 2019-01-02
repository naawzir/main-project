$(document).ready(() => {
    const solicitorId = $('#solicitorId').text();
    const dataTablesInfo =
        [{
            url: '/solicitors/performance/get-solicitors-stats-records',
            dataTableID: '#solicitorsStatsTable',
            ordering: [[0, 'asc']],
            stateSave: true,
            cols:
                [
                    {
                        data: 'solicitor',
                        name: 'solicitor',
                    },
                    {
                        data: 'pipeline',
                        name: 'pipeline',
                    },
                    {
                        data: 'capacity_remaining',
                        name: 'capacity_remaining',
                    },
                    {
                        data: 'instructions_mtd',
                        name: 'instructions_mtd',
                    },
                    {
                        data: 'unpanelled_instructions_mtd',
                        name: 'unpanelled_instructions_mtd',
                    },
                    {
                        data: 'completions_mtd',
                        name: 'completions_mtd',
                    },
                    {
                        data: 'aborts_mtd',
                        name: 'aborts_mtd',
                    },
                    {
                        data: 'slug',
                        name: 'slug',
                        render: (data, type, full) => `<a href="/solicitors/performance/${full.slug}"><button class="success-button col-sm-24">Edit</button></a>`,
                    },
                ],
        },
        {
            url: `/solicitors/performance/get-solicitor-stats-records/${solicitorId}`,
            dataTableID: '#solicitorStatsTable',
            ordering: [[0, 'asc']],
            stateSave: true,
            cols:
                [
                    {
                        data: 'solicitorOffice',
                        name: 'solicitorOffice',
                    },
                    {
                        data: 'pipeline',
                        name: 'pipeline',
                    },
                    {
                        data: 'capacity_remaining',
                        name: 'capacity_remaining',
                    },
                    {
                        data: 'instructions_mtd',
                        name: 'instructions_mtd',
                    },
                    {
                        data: 'unpanelled_instructions_mtd',
                        name: 'unpanelled_instructions_mtd',
                    },
                    {
                        data: 'completions_mtd',
                        name: 'completions_mtd',
                    },
                    {
                        data: 'aborts_mtd',
                        name: 'aborts_mtd',
                    },
                    {
                        data: 'slug',
                        name: 'slug',
                        render: (data, type, full) => `<a href="/solicitors/office/${full.slug}"><button class="success-button col-sm-24">Edit</button></a>`,
                    },
                ],
        }];

    window.makeDatatable(dataTablesInfo);
});
