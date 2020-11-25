/**
 * @package Helix3 Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

jQuery(function ($) {
    // ************    START Helix 1.4 JS    ************** //
    // **************************************************** //

    /* make li in menus clickable */
    $('li.sp-menu-item').click(function () {
        $(this).find("a").get(0).click();
    })

    let display_type = "";
    window.onresize = function () {
        display_type = getDisplayType();
    }

    //Default
    if (typeof sp_offanimation === 'undefined' || sp_offanimation === '') {
        sp_offanimation = 'default';
    }

    // attach toggle button
    let container_width = $('#sp-header > .container').width();
    if(container_width < 970 ) {
        $('.menu-item-user-profile.icon.om-account').attr('id', 'offcanvas-toggler');
    }

    if (sp_offanimation == 'default') {
        $('#offcanvas-toggler').on('click', function (event) {
            event.preventDefault();
            $('.off-canvas-menu-init').addClass('offcanvas');
        });

        $('<div class="offcanvas-overlay"></div>').insertBefore('.offcanvas-menu');
        $('.close-offcanvas, .offcanvas-overlay').on('click', function (event) {
            event.preventDefault();
            $('.off-canvas-menu-init').removeClass('offcanvas');
        });

    }

    // Slide Top Menu
    if (sp_offanimation == 'slidetop') {
        $('#offcanvas-toggler').on('click', function (event) {
            event.preventDefault();
            $('.off-canvas-menu-init').addClass('slide-top-menu');
        });

        $('<div class="offcanvas-overlay"></div>').insertBefore('.offcanvas-menu');
        $('.close-offcanvas, .offcanvas-overlay').on('click', function (event) {
            event.preventDefault();
            $('.off-canvas-menu-init').removeClass('slide-top-menu');
        });
    }

    //Full Screen
    if (sp_offanimation == 'fullscreen') {
        $('#offcanvas-toggler').on('click', function (event) {
            event.preventDefault();
            $('.off-canvas-menu-init').addClass('full-screen-off-canvas');
        });
        $(document).ready(function () {
            $('.off-canvas-menu-init').addClass('full-screen');
        });
        $('.close-offcanvas, .offcanvas-overlay').on('click', function (event) {
            event.preventDefault();
            $('.off-canvas-menu-init').removeClass('full-screen-off-canvas');
        });
    }

    //Full screen from top
    if (sp_offanimation == 'fullScreen-top') {
        $('#offcanvas-toggler').on('click', function (event) {
            event.preventDefault();
            $('.off-canvas-menu-init').addClass('full-screen-off-canvas-ftop');
        });
        $(document).ready(function () {
            $('.off-canvas-menu-init').addClass('full-screen-ftop');
        });
        $('.close-offcanvas, .offcanvas-overlay').on('click', function (event) {
            event.preventDefault();
            $('.off-canvas-menu-init').removeClass('full-screen-off-canvas-ftop');
        });
    }

    //Dark with plus
    if (sp_offanimation == 'drarkplus') {
        $('#offcanvas-toggler').on('click', function (event) {
            event.preventDefault();
            $('.off-canvas-menu-init').addClass('new-look-off-canvas');
        });
        $('<div class="offcanvas-overlay"></div>').insertBefore('.offcanvas-menu');
        $(document).ready(function () {
            $('.off-canvas-menu-init').addClass('new-look');
        });
        $('.close-offcanvas,.offcanvas-overlay').on('click', function (event) {
            event.preventDefault();
            $('.off-canvas-menu-init').removeClass('new-look-off-canvas');
        });
    }

    // if sticky header
    if ($("body.sticky-header").length > 0) {
        var fixedSection = $('#sp-header');
        // sticky nav
        var headerHeight = fixedSection.outerHeight();
        var stickyNavTop = fixedSection.offset().top;
        fixedSection.addClass('animated');
        fixedSection.before('<div class="nav-placeholder"></div>');
        $('.nav-placeholder').height('inherit');
        //add class
        fixedSection.addClass('menu-fixed-out');
        var stickyNav = function () {
            var scrollTop = $(window).scrollTop();
            if (scrollTop > stickyNavTop) {
                fixedSection.removeClass('menu-fixed-out').addClass('menu-fixed');
                $('.nav-placeholder').height(headerHeight);
            } else {
                if (fixedSection.hasClass('menu-fixed')) {
                    fixedSection.removeClass('menu-fixed').addClass('menu-fixed-out');
                    $('.nav-placeholder').height('inherit');
                }
            }
        };
        stickyNav();
        // **************  END:: Others SCRIPT  *************** //
        // **************************************************** //
    }
    // go to top
    if (typeof sp_gotop === 'undefined') {
        sp_gotop = '';
    }

    if (sp_gotop) {
        // go to top
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('.scrollup').fadeIn();
            } else {
                $('.scrollup').fadeOut(400);
            }
        });

        $('.scrollup').click(function () {
            $("html, body").animate({
                scrollTop: 0
            }, 600);
            return false;
        });
    } // has go to top

    // Preloader
    // if (typeof sp_preloader === 'undefined') {
    //     sp_preloader = '';
    // }
    //
    // if (sp_preloader) {
    //     $(window).on('load', function () {
    //         if ($('.sp-loader-with-logo').length > 0) {
    //             move();
    //         }
    //         setTimeout(function () {
    //             $('.sp-pre-loader').fadeOut();
    //         }, 1000);
    //     });
    // } // has preloader
    //preloader Function
    function move() {
        var elem = document.getElementById("line-load");
        var width = 1;
        var id = setInterval(frame, 10);

        function frame() {
            if (width >= 100) {
                clearInterval(id);
            } else {
                width++;
                elem.style.width = width + '%';
            }
        }
    }

    // ************    END:: Helix 1.4 JS    ************** //
    // **************************************************** //

    // **************   START Mega SCRIPT   *************** //
    // **************************************************** //

    //mega menu
    $('.sp-megamenu-wrapper').parent().parent().css('position', 'static').parent().css('position', 'relative');
    $('.sp-menu-full').each(function () {
        $(this).parent().addClass('menu-justify');
    });

    // boxlayout
    if ($("body.layout-boxed").length > 0) {
        var windowWidth = $('#sp-header').parent().outerWidth();
        $("#sp-header").css({"max-width": windowWidth, "left": "auto"});
    }

    // **************   END:: Mega SCRIPT   *************** //
    // **************************************************** //

    // **************  START Others SCRIPT  *************** //
    // **************************************************** //

    //Tooltip
    $('[data-toggle="tooltip"]').tooltip();

    // Article Ajax voting
    $(document).on('click', '.sp-rating .star', function (event) {
        event.preventDefault();

        var data = {
            'action': 'voting',
            'user_rating': $(this).data('number'),
            'id': $(this).closest('.post_rating').attr('id')
        };

        var request = {
            'option': 'com_ajax',
            'plugin': 'helix3',
            'data': data,
            'format': 'json'
        };

        $.ajax({
            type: 'POST',
            data: request,
            beforeSend: function () {
                $('.post_rating .ajax-loader').show();
            },
            success: function (response) {
                var data = $.parseJSON(response.data);

                $('.post_rating .ajax-loader').hide();

                if (data.status == 'invalid') {
                    $('.post_rating .voting-result').text('You have already rated this entry!').fadeIn('fast');
                } else if (data.status == 'false') {
                    $('.post_rating .voting-result').text('Somethings wrong here, try again!').fadeIn('fast');
                } else if (data.status == 'true') {
                    var rate = data.action;
                    $('.voting-symbol').find('.star').each(function (i) {
                        if (i < rate) {
                            $(".star").eq(-(i + 1)).addClass('active');
                        }
                    });

                    $('.post_rating .voting-result').text('Thank You!').fadeIn('fast');
                }

            },
            error: function () {
                $('.post_rating .ajax-loader').hide();
                $('.post_rating .voting-result').text('Failed to rate, try again!').fadeIn('fast');
            }
        });
    });

    // Use getEntriesByType() to just get the "navigation" events
    TRANSITION_RELOAD_STATUS = performance.getEntriesByType("navigation");
    for (var i = 0; i < TRANSITION_RELOAD_STATUS.length; i++) {
        TRANSITION_RELOAD_STATUS = TRANSITION_RELOAD_STATUS[i].type === "reload";
        break;
    }

    // **************  START:: Scroll Header effect ******* //
    // **************************************************** //
    $(window).scroll(() => {
             stickyNav();
            if ($(this).scrollTop() > 0) {

                // scroll position for effect end based on height of previouse
                // let end_effect_on = $('#sp-header').height() + $('#sp-search-area').height();
                // let current_scroll_position = $(this).scrollTop() / end_effect_on;
                // if (current_scroll_position > 1) current_scroll_position = 1;
    //
    //             // Comment becous: remove resizing in ticket
    //             // http://redmine.masq/issues/3890
    //             setHeaderSize(current_scroll_position);
            }
        }
    );

    // **************  END::  Scroll Header effect ******* //
    // **************************************************** //


    // **************  START:: content min size calculation //
    // **************************************************** //
    document.addEventListener('DOMContentLoaded', setContentMinHeight());
    // **************  END:: content min size calculation * //
    // **************************************************** //
});

