<?php

/********************************************/
/* Copyright Michael Labriola (c) 2012-2017 */
/********************************************/

class CRM_Regpoll_Form_RegPollCounty extends CRM_Core_Form
{
	function buildQuickForm( )
	{
		$dao = new CRM_Core_DAO( );

		$dao->query( 'SELECT county_id, county_name FROM regpoll_avail_county ORDER BY county_name' );

		$counties = [ '' => '- Select -' ];

		while ( $dao->fetch ( ) )
			$counties[$dao->county_id] = $dao->county_name;

		$dao->free( );

		$this->add( 'select', 'county', 'County:', $counties );

		$buttons = [
				[ 'type' => 'back', 'name' => 'Back' ],
				[ 'type' => 'next', 'name' => 'Proceed to Step 2' ]
				];
										
		$this->addButtons( $buttons );

		CRM_Utils_System::setTitle( 'Volunteer to Collect Signatures' );
	}

	function addRules( )
	{
		$this->addFormRule( [ 'CRM_Regpoll_Form_RegPollCounty', 'RegPollCountyRules' ] );
	}

	static function RegPollCountyRules( $values )
	{
		$errors = [ ];

		if ( empty( $values['county'] ) )
			$errors['county'] = 'Please select a county.';

		return empty( $errors ) ? true : $errors;
	}
}