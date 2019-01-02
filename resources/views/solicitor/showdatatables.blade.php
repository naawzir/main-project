<script>
    $(document).ready(function() {
        var datatables_info =
            [{
                url: '/solicitors/get-pricing-structures/{{$feeStructure->id}}',
                dataTableID: '#pricingStructureTable',
                ordering: [[4, "asc"]],
                stateSave: false,
                displaylength: -1,
                dom: `<r>Bt<i>`,
                cols:
                [{
                    data: 'price_from',
                    name: 'price_from',
                    render: function (data, type, full, meta) {
                        return '<span class="pound-symbol">£</span> ' + full.price_from;
                    }
                },
                {
                    data: 'price_to',
                    name: 'price_to',
                    render: function (data, type, full, meta) {
                        return '<span class="pound-symbol">£</span> ' + full.price_to;
                    }
                },
                {
                    data: 'legal_fee',
                    name: 'legal_fee',
                    render: function (data, type, full, meta) {
                        return '<span class="pound-symbol">£</span> ' + full.legal_fee;
                    }
                },
                {
                    data: 'referral_fee',
                    name: 'referral_fee',
                    render: function (data, type, full, meta) {
                        return '<span class="pound-symbol">£</span> ' + full.referral_fee;
                    }
                },
                {
                    data: 'case_type',
                    name: 'case_type'
                }]
            },
                {
                    url: '/solicitors/getusersforoffice/{{$solicitorOffice->id}}',
                    dataTableID: '#staffTable',
                    ordering: [[3, 'asc'], [0, 'asc']],
                    stateSave: false,
                    displaylength: -1,
                    dom: `<r>Bt<i>`,
                    cols:
                    [{
                        data: 'forenames',
                        name: 'forenames',
                        render: function (data, type, full, meta) {
                            return full.forenames + ' ' + full.surname;
                        }
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'id',
                        name: 'id',
                        render: function (data, type, full, meta) {
                            return '<a href="/solicitors/edit/user/' + full.id + '"><button class="success-button">Edit</button></a>';
                        }
                    }]
                }];
        makeDatatable(datatables_info);

        $('#editpricing').on('click', function(){
            window.location = '/solicitors/edit/pricing/{{$feeStructure->id}}';
        });

        $('#editfees').on('click', function(){
            window.location = '/solicitors/edit/fees/{{$feeStructure->id}}';
        });

        $('.nav-link').click(function(e){
            e.preventDefault();
            $('.nav-link').removeClass('active');
            $(this).addClass('active');
            $('section.panel-body').not('#' + $(this).attr('data-toggle')).addClass('hidden');
            $('#' + $(this).attr('data-toggle')).removeClass('hidden');
        });
    });
</script>