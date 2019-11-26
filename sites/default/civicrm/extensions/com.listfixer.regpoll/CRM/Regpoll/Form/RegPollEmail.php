<?php

/********************************************/
/* Copyright Michael Labriola (c) 2012-2017 */
/********************************************/

class CRM_Regpoll_Form_RegPollEmail extends CRM_Core_Form
{
	function buildQuickForm( )
	{
		$this->add( 'text', 'email', 'Email:', [ 'size' => '50' ] );

		$buttons = [
				[ 'type' => 'back', 'name' => 'Back' ],
				[ 'type' => 'next', 'name' => 'Continue' ]
				];

		$this->addButtons( $buttons );

		CRM_Utils_System::setTitle( 'Volunteer to Collect Signatures' );
	}

	function addRules( )
	{
		$this->addFormRule( [ 'CRM_Regpoll_Form_RegPollEmail', 'RegPollEmailRules' ] );
	}

	static function RegPollEmailRules( $values )
	{
		$errors = [ ];

		if ( empty( $values['email'] ) )
			$errors['email'] = 'Please enter your email address.';

		elseif ( !preg_match( '/^[A-Z0-9._%+-]+@[A-Z0-9.0]+\.[A-Z]{2,4}$/i', $values['email'] ) )
			$errors['email'] = "Invalid email address.";

		return empty( $errors ) ? true : $errors;
	}
}