// Header resize on scroll
function setHeaderSize(percent) {
    // Transition time based on Reload status And Scroll
    let transition_time = 30; // (ms)

    let selectors = {
        // Box sizes from 90 to 50 px
        '#sp-header.menu-fixed, #sp-header.menu-fixed .logo': {
            'height': rangeResizeCalculator(percent, {
                min: 50,
                max: 90
            })
        },
        // Image sizes | 33 -> 20
        '#sp-header.menu-fixed img.sp-default-logo.hidden-xs, #sp-header.menu-fixed img.sp-default-logo.visible-xs': {
            height: rangeResizeCalculator(percent, {
                min: 20,
                max: 33
            })
        },
        // Margins changes:
        // Header menu margin-top | 17 -> 0
        '#sp-header.menu-fixed li.sp-menu-item': {
            'margin-top': rangeResizeCalculator(percent, {
                min: 0,
                max: 17
            })
        },
        // Offcanves button | 25 -> 9
        '#sp-header.menu-fixed #offcanvas-toggler': {
            'margin-top': rangeResizeCalculator(percent, {
                min: 9,
                max: 25
            })
        },
    };
    for (let selector in selectors) {
        // jQuery(selector).css(selectors[selector]);

        // if (!TRANSITION_RELOAD_STATUS) jQuery(selector).css({
        //     '-webkit-transition': 'all ' + transition_time + 'ms cubic-bezier(0.63, 0.28, 0.46, 0.88)',
        //     'transition': 'all ' + transition_time + 'ms cubic-bezier(0.63, 0.28, 0.46, 0.88)'
        // });
    }

    // When reload - execute animation for longer time
    TRANSITION_RELOAD_STATUS = false;
    // When is not reloaded scrolling points are those which defines how much to resize time is 0.002s

}

