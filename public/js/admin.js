 Dropzone.autoDiscover = false;
 
 $(document).ready(function () {
  
    "use strict";
    var path = $('.site_url').val();

    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover()

    $('body').on('click', '.menu-close', function () {
        var ele = $(this);
        $('#main-wrapper').addClass('page-menu-small');
        ele.find('i').removeClass('fa-hand-point-left');
        ele.find('i').addClass('fa-hand-point-right');
        ele.removeClass('menu-close');
        ele.addClass('menu-open');
    });

    $('body').on('click', '.menu-open', function () {
        var ele = $(this);
        $('#main-wrapper').removeClass('page-menu-small');
        ele.find('i').removeClass('fa-hand-point-right');
        ele.find('i').addClass('fa-hand-point-left');
        ele.removeClass('menu-open');
        ele.addClass('menu-close');
    });

    if ($('.customer-name').length) {
        $(".customer-name").autocomplete({
            source: path.concat('customer/search'),
            minLength: 2,
            focus: function() {return false;},
            select: function( event, ui ) {
                $('.customer-id').val(ui.item.id);
                $('.customer-name').val(ui.item.label);
                $('.customer-mail').val(ui.item.email);
                $('.customer-mobile').val(ui.item.mobile);
                return false;
            }
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
            return $( "<li>" )
            .append('<div>' + item.label + '<div class="font-12"> ( ' + item.email + ' )</div><div class="font-12"> ( ' + item.mobile + ' )</div></div>')
            .appendTo( ul );
        };
    }

    $('body').on('click', 'li.has-sub > a', function () {
        var ele = $(this), target = ele.parent('li.has-sub').find('ul.sub-menu:first');
        ele.parent('li.has-sub').siblings('li').find('a .arrow').removeClass('rotate');
        if (target.css('display') === "none") {
            ele.parent('li.has-sub').siblings('li').find('.sub-menu').slideUp();
            ele.find('.arrow').addClass('rotate');
            target.slideDown();
        } else {
            ele.parent('li.has-sub').find('.arrow').removeClass('rotate');
            ele.parent('li.has-sub').find('ul.sub-menu').slideUp();
        }
        return false;
    });

    $('body').on('click', '.open-left-menu', function () {
        var ele = $('.menu-wrapper'), nav_ele = $('.navbar-container');
        $('body').append('<div class="menu-overlay"></div>');
        ele.addClass('menu-mobile-open');
        nav_ele.addClass('menu-mobile-open');
    });

    $('body').on('click', '.menu-overlay', function () {
        $('.menu-wrapper, .navbar-container').removeClass('menu-mobile-open');
        $('.menu-overlay').remove();
    });

    $('body').on('click', '.open-page-menu-desktop', function () {
        var ele = $('.page-hdr-desktop');
        $('.page-search').slideUp(300);
        if (ele.css('display') === "none") {
            ele.slideDown(300);
        } else {
            ele.slideUp(300);
        }
    });
    

    $(document).on('click', '.table-delete', function () {
        $('.delete-card-button input.delete-id').val($(this).find('input').val());
        $("#delete-card").modal({
            keyboard: true
        });
    });

    $('#delete-card').on('hidden.bs.modal', function (e) {
        $('.delete-card-button input.delete-id').val('');
    });

    $('#media-upload').on('show.bs.modal', function (e) {
        var uploaded = $('#media-upload .uploaded');
        if (uploaded.val() === '0') {
            var path = $('.site_url').val().concat('get/media');
            $.ajax({
                type: 'get',
                url: path,
                data: { name: 'media', _token: $('.s_token').val() },
                error: function () {},
                success: function (response) {
                    $('#media-upload .media-all').append(response);
                    uploaded.val('1');
                }
            });
        }
        $('#media-upload .media-all').addClass('media-modal-open');
    });

    $('.image-upload').click(function () {
        $(this).parent().addClass('image-upload-progress');
        $("#media-upload").modal('show');
    });

    $("#media-upload").on('hidden.bs.modal', function () {
        $(this).parent().find('.image-upload-progress').removeClass('image-upload-progress');
        $('#media-upload .media-all').removeClass('media-modal-open');
    });

    $("#media-dropzone").dropzone({
        addRemoveLinks: false,
        acceptedFiles: "image/*",
        maxFilesize: 5,
        autoProcessQueue: true,
        dictDefaultMessage: 'Drop files here or click here to upload <br /><br /> Only Image',
        init: function() {
            var reportDropzone = this;
            reportDropzone.on("sending", function(file, xhr, formData) {
                formData.append("_token", $('.s_token').val());
            });

            reportDropzone.on("success", function(file, xhr) {
                var response = JSON.parse(xhr);
                if (response.error === false) {
                    $('.media-all').prepend(response.media);
                    toastr.success('Uploaded Succefully', 'Report uploaded Succefully.');
                } else {
                    toastr.error('Error', response.message);
                }
                reportDropzone.removeFile(file);
            });

            reportDropzone.on("error", function(file, message) { 
                toastr.error('Error', message);
                reportDropzone.removeFile(file); 
            });
        },
    });

    $('#media-upload').on('click', '.media-modal-open .picture', function () {
        var image = $(this).find('input').val();
        $('.image-upload-progress .saved-picture').append('<img src="public/uploads/' + image + '" alt="">');
        $('.image-upload-progress .saved-picture input[type=hidden]').val(image);
        $('.image-upload-progress .saved-picture').show();
        $('.image-upload-progress .image-upload').hide();
        $('.image-upload-progress .saved-picture-delete').show();
        $('.content-input').removeClass('image-upload-progress');
        $('#media-upload').modal('hide');
    });

    // Image Delete 
    $('.media-all').on('click', '.block .remove', function () {
        var ele = $(this), ele_par = ele.parent(),
        media = ele_par.find('.picture input').val(),
        id = ele_par.find('.block-id').val();
        $.ajax({
            method: "POST",
            url: path.concat('media/delete'),
            data: { page: 'media', name: media, id: id, _token: $('.s_token').val() },
            error: function () {
                alert('Sorry Try Again!');
            },
            success: function (response) {
                response = JSON.parse(response);
                if (response.error === false) {
                    ele.parents('.block').remove();
                    toastr.success('Deleted', 'File deleted Succefully.');
                } else {
                    toastr.success('Wanrning', 'File could not be deleted!.');
                }
            }
        });
    });

    $('.saved-picture-delete').click(function () {
        $(this).siblings('.saved-picture').find('img').remove();
        $(this).siblings('.saved-picture').find('input').val('');
        $(this).siblings('.saved-picture').hide();
        $(this).siblings('.image-upload').show();
        $(this).hide();
    });


    $('.menu-dropdown').click(function () {
        var ele = $(this);
        if (ele.siblings('.sub-menu').css('display') === 'none') {
            $('.sub-menu').slideUp();
            $('#menu-menu ul a').removeClass('menu-arrow-rotate');
            ele.addClass('menu-arrow-rotate');
            ele.siblings('.sub-menu').slideDown(200);
        } else {
            ele.removeClass('menu-arrow-rotate');
            $('.sub-menu').slideUp(200);
        }
    });


    $('.theme-accordion:nth-child(1) .theme-accordion-bdy').slideDown();
    $('.theme-accordion:nth-child(1) .theme-accordion-control i').addClass('ti-minus');
    $('body').on('click', '.theme-accordion-hdr', function () {
        var ele = $(this);
        $('.theme-accordion-bdy').slideUp();
        $('.theme-accordion-control i').removeClass('ti-minus');
        if (ele.parents('.theme-accordion').find('.theme-accordion-bdy').css('display') === "none") {
            ele.find('.theme-accordion-control i').addClass('ti-minus');
            ele.parents('.theme-accordion').find('.theme-accordion-bdy').slideDown();
        } else {
            ele.find('.theme-accordion-control i').removeClass('ti-minus');
            ele.parents('.theme-accordion').find('.theme-accordion-bdy').slideUp();
        }
    });

    // Image Live Preview
    $('.adm-add-img p').click(function () {
        $('.adm-add-img img').remove();
        $('.adm-add-img').hide();
        $('#picture_container input[type=hidden]').val("");
        $('.picture').show();
    });

    //Filter date picker
    $('.filter-date').datepicker({
        dateFormat: $('.common_date_format').val()
    });

    //Filter date picker
    $('.date').datepicker({
        dateFormat: $('.common_date_format').val()
    });

    if ($('.table-date-range').length) {
        var tabledate_data = $('.table-date-range').data();
        $('.table-date-range').daterangepicker({
            autoApply: false,
            alwaysShowCalendars: true,
            opens: 'left',
            applyButtonClasses: 'btn-danger',
            cancelClass: 'btn-white',
            locale: {
                format: $('.common_daterange_format').val(),
                separator: " => ",
            },
            startDate: tabledate_data.start,
            endDate: tabledate_data.end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'All Time': [moment('2015-01-01'), moment().add(1, 'days')],
            },
        });

        $('.table-date-range').on('apply.daterangepicker', function(ev, picker) {
            window.location.replace(path+tabledate_data.route+'&start='+picker.startDate.format('YYYY-MM-DD')+'&end='+picker.endDate.format('YYYY-MM-DD'));
        });
    }

    $('body').on('click', '.edit-paymenttype', function () {
        var ele = $(this);
        $('#addPaymentType input[name="name"]').val(ele.data("name"));
        $('#addPaymentType input[name="id"]').val(ele.data("id"));
        $('#addPaymentType select[name="status"]').val(ele.data("status"));
        $('#addPaymentType .modal-title').text('Edit Payment Method');
        $('#addPaymentType form').attr('action', $('.site_url').val().concat('paymentmethod/edit'));
        $('#addPaymentType').modal('show');
    });
    $('#addPaymentType').on('hidden.bs.modal', function (e) {
        $('#addPaymentType .modal-title').text('New Payment Method');
        $('#addPaymentType input').not( "[name='_token']" ).val('');
        $('#addPaymentType textarea').val('');
        $('#addPaymentType form').attr('action', $('.site_url').val().concat('paymentmethod/add'));
    });

    //New or Edit Medicine Category Modal *************
    $('body').on('click', '.edit-mcategory', function () {
        var ele = $(this);
        var data = ele.data();
        $('#add-mcategory input[name="name"]').val(ele.data("name"));
        $('#add-mcategory input[name="id"]').val(ele.data("id"));
        $('#add-mcategory .modal-title').text('Edit Medicine Category');
        $('#add-mcategory form').attr('action', $('.site_url').val().concat('medicine/category/edit'));
        $('#add-mcategory').modal('show');
    });
    $('#add-mcategory').on('hidden.bs.modal', function (e) {
        $('#add-mcategory .modal-title').text('New Medicine Category');
        $('#add-mcategory input').not( "[name='_token']" ).val('');
        $('#add-mcategory textarea').val('');
        $('#add-mcategory form').attr('action', $('.site_url').val().concat('medicine/category/add'));
    });

    $("#uploadmedicine-modal").on('change', '#medicinefile', function(e) {
        if (e.target.files.length > 0) {
            $(this).siblings('label').text(e.target.files[0].name);
        }
    });

    //Stock table update modal
    $('.stock-table').on('click', '.edit-stock', function () {
        var ele = $(this), ele_parent = ele.parents('tr'),
        data = ele.data();
        $('#editstock-modal input[name="available"]').val(ele_parent.find('.available').text());
        $('#editstock-modal input[name="id"]').val(data.id);
        $('#editstock-modal input[name="medicine_id"]').val(data.medicineid);
        $('#editstock-modal .medicine').html(ele_parent.find('.medicine').text());
        $('#editstock-modal .batch').html(ele_parent.find('.batch').text());
        $('#editstock-modal .expiry').html(ele_parent.find('.expiry').text());
        $('#editstock-modal .qty').html(ele_parent.find('.qty').text());
        $('#editstock-modal .sold').html(ele_parent.find('.sold').text());
        $('#editstock-modal').modal('show');
    });

    $('#editstock-modal').on('hidden.bs.modal', function (e) {
        $('#editstock-modal input').not( "[name='_token']" ).val('');
    });

    //New or Edit supplier Modal *************
    $('body').on('click', '.edit-supplier', function () {
        var ele = $(this);
        var data = ele.data();
        $('#add-supplier input[name="name"]').val(ele.data("name"));
        $('#add-supplier input[name="email"]').val(ele.data("email"));
        $('#add-supplier input[name="phone"]').val(ele.data("phone"));
        $('#add-supplier textarea[name="address"]').val(ele.data("address"));
        $('#add-supplier input[name="id"]').val(ele.data("id"));
        $('#add-supplier .modal-title').text('Edit Supplier');
        $('#add-supplier form').attr('action', $('.site_url').val().concat('supplier/edit'));
        $('#add-supplier').modal('show');
    });

    $('#add-supplier').on('hidden.bs.modal', function (e) {
        $('#add-supplier .modal-title').text('New Supplier');
        $('#add-supplier input').not( "[name='_token']" ).val('');
        $('#add-supplier textarea').val('');
        $('#add-supplier form').attr('action', $('.site_url').val().concat('supplier/add'));
    });

    var dataTable = $('.datatable-table').DataTable({
        aLengthMenu: [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
        iDisplayLength: 10,
        pagingType: 'full_numbers',
        order: [],
        dom: "<'row align-items-center pb-3'<'col-sm-6 text-left'l><'col-sm-6 text-right'f>><'row'<'col-sm-12'tr>><'row align-items-center pt-3'<'col-sm-12 col-md-4'i><'col-sm-12 col-md-8 text-right dataTables_pager'p>>",
        responsive: true,
        language: {
            "paginate": {
                "first":       '<i class="las la-angle-double-left"></i>',
                "previous":    '<i class="las la-angle-left"></i>',
                "next":        '<i class="las la-angle-right"></i>',
                "last":        '<i class="las la-angle-double-right"></i>'
            },
        }
    });

    var countdatatable = $('.datatable-count-table').DataTable({
        aLengthMenu: [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
        iDisplayLength: 10,
        pagingType: 'full_numbers',
        order: [],
        dom: "<'row align-items-center pb-3'<'col-sm-6 text-left'l><'col-sm-6 text-right'f>><'row'<'col-sm-12'tr>><'row align-items-center pt-3'<'col-sm-12 col-md-4'i><'col-sm-12 col-md-8 text-right dataTables_pager'p>>",
        responsive: false,
        language: {
            "paginate": {
                "first":       '<i class="las la-angle-double-left"></i>',
                "previous":    '<i class="las la-angle-left"></i>',
                "next":        '<i class="las la-angle-right"></i>',
                "last":        '<i class="las la-angle-double-right"></i>'
            },
        },
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

    $('.toggle-button a').on( 'click', function (e) {
        e.preventDefault();
        var ele = $(this);
        var column = countdatatable.column(ele.attr('data-column'));
        if (column.visible()) {
            ele.find('i').removeClass('la-toggle-on');
            ele.find('i').addClass('la-toggle-off');
        } else {
            ele.find('i').addClass('la-toggle-on');
            ele.find('i').removeClass('la-toggle-off');
        }
        column.visible(!column.visible());
    });

    if ($('.alert-message').length) {
        var alert_message = JSON.parse($('.alert-message').val());
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "10000",
            "hideDuration": "10000",
            "timeOut": "2000",
            "extendedTimeOut": "800",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        toastr[alert_message.alert](alert_message.value, alert_message.alert);
    }
});



