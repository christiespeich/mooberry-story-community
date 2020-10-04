jQuery(document).ready(function () {




    jQuery('ul.mbdsc_taxonomy_menu span.mbdsc_taxonomy_menu_toggle').on('click', function() {
        jQuery(this).parent().children('ul.children').toggle(250);
        jQuery(this).toggleClass('mbdsc_taxonomy_menu_closed');
        jQuery(this).toggleClass('mbdsc_taxonomy_menu_open');
        jQuery(this).toggleClass('dashicons-arrow-right-alt2');
        jQuery(this).toggleClass('dashicons-arrow-down-alt2');
    })


});