// Note percent value range  is from 0.01 to 1
function rangeResizeCalculator(percent, range) {
    // No more then 1
    if (percent >= 1) {
        percent = 1;
    }

    let distance = range.max - range.min;
    return range.min + (1 - percent) * distance;
}

// Js Helper Methods
function getDisplayType() {
    let width = window.innerWidth;
    let type = "";
    switch (true) {
        case (width < 768):
            type = "xs";
            break;
        case (width >= 992) :
            type = "md";
            break;
        case (width >= 768) :
            type = "sm";
            break;
    }

    return type;
}

function setContentMinHeight() {
    let sum_height = 0;
    let elemenst = jQuery('.body-innerwrapper>section[id!=sp-main-body], .body-innerwrapper > footer').each(
        function () {
            sum_height += jQuery(this).outerHeight();
        }
    );

    // onbeforeprint="setContentMinHeight()"
    // console.log(elemenst , sum_height, 'calc(100vh - '+sum_height+')', jQuery('.body-innerwrapper>section#sp-main-body') );
    jQuery('.body-innerwrapper>section#sp-main-body').css('min-height', 'calc(100vh - ' + sum_height + 'px )');

}

/*
	Generate dropdown USES http://davidstutz.de/bootstrap-multiselect/#templates
	NEW DOCUMENTATION LINK : http://davidstutz.github.io/bootstrap-multiselect/

 */
