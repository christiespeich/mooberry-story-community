<p>
	<label for="<?php echo $this->get_field_id('mbds_sw_title'); ?>"><?php _e('Widget Title:', 'mooberry-story'); ?></label>
	<input type="text" name="<?php echo $this->get_field_name('mbds_sw_title'); ?>" id="<?php echo $this->get_field_id('mbds_sw_title'); ?>" value="<?php echo esc_attr($mbds_sw_title); ?>">
</p>
<p>
	<label id="<?php echo $this->get_field_id( 'mbds_story_limit' ); ?>_label" for="<?php echo $this->get_field_id( 'mbds_story_limit' ); ?>"><?php _e('How many stories to list:', 'mooberry-story'); ?></label>
    <input type="text" name="<?php echo $this->get_field_name('mbds_story_limit'); ?>" id="<?php echo $this->get_field_id('mbds_story_limit'); ?>" value="<?php echo esc_attr($mbds_story_limit); ?>">

</p>
