jQuery(document).ready(function () {

	// if at least one checkbox in a group is checked, then turn off the validation
    // for the checkbox group. HTML5 validation requires all checkboxes to be checked
    jQuery('#mbdsc_taxonomy_fields_metabox input[name="submit-cmb"]').on('click', mbdsc_validate_checkboxes);




// if at least one checkbox in a group is checked, then turn off the validation
 // for the checkbox group. HTML5 validation requires all checkboxes to be checked
function mbdsc_validate_checkboxes() {
    jQuery("ul.cmb2-checkbox-list").each(function () {
        $checkbox_list = jQuery(this);
        $cbx_group = $checkbox_list.find('input:required');

        $cbx_group.prop('required', true);
        if ($cbx_group.is(":checked")) {
            $cbx_group.prop('required', false);
        }
    });
}


});
