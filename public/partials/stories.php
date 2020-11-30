<?php
$current_page = get_query_var('paged' ); // isset($_GET['paged']) ? intval( $_GET['paged'] ) : 1;
$current_page = max( $current_page, 1 );
$per_page = 10;
$listing_args = array('offset' => ( $current_page - 1 ) * $per_page,
                    'posts_per_page'    =>  $per_page,
                //      'post_status' => array(MOOBERRY_DIRECTORY_EDITED_STATUS, MOOBERRY_DIRECTORY_APPROVED_STATUS, MOOBERRY_DIRECTORY_DENIED_STATUS, MOOBERRY_DIRECTORY_PENDING_STATUS, MOOBERRY_DIRECTORY_EXPIRED_STATUS ),
                //        'post_parent'   =>  '0',
                );

$user = new Mooberry_Story_Community_Author( get_current_user_id());
$edit_page_id = Mooberry_Story_Community_Main_Settings::get_edit_story_page();
$edit_page = get_permalink($edit_page_id);
$edit_chapter_page_id = Mooberry_Story_Community_Main_Settings::get_edit_chapter_page();
$edit_chapter_page = get_permalink($edit_chapter_page_id);

$stories = Mooberry_Story_Community_Story_Collection::get_stories_by_user( get_current_user_id(),  array('offset' => ( $current_page - 1 ) * $per_page,
                    'posts_per_page'    =>  $per_page,) );

$paginate_links =  paginate_links( array( 'total' => ceil($user->story_count / $per_page ),
                                'current'   => $current_page,
                                    'format'    =>  '?paged=%#%',
                    )
);

//$stories = Mooberry_Story_Community_Story_Collection::get_stories_by_user( get_current_user_id() );

?>
<?php echo $paginate_links; ?>
<table class="mbdsc_account_story_table">
	<thead>
		<tr>
			<th>Title</th>
            <th>Chapters</th>
			<th>Reviews</th>
            <th>Completed</th>
            <th>Last Updated</th>
			<th>Started</th>
            <th> </th>
            </tr>
		</thead>
	<tbody id="mbdsc_account_story_table_body">
    <?php

    foreach ( $stories as $story ) {

        $last_updated = $story->get_most_recent_chapter_date();
	    ?>
        <tr id="mbdsc_story_row-<?php echo esc_attr($story->id); ?>">

            <td class="mbdsc_story_title">

                    <a href="<?php echo esc_attr(get_permalink($story->id)); ?>"><?php echo esc_html( $story->title ); ?></a>

            </td>
            <td>
                <?php echo esc_html( $story->get_chapter_count() ); ?>
            </td>
            <td>
                <?php echo esc_html( $story->review_count ); ?>
            </td>
            <td><?php echo $story->is_complete ? '<img class="mbdsc_story_complete" src="' . MOOBERRY_STORY_COMMUNITY_PLUGIN_URL . '/assets/check.png" alt="Completed" title="Completed">' :'' ?></td>
            <td><?php echo esc_html( date( 'm/d/y', strtotime( $last_updated ? $last_updated : $story->last_updated ) ) ); ?></td>
            <td><?php echo esc_html( date( 'm/d/y', strtotime($story->posted ) ) ); ?>
            </td>
            <td>
                <A href="<?php echo  $edit_chapter_page . '?chapter_id=0&story_id=' . $story->id ; ?>">
                        <img class="mbdsc_chapter_add mbdsc_story_actions" src="<?php echo MOOBERRY_STORY_COMMUNITY_PLUGIN_URL ?>/assets/add.png" alt="Add Chapter" title="Add Chapter"></a>

                    <A href="<?php echo  $edit_page . '?story_id=' . $story->id ; ?>">
                        <img class="mbdsc_story_edit mbdsc_story_actions" src="<?php echo MOOBERRY_STORY_COMMUNITY_PLUGIN_URL ?>/assets/edit.png" alt="Edit Story" title="Edit Story"></a>

	            <img data-story-id="<?php echo esc_attr($story->id); ?>" class="mbdsc_story_delete mbdsc_story_actions" src="<?php echo MOOBERRY_STORY_COMMUNITY_PLUGIN_URL ?>/assets/delete.png" alt="Delete Story" title="Delete Story">

            </td>

        </tr>
	    <?php
    }

    ?>
	</tbody>
    </table>

<?php echo $paginate_links; ?>
