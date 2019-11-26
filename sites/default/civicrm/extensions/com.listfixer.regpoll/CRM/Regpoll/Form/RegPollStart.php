<?php

/********************************************/
/* Copyright Michael Labriola (c) 2012-2017 */
/********************************************/

class CRM_Regpoll_Form_RegPollStart extends CRM_Core_Form
{
	function buildQuickForm( )
	{
		$dao = new CRM_Core_DAO( );

		$dao->query( 'SELECT description FROM regpoll_event' );

		if ( $dao->fetch( ) )
			$this->assign( 'description', $dao->description );

		$dao->free( );

		$title = 'Volunteer to Collect Signatures';

		if ( $this->controller->get( 'hash_ok' ) )
		{
			$buttons = [ [ 'type' => 'next', 'name' => 'Continue' ] ];
			$this->assign( 'nick_name', $this->get( 'nick_name' ) );
			$title .= ' - Welcome ' . $this->get( 'nick_name' ) . '!';
		}
		else
			$buttons = [ [ 'type' => 'next', 'name' => 'Start', 'isDefault' => true ] ];
										
		$this->addButtons( $buttons );

		CRM_Utils_System::setTitle( $title );
	}
}