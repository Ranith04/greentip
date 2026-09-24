$(document).ready(function() {
    $('#FormResetPassword').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        addOns: {
            reCaptcha2: {
                element: 'captchaContainer',
                theme: 'light',
                siteKey: '6LdrYGUUAAAAABOcK3alH_JB74nSYbPL2gShJWXz',
                timeout: 120,
                message: 'The captcha is not valid'
            }
        },
        fields: {
            resetPassword: {
                validators: {
                    notEmpty: {
                        message: 'The Password is required and cannot be empty.'
                    },
                    stringLength: {
                        min: 8,
                        max: 30,
                        message: 'The Password must be between 8 and 30'
                    }
                }
            },
            resetConPassword: {
                validators: {
                    /*  notEmpty: {
                     message: 'The Confirm Password is required and cannot be empty.'
                     },*/
                   
                    identical: {
                        field: 'resetPassword',
                        message: 'The Confirm Password and Password are not the same'
                    }
                }
            }
        }
    });
    $('#ChangePasswordForm').formValidation({
        framework: 'bootstrap',
       /* icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },*/
        fields: {
            OldPassword: {
                validators: {
                    notEmpty: {
                        message: 'Old Password is required and cannot be empty.'
                    },
                    stringLength: {
                        min: 8,
                        max: 30,
                        message: 'Old Password must be between 8 and 30'
                    }
                }
            },
            NewPassword: {
                validators: {
                    notEmpty: {
                        message: 'The Password is required and cannot be empty.'
                    },
                    stringLength: {
                        min: 8,
                        max: 30,
                        message: 'The Password must be between 8 and 30'
                    }
                }
            },
            ConPassword: {
                validators: {
                    /*  notEmpty: {

                     message: 'The Confirm Password is required and cannot be empty.'

                     },*/
                    
                    identical: {
                        field: 'NewPassword',
                        message: 'The Confirm Password and Password are not the same'
                    }
                }
            }
        }
    });
    $('#EditProfileForm').formValidation({
        excluded: ':disabled',
        framework: 'bootstrap',
        fields: {
            phone: {
                validators: {
                    notEmpty: {
                        message: 'The Phone Number field is required.'
                    },
                    numeric: {
                        message: 'The phone number field must be a number.'
                    },
                    stringLength: {
                        message: 'Phone Number must be of 10 digits',
                        max: 10,
                        min: 10
                    }
                }
            },
            designation: {
                validators: {
                    notEmpty: {
                        message: 'The Designation field is required.'
                    },
                    stringLength: {
                        min: 1,
                        max: 30,
                        message: 'The Last Name field must be at least 4 characters in length.'
                    }
                }
            }
        }
    });
    $('#Contact').formValidation({
        excluded: ':disabled',
        framework: 'bootstrap',
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'The Name field is required.'
                    }
                }
            },
            phone: {
                validators: {
                    notEmpty: {
                        message: 'The Phone Number field is required.'
                    },
                    numeric: {
                        message: 'Please enter valid phone number.'
                    },
                    stringLength: {
                        message: 'Phone Number must be of 10 digits',
                        max: 10,
                        min: 10
                    }
                }
            },
            email: {
                validators: {
                    stringCase: {
                        message: 'The Email must be in lowercase',
                        'case': 'lower'
                    },
                    regexp: {
                        regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                        message: 'Not a valid Email, Please enter valid email address.'
                    },
                    notEmpty: {
                        message: 'The Email is required and cannot be empty.'
                    },
                    stringLength: {
                        max: 150,
                        message: 'The Email must less than 30 characters'
                    }
                }
            },
            message: {
                validators: {
                    notEmpty: {
                        message: 'The Message field is required.'
                    },
                    stringLength: {
                        min: 10,
                        message: 'The Message field must be at least 10 characters in length.'
                    }
                }
            }
        }
    });
});