var requested_fields = {};

function chkLimit(uniqueId, limit) {
    var count_i = $("#result" + uniqueId + " .listingResult").length;
    if (count_i == limit) {
        $('#upload' + uniqueId).hide();
    } else {
        $('#upload' + uniqueId).show();
    }
    $("#status" + uniqueId).html('').hide();
    $("#progressBar" + uniqueId).attr('value', '0').hide();
    //console.log(uniqueId);
    //console.log($('#fldVal'+uniqueId).length);
    if ($('#fldVal' + uniqueId).length == 1) {
        //console.log(requested_fields.hasOwnProperty(uniqueId));
        if (requested_fields.hasOwnProperty(uniqueId)) {
            if ($('#result' + uniqueId + ' input').length > 0) {
                $('#fldVal' + uniqueId).val('1');
            } else {
                $('#fldVal' + uniqueId).val('');
            }
            //console.log($('#fldVal'+uniqueId).attr('name'));
            $('#ProductFrm').formValidation('revalidateField', $('#fldVal' + uniqueId).attr('name'));
        } else {
            requested_fields[uniqueId] = uniqueId;
        }
    }
}

function hasExtension(inputID, exts) {
    var fileName = document.getElementById(inputID).value;
    return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
}

function fileUploader(uniqueId, limit, name) {
    limit = parseInt(limit);
    chkLimit(uniqueId, limit);
    $("#files" + uniqueId).stop().change(function() {
        exts = ['jpg', 'jpeg', 'png', 'gif'];
        exp = new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$');
        var count_i = $("#result" + uniqueId + " .listingResult").length;
        var formData = new FormData();
        //for each entry, add to formdata to later access via $_FILES["file" + i]
        for (var i = 0, len = document.getElementById('files' + uniqueId).files.length; i < len; i++) {
            if (exp.test(document.getElementById('files' + uniqueId).files[i].name.toLowerCase())) {
                formData.append("files" + i, document.getElementById('files' + uniqueId).files[i]);
                count_i++;
            } else {
                alert('You can upload ' + exts.join(', '));
                return false;
                break;
            }
        }
        if (i > 0) {
            if (count_i <= limit) {
                var ajax = new XMLHttpRequest();
                ajax.upload.addEventListener("progress", function(event) {
                    progressHandler(event, uniqueId);
                }, false);
                ajax.addEventListener("load", function(event) {
                    completeHandler(event, uniqueId, limit, name);
                }, false);
                ajax.addEventListener("error", function(event) {
                    errorHandler(event, uniqueId);
                }, false);
                ajax.addEventListener("abort", function(event) {
                    abortHandler(event, uniqueId);
                }, false);
                ajax.open("POST", BASE_URL + "ajax/ajaxUploadTempMulti");
                ajax.send(formData);
            } else {
                alert('You can upload ' + limit + ' files.');
                $("#files" + uniqueId).val('');
            }
        }
    });
    $("#upload" + uniqueId).stop().click(function() {
        $('#files' + uniqueId).trigger('click');
        chkLimit(uniqueId, limit);
    });
    //$('#upload'+uniqueId).trigger('click');
    $('#result' + uniqueId).stop().delegate('.remove_img' + uniqueId, 'click', function() {
        if (confirm('Are you sure to want delete this image?')) {
            $(this).parents('.listingResult').remove();
            chkLimit(uniqueId, limit);
        }
    });
}

function progressHandler(event, uniqueId) {
    var percent = (event.loaded / event.total) * 100;
    $("#progressBar" + uniqueId).attr('value', Math.round(percent)).show();
    //$("#status"+uniqueId).html(Math.round(percent)+"% uploaded... please wait");
    $('#upload' + uniqueId).prop('disabled', true);
    $(":submit").prop('disabled', true);
}

function completeHandler(event, uniqueId, limit, name) {
    var obj = JSON.parse(event.target.responseText);
    if (obj.type == 'error') {
        $("#status" + uniqueId).html(obj.message).show();
        $("#progressBar" + uniqueId).hide();
        $('#upload' + uniqueId).attr('disabled', false);
    } else {
        if (name == 'photos') {
            var resName = name + '[]';
        } else {
            var resName = limit == 1 ? name : name + '[]';
        }
        for (m = 0; m < obj.data.length; m++) {
            $("#result" + uniqueId).append('<div class="listingResult col-md-4"><div class="upimgopt"><input  type="hidden" name="' + resName + '" value="' + obj.data[m].name + '"><a title="Delete Image" class="deleteimg remove_img' + uniqueId + '"  href="javascript:void(0);"><i class="fa  fa-trash-o"></i></a></div><img style="float:left;" src="' + obj.data[m].imageUrl + '"></div>');
            $("#status" + uniqueId).html('').hide();
        }
        ///$("#result"+uniqueId).append('<div class="listingResult col-md-4"><div class="upimgopt"><input  type="hidden" name="field['+uniqueId+'][]" value="'+obj.data.image+'"><a title="Delete Image" class="deleteimg remove_img'+uniqueId+'"  href="javascript:void(0);"><i class="fa  fa-trash-o"></i></a></div><img style="float:left;" src="'+obj.data.imageUrl+'"><span class="imgTitle">'+obj.data.image+'</span></span></div>');
        $("#files" + uniqueId).val('');
        $('#upload' + uniqueId).attr('disabled', false);
        chkLimit(uniqueId, limit);
    }
    $("#progressBar" + uniqueId).attr('value', '0');
    $(":submit").prop('disabled', false);
    //$("#progressBar"+uniqueId).val(0);
}

function errorHandler(event, uniqueId) {
    $("#status" + uniqueId).html('Upload Failed').show();
    //$("#status"+uniqueId).html("Upload Failed");
    /*$('#upload'+uniqueId).prop('disabled',true);
     $('input#save').prop('disabled',true);*/
}

function abortHandler(event, uniqueId) {
    $("#status" + uniqueId).html('Upload Aborted').show();
    //$("#status"+uniqueId).html("Upload Aborted");
    /*$('#upload'+uniqueId).prop('disabled',true);
     $('input#save').prop('disabled',true);*/
}