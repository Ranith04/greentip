function ajaxLoaderStart() {
    if (jQuery('body').find('#resultLoading').attr('id') != 'resultLoading') {
        jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="' + BASE_URL + 'assets/img/loading.gif"><div></div></div><div class="bg"></div></div>');
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
        'background': '#f5f5f5',
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

function ajaxLoaderStop() {
    jQuery('#resultLoading .bg').height('100%');
    jQuery('#resultLoading').fadeOut(300);
    jQuery('body').css('cursor', 'default');
}

function LoadMoreDataDashboard(container_ele, item_ele, pagination_ele, next_page_url_ele, box_type) {
    var next_page_url = '';


    next_page_url = $(next_page_url_ele).attr('href');


    $(container_ele + ' .loadmoredataDashboard').remove();


    if ($(container_ele).length > 0 && $(item_ele).length > 0 && $(pagination_ele).length > 0 && $(next_page_url_ele).length > 0 && (next_page_url != undefined || next_page_url != '')) {
        $(pagination_ele).hide();
        next_page_url = $(next_page_url_ele).attr('href');
        if (next_page_url != '') {

            $(container_ele).append('<div class="loadmoredataDashboard"><div align="center"><a href="javascript:void(0);" class="view-more">VIEW MORE</a></div>');
        }
    }
    else {
        $(pagination_ele).hide();
        $(container_ele + ' .loadmoredataDashboard').remove();
    }


    $(container_ele + ' .view-more').click(function () {

        if (next_page_url_ele) {

            $(this).parent().remove();
            $(container_ele).append('<div class="loadmoredataDashboard"><img src="' + SiteUrl + 'assets/img/loading.gif"></div>');
            //console.log(next_page_url);
            $.get(next_page_url, function (data) {

                var container_content = $(data).find(container_ele).html();


                if (container_content) {

                    if ($(data).find(item_ele).length > 0) {
                        $(data).find(item_ele).each(function () {
                            var classes = $(this).attr('class');
                            var style = $(this).attr('class');
                            var ids = $(this).attr('class');
                            var item_html = $(this).html();
                            var attr_list = '';
                            if (ids) {
                                attr_list += ' id="' + ids + '" ';
                            }

                            if (classes) {
                                attr_list += ' class="' + classes + '" ';
                            }

                            if (style) {
                                attr_list += ' style="' + style + '" ';
                            }


                            $(item_ele + ':last').after('<' + box_type + attr_list + '>' + item_html + '</' + box_type + '>');


                        });
                    }

                    $(pagination_ele).html($(data).find(pagination_ele).html());

                    //console.log(container_content);
                    //$(container_ele).html(container_content);
                    LoadMoreDataDashboard(container_ele, item_ele, pagination_ele, next_page_url_ele, box_type);
                }
            });
        }
    });
}