function dropdownGenerator(dropdown_id, definitions) {
    let defaults = {
        buttonWidth: '100%',
        buttonContainer: '<div class="col-xs-12 col-no-gutters" id="dropdown_' + dropdown_id + '" />',
        buttonClass: 'btn btn-white drop-down-btn',
        maxHeight: '400',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        includeResetOption: true,
        resetText: 'Clear',
        numberDisplayed: 1,
        onChange: (option, checked, select) => change(option, checked, select, dropdown_id),
        onDropdownShown: function (event) {
            let dd_id = jQuery(event.target).attr('id');
            // select caret
            let arrow = jQuery('#' + dd_id + ' > button > b');
            arrow.toggleClass('fa-angle-down');
            arrow.toggleClass('fa-angle-up');

        },

        onDropdownHide: function (event) {
            let dd_id = jQuery(event.target).attr('id');
            // select caret
            let arrow = jQuery('#' + dd_id + ' > button > b');
            arrow.toggleClass('fa-angle-up');
            arrow.toggleClass('fa-angle-down');
        },
        templates: {
            filter: '<li class="multiselect-item filter" style="order: 0">' +
                '<input class="form-control multiselect-search" type="text">' +
                '</li>',
            filterClearBtn: '',
            button: '<button type="button" id="ddb_' + dropdown_id + '" class="multiselect dropdown-toggle btn btn-white" data-toggle="dropdown" ' +
                'title="None selected" aria-expanded="false" style=" overflow: hidden; text-overflow: ellipsis;">' +
                '<span class="multiselect-selected-text col-no-gutters" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis"> Non Selected Text</span> <b class="fa fa-angle-down col-no-gutters dropdown-arrows" style="text-align: right;  font-size: 17px"></b>' +
                '</button>',
            resetButton: '<li class="multiselect-reset text-right"  style="order: 0"><div class="reset-button-row"><a class="filter-btns clear col-xs-3 col-xs-offset-6 col-sm-offset-9" ></a></div></li>',

        },

    };

    // put definitions data into defaults
    for (let key in defaults) {
        // Not defined in definitions set defaults
        if ((typeof definitions[key] === 'undefined')) definitions[key] = defaults[key];

        if ((typeof definitions[key] === 'object')) {
            for (let property_idx in defaults[key]) {
                if ((typeof definitions[key][property_idx] === 'undefined')) definitions[key][property_idx] = defaults[key][property_idx];
            }
            continue;
        }

    }
    // Drop downs are often a part of content so we need to recalculate min width definition
    setContentMinHeight();
    // multiselect documentation: http://davidstutz.de/bootstrap-multiselect/#templates
    jQuery('#' + dropdown_id).multiselect(definitions);

    // Attach change listener, Clear buttons should order after clear
    jQuery('#dropdown_' + dropdown_id + ' a.filter-btns.clear').click(function () {
        change(null, null, null, dropdown_id);
    });

}

function change(option, checked, select, dropdown_id, sort = 1) {
    if(sort) sortActiveFirst(option, checked, select, dropdown_id);

    // Fold if there is selected
    boldManager(dropdown_id);
}

function setOrderStyle(elements) {
    elements.forEach((value, index) => {
        let v = jQuery(value)
        v.css("order", index + 2);
        v.find('.active.btn-success').removeClass('btn-success');
    });

}

function focusDropdownSearch(dropdown_id) {
    let filter_input_selectior = 'div#dropdown_' + dropdown_id + ' input.form-control.multiselect-search'; // filter_ad_network_id
    // let old = jQuery(filter_input_selectior).val();
    jQuery(filter_input_selectior).val('').trigger('keydown').focus();
}

