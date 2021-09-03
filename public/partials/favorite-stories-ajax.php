<?php
global $mbdsc_reader_factory;
		if ( is_user_logged_in()) {
			$user_id = get_current_user_id();
			$reader  = $mbdsc_reader_factory->create_reader( $user_id );
			$stories = $reader->get_favorite_stories();
		}
	  $list = new Mooberry_Story_Community_Story_List_Display($stories);
	  echo $list->display();
