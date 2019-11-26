<?php

/********************************************/
/* Copyright Michael Labriola (c) 2012-2017 */
/********************************************/

class CRM_Regpoll_StateMachine_RegPoll extends CRM_Core_StateMachine
{
	function __construct( $controller, $action = CRM_Core_Action::NONE )
	{
		parent::__construct( $controller, $action );

		if ( $controller->get( 'hash_ok') )
			$this->_pages = [
					'CRM_Regpoll_Form_RegPollStart' => null,
					'CRM_Regpoll_Form_RegPollCounty' => null,
					'CRM_Regpoll_Form_RegPollTown' => null,
					'CRM_Regpoll_Form_RegPollLocation' => null,
					'CRM_Regpoll_Form_RegPollShift' => null,
					'CRM_Regpoll_Form_RegPollConfirm' => null
					];
		else
			$this->_pages = [
					'CRM_Regpoll_Form_RegPollStart' => null,
					'CRM_Regpoll_Form_RegPollEmail' => null,
					'CRM_Regpoll_Form_RegPollWait' => null
					];

		$this->addSequentialPages( $this->_pages, $action );
	}
}

