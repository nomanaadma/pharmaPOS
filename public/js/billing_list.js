$(function () {

    const dtc = $('.datatable-table-custom');

    const columns = [
        { data: "id" },
        { data: "name" },
        { data: "subtotal" },
        { data: "tax" },
        { data: "discount_value" },
        { data: "amount" },
        { data: "bill_date", type: 'date' },
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
            url: '/index.php?route=billing/filter',
            data: {
                _token: $('.s_token').val()
            },
            type: 'POST'
        },
        serverSide: true,
        "processing": true,
        searchBuilder: true,
        dom: 'Qlfrtip',
        footerCallback: function ( row, data, start, end, display ) {

            var api = this.api(), data, column;
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                i.replace(/[\Rs,]/g, '')*1 :
                typeof i === 'number' ?
                i : 0;
            };

            for (var i = row.childElementCount - 1; i >= 0; i--) {
                if (i == 0 ) {
                    $( api.column(i).footer() ).html('Total');
                } else if (i > 0) {
                    column = api.column(i).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    
                    column = api.column( i, { page: 'current'} ).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    if (column) {
                        $( api.column(i).footer() ).html($('.common_currency').val()+column.toFixed(2));
                    }
                }

            }
        }
    });
    
});