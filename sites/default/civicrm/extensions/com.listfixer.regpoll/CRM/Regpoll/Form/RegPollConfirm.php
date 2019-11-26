<?php

/********************************************/
/* Copyright Michael Labriola (c) 2012-2017 */
/********************************************/

class CRM_Regpoll_Form_RegPollConfirm extends CRM_Core_Form
{
	function buildQuickForm( )
	{
		$this->assign( 'shift_info', $this->controller->get( 'shift_info' ) );
		
		CRM_Utils_System::setTitle( 'Volunteer to Collect Signatures' );
	}
}
