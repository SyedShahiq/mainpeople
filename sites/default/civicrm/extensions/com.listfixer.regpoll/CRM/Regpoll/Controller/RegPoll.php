<?php

/********************************************/
/* Copyright Michael Labriola (c) 2012-2017 */
/********************************************/

class CRM_Regpoll_Controller_RegPoll extends CRM_Core_Controller
{
	function __construct( $title = null, $action = CRM_Core_Action::NONE, $modal = true )
	{
		parent::__construct( $title, $modal );

		$this->set( 'hash_ok', false );

		if ( !empty( $_GET['id'] ) ) $this->set( 'id', preg_replace( '/[\D]/', '', $_GET['id'] ) );
		if ( !empty( $_GET['hash']  ) ) $this->set( 'hash',  preg_replace ( '/[^0-9a-f]/', '', $_GET['hash']  ) );
		if ( !empty( $_GET['ref'] ) ) $this->set( 'ref', preg_replace( '/[\D]/', '', $_GET['ref'] ) );

		$id = $this->get( 'id' );
		$hash = $this->get( 'hash' );

		if ( !empty( $id ) && !empty( $hash ) )
		{
			$dao = new CRM_Core_DAO( );

			$dao->query( 'SELECT c.id, COALESCE(c.nick_name,c.first_name,e.email) as nick_name FROM civicrm_contact c LEFT JOIN civicrm_email e ON c.id = e.contact_id AND e.is_primary WHERE c.id=' . $id . ' AND c.hash="' . $hash . '"' );

			if ( $dao->fetch( ) )
			{
				$this->set( 'hash_ok', true );
				$this->set( 'nick_name', $dao->nick_name );
			}

			$dao->free( );
		}
	
		$this->_stateMachine = new CRM_Regpoll_StateMachine_RegPoll( $this, $action );

		$this->addPages( $this->_stateMachine, $action );

		$this->addActions( );
	}
}
