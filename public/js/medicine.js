$(function () {

    const dtc = $('.datatable-table-custom');

    const columns = [
        {
            data: null,
            orderable: false,
            searchable: false,
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        { data: "name" },
        { data: "generic" },
        { data: "company" },
        { data: "medicine_group" },
        { data: "unit" },
        { data: "unitpacking" },
        { data: "category" },
        { data: "storebox" },
        { data: "reorderlevel" },
        { data: "livestock" },
        { data: "status" },
    ];

    if(dtc.find('.action-th').length) {
        columns.push({ data: "action", className: 'table-action'  });
    }

    dtc.DataTable({
        aLengthMenu: [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
        iDisplayLength: 10,
        pagingType: 'full_numbers',
        order: [],
        responsive: true,
        language: {
            "paginate": {
                "first":       '<i class="las la-angle-double-left"></i>',
                "previous":    '<i class="las la-angle-left"></i>',
                "next":        '<i class="las la-angle-right"></i>',
                "last":        '<i class="las la-angle-double-right"></i>'
            },
        },
        columns,
        ajax: {
            url: '/index.php?route=medicine/filter',
            data: {
                _token: $('.s_token').val()
            },
            type: 'POST'
        },
        serverSide: true,
        "processing": true,
        searchBuilder: true,
        dom: 'Qlfrtip',
    });
    
});