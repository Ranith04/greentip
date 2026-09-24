$(document).ready(function () {
    $(document).on("submit", ".ajax_form", function (event) {
        // ajaxLoaderStart();
        var posturl = $(this).attr('action');
        var callbackFunction = $(this).attr('data-callback_function');
        if (callbackFunction) {
            var funcCall = callbackFunction + "()";
            var ret = eval(funcCall);
            if (ret == false) {
                return false;
            }
        }
        var formid = '#' + $(this).attr('id');
        if (formid == '#submit-query') {

            var category_id = $('#category_id').val();

            var query = $('#query').val();
            if (category_id != '' && query != '') {
                if (confirm('Are you sure you want to submit this query?')) {

                } else {
                    // $(formid).find('.alert.ajax_report').hide();
                    return false;
                }
            }
        }

        $(this).ajaxSubmit({
            url: posturl, dataType: 'json', beforeSend: function () {
                $(formid).find('.alert.ajax_report').fadeIn();
                $(formid).find('.alert.ajax_report').removeClass('alert-success').removeClass('alert-danger').removeClass('alert-info');
                $(formid).find('.alert.ajax_report').addClass('alert-info').children('.ajax_message').html('<p><strong>Please wait! </strong>Your action is in proccess...</p>');
                if (formid == '#submit-query') {

                    var category_id = $('#category_id').val();

                    var query = $('#query').val();
                     if (category_id == '' &&  query == '' ) {
                        $(formid).find('.alert.ajax_report').removeClass('alert-danger').removeClass('alert-info');
                        $(formid).find('.alert.ajax_report').addClass('alert-danger').children('.ajax_message').html('Please Select Category <br> Please Enter Query');
                        return false;
                    }
                    else if (category_id == '') {
                        $(formid).find('.alert.ajax_report').removeClass('alert-danger').removeClass('alert-info');
                        $(formid).find('.alert.ajax_report').addClass('alert-danger').children('.ajax_message').html('Please select category');
                        return false;
                    } else if (query == '') {
                        $(formid).find('.alert.ajax_report').removeClass('alert-danger').removeClass('alert-info');
                        $(formid).find('.alert.ajax_report').addClass('alert-danger').children('.ajax_message').html('Please enter question');
                        return false;
                    }

                }
                $(formid).find("input[type=submit]").attr("disabled", "disabled");
                $(formid).find("button[type=submit]").attr("disabled", "disabled");
                $(formid).find('.wait-div-form').show();

            },
            success: function (response) {
                // ajaxLoaderStop();
                $("input[type=submit]").removeAttr("disabled");
                $(formid).closest('form').find("input[type=submit]").removeAttr("disabled");
                $(formid).closest('form').find("button[type=submit]").removeAttr("disabled");
                $(formid).find('.wait-div-form').hide();
                $(formid).find('.alert.ajax_report').removeClass('alert-success').removeClass('alert-danger').removeClass('alert-info');
                $(formid).find('.form-group').removeClass('has-error');
                $(formid).find('.form-group .form-message').remove();
                if (response.message) {
                    if (response.messageNot) {
                        $(formid).find('.alert.ajax_report').fadeOut(100);
                    } else {
                        if (response.success) {
                            $(formid).find('.alert.ajax_report').fadeIn();
                            $(formid).find('.alert.ajax_report').addClass('alert-success').children('.ajax_message').html(response.message);
                        }
                        else {
                            if (typeof response.message === 'string') {
                                $(formid).find('.alert.ajax_report').addClass('alert-danger').children('.ajax_message').html(response.message);
                                $(formid).find('.alert.ajax_report').show();
                            } else {
                                $.each(response.message, function (key, value) {
                                    $(formid).find('[name="' + key + '"]').closest('.form-group').addClass('has-error');
                                    if ($(formid).find('[name="' + key + '"]').closest('td').length) {
                                        $(formid).find('[name="' + key + '"]').closest('td').append('<small class="form-message help-block">' + value + '</small>');
                                    } else {
                                        $(formid).find('[name="' + key + '"]').closest('div').append('<small class="form-message help-block">' + value + '</small>');
                                    }
                                });
                            }
                        }
                    }
                } else {
                    $(formid).find('.alert').fadeOut(100);
                }
                if (response.resetform) $(formid).resetForm();
                if (response.url) setTimeout(function () {
                    window.location.href = response.url;
                }, 2000);
                if (response.loginurl) setTimeout(function () {
                    window.location.href = response.loginurl;
                }, 200);
                if (response.parentUrl) window.top.location.href = response.parentUrl;
                if (response.selfReload) window.location.reload();
                if (response.slideToThisDiv) slideToDiv(response.divId);
                if (response.slideToTop) slideToTop();
                if (response.slideToThisForm) slideToElement(formid);
                if (response.ajaxPageCallBack) {
                    response.formid = formid;
                    ajaxPageCallBack(response);
                }
                if (response.hideModel) {
                    setTimeout(function () {
                        $('.modal').modal('hide');
                    }, 1000);
                }
                if (response.popup) {
                    parent.$.fancybox.update();
                }
                setTimeout(function () {
                    $(formid).find('.ajax_report').fadeOut(1000);
                    if (response.popup) {
                        parent.$.fancybox.update();
                    }
                }, 7000);
                setTimeout(function () {
                    if (response.popup) {
                        parent.$.fancybox.update();
                    }
                }, 8100);
            },
            error: function (response) {
                alert('Connection error')
            }
        });
        return false;
    });
});
$(document).ready(function (e) {
    $(document).on("click", ".alert .close", function (event) {
        $(this).closest(".ajax_report").hide();
    });
});

