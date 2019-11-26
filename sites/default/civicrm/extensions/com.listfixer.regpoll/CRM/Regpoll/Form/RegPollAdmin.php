<?php

/********************************************/
/* Copyright Michael Labriola (c) 2012-2017 */
/********************************************/

require_once "CRM/Core/Form.php";

class CRM_Regpoll_Form_RegPollAdmin extends CRM_Core_Form
{
	function buildQuickForm( )
	{
		if ( empty( $_SESSION['CiviCRM']['userID'] ) )
		{
			echo 'Unauthorized';
			CRM_Utils_System::civiExit( );
		}

		$dao = new CRM_Core_DAO( );

		$dao->query( 'SELECT id, display_name FROM civicrm_contact WHERE id IN ( SELECT DISTINCT organizer_id FROM regpoll_poll WHERE organizer_id IS NOT NULL ) ORDER BY display_name' );

		$orgs['0'] = 'All';

		while ( $dao->fetch( ) )
			$orgs[$dao->id] = $dao->display_name;

		if ( empty( $orgs[$user_id] ) )
			$default_org = '0';
		else
			$default_org = $user_id;

		$dao->query( 'SELECT county_id, county_name FROM regpoll_county ORDER BY county_name' );

		while ( $dao->fetch( ) )
			$orgs[$dao->county_id] = $dao->county_name;

		$user_id = $_SESSION['CiviCRM']['userID'];

		$this->assign( 'default_org', $default_org );
		$this->assign( 'orgs', $orgs );

		$title = 'Polling Location Administration';

		$dao->free( );

		CRM_Utils_System::setTitle( $title );
	}
}

