jQuery(document)
  .ready(function () {

	  // settings page
	  jQuery('#mbdsc_create_pages_button')
		.on('click', mbdsc_create_pages)

	  // if at least one checkbox in a group is checked, then turn off the validation
	  // for the checkbox group. HTML5 validation requires all checkboxes to be checked
	  jQuery('#mbdsc_taxonomy_fields_metabox input[name="submit-cmb"]')
		.on('click', mbdsc_validate_checkboxes)
	  jQuery('.post-type-mbdsc_story #publish')
		.on('click', mbdsc_validate_checkboxes)
	  jQuery('.post-type-mbdsc_story input[name="save"]')
		.on('click', mbdsc_save_story)

	  dialog = jQuery('#mbdsc_chapter_dialog')
		.dialog({
			        dialogClass: 'no-close',
			        autoOpen: false,
			        height: 'auto',
			        width: '50%',
			        minWidth: 200,
			        modal: true,
			        buttons: {
				        'Save Chaper': addChapter,
				        Cancel: function () {
					        if (confirm('Are you sure you want to cancel without saving?')) {
						        dialog.dialog('close')
					        }
				        }
			        },
			        close: function () {
				        //jQuery('#mbdsc_chapter_form')[0].reset();
				        //allFields.removeClass( "ui-state-error" );
				        jQuery('#mbdsc_chapter_title')
				          .val('')
				        jQuery('#mbdsc_edit_chapter_id')
				          .val('')
				        tinymce.editors.mbdsc_chapter_text.setContent('')

				        jQuery('#mbdsc_chapter_form_error')
				          .hide()
				        jQuery('#mbdsc_chapter_form_loading')
				          .hide()
				        jQuery('#mbdsc_chapter_form')
				          .show()

			        }
		        })

	  form = dialog.find('form')
	               .on('submit', function (event) {
		               event.preventDefault()
		               addUser()
	               })

	  jQuery('.mbdsc_chapter_delete_icon')
		.on('click', mbdsc_delete_chapter)

	  jQuery('[id^="mbdbsc_chapter_edit_"]')
		.on('click', mbdsc_edit_chapter)

	  jQuery('#mbdsc_add_chapter')
		.on('click', function (event) {
			//console.log(jQuery("input[name='mbdsc_story_chapter_titles']").val() );
			if (jQuery('input[name=\'mbdsc_story_chapter_titles\']:checked')
			      .val() === 'auto') {
				var chapter_count = jQuery('#mbdsc_chapter_list li').length

				jQuery('#mbdsc_chapter_title')
				  .attr('disabled', 'disabled')
				  .val('Chapter ' + (chapter_count + 1))
			} else {
				jQuery('#mbdsc_chapter_title')
				  .removeAttr('disabled')
			}
			dialog.dialog('open')
			event.preventDefault()
		})

// make the grid sortable
	  jQuery('#mbdsc_chapter_list')
		.sortable({
			          opacity: 0.5,
			          placeholder: 'ui-state-highlight',
			          cursor: 'pointer',
			          create: mbdsc_chapter_list_update,
			          update: mbdsc_chapter_list_update,
			          deactivate: function () {
				          window.unsaved_changes = true
			          }
		          })

  })

function mbdsc_delete_chapter (event) {
	if (confirm('Are you sure you want to delete this chapter?')) {

		var chapter = jQuery(this)
		  .data('chapter_id')
		var data = {
			'action': 'mbdsc_delete_chapter',
			'chapter': chapter,
			'security': mbdsc_admin_ajax_object.mbdsc_admin_security
		}
		var mbdsc_delete_chapter = jQuery.post(mbdsc_admin_ajax_object.ajax_url, data)

		mbdsc_delete_chapter.done(function (results) {
			if (results) {
				jQuery('#mbdsc_chapter_' + chapter)
				  .remove()
				jQuery('#mbdsc_chapter_delete_icon_' + chapter)
				  .remove()
			}
		})

	}
}