function openModel(url, position) {
    $(position).modal({
        show: true,
        backdrop: 'static'
    });

    //$(position).modal('show');

    $(position).find('.modal-content').html('<div style="text-align:center;"><img src="' + templateassets + 'img/loading.gif" alt="" class="loading">Please wait...</div>');
    $.get(url, function (data) {
        $(position).find('.modal-content').html(data);
        return false;
    });
}

function slideToElement(element, position) {
    if (position == '') {
        position = 150;
    }
    $("html, body").animate({scrollTop: $(element).offset().top - position}, 1000);
}

function slideToDiv(element) {
    $("html, body").animate({scrollTop: $(element).offset().top - 50}, 1000);
}

function slideToTop(position) {
    if (position === '' || position === undefined) {
        position = 50;
    }
    $("html, body").animate({scrollTop: position}, 1000);
}

function show_messege_toast(type, message) {
    switch (type) {
        case"success":
            toastr.success(message, 'Information', {timeOut: 5000});
            break;
        case"warning":
            toastr.warning(message, 'Information', {timeOut: 5000});
            break;
        case"error":
            toastr.error(message, 'Information', {timeOut: 5000});
            break;
        case"info":
            toastr.info(message, 'Information', {timeOut: 5000});
            break;
        case"clear":
            toastr.clear();
            break;
        default:
            toastr.info(message, 'Information', {timeOut: 5000});
    }
}

function show_messege(message, type) {
    if (type == 'error') var color = '#d9534f'; else if (type == 'success') var color = '#5cb85c'; else
        var color = '#5bc0de';
    var dialog = new BootstrapDialog({
        message: function (dialogRef) {
            var $message = $('<div>' + message + '</div>');
            return $message;
        }, closable: true
    });
    dialog.realize();
    dialog.getModalHeader().hide();
    dialog.getModalFooter().hide();
    dialog.getModalBody().css('background-color', color);
    dialog.getModalBody().css('border-radius', '5px');
    dialog.getModalBody().css('color', '#fff');
    dialog.getModalBody().css('text-align', 'center');
    dialog.getModalContent().css('background-color', 'transparent');
    dialog.getModalDialog().css('background', 'transparent');
    dialog.open();
    setTimeout(function () {
        dialog.close();
    }, 4000);
}

function show_modal(message, type, p_info, model_id) {
    var html = '';
    html += '<div class="modal-body"><div class="add-cart-popup">';
    html += '<div class="alert alert-' + type + '">' + message + '</div>';
    html += '<div class="media"><div class="media-left"><img alt="' + p_info.name + '" src="' + p_info.image + '" width="80" class="media-object"></div>';
    html += '<div class="media-body"><h4><a href="' + p_info.url + '">' + p_info.name + '</a></h4><p><span>Price: <strong><i class="fa fa-rupee"></i>' + p_info.price + '</strong></span> <span>Qty.: <strong>' + p_info.qty + '</strong></span></p>';
    html += '</div></div>';
    html += '<div class="graybuttons clearfix continue-shop"> <a href="javascript:void(0);" class="btn btn-black pull-left">Continue Shopping</a> <a href="' + checkoutUrl + '" class="btn btn-black pull-right">Go to shopping cart</a> </div>';
    html += '</div></div>';
    if (model_id == '') model_id = 'modal-regular';
    $('#' + model_id).find('.modal-content.my-model').html(html);
    $('#' + model_id).modal('show');
}

function ajaxPageCallBack(response) {
    var CallBackRequest = response.CallBackRequest;
    if (CallBackRequest == 'demo_form') {
        $('.tt-search-popup').removeClass('open');
    }
}
