/**
 * Created by abhishek on 7/2/15.
 */
var Login = function(){
    $('#LoginForm').formValidation({
        group: 'list-group-item',
        fields: {
            username: {
                validators: {
                    notEmpty: {
                        message: 'The Username is required and cannot be empty.'
                    },
                    regexp: {
                        regexp: /^[a-z0-9_-]+$/,
                        message: 'The Username can only consist {a-z}, {0-9}, _ and - characters.'
                    },
                    stringLength: {
                        min: 8,
                        max: 30,
                        message: 'The Username must be between 8 and 30'
                    },
                    stringCase: {
                        message: 'The Username must be in lowercase',
                        'case': 'lower'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'The Password is required and cannot be empty.'
                    },
                    stringLength: {
                        min: 8,
                        max: 30,
                        message: 'The username must be between 8 and 30'
                    }
                }
            }
        }
    });
}