$(document).ready(function () {

    $('#lgn-submit').click(function () {
        var clck_invld = 0;

        if ($('#lgn-pass').val().trim().length < 4) {
            $('#lgn-pass').parents('.lgn-input').addClass('is-invalid');
            clck_invld = 1;
            $('#lgn-pass').focus();
        }
        if ($('#lgn-mail').val().trim().length < 2) {
            $('#lgn-mail').parents('.lgn-input').addClass('is-invalid');
            clck_invld = 1;
            $('#lgn-mail').focus();
        }
        if (clck_invld === 1) {
            return false;
        }
    });

    $('#forgot-submit').click(function () {
        var clck_invld = 0;
        
        if ($('#lgn-mail').val().trim().length < 2) {
            $('#lgn-mail').parents('.lgn-input').addClass('is-invalid');
            clck_invld = 1;
            $('#lgn-mail').focus();
        }
        if (clck_invld === 1) {
            return false;
        }
    });

    $('#reset-submit').click(function () {
        var clck_invld = 0;

        if ($('#lgn-new').val().trim().length < 8) {
            $('#lgn-new').parents('.lgn-input').addClass('is-invalid');
            clck_invld = 1;
            $('#lgn-new').focus();
        }

        if ($('#lgn-confirm').val().trim().length < 8) {
            $('#lgn-confirm').parents('.lgn-input').addClass('is-invalid');
            clck_invld = 1;
            $('#lgn-confirm').focus();
        }

        if ($('#lgn-new').val().trim() !== $('#lgn-confirm').val().trim()) {
            $('#lgn-confirm').parents('.lgn-input').addClass('is-invalid');
            clck_invld = 1;
            $('#lgn-confirm').focus();
        }
        
        if (clck_invld === 1) {
            return false;
        }
    });

});