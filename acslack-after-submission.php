<?php
require_once 'vendor/autoload.php'; // AC 5.0 Feather SDK

add_action('gform_after_submission', 'postToActiveCollab', 10, 2);
function postToActiveCollab( $entries, $form ){
	$ac_form_data      = $form[ 'acslackaddon' ];
	$ac_name 		   = $ac_form_data[ 'ac-task' ]; 
	$ac_project 	   = $ac_form_data[ 'ac-project' ];
	$ac_tasklist 	   = $ac_form_data[ 'ac-tasklist' ];
	$ac_url 		   = $ac_form_data[ 'ac-url' ];
	$ac_key			   = $ac_form_data[ 'ac-key' ];

	if( isset( $ac_project, $ac_tasklist ) ){
		$token_from_settings = new \ActiveCollab\SDK\Token( $ac_key , $ac_url );
		$client = new \ActiveCollab\SDK\Client($token_from_settings);

		// Finds the project ID for the given project name
		$projects = getACData( 'projects', $client );
		foreach( $projects as $project ){
			if( $project['name'] == $ac_project ){
				$ac_project_id = $project['id'];
				break;
			}
		}

		// Finds the tasklist ID for the given tasklist name
		$tasklists = getACData( 'projects/' . $ac_project_id . '/task-lists', $client );
		foreach( $tasklists as $tasklist){
			if( $tasklist['name'] == $ac_tasklist ){
				$ac_tasklist_id = $tasklist['id'];
				break;
			}
		}

		if( isset( $ac_project_id, $ac_tasklist_id ) ){
			$ac_name_post = $entries['id'] . ': ' . $form['title'];

			if( isset($ac_name) ){
				$ac_name_post .= ' - ' . $ac_name;
			}

			$create_task = $client->post( 'projects/' . $ac_project_id . '/tasks', [
				'name' => replaceMergeTags( $ac_name_post, $entries, $form ),
				'task_list_id' => $ac_tasklist_id,
				'labels' => ['New'],
				'body' => printEntries( $entries, $form )
			]);

			preg_match('/projects..\d\d..tasks..(\d*)/', print_r($create_task, TRUE), $ac_task_id);
			$GLOBALS['ac_task_id'] = $ac_task_id[1];
			$GLOBALS[ 'ac_url' ] = $ac_url . "/projects/" . $ac_project_id . "/tasks/" . $GLOBALS['ac_task_id'][1];
			return $create_task;
		}
	}
	$GLOBALS['ac_task_id'] = $GLOBALS[ 'ac_url' ] = '';
	return null;
}

add_action('gform_after_submission', 'postToSlack', 11, 2);
function postToSlack( $entries, $form ){
	$slack_form_data = $form[ 'acslackaddon' ];
	$slack_message 	 = $slack_form_data[ 'slack-message' ];
	$slack_channel   = $slack_form_data[ 'slack-channel' ];
	$slack_username  = $slack_form_data[ 'slack-username' ];
	$slack_emoji     = $slack_form_data[ 'slack-emoji' ];
	$slack_key   	 = $slack_form_data[ 'slack-key' ];

	if( isset( $slack_message, $slack_key, $slack_channel, $slack_username, $slack_emoji )){
		$post_url = 'https://slack.com/api/chat.postMessage';

		$data = array(
			'text'          => replaceMergeTags( stripcslashes( $slack_message ), $entries, $form ),
			'channel'       => '#' . $slack_channel,
			'username'		=> $slack_username,
			'icon_emoji'    => $slack_emoji,
			'token'			=> $slack_key
		);

		$context = stream_context_create(
			array( 'http' => array(
				'header' => 'Content-type: application/x-www-form-urlencoded\r\n',
				'method' => 'POST',
				'content' => http_build_query( $data )
		)));

		$result = file_get_contents( $post_url, false, $context );
		return $result;
	}
	return null;
}

function getACData( $url, $client ){
	$return = array();
	if( !emptyMultidim($get = $client->get( $url . '?page=9999')->getJson()) ){
		return $get;
	}
	for ( $page = 1; !emptyMultidim($get = $client->get( $url . '?page=' . $page )->getJson()); $page++){
		$return = array_merge( $return, $get );
	}
	return $return;
}

function emptyMultidim( $array ){
	$empty = true;
	foreach( $array as $element ){
		if( !is_array( $element) )
			return false;
		else
			$empty = $empty && emptyMultidim( $element );
	}
	return $empty;
}

function printEntries( $entries, $form ){
	$return = '';
	foreach( $form['fields'] as $field){
		$return .= $field['label'] . ': ' . trim( $entries[ $field['id'] ] ) . '</br>';
	}

	return $return;
}


function replaceMergeTags($str, $entries, $form ){
	foreach( $form['fields'] as $field )
		$str = preg_replace( '#{[^}]*:' . $field['id'] . '}#', $entries[ $field['id'] ], $str );
	if( isset( $GLOBALS['ac_url'], $GLOBALS['ac_task_id'] ) )
		$str = str_replace( '{ac_task_url}', $GLOBALS['ac_url'], str_replace(
					'{ac_task_id}', $GLOBALS['ac_task_id'], $str ));
	return $str;
}