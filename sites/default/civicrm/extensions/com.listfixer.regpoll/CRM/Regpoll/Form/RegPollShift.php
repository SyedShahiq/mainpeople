<?php

/********************************************/
/* Copyright Michael Labriola (c) 2012-2017 */
/********************************************/

class CRM_Regpoll_Form_RegPollShift extends CRM_Core_Form
{
	function buildQuickForm( )
	{
		$data = $this->controller->container( );

		$poll_id = $data['values']['RegPollLocation']['poll_id'];

		$s[1] = $s[2] = $s[3] = $s[4] = false;

		if ( !empty( $poll_id ) )
		{
			$dao = new CRM_Core_DAO( );

			$dao->query( 'SELECT opens, is_target, s1, s2, s3, s4 FROM regpoll_avail_location WHERE poll_id="' . $poll_id . '"' );

			if ( $dao->fetch ( ) )
			{
				$opens = $dao->opens;
				
				if ( ( $opens != '10 AM' && $dao->s1 == 0 ) || $dao->s2 == 0 || $dao->s3 == 0 || $dao->s4 == 0 || $dao->is_target == 0 )
					// If any shift is missing a person, then only show empty shifts.
					$limit = 1;
				else
					// If all shifts have at least one person, then only show shifts without two people.
					$limit = 2;

				$s[1] = ( $opens != '10 AM' && $dao->s1 < $limit );
				$s[2] = ( $dao->s2 < $limit );
				$s[3] = ( $dao->s3 < $limit );
				$s[4] = ( $dao->s4 < $limit );
			}

			$dao->free( );
		}

		$times = [ 1 => '7:00 AM to 10:00 AM', 2 => '10:00 AM to 1:00 PM', 3 => '1:00 PM to 5:00 PM', 4 => '5:00 PM to 8:00 PM' ];

		for ( $i = 1; $i <= 4; $i++ )
			if ( $s[$i] )
			{
				$this->assign( 's' . $i . '_label', $times[$i] );
				$this->addElement( 'checkbox', 's' . $i, 'Shift ' . $i . ':' );
			}

		$this->addElement( 'checkbox', 'captain', 'Poll Captain:' );
		$this->addElement( 'checkbox', 'notary', 'Notary:' );
		$this->add( 'hidden', 'msg' );

		$buttons = [
				[ 'type' => 'back', 'name' => 'Go Back to Step 3' ],
				[ 'type' => 'next', 'name' => 'Sign Up' ]
				];

		$this->addButtons( $buttons );

		CRM_Utils_System::setTitle( 'Volunteer to Collect Signatures' );
	}

	function addRules( )
	{
		$this->addFormRule( [ 'CRM_Regpoll_Form_RegPollShift', 'RegPollShiftRules' ] );
	}

	static function RegPollShiftRules( $values )
	{
		$errors = [ ];

		if ( empty( $values['s1'] ) && empty( $values['s2'] ) && empty( $values['s3'] ) && empty( $values['s4'] ) )
			$errors['msg'] = 'Please check one or more shifts.';

		return empty( $errors ) ? true : $errors;
	}

