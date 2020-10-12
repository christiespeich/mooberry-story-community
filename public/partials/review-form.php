<hr style="clear:both;">
<h3>Leave a Review</h3>
<form id="mbdsc_review_form">
    <div class="mbdsc_review_name_block">
        <label for="mbdsc_review_name" class="mbdsc_review_name_label mbdsc_review_form_label">Name:</label>
        <input id="mbdsc_review_name" class="mbdsc_review_name" type="text" required="required"/>
    </div>

    <div class="mbdsc_review_email_block">
        <label for="mbdsc_review_email" class="mbdsc_review_email_label mbdsc_review_form_label">Email:</label>
        <input
                id="mbdsc_review_email"
                class="mbdsc_review_email"
                type="email"
                required="required"/><div class="mbdsc_review_email_notice">
         <?php echo $show_email == 'yes' ?  __('Email address will be displayed with your review', 'mooberry-story-community') : __('Email address will not be displayed with your review', 'mooberr-story-community' ) ?></div>
    </div>

    <div class="mbdsc_review_content_block">
        <label for="mbdsc_review_content" class="mbdsc_review_content_label mbdsc_review_form_label">Review:</label>
        <textarea id="mbdsc_review_content" class="mbdsc_review_content" required="required"></textarea>
    </div>
    <input type="hidden" id="mbdsc_review_chapter" value="<?php echo esc_attr( $chapter->id ); ?>"/>

    <button id="mbdsc_review_submit" class="button-secondary">Submit</button><img style="display:none;" id="mbdsc_chatper_review_loading" src="<?php echo MOOBERRY_STORY_COMMUNITY_PLUGIN_URL ?>assets/ajax-loader.gif"/>

</form>
<hr style="clear:both;">

