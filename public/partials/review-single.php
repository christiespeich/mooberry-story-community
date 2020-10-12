<div class="mbdsc_reviewer_block" >
    <p><label class="mbdsc_reviewer_name_label" id="mbdsc_review_name_label_<?php echo esc_attr($review->id) ?>">From:</label> <?php echo esc_html( $review->reviewer_name ); ?>
    <?php echo $show_email == 'yes' ?  '( <a href="mailto:' .  esc_attr( $review->reviewer_email ) . '">' .  esc_html( $review->reviewer_email ) . '</a> )' : '' ?>
        <br/>
        <label class="mbdsc_reviewer_datetime_label" id="mbdsc_review_datetime_label_<?php echo esc_attr($review->id) ?>">Submitted:</label> <?php echo $timestamp; ?></p>
</div>
<div class="mbdsc_review_content_block" >
	<?php echo $review_content; ?>
</div>