	function postProcess ( )
	{
		$id = $this->controller->get( 'id' );
		$ref = $this->controller->get( 'ref' );

		$data = $this->controller->container( );
		$values = $data['values']['RegPollShift'];
		$poll_id = $data['values']['RegPollLocation']['poll_id'];

		$dao = new CRM_Core_DAO( );

		$dao->query( 'SELECT start_date FROM regpoll_event' );
		if ( !$dao->fetch ( ) ) return;

		$start_date = substr( $dao->start_date, 0, 10 );
		
		$dao->query( 'SELECT event_id, location, address, town FROM regpoll_avail_location WHERE poll_id="' . $poll_id . '"' );
		if ( !$dao->fetch ( ) ) return;

		$event_id = $dao->event_id;
		$location = $dao->location;
		$address = $dao->address;
		$town = $dao->town;

		if ( isset( $values['captain'] ) )
			$captain = $values['captain'];
		else
			$captain = null;

		if ( isset( $values['notary'] ) )
			$notary = $values['notary'];
		else
			$notary = null;

		if ( isset( $values['s1'] ) )
			$s1 = $values['s1'];
		else
			$s1 = null;

		if ( isset( $values['s2'] ) )
			$s2 = $values['s2'];
		else
			$s2 = null;

		if ( isset( $values['s3'] ) )
			$s3 = $values['s3'];
		else
			$s3 = null;

		if ( isset( $values['s4'] ) )
			$s4 = $values['s4'];
		else
			$s4 = null;

		$d1 = ( $s1 ? 'concat(char(1),"1",char(1),"2",char(1),"3",char(1))' : '""' );
		$d2 = ( $s2 ? 'concat(char(1),"1",char(1),"2",char(1),"3",char(1))' : '""' );
		$d3 = ( $s3 ? 'concat(char(1),"1",char(1),"2",char(1),"3",char(1),"4",char(1))' : '""' );
		$d4 = ( $s4 ? 'concat(char(1),"1",char(1),"2",char(1),"3",char(1))' : '""' );

		$sql = 'SELECT e.id FROM civicrm_participant p JOIN civicrm_value_election_day_tabling_54 e ON p.id=e.entity_id WHERE p.contact_id=' . $id . ' AND p.event_id=' . $event_id . ' AND e.location_id_376="' . $poll_id . '" LIMIT 1';

		$dao->query( $sql );

		if ( $dao->fetch ( ) )
		{
			$record_id = $dao->id;
			$sql = 'UPDATE civicrm_value_election_day_tabling_54 SET ';
			$sql .= 'location_name_377="' . $location . ', ' . $town . '"';

			if ( $d1 != '""' )
				$sql .= ', shift_1_378=' . $d1;

			if ( $d2 != '""' )
				$sql .= ', shift_2_379=' . $d2;

			if ( $d3 != '""' )
				$sql .= ', shift_3_380=' . $d3;

			if ( $d4 != '""' )
				$sql .= ', shift_4_381=' . $d4;

			if ( !empty( $ref ) )
				$sql .= ', referred_by_382=' . $ref . ' ';

			$sql .= 'WHERE id=' . $record_id;

			if ( !$dao->query( $sql ) ) return;
		}
		else
		{
			if ( $id == 280078 || $id == 280079 )
				$status_id = 5; // Pending
			else
				$status_id = 1; // Registered

			$sql = 'INSERT INTO civicrm_participant (contact_id, event_id, status_id, role_id, register_date) ';
			$sql .= 'VALUES (' . $id . ', ' . $event_id . ', ' . $status_id . ', 2, NOW())';

			if ( !$dao->query( $sql ) ) return;

			if ( empty( $ref ) ) $ref = 'NULL';

			$sql = 'INSERT INTO civicrm_value_election_day_tabling_54 ';
			$sql .= '(entity_id, location_id_376, location_name_377, shift_1_378, shift_2_379, shift_3_380, shift_4_381, referred_by_382)';
			$sql .= 'VALUES ( LAST_INSERT_ID( ), "' . $poll_id . '", "' . $location . ', ' . $town . '", ' . $d1 . ', ' . $d2 . ', ' . $d3 . ', ' . $d4 . ', ' . $ref . ')';

			if ( !$dao->query( $sql ) ) return;
		}

		if ( $notary )
			$dao->query( 'INSERT INTO civicrm_value_demographics (entity_id,notary_329) VALUES(' . $id . ',1) ON DUPLICATE KEY UPDATE notary_329=1' );

		if ( $captain )
			$dao->query( 'INSERT IGNORE INTO civicrm_entity_tag (entity_table,entity_id,tag_id) VALUES("civicrm_contact",' . $id . ',185)' );
	
		$dao->query( 'SELECT e.email, v.summary FROM civicrm_email e, regpoll_event v  WHERE contact_id=' . $id . ' AND is_primary=1 LIMIT 1' );
		if ( !$dao->fetch( ) ) return;

		$email = $dao->email;

		$shifts = [ ];

		if ( $s1 ) $shifts[] = '7:00 AM to 10:00 AM';
		if ( $s2 ) $shifts[] = '10:00 AM to 1:00 PM';
		if ( $s3 ) $shifts[] = '1:00 PM to 5:00 PM';
		if ( $s4 ) $shifts[] = '5:00 PM to 8:00 PM';

		$shift_list = implode( ' and ', $shifts );

		$email_text = 'You\'re signed up for ' . $shift_list . ' at ' . '"' . $location . '" located at ' . $address . ' in ' . $town . ' on ' . date( 'l F j', strtotime( $start_date ) ) . '.';

		$this->controller->set( 'shift_info', $email_text );
						  
		$email_text .= "\r\n\r\n" . $dao->summary . "\r\n\r\n";
		$email_text .= 'If you know anyone who might also want to volunteer, forward them this link:' . "\r\n\r\n";
		$email_text .= 'https://www.mainepeoplesalliance.org/civicrm/regpoll?ref=' . $id . "\r\n\r\n";

		mail( $email, 'Volunteer Confirmation', $email_text, $headers = 'From:Jennie@mainepeoplesalliance.org' );

		$dao->free( );
	}
}