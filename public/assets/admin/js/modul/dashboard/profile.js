// Class definition
var KTAccountSettingsSigninMethods = function () {
    var signInForm;
    var signInMainEl;
    var signInEditEl;
    var passwordMainEl;
    var passwordEditEl;
    var signInChangeEmail;
    var signInCancelEmail;
    var passwordChange;
    var passwordCancel;

    var toggleChangeEmail = function () {
        signInMainEl.classList.toggle('d-none');
        signInChangeEmail.classList.toggle('d-none');
        signInEditEl.classList.toggle('d-none');
    }

    var toggleChangePassword = function () {
        passwordMainEl.classList.toggle('d-none');
        passwordChange.classList.toggle('d-none');
        passwordEditEl.classList.toggle('d-none');
    }

    // Private functions
    var initSettings = function () {  
        if (!signInMainEl) {
            return;
        }        

        // toggle UI
        signInChangeEmail.querySelector('button').addEventListener('click', function () {
            toggleChangeEmail();
        });

        signInCancelEmail.addEventListener('click', function () {
            toggleChangeEmail();
        });

        passwordChange.querySelector('button').addEventListener('click', function () {
            toggleChangePassword();
        });

        passwordCancel.addEventListener('click', function () {
            toggleChangePassword();
        });
    }

    var handleChangeEmail = function (e) {
        var validation;        

        if (!signInForm) {
            return;
        }

        validation = FormValidation.formValidation(
            signInForm,
            {
                fields: {
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'Email is required'
                            },
                            email: {
                                message: 'The value is not a valid email address'
                            }
                        }
                    },

                    password: {
                        validators: {
                            notEmpty: {
                                message: 'Password is required'
                            }
                        }
                    }
                },

                plugins: { 
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row'
                    })
                }
            }
        );

        signInForm.querySelector('#button_update_email').addEventListener('click', function (e) {
            e.preventDefault();
            validation.validate().then(function (status) {
                if (status == 'Valid') {
                    update_email(validation);
                } else {
                    swal.fire({
                        text: "Sorry, looks like there are some errors detected, please try again.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    });
                }
            });
        });
    }

    var handleChangePassword = function (e) {
        var validation;

        // form elements
        

        if (!passwordForm) {
            return;
        }

        validation = FormValidation.formValidation(
            passwordForm,
            {
                fields: {
                    currentpassword: {
                        validators: {
                            notEmpty: {
                                message: 'Current Password is required'
                            }
                        }
                    },

                    newpassword: {
                        validators: {
                            notEmpty: {
                                message: 'New Password is required'
                            }
                        }
                    },

                    confirmpassword: {
                        validators: {
                            notEmpty: {
                                message: 'Confirm Password is required'
                            },
                            identical: {
                                compare: function() {
                                    return passwordForm.querySelector('[name="newpassword"]').value;
                                },
                                message: 'The password and its confirm are not the same'
                            }
                        }
                    },
                },

                plugins: { //Learn more: https://formvalidation.io/guide/plugins
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row'
                    })
                }
            }
        );

        passwordForm.querySelector('#button_update_password').addEventListener('click', function (e) {
            e.preventDefault();
            console.log('click');

            validation.validate().then(function (status) {
                if (status == 'Valid') {
                    update_password(validation);
                } else {
                    swal.fire({
                        text: "Sorry, looks like there are some errors detected, please try again.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    });
                }
            });
        });
    }

    // Public methods
    return {
        init: function () {
            passwordForm = document.getElementById('form_update_password');
            signInForm = document.getElementById('form_update_email');
            signInMainEl = document.getElementById('kt_signin_email');
            signInEditEl = document.getElementById('pane_update_email');
            passwordMainEl = document.getElementById('kt_signin_password');
            passwordEditEl = document.getElementById('pane_update_password');
            signInChangeEmail = document.getElementById('kt_signin_email_button');
            signInCancelEmail = document.getElementById('button_cancel_update_email');
            passwordChange = document.getElementById('kt_signin_password_button');
            passwordCancel = document.getElementById('button_cancel_update_password');

            initSettings();
            handleChangeEmail();
            handleChangePassword();
        }
    }

    
function update_email(validation) {
    var message, icon;
    var btn = $('#button_update_email');
    var btn_text = btn.html();
    var email = signInForm.querySelector('[name="email"]').value;
    var password = signInForm.querySelector('[name="password"]').value;

    $.ajax({
        url: signInForm.getAttribute('action'),
        method: signInForm.getAttribute('method'),
        data: {
            _token: csrf_token,
            email: email,
            password: password
        },
        dataType: 'json',
        beforeSend: function () {
            btn.html('Loading...');
            btn.attr('disabled', true);
        },
        success: function (data) {
            // console.log(data);
            btn.html(btn_text);
            btn.attr('disabled', false);
            if (data.status == 200) {
                icon = 'success';
            } else if (data.status == 700) {
                icon = 'error';
            } else {
                icon = 'warning';
            }
            if (data.status == 200) {
                Swal.fire({
                    html: data.message,
                    icon: icon,
                    buttonsStyling: !1,
                    confirmButtonText: "OK",
                    customClass: {
                        confirmButton: css_btn_confirm
                    }
                }).then((function (t) {
                    if (t.isConfirmed) {
                        signInForm.reset();
                        validation.resetForm();
                        toggleChangeEmail();
                        $('#display_email').text(email);
                        signInForm.querySelector('[name="email"]').value = email;
                    }
                }))
            } else {
                Swal.fire({
                    html: data.message,
                    icon: icon,
                    buttonsStyling: !1,
                    confirmButtonText: "OK",
                    customClass: {
                        confirmButton: css_btn_confirm
                    }
                })
            }
        }
    });
}

function update_password(validation) {
    var message, icon;
    var btn = $('#button_update_password');
    var btn_text = btn.html();
    var currentpassword = passwordForm.querySelector('[name="currentpassword"]').value;
    var newpassword = passwordForm.querySelector('[name="newpassword"]').value;
    var confirmpassword = passwordForm.querySelector('[name="confirmpassword"]').value;

    $.ajax({
        url: passwordForm.getAttribute('action'),
        method: passwordForm.getAttribute('method'),
        data: {
            _token: csrf_token,
            currentpassword: currentpassword,
            newpassword: newpassword,
            confirmpassword: confirmpassword
        },
        dataType: 'json',
        beforeSend: function () {
            btn.html('Loading...');
            btn.attr('disabled', true);
        },
        success: function (data) {
            // console.log(data);
            btn.html(btn_text);
            btn.attr('disabled', false);
            if (data.status == 200) {
                icon = 'success';
            } else if (data.status == 700) {
                icon = 'error';
            } else {
                icon = 'warning';
            }
            if (data.status == 200) {
                Swal.fire({
                    html: data.message,
                    icon: icon,
                    buttonsStyling: !1,
                    confirmButtonText: "OK",
                    customClass: {
                        confirmButton: css_btn_confirm
                    }
                }).then((function (t) {
                    if (t.isConfirmed) {
                        passwordForm.reset();
                        validation.resetForm();
                        toggleChangePassword();
                    }
                }))
            } else {
                Swal.fire({
                    html: data.message,
                    icon: icon,
                    buttonsStyling: !1,
                    confirmButtonText: "OK",
                    customClass: {
                        confirmButton: css_btn_confirm
                    }
                })
            }
        }
    });
}
}();

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTAccountSettingsSigninMethods.init();
});




