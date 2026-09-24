<script src="<?php echo assets_url('js', 'function.js'); ?>"></script>
<script src="<?php echo assets_url('js', 'validation/formValidation.min.js'); ?>"></script>
<script src="<?php echo assets_url('js', 'validation/bootstrap.min.js'); ?>"></script>
<script src="<?php echo assets_url('js', 'jquery.form.js');  ?>"></script>
<script src="<?php echo assets_url('js', 'formClass.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $(".toggle-accordion").on("click", function () {
            var accordionId = $(this).attr("accordion-id"),
                numPanelOpen = $(accordionId + ' .collapse.in').length;

            $(this).toggleClass("active");

            if (numPanelOpen == 0) {
                openAllPanels(accordionId);
            } else {
                closeAllPanels(accordionId);
            }
        })
        openAllPanels = function (aId) {
            console.log("setAllPanelOpen");
            $(aId + ' .panel-collapse:not(".in")').collapse('show');
        }
        closeAllPanels = function (aId) {
            console.log("setAllPanelclose");
            $(aId + ' .panel-collapse.in').collapse('hide');
        }
		/*  var isMobile = window.orientation > -1; 
		if(isMobile){
			$('.is_header').hide();
			$('.myfooter').hide();
			$('.post_query_mobile').hide();
			$('.body-container').addClass('ismobile');
		} */
    });
    jQuery(document).ready(function () {
        setTimeout("jQuery('.front_flashdata').fadeOut();", 5000);
    });
    jQuery(function () {
        var url = window.location.pathname;
        var urlRegExp = new RegExp(url.replace(/\/$/, '') + "$");
        jQuery('.navv  ul li a').each(function () {
            if (urlRegExp.test(this.href.replace(/\/$/, ''))) {
                if (url == '/') {
                    jQuery(this).removeClass('active');
                }
                else {
                    jQuery(this).addClass('active');
                }
            }
        });
    });

    $('body').on('hidden.bs.modal', '.modal', function () {
        $('li.login a').html('Login');
        $('#regularModal').find(".modal-content").html(""); // Just clear the contents.

         $('#myModal').find(".modal-content").html("");  
        $(this).removeData('bs.modal');
    });
    $('body').delegate('.loginModal,.registerModal,.forgotPasswordModal', 'click', function () {
        $('li.login a').html('Login');
        var dataURL = $(this).attr('data-href');
        $('#regularModal .modal-content').load(dataURL, function () {
            $('#regularModal').modal({show: true});
        });
    });
    $('body').delegate('.registerModal', 'click', function () {
        $('li.login a').html('Register');
        var dataURL = $(this).attr('data-href');
        $('#regularModal .modal-content').load(dataURL, function () {
            $('#regularModal').modal({show: true});
        });
    });
    $('body').delegate('.assignExpertModal,.ReviewModal', 'click', function () {
        var dataURL = $(this).attr('data-href');
        $('#myModal .modal-content').load(dataURL, function () {
            $('#myModal').modal({show: true});  
        });

    });
</script>