// Sort dropdown li items
// group them by class and after that desc by value for each group
function sortActiveFirst(option, checked, select, dropdown_id) {
    let dropdown_selectors = {
        active: 'div#dropdown_' + dropdown_id + ' > ul > li.active',
        inactive: 'div#dropdown_' + dropdown_id + ' > ul > li:not(.active):not(.multiselect-item.filter):not(.multiselect-reset)',
    };
    let groups = ['active', 'inactive'];
    let result = [];


    var count_active = 0;
    groups.forEach(
        (value, index) => {
            jQuery(dropdown_selectors[value])
                .sort((a, b) => {
                    // Compare by groups
                    let cmp = jQuery(a).attr('class') > jQuery(b).attr('class') ? 1 : -1;
                    if (jQuery(a).attr('class') == jQuery(b).attr('class')) {
                        cmp = 0;
                    }

                    // Compare by names
                    if (cmp = 0) {
                        cmp = jQuery(a).text() > jQuery(b).text() ? 1 : -1;
                    }
                    return cmp;
                })
                .each((key, val) => {
                    jQuery(val).attr('style', '');
                    result.push(val);
                });
        }
    );

    setOrderStyle(result);
    // Clear search value;
    jQuery('input.form-control.multiselect-search').val('');


    // Focus search field after select
    // focusDropdownSearch(dropdown_id);
}

function clearAllFilters() {

    jQuery('input#filter_filter_search').val('');

    for (id in DropdownsMap) {
        jQuery('#' + id + ' option:selected').each(function () {
            jQuery(this).prop('selected', false);
        })
        jQuery('#' + id).multiselect('refresh');

        let result = [];

        // Select active
        jQuery('div#dropdown_' + id + ' > ul > li:not(.active):not(.multiselect-item.filter):not(.multiselect-reset)')
            .sort((a, b) => {
                // Compare by groups
                let cmp = jQuery(a).attr('class') > jQuery(b).attr('class') ? 1 : -1;
                if (jQuery(a).attr('class') == jQuery(b).attr('class')) {
                    cmp = 0;
                }
                // console.log(cmp, jQuery(a).attr('class'), jQuery(b).attr('class'));

                // Compare by names
                if (cmp = 0) {
                    cmp = jQuery(a).text() > jQuery(b).text() ? 1 : -1;
                }
                return cmp;
            })
            .each((key, val) => {
                jQuery(val).attr('style', '');
                result.push(val);
            });

        setOrderStyle(result);
        boldManager(id);
    }
}

function boldManager(dropdown_id) {
    let selector = 'div#dropdown_' + dropdown_id + ' > ul > li.active';
    let btn_selector = 'div#dropdown_' + dropdown_id + '> button';
    let selected = jQuery(selector).get();
    let button = jQuery(btn_selector);

    if (selected.length > 0) {
        button.css('font-weight', 'bold');
    } else {
        button.css('font-weight', 'normal');
    }

}


function triggerErrorMessage(){
    jQuery('button#error-modal-trigger').click();
}

function  throwMessage(message, buttons = [], title = null){
    jQuery('#error-modal p#modal-error-msg-box').html(message);

    if(!title){
        jQuery('#error-modal h3.modal-title').css('display', 'none');
    }else {
        jQuery('#error-modal h3.modal-title').html(title);
    }

    if(buttons && Array.isArray(buttons)) {
        jQuery('#error-modal .modal-footer').html('');
        for(button of buttons){
            jQuery('#error-modal .modal-footer').append(jQuery('<button />', button));
        }
    }
    triggerErrorMessage();
}


function attachDeleteConfirmation(elements_selector, message , url ){
    jQuery(elements_selector ).on( "click", function() {
        let element_id = jQuery( this ).attr('id');
        throwMessage(message, [
            {
                id: 'yes',
                text: 'Yes', //set text
                class: '', // set classes
                type: 'submit',
                click: function () {
                    jQuery.ajax({
                        url: url,
                        type: "post",
                        data: { 'elementId': element_id}, // event.target.id

                        success : function(response){
                            response = JSON.parse(response);
                            // console.log(response.status);
                            if(response.status == "Y") {
                                jQuery('#'+element_id).closest('article').css('display', 'none');
                                jQuery('button.close').click();
                            }
                            // location.reload();// not need, we can remove the item
                        },

                        error: function(jqXhr, textStatus, errorMessage) { // error callback
                            //							        $('p').append('Error: ' + errorMessage);
                            console.log('error ');
                            console.log(errorMessage);
                        }
                    });
                }
            },
            { // Close like
                text: 'No', //set text
                class: 'btn-white-grey', // set classes
                type: 'submit',
                'data-dismiss': 'modal',
                'aria-label': 'Close',
            },
        ])
    });
}