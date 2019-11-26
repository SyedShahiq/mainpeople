<?php

/********************************************/
/* Copyright Michael Labriola (c) 2012-2017 */
/********************************************/

class CRM_Regpoll_Form_RegPollWait extends CRM_Core_Form
{
	function preProcess( )
	{
		$data = $this->controller->container();

		$email = $data['values']['RegPollEmail']['email'];

		$contacts = [ ];

		if ( !empty( $email ) )
		{
			$dao = new CRM_Core_DAO( );

			$dao->query( 'SELECT DISTINCT c.id, c.display_name, c.hash FROM civicrm_email e, civicrm_contact c WHERE c.is_deleted=0 AND e.contact_id = c.id AND e.email="' . $email . '"' );

			while ( $dao->fetch( ) )
			{
				$id = $dao->id;
				$contacts[$id]['name'] = $dao->display_name;
				$contacts[$id]['hash'] = $dao->hash;
			}

			$dao->free( );
		}

		if ( count( $contacts ) )
		{
			$email_text = 'Open the link below to continue...' . "\r\n\r\n";

			$ref = $this->controller->get( 'ref' );

			if ( !empty( $ref ) )
				$url_ref = '&ref=' . $ref;
			else
				$url_ref = '';

			foreach ( $contacts as $id => $contact )
			{
				$name = $contact['name'];
				$hash = $contact['hash'];

				if ( count( $contacts ) > 1 ) $email_text .= $name . ':  ';
					
				$email_text .= 'https://www.mainepeoplesalliance.org/civicrm/regpoll?id=' . $id . '&hash=' . $hash . $url_ref . "\r\n\r\n";
			}

			$email_text .= 'You\'ll be sent back to the same screen. Click "Start" again to really get started!';

			mail( $email, 'Maine Peoples Alliance Volunteer Link', $email_text, 'From:Jennie@mainepeoplesalliance.org' );

			$this->set( 'email_status', 'existing' );
		}
		else
			$this->set( 'email_status', 'new' );
	}

	function buildQuickForm( )
	{
		$email_status = $this->get( 'email_status' );
		$this->assign( 'email_status', $email_status );

		if ( $email_status == 'new' )
		{
			$this->add( 'text', 'first_name', 'First Name:' );
			$this->add( 'text', 'last_name', 'Last Name:' );
			$this->add( 'text', 'phone', 'Phone:' );
			$this->add( 'text', 'address', 'Address:', [ 'size' => '50' ] );
			$this->add( 'text', 'city', 'City:' );
			$this->add( 'text', 'zip', 'Zip:', [ 'size' => '5' ] );

			$buttons = [
					[ 'type' => 'back', 'name' => 'Go Back' ],
					[ 'type' => 'refresh', 'name' => 'Continue' ]
					];

			$this->addButtons( $buttons );
		}
		
		CRM_Utils_System::setTitle( 'Volunteer to Collect Signatures' );
	}

	function addRules( )
	{
		$this->addFormRule( [ 'CRM_Regpoll_Form_RegPollWait', 'RegPollWaitRules' ] );
	}

	static function RegPollWaitRules( $values )
	{
		$errors = [ ];

		if ( isset( $values['_qf_RegPollWait_refresh'] ) )
			$refresh = $values['_qf_RegPollWait_refresh'];
		else
			$refresh = '';

	   if ( $refresh == 'Continue' )
		{
			if ( empty( $values['first_name'] ) )
				$errors['first_name'] = 'Please enter your first name.';

			if ( empty( $values['last_name'] ) )
				$errors['last_name'] = 'Please enter your last name.';

			if ( empty( $values['phone'] ) )
				$errors['phone'] = 'Please enter your phone number.';
			else
			{
				$just_digits = preg_replace( '/[\D]/', '', $values['phone'] );

				if ( strlen( $just_digits ) != 7 && strlen( $just_digits) != 10 )
					$errors['phone'] = 'Invalid phone number.';
			}

			if ( empty( $values['address'] ) )
				$errors['address'] = 'Please enter your address.';

			if ( empty( $values['city'] ) )
				$errors['city'] = 'Please enter your city.';

			if ( empty( $values['zip'] ) )
				$errors['zip'] = 'Please enter your zip.';
		}

		return empty( $errors ) ? true : $errors;
	}

	function postProcess ( )
	{
		$data = $this->controller->container();

		$email = $data['values']['RegPollEmail']['email'];

		$values = $data['values']['RegPollWait'];

		$params['contact_type'] = 'Individual';
		$params['first_name'] = $values['first_name'];
		$params['last_name'] = $values['last_name'];

		$params['email'] = [ '1' => [
				'location_type_id' => 1,
				'is_primary' => 1,
				'email' => $email ] ];

		$just_digits = preg_replace( '/[\D]/', '', $values['phone'] );
		if ( strlen( $just_digits ) == 7 ) $just_digits = '207' . $just_digits;

		$params['phone'] = [ '1' => [
			'location_type_id' => 1,
			'is_primary' => 1,
			'phone' => '(' . substr( $just_digits, 0, 3 ) . ') ' . substr( $just_digits, 3, 3 ) . '-' . substr( $just_digits, 6, 4 )
				] ];
				
		$params['address'] = [ '1' => [
			'location_type_id' => 1,
			'is_primary' => 1,
			'street_address' => $values['address'],
			'city' => $values['city'],
			'postal_code' => $values['zip'],
			'state_province_id' => 1018,
			'country_id' => 1228 ] ];

		$contact = CRM_Contact_BAO_Contact::create( $params );
	}
}
