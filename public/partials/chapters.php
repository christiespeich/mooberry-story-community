<?php
$edit_page_id = Mooberry_Story_Community_Main_Settings::get_edit_chapter_page();
$edit_page = get_permalink($edit_page_id);

$chapters = Mooberry_Story_Community_Chapter_Collection::get_chapters_by_story($_GET['story_id']);


?>
<div id="mbdsc_account_chapter_table_div">

<table class="mbdsc_account_chapter_table">
	<thead>
		<tr>
			<th> </th>
			<th>Title</th>
			<th>Reviews</th>
            <th>Last Updated</th>
			<th>Started</th>
            <th> </th>
            </tr>
		</thead>
	<tbody id="mbdsc_account_chapter_table_body">
    <?php

    foreach ( $chapters as $chapter ) {

	    ?>
        <tr id="mbdsc_chapter_<?php echo esc_attr($chapter->id); ?>">
	        <td> <img class="mbdsc_chapter_sort mbdsc_chapter_actions" src="<?php echo MOOBERRY_STORY_COMMUNITY_PLUGIN_URL ?>/assets/sort.png" alt="Sort Chapters" title="Sort Chapters"></td>
            <td class="mbdsc_chapter_title">

                    <a href="<?php echo esc_attr(get_permalink($chapter->id)); ?>"><?php echo esc_html( $chapter->title ); ?></a>

            </td>

            <td>
                <?php echo esc_html( $chapter->review_count ); ?>
            </td>
            <td><?php echo esc_html( date( 'm/d/y', strtotime($chapter->last_updated ) ) ); ?></td>
            <td><?php echo esc_html( date( 'm/d/y', strtotime($chapter->posted ) ) ); ?>
            </td>
            <td>
                    <A href="<?php echo  $edit_page . '?chapter_id=' . $chapter->id ; ?>">
                        <img class="mbdsc_chapter_edit mbdsc_chapter_actions" src="<?php echo MOOBERRY_STORY_COMMUNITY_PLUGIN_URL ?>/assets/edit.png" alt="Edit Chapter" title="Edit Chapter"></a>

	            <img data-chapter-id="<?php echo esc_attr($chapter->id); ?>" class="mbdsc_chapter_delete mbdsc_chapter_actions" src="<?php echo MOOBERRY_STORY_COMMUNITY_PLUGIN_URL ?>/assets/delete.png" alt="Delete Chapter" title="Delete Chapter">

            </td>

        </tr>
	    <?php
    }

    ?>
	</tbody>
    </table>
    </div>

