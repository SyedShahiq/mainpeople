<?php

/********************************************/
/* Copyright Michael Labriola (c) 2012-2017 */
/********************************************/

class CRM_Regpoll_Form_RegPollTown extends CRM_Core_Form
{
	function buildQuickForm( )
	{
		$data = $this->controller->container();
		$county = $data['values']['RegPollCounty']['county'];

		$dao = new CRM_Core_DAO( );

		$buttons =[
				[ 'type' => 'back', 'name' => 'Go Back to Step 1' ],
				[ 'type' => 'next', 'name' => 'Proceed to Step 3' ]
			];
				    
		$dao->query( 'SELECT town FROM regpoll_avail_town WHERE county_id="' . $county . '" ORDER BY town' );

		$towns = [ '' => '- Select -' ];

		while ( $dao->fetch ( ) )
			$towns[$dao->town] = $dao->town;

		$dao->free( );

		$this->add( 'select', 'town', 'Town:', $towns, null, [ 'size' => '16' ] );

		$this->addButtons( $buttons );

		CRM_Utils_System::setTitle( 'Volunteer to Collect Signatures' );
	}

	
	public function addRules( )
	{
		$this->addFormRule( [ 'CRM_Regpoll_Form_RegPollTown', 'RegPollTownRules' ] );
	}

	static function RegPollTownRules( $values )
	{
		$errors = [ ];

		if ( empty( $values['town'] ) && empty( $values['_qf_RegPollTown_refresh'] ) )
			$errors['town'] = 'Please select a town.';

		return empty( $errors ) ? true : $errors;
	}
}
