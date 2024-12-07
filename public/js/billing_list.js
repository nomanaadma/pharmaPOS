$(function () {

    const dtc = $('.datatable-table-custom');

    const columns = [
        { data: "id" },
        { data: "name" },
        { data: "subtotal", type: 'num' },
        { data: "tax", type: 'num' },
        { data: "discount_value", type: 'num' },
        { data: "amount", type: 'num' },
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

            let dataHtml = '<div class="row datatotals">';

            var api = this.api(), data, column;
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                i.replace(/[\Rs,]/g, '')*1 :
                typeof i === 'number' ?
                i : 0;
            };

            if (data.length === 0) {
                for (var i = 0; i < row.childElementCount; i++) {
                    $(api.column(i).footer()).html('');
                }
                return;
            }

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

                        const numbers = $('.common_currency').val()+column.toFixed(2);
                        const columnTitle = $(api.column(i).header()).text();

                        dataHtml += `
                            <div class="col-md-3">
                                <div class="dashboard-stat">
                                    <div class="content"><h4 class="text-dark">${numbers}</h4>
                                    <span class="text-dark">${columnTitle}</span></div>
                                    <div class="icon"><i class="las la-dollar-sign text-secondary"></i></div>
                                </div>
                            </div>
                        `;

                        $( api.column(i).footer() ).html(numbers);
                    }
                }
            }

            dataHtml += '</div>';

            $('.datatotals').remove();

            $(dataHtml).insertBefore('.dtsb-searchBuilder');

        }
    });
    
});