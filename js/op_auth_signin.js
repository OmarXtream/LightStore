var OpAuthSignIn = function() {
    var initValidationSignIn = function(){
        jQuery('.js-validation-signin').validate({
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e) {
                jQuery(e).parents('.form-group > div').append(error);
            },
            highlight: function(e) {
                jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
            },
            success: function(e) {
                jQuery(e).closest('.form-group').removeClass('is-invalid');
                jQuery(e).remove();
            },
            rules: {
                'login-username': {
                    required: true,
                    minlength: 3
                },
                'login-password': {
                    required: true,
                    minlength: 5
                }
            },
            messages: {
                'login-username': {
                    required: 'الرجاء ادخال إسم مستخدم او بريد الإلكتروني',
                    minlength: 'يجب أن يتكون اسم المستخدم إو البريد الإلكتروني من 3 احرف واكثر..'
                },
                'login-password': {
                    required: 'يرجى تقديم كلمة مرور',
                    minlength: 'يجب أن تتكون كلمة المرور الخاصة بك من 5 أحرف على الأقل'
                }
            }
        });
    };

    return {
        init: function () {
            // Init Sign In Form Validation
            initValidationSignIn();
        }
    };
}();

// Initialize when page loads
jQuery(function(){ OpAuthSignIn.init(); });