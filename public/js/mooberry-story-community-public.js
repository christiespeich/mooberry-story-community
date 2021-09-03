jQuery(document).ready(function () {

    jQuery('ul.mbdsc_taxonomy_menu span.mbdsc_taxonomy_menu_toggle').on('click', function() {
        jQuery(this).parent().children('ul.children').toggle(250);
        jQuery(this).toggleClass('mbdsc_taxonomy_menu_closed');
        jQuery(this).toggleClass('mbdsc_taxonomy_menu_open');
        jQuery(this).toggleClass('dashicons-arrow-right-alt2');
        jQuery(this).toggleClass('dashicons-arrow-down-alt2');
    })


    jQuery('#mbdsc_review_submit').on('click', mbdsc_submit_review );

  jQuery('.mbdsc_fave_story_star').on('click', mbdsc_toggle_fave_story_status );

  jQuery( "#mbdsc_user_profile_tabs" ).tabs();

});

function mbdsc_submit_review( e ) {

    if (jQuery('#mbdsc_review_form')[0].checkValidity()) {
          e.preventDefault();
        jQuery('#mbdsc_review_email')
          .attr('disabled', 'disabled');
        jQuery('#mbdsc_review_name')
          .attr('disabled', 'disabled');
        jQuery('#mbdsc_review_content')
          .attr('disabled', 'disabled');
        jQuery(this)
          .attr('disabled', 'disabled')
          .hide();
        jQuery('#mbdsc_chatper_review_loading')
          .show();

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

        var mbdsc_submit_review = jQuery.post(mbdsc_public_ajax_object.ajax_url, data)
                                        .always(function (results) {
                                            jQuery('#mbdsc_review_email')
                                              .removeAttr('disabled', 'disabled');
                                            jQuery('#mbdsc_review_content')
                                              .removeAttr('disabled', 'disabled');
                                            jQuery('#mbdsc_review_name')
                                              .removeAttr('disabled', 'disabled');
                                            jQuery('#mbdsc_review_submit')
                                              .removeAttr('disabled', 'disabled')
                                              .show();
                                            jQuery('#mbdsc_chatper_review_loading')
                                              .hide();

                                        })
                                        .done(function (results) {

                                            jQuery('#mbdsc_review_form')[0].reset();

                                            var review_data = JSON.parse(results);

                                            jQuery('.mbdsc_chapter_review_count').html(review_data.count);
                                            jQuery('.mbdsc_chapter_reviews')
                                              .prepend(review_data.review);

                                        })
                                        .fail(function (results) {
                                            alert('Sorry, an error occurred.');
                                        })

                                       ;

    }

}

function mbdsc_toggle_fave_story_status(e ) {
  e.preventDefault();

  var story_id = jQuery(this)
    .data('story');

  jQuery('.mbdsc_fave_story_star[data-story="' + story_id + '"] i')
    .addClass('fa-spin');
  var data = {
    'action': 'mbdsc_toggle_fave_story_status',
    'story_id': story_id,
    'security': mbdsc_public_ajax_object.mbdsc_public_security,
  };

  var mbdsc_toggle_fave_story_status = jQuery.post(mbdsc_public_ajax_object.ajax_url, data);

  mbdsc_toggle_fave_story_status.done(function (data) {

    jQuery('.mbdsc_fave_story_star[data-story="' + data + '"]')
      .toggle();
    //Class('fa-star').toggleClass('fa-star-o');

    /*var label;
   if (    jQuery('.mbdbbs_fave_author_star[data-author="' + data + '"] i').attr('title' ) == 'This author is on your favorites list. Click to remove them.' ) {
       label = 'This author is not on your favorites list. Click to add them.';
   } else {
       label = 'This author is on your favorites list. Click to remove them.';
   }
   jQuery('.mbdbbs_fave_author_star[data-author="' + data + '"]').attr('aria-label', label);
   jQuery('.mbdbbs_fave_author_star[data-author="' + data + '"] i').attr('title', label);
*/

  });

  mbdsc_toggle_fave_story_status.always(function (result) {

    jQuery('.mbdsc_fave_story_star[data-story="' + result + '"] i')
      .removeClass('fa-spin');

    jQuery('div#mbdsc_favorite_stories').fadeOut("slow");

    // reload fave stories if needed
    var data = {
      'action': 'mbdsc_reload_favorite_stories',
      'security': mbdsc_public_ajax_object.mbdsc_public_security,
    };
    var mbdsc_reload_favorite_stories = jQuery.post(mbdsc_public_ajax_object.ajax_url, data);
    mbdsc_reload_favorite_stories.done(function (data) {

      jQuery('div#mbdsc_favorite_stories').html(data);

    });
    mbdsc_reload_favorite_stories.always(function (data) {
      jQuery('div#mbdsc_favorite_stories')
        .fadeIn("slow");
    });
  });

}

