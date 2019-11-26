<?php

/********************************************/
/* Copyright Michael Labriola (c) 2012-2017 */
/********************************************/

class CRM_Regpoll_Form_RegPollLocation extends CRM_Core_Form
{
	function buildQuickForm( )
	{
		$data = $this->controller->container( );
		$town = $data['values']['RegPollTown']['town'];
		
		$dao = new CRM_Core_DAO( );

		$dao->query( 'SELECT poll_id, location, address FROM regpoll_avail_location WHERE town="' . $town . '" ORDER BY location' );

		$polls = [ '' => '- Select -' ];

		while ( $dao->fetch ( ) )
			$polls[$dao->poll_id] = $dao->location . ": " . $dao->address;

		$dao->free( );

		$this->add( 'select', 'poll_id', 'Location:', $polls, null, [ 'size' => '16' ] );

		$buttons = [
				[ 'type' => 'back', 'name' => 'Go Back to Step 2' ],
				[ 'type' => 'next', 'name' => 'Proceed to Step 4' ]
				];

		$this->addButtons( $buttons );

		CRM_Utils_System::setTitle( 'Volunteer to Collect Signatures' );
	}

	function addRules( )
	{
		$this->addFormRule( [ 'CRM_Regpoll_Form_RegPollLocation', 'RegPollLocationRules' ] );
	}

	static function RegPollLocationRules( $values )
	{
		$errors = [ ];

		if ( empty( $values['poll_id'] ) )
			$errors['poll_id'] = 'Please select a location.';

		return empty( $errors ) ? true : $errors;
	}
}