/**
 * Created by Abhishek on 10/9/14.
 */
var SyonApp = function () {

    var currentPage = ''; // current page
    var baseUrl = ''; // current page
    var controllerMethod = '';

    var AjaxLoaderStart = function ajaxLoaderStart() {
        if (jQuery('body').find('#resultLoading').attr('id') != 'resultLoading') {
            jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="' + baseUrl + 'images/ajax-loader.gif"><div></div></div><div class="bg"></div></div>');
        }

        jQuery('#resultLoading').css({
            'width': '100%',
            'height': '100%',
            'position': 'fixed',
            'z-index': '10000000',
            'top': '0',
            'left': '0',
            'right': '0',
            'bottom': '0',
            'margin': 'auto'
        });

        jQuery('#resultLoading .bg').css({
            'background': '#000000',
            'opacity': '0.7',
            'width': '100%',
            'height': '100%',
            'position': 'absolute',
            'top': '0'
        });

        jQuery('#resultLoading>div:first').css({
            'width': '250px',
            'height': '75px',
            'text-align': 'center',
            'position': 'fixed',
            'top': '0',
            'left': '0',
            'right': '0',
            'bottom': '0',
            'margin': 'auto',
            'font-size': '16px',
            'z-index': '10',
            'color': '#ffffff'

        });

        jQuery('#resultLoading .bg').height('100%');
        jQuery('#resultLoading').fadeIn(300);
        jQuery('body').css('cursor', 'wait');
    }

    var AjaxLoaderStop = function ajaxLoaderStop() {
        jQuery('#resultLoading .bg').height('100%');
        jQuery('#resultLoading').fadeOut(300);
        jQuery('body').css('cursor', 'default');
    }

    return {
        //Initialise theme pages
        init: function () {
            if (SyonApp.isPage("login")) {
                Login();
            }
        },

        //Set page
        setPage: function (name) {
            currentPage = name;
        },


        //Set Base URL
        setBaseUrl: function (name) {
            baseUrl = name;
        },


        //Set Base controller Method
        setControllerMethod: function (name) {
            controllerMethod = name;
        },

        isPage: function (name) {
            return currentPage == name ? true : false;
        },
        //public function to add callback a function which will be called on window resize
        addResponsiveFunction: function (func) {
            responsiveFunctions.push(func);
        },
        // wrapper function to scroll(focus) to an element
        scrollTo: function (el, offeset) {
            pos = (el && el.size() > 0) ? el.offset().top : 0;
            jQuery('html,body').animate({
                scrollTop: pos + (offeset ? offeset : 0)
            }, 'slow');
        },

        // function to scroll to the top
        scrollTop: function () {
            SyonApp.scrollTo();
        },
        // initializes uniform elements
        initUniform: function (els) {
            if (els) {
                jQuery(els).each(function () {
                    if ($(this).parents(".checker").size() == 0) {
                        $(this).show();
                        $(this).uniform();
                    }
                });
            } else {
                handleAllUniform();
            }
        }
    };

}();