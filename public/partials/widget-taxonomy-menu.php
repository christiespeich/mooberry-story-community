<p>
	<label for="<?php echo $this->get_field_id('mbds_sw_title'); ?>"><?php _e('Widget Title:', 'mooberry-story'); ?></label>
	<input type="text" name="<?php echo $this->get_field_name('mbds_sw_title'); ?>" id="<?php echo $this->get_field_id('mbds_sw_title'); ?>" value="<?php echo esc_attr($mbds_sw_title); ?>">
</p>
<p>
	<label id="<?php echo $this->get_field_id( 'mbdsc_taxonomy' ); ?>_label" for="<?php echo $this->get_field_id( 'mbdsc_taxonomy' ); ?>"><?php _e('Which Taxonomy', 'mooberry-story'); ?></label>
    <select name="<?php echo $this->get_field_name('mbdsc_taxonomy'); ?>" id="<?php echo $this->get_field_id('mbdsc_taxonomy'); ?>">
        <option></option>
        <?php
        foreach ( $taxonomies as $taxonomy) {
            $selected = '';
            if ( $taxonomy->name == $mbdsc_taxonomy ) {
                $selected = ' selected ';
            }

            ?>
        <option <?php echo $selected ?>value="<?php echo esc_attr($taxonomy->name) ?>"><?php echo esc_html($taxonomy->label); ?></option>

        <?php } ?>

</select></p>
