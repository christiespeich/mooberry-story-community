jQuery(document)
  .ready(function () {

	  jQuery('#mbdsc_account_page_tabs')
		.tabs()
	  jQuery('.mbdsc_account_story_table img.mbdsc_story_delete')
		.on('click', mbdsc_delete_story)
	  jQuery('#mbdsc_account_chapter_table_body')
		.sortable({
			          opacity: 0.5,
			          handle: '.mbdsc_chapter_sort',
			          helper: 'clone',
			          cursor: 'pointer',
						update: mbdsc_save_chapter_order,
		          })

	  jQuery('.mbdsc_taxonomy_add_new_button').on('click', mbdsc_add_new_taxonomy_term);
	  jQuery('.mbdsc_taxonomy_add_new_cancel_button').on('click', mbdsc_cancel_add_new_taxonomy_term);


	  jQuery('ul.mbdsc_taxonomy_menu span.mbdsc_taxonomy_menu_toggle')
		.on('click', function () {
			jQuery(this)
			  .parent()
			  .children('ul.children')
			  .toggle(250)
			jQuery(this)
			  .toggleClass('mbdsc_taxonomy_menu_closed')
			jQuery(this)
			  .toggleClass('mbdsc_taxonomy_menu_open')
			jQuery(this)
			  .toggleClass('dashicons-arrow-right-alt2')
			jQuery(this)
			  .toggleClass('dashicons-arrow-down-alt2')
		})

	  jQuery('#mbdsc_review_submit')
		.on('click', mbdsc_submit_review);

	   // if at least one checkbox in a group is checked, then turn off the validation
	  // for the checkbox group. HTML5 validation requires all checkboxes to be checked
	  jQuery('#mbdsc_story_meta_box input[name="submit-cmb"]')
		.on('click', mbdsc_validate_checkboxes)

  })

function mbdsc_submit_review (e) {

	if (jQuery('#mbdsc_review_form')[0].checkValidity()) {
		e.preventDefault()
		jQuery('#mbdsc_review_email')
		  .attr('disabled', 'disabled')
		jQuery('#mbdsc_review_name')
		  .attr('disabled', 'disabled')
		jQuery('#mbdsc_review_content')
		  .attr('disabled', 'disabled')
		jQuery(this)
		  .attr('disabled', 'disabled')
		  .hide()
		jQuery('#mbdsc_chatper_review_loading')
		  .show()

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
			                                  .removeAttr('disabled', 'disabled')
			                                jQuery('#mbdsc_review_content')
			                                  .removeAttr('disabled', 'disabled')
			                                jQuery('#mbdsc_review_name')
			                                  .removeAttr('disabled', 'disabled')
			                                jQuery('#mbdsc_review_submit')
			                                  .removeAttr('disabled', 'disabled')
			                                  .show()
			                                jQuery('#mbdsc_chatper_review_loading')
			                                  .hide()

		                                })
		                                .done(function (results) {

			                                jQuery('#mbdsc_review_form')[0].reset()

			                                var review_data = JSON.parse(results)

			                                jQuery('.mbdsc_chapter_review_count')
			                                  .html(review_data.count)
			                                jQuery('.mbdsc_chapter_reviews')
			                                  .prepend(review_data.review)

		                                })
		                                .fail(function (results) {
			                                alert('Sorry, an error occurred.')
		                                })



	}

}

function mbdsc_delete_story () {
	if (confirm('Are you sure you want to delete this story?')) {
		jQuery('#mbdsc_account_story_table_body')
		  .addClass('loading');

		var story = jQuery(this)
		  .data('story-id')
		var data = {
			'action': 'mbdsc_delete_story',
			'story': story,
			'security': mbdsc_public_ajax_object.mbdsc_public_security
		}
		var mbdsc_delete_story = jQuery.post(mbdsc_public_ajax_object.ajax_url, data)

		mbdsc_delete_story.done(function (results) {
			if (results) {
				jQuery('#mbdsc_story_row-' + story)
				  .remove()

			}
		})
		mbdsc_delete_story.always(function (results) {
			jQuery('#mbdsc_account_story_table_body')
			  .removeClass('loading');
		})

	}

}

function mbdsc_toggle_new_taxonomy_term( target ) {
	target.siblings('.mbdsc_taxonomy_new_item').toggle().siblings('.mbdsc_taxonomy_new_item_parent').toggle();
	target.toggle();
}

function mbdsc_add_new_taxonomy_term() {
	mbdsc_toggle_new_taxonomy_term(jQuery(this));
	jQuery(this).siblings('.mbdsc_taxonomy_add_new_cancel_button').toggle();
}

function mbdsc_cancel_add_new_taxonomy_term() {
	mbdsc_toggle_new_taxonomy_term(jQuery(this));
	jQuery(this).siblings('.mbdsc_taxonomy_add_new_button').toggle();
}

function mbdsc_save_chapter_order( event, ui) {
	jQuery('#mbdsc_account_chapter_table_body')
	  .sortable("disable");

	jQuery('#mbdsc_account_chapter_table_div')
	  .addClass('loading');
	var data = {
		'action': 'save_chapters_order',
		'storyID': jQuery('#post_ID')
		  .val(),
		'chapters': jQuery('#mbdsc_account_chapter_table_body')
		  .sortable('serialize'),
		'security': mbdsc_public_ajax_object.mbdsc_public_security
	}
	var mbdsc_save_chapter_order = jQuery.post(mbdsc_public_ajax_object.ajax_url, data);
	mbdsc_save_chapter_order.always(function () {
		jQuery('#mbdsc_account_chapter_table_div')
		  .removeClass('loading');
		jQuery('#mbdsc_account_chapter_table_body')
		  .sortable("enable");
	})

}
