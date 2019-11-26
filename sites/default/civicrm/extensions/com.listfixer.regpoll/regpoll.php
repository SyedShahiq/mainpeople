<?php

function regpoll_civicrm_xmlMenu( &$files )
{
	$files[] = __DIR__ . '/xml/Menu/regpoll.xml';
}

function regpoll_civicrm_merge( $type, $data, $new_id = NULL, $old_id = NULL, $tables = NULL )
{
	if ( !empty( $new_id ) && !empty( $old_id ) && $type == 'sqls' )
	{
		$dao = new CRM_Core_DAO( );

		$sql = "UPDATE regpoll_poll SET contact_id=$new_id WHERE contact_id=$old_id";
		$dao->query( $sql );

		$sql = "UPDATE regpoll_poll SET organizer_id=$new_id WHERE organizer_id=$old_id";
		$dao->query( $sql );
	}
}
	
function regpoll_civicrm_config( &$config = NULL )
{
	static $configured = FALSE;

	if ( $configured ) return;

	$configured = TRUE;

	$template =& CRM_Core_Smarty::singleton( );

	$extRoot = dirname( __FILE__ ) . DIRECTORY_SEPARATOR; 

	$extDir = $extRoot . 'templates';

	if ( is_array( $template->template_dir ) )
		array_unshift( $template->template_dir, $extDir );
	else
		$template->template_dir = array( $extDir, $template->template_dir );

	set_include_path( $extRoot . PATH_SEPARATOR . get_include_path( ) );
}