function mbdsc_edit_chapter (event) {
	event.preventDefault()

	if (jQuery('input[name=\'mbdsc_story_chapter_titles\']:checked')
	      .val() === 'auto') {
		var chapter_count = jQuery('#mbdsc_chapter_list li').length

		jQuery('#mbdsc_chapter_title')
		  .attr('disabled', 'disabled')
		  .val('Chapter ' + (chapter_count + 1))
	} else {
		jQuery('#mbdsc_chapter_title')
		  .removeAttr('disabled')
	}

	dialog.dialog('open')

	var chapter = jQuery(this)
	  .data('chapter_id')
	jQuery('#mbdsc_edit_chapter_id')
	  .val(chapter)

	jQuery('#mbdsc_chapter_form_loading')
	  .show()
	jQuery('#mbdsc_chapter_form')
	  .hide()
	jQuery('.ui-dialog-buttonset')
	  .children('button')
	  .addClass('ui-state-disabled')
	  .attr('disabled', true)
	jQuery('.ui-dialog-titlebar-close')
	  .addClass('ui-state-disabled')
	  .attr('disabled', true)

	var data = {
		'action': 'get_chapter',
		'chapter': chapter,
		'security': mbdsc_admin_ajax_object.mbdsc_admin_security
	}
	var mbdsc_get_chapter = jQuery.post(mbdsc_admin_ajax_object.ajax_url, data)

	mbdsc_get_chapter.done(function (results) {

		jQuery('.ui-dialog-buttonset')
		  .children('button')
		  .removeClass('ui-state-disabled')
		  .removeAttr('disabled')
		jQuery('.ui-dialog-titlebar-close')
		  .removeClass('ui-state-disabled')
		  .removeAttr('disabled')
		jQuery('#mbdsc_chapter_form_loading')
		  .hide()
		jQuery('#mbdsc_chapter_form')
		  .show()

		response = JSON.parse(results)

		jQuery('#mbdsc_chapter_title')
		  .val(response.title)
		tinymce.editors.mbdsc_chapter_text.setContent(response.body)

	})

}

function addChapter () {
	jQuery('#mbdsc_chapter_form_loading')
	  .show()
	jQuery('#mbdsc_chapter_form')
	  .hide()
	jQuery('.ui-dialog-buttonset')
	  .children('button')
	  .addClass('ui-state-disabled')
	  .attr('disabled', true)
	jQuery('.ui-dialog-titlebar-close')
	  .addClass('ui-state-disabled')
	  .attr('disabled', true)
	jQuery('#mbdsc_chapter_title')
	  .removeAttr('disabled')

	var valid = true

	// validation

	var title = jQuery('#mbdsc_chapter_title')
	  .val()
	var chapter = tinymce.editors.mbdsc_chapter_text.getContent()

	var chapter_id = jQuery('#mbdsc_edit_chapter_id')
	  .val()

	var data = {
		'action': 'save_chapter',
		'story': jQuery('#post_ID')
		  .val(),
		'title': title,
		'chapter': chapter,
		'chapter_id': chapter_id,
		'security': mbdsc_admin_ajax_object.mbdsc_admin_security
	}
	var mbdsc_save_chapter = jQuery.post(mbdsc_admin_ajax_object.ajax_url, data)

	mbdsc_save_chapter.always(function (e) {
		jQuery('.ui-dialog-buttonset')
		  .children('button')
		  .removeClass('ui-state-disabled')
		  .removeAttr('disabled')
		jQuery('.ui-dialog-titlebar-close')
		  .removeClass('ui-state-disabled')
		  .removeAttr('disabled')
		jQuery('#mbdsc_chapter_form_loading')
		  .hide()
		jQuery('#mbdsc_chapter_form')
		  .show()

	})
	mbdsc_save_chapter.fail(function (e) {
		jQuery('#mbdsc_chapter_form_error')
		  .show()
		jQuery('#mbdsc_chapter_form_loading')
		  .hide()
		jQuery('#mbdsc_chapter_form')
		  .show()
	})

	mbdsc_save_chapter.done(function (new_chapter_info) {
		var new_chapter = JSON.parse(new_chapter_info)

		var new_chapter_id = new_chapter.new_chapter_id
		var new_chapter_link = new_chapter.new_chapter_url

		if (new_chapter_id != 0) {
			jQuery('#mbdsc_chapter_list')
			  .append('<img class="mbdsc_chapter_delete_icon" id="mbdsc_chapter_delete_icon_'
			          + new_chapter_id
			          + '" src="'
			          + mbdsc_admin_ajax_object.mbdsc_plugin_url
			          + 'assets/delete.png" data-chapter_id="'
			          + new_chapter_id
			          + '"/><li id="mbdsc_chapter_'
			          + new_chapter_id
			          + '" class="ui-state-default"><span class="ui-icon"></span><div class="mbdsc_chapter_title"'
			          + ' id="mbdsc_chapter_title_'
			          + new_chapter_id
			          + '">'
			          + title
			          + '</div><a href="'
			          + new_chapter_link
			          + '" target="_new"><img'
			          + ' class="mbdsc_chapter_preview_icon" src="'
			          + mbdsc_admin_ajax_object.mbdsc_plugin_url
			          + 'assets/new_window_icon.png"/></a><img class="mbdsc_chapter_list_edit" id="mbdbsc_chapter_edit_'
			          + new_chapter_id
			          + '" src="'
			          + mbdsc_admin_ajax_object.mbdsc_plugin_url
			          + 'assets/edit.png" data-chapter_id="'
			          + new_chapter_id
			          + '"/></li>')
			mbdsc_chapter_list_update()

		} else {

			jQuery('#mbdsc_chapter_title_' + chapter_id)
			  .html(title)
		}
		dialog.dialog('close')
	})

}

