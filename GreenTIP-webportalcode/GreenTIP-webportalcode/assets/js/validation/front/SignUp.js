/**
 * Created by abhishek on 7/2/15.
 */

var SignUp = function(){
    $('#SignUpForm').formValidation({
        fields: {
            RegFullName: {
                validators: {
                    notEmpty: {
                        message: 'Full Name is required and can\'t be empty'
                    },
                    stringLength: {
                        max: 60,
                        message: 'The First Name must be between 8 and 30'
                    }
                }
            },
            RegUsername: {
                validators: {
                    notEmpty: {
                        message: 'Username is required and can\'t be empty'
                    },
                    regexp: {
                        regexp: /^[a-z0-9_-]+$/,
                        message: 'The Username can only consist {a-z}, {0-9}, _ and - characters.'
                    },
                    remote: {
                        url: baseUrl + 'ajax/ajaxCheckUsername',
                        // Send { email: 'its value', username: 'its value' } to the back-end
                        data: function (validator) {
                            return {
                                RegUsername: validator.getFieldElements('RegUsername').val()
                            };
                        },
                        message: 'This Username already exist.'
                    }
                }
            },
            RegEmail: {
                validators: {
                    notEmpty: {
                        message: 'The Email is required and cannot be empty.'
                    },
                    emailAddress: {
                        message: 'The value is not a valid email address'
                    },
                    remote: {
                        url: baseUrl + 'ajax/ajaxCheckEmail',
                        // Send { email: 'its value', username: 'its value' } to the back-end
                        data: function (validator) {
                            return {
                                RegEmail: validator.getFieldElements('RegEmail').val()
                            };
                        },
                        message: 'This Email already exist.'
                    }
                }
            },
            RegPassword: {
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
            RegConPassword: {
                validators: {
                    notEmpty: {
                        message: 'The Confirm Password is required and cannot be empty.'
                    },
                    stringLength: {
                        min: 8,
                        max: 30,
                        message: 'The Confirm Password must be between 8 and 30'
                    },
                    identical: {
                        field: 'RegPassword',
                        message: 'The Confirm Password and Password are not the same'
                    }
                }
            }
        }
    });
}

