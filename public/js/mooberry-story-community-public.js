jQuery(document).ready(function () {




    jQuery('ul.mbdsc_taxonomy_menu span.mbdsc_taxonomy_menu_toggle').on('click', function() {
        jQuery(this).parent().children('ul.children').toggle(250);
        jQuery(this).toggleClass('mbdsc_taxonomy_menu_closed');
        jQuery(this).toggleClass('mbdsc_taxonomy_menu_open');
        jQuery(this).toggleClass('dashicons-arrow-right-alt2');
        jQuery(this).toggleClass('dashicons-arrow-down-alt2');
    })


    jQuery('#mbdsc_review_submit').on('click', mbdsc_submit_review );

});

function mbdsc_submit_review( e ) {
    if (jQuery('#mbdsc_review_form')[0].checkValidity()) {
        jQuery('#mbdsc_review_email')
          .attr('disabled', 'disabled');
        jQuery('#mbdsc_review_name')
          .attr('disabled', 'disabled');
        jQuery('#mbdsc_review_content')
          .attr('disabled', 'disabled');
        jQuery(this)
          .attr('disabled', 'disabled').hide();
        jQuery('#mbdsc_chatper_review_loading').show();

        var data = {
            'action': 'mbdsc_submit_review',
            'chapter': jQuery('#mbdsc_review_chapter')
              .val(),
            'email': jQuery('#mbdsc_review_email')
              .val(),
            'name': jQuery('#mbdsc_review_name')
              .val(),
            'content': jQuery('#mbdsc_review_content')
              .val(),
            'security': mbdsc_public_ajax_object.mbdsc_public_security
        }
        var mbdsc_submit_review = jQuery.post(mbdsc_public_ajax_object.ajax_url, data);

        mbdsc_submit_review.done(function (results) {
            jQuery('#mbdsc_review_form')[0].reset();

            jQuery('.mbdsc_chapter_reviews').prepend(results);


        });
        mbdsc_submit_review.fail( function (results)  {
            alert('Sorry, an error occurred.');
        });

        mbdsc_submit_review.always(function (results) {
            e.preventDefault();
            jQuery('#mbdsc_review_email')
              .removeAttr('disabled', 'disabled');
            jQuery('#mbdsc_review_content')
              .removeAttr('disabled', 'disabled');
            jQuery('#mbdsc_review_name')
              .removeAttr('disabled', 'disabled');
            jQuery('#mbdsc_review_submit')
              .removeAttr('disabled', 'disabled').show();
            jQuery('#mbdsc_chatper_review_loading').hide();

        });

    }

}