function mbdsc_save_story () {
	mbdsc_validate_checkboxes()
	mbdsc_order_chapters()
}


// save the sorted grid via ajax
function mbdsc_order_chapters () {
	var data = {
		'action': 'save_chapters_order',
		'storyID': jQuery('#post_ID')
		  .val(),
		'chapters': jQuery('#mbdsc_chapter_list')
		  .sortable('serialize'),
		'security': mbdsc_admin_ajax_object.mbdsc_admin_security
	}
	var mbdsc_save_chapter_order = jQuery.post(mbdsc_admin_ajax_object.ajax_url, data)
	/*mbdsc_save_chapter_order.done(function (data) {
		mbdsc_chapter_list_update();
	});*/

}

// update the icons in the grid
function mbdsc_chapter_list_update () {
	// remove all the classes and add ui-icon on all of the items in the grid
	jQuery('#mbdsc_chapter_list li span')
	  .removeClass()
	  .addClass('ui-icon')
	// add a down arrow to the first item
	jQuery('#mbdsc_chapter_list li:first span')
	  .addClass('ui-icon-arrowthick-1-s')
	// add an up arrow to the last item
	jQuery('#mbdsc_chapter_list li:last span')
	  .addClass('ui-icon-arrowthick-1-n')
	// add an up and down arrow to any non-first and non-last item
	jQuery('#mbdsc_chapter_list li')
	  .not(':first')
	  .not(':last')
	  .children('span')
	  .addClass('ui-icon-arrowthick-2-n-s')
	jQuery('[id^="mbdbsc_chapter_edit_"]')
	  .on('click', mbdsc_edit_chapter)
	jQuery('.mbdsc_chapter_delete_icon')
	  .on('click', mbdsc_delete_chapter)
}

function mbdsc_create_pages () {

	jQuery('#mbdsc_create_pages_progress')
	  .show()

	const pages = []
	jQuery('select[id^="mbdsc_pages_"]')
	  .each(function () {
		  if (jQuery(this)
			    .val() === '0') {
			  pages.push(jQuery(this)
				           .attr('id'))
		  }
	  })

	var data = {
		'action': 'mbdsc_create_pages',
		'pages': pages,
		'security': mbdsc_admin_ajax_object.mbdsc_admin_security
	}
	var mbdsc_create_pages = jQuery.post(mbdsc_admin_ajax_object.ajax_url, data)
	mbdsc_create_pages.always(function () {
		jQuery('#mbdsc_create_pages_progress')
		  .hide()
	})
	mbdsc_create_pages.done(function (results) {
		const added_pages = JSON.parse(results)

		pages.map(function (page) {

			if (added_pages[page]) {

				jQuery('#' + page)
				  .append(new Option(added_pages[page].title, added_pages[page].id))
				  .val(added_pages[page].id)
			}
		})

	})

}



