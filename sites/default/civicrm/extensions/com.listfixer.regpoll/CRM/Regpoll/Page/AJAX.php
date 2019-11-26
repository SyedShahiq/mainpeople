<?php

/********************************************/
/* Copyright Michael Labriola (c) 2012-2017 */
/********************************************/

class CRM_Regpoll_Page_AJAX
{
	static function polllist( )
	{
		if ( empty( $_SESSION['CiviCRM']['userID'] ) )
		{
			echo 'Unauthorized';
			CRM_Utils_System::civiExit( );
		}

		$org_id = ( isset( $_GET['org'] ) ? preg_replace( '/[^0-9A-Z]/', '', $_GET['org'] ) : 0 );
		$unstaffed = ( $_GET['unstaffed'] == 'true' );
		$secondary = ( $_GET['secondary'] == 'true' );

		$dao = new CRM_Core_DAO( );

		$dao->query( 'SELECT id, name FROM regpoll_status_type' );

		$types = [ ];

		while ( $dao->fetch( ) )
			$types[$dao->id] = $dao->name;

		if ( $secondary )
			$criteria = 'WHERE true ';
		else
			$criteria = 'WHERE is_target';

		if ( !$unstaffed )
			$criteria .= ' AND ( any_shift OR contact_id IS NOT NULL )';

		if ( $org_id )
			$criteria .= ' AND (organizer_id="' . $org_id . '" OR county_id="' . $org_id . '")';

		$dao->query( 'SELECT organizer_id, organizer_inits, county_id, town, opens, poll_id, location, address, is_target, goal, contact_id, display_name, phone, s1, s2, s3, s4 FROM regpoll_info ' . $criteria . ' ORDER BY county_id, town, location' );

		$poll = array( ) ;

		while ( $dao->fetch( ) )
		{
			$poll_id = $dao->poll_id;

			$poll[$poll_id]['town'] = $dao->organizer_inits . ' - ' . $dao->county_id . '<br><strong>' . $dao->town . '</strong><br>Opens ' . $dao->opens;
			$poll[$poll_id]['location'] = $dao->location;
			$poll[$poll_id]['address'] = $dao->address;
			$poll[$poll_id]['is_target'] = $dao->is_target;
			$poll[$poll_id]['goal'] = $dao->goal;
			$poll[$poll_id]['capt_contact_id'] = $dao->contact_id;
			$poll[$poll_id]['capt_display_name'] = $dao->display_name;
			$poll[$poll_id]['capt_phone'] = $dao->phone;
			$poll[$poll_id]['grid'][1] = array( );
			$poll[$poll_id]['grid'][2] = array( );
			$poll[$poll_id]['grid'][3] = array( );
			$poll[$poll_id]['grid'][4] = array( );
		}

		$num_shifts = 0;
		$dao->query( 'SELECT contact_id, poll_id, status_id, s1a, s1b, s1c, s1, s2a, s2b, s2c, s2, s3a, s3b, s3c, s3d, s3, s4a, s4b, s4c, s4, display_name, phone, poll_captain FROM regpoll_participant_info ORDER BY poll_id, s1a DESC, s1b DESC, s1c DESC, s1 DESC, s2a DESC, s2b DESC, s2c DESC, s2 DESC, s3a DESC, s3b DESC, s3c DESC, s3d DESC, s3 DESC, s4a DESC, s4b DESC, s4c DESC, s4 DESC' );
		while ( $dao->fetch( ) )
		{
			$poll_id = $dao->poll_id;
			if ( isset( $poll[$poll_id]['location'] ) )
			{
				$num_shifts += round( ( $dao->s1a + $dao->s1b + $dao->s1c + $dao->s2a + $dao->s2b + $dao->s2c + $dao->s3a + $dao->s3b + $dao->s3c + $dao->s3d + $dao->s4a + $dao->s4b + $dao->s4c ) * 4/13 );

				$shift[1]['count'] = $dao->s1a + $dao->s1b + $dao->s1c;
				$shift[1]['shifts'] = array( 1 => $dao->s1a, 2 => $dao->s1b, 3 => $dao->s1c );

				$shift[2]['count'] = $dao->s2a + $dao->s2b + $dao->s2c;
				$shift[2]['shifts'] = array( 1 => $dao->s2a, 2 => $dao->s2b, 3 => $dao->s2c );

				$shift[3]['count'] = $dao->s3a + $dao->s3b + $dao->s3c + $dao->s3d;
				$shift[3]['shifts'] = array( 1 => $dao->s3a, 2 => $dao->s3b, 3 => $dao->s3c, 4 => $dao->s3d );

				$shift[4]['count'] = $dao->s4a + $dao->s4b + $dao->s4c;
				$shift[4]['shifts'] = array( 1 => $dao->s4a, 2 => $dao->s4b, 3 => $dao->s4c );

				foreach ( $shift AS $s => $info )
				{
					if ( $info['count'] )
					{
						$contact_id = $dao->contact_id;
						$poll[$poll_id]['grid'][$s][$contact_id]['name'] = $dao->display_name;
						$poll[$poll_id]['grid'][$s][$contact_id]['phone'] = $dao->phone;
						$poll[$poll_id]['grid'][$s][$contact_id]['pc'] = $dao->poll_captain;
						$poll[$poll_id]['grid'][$s][$contact_id]['status_id'] = $dao->status_id;
						$poll[$poll_id]['grid'][$s][$contact_id]['shifts'] = $info['shifts'];
						if ( isset( $contact[$contact_id][$s] ) )
							$contact[$contact_id][$s] += 1;
						else
							$contact[$contact_id][$s] = 1;
					}
				}
			}
		}

		$dao->free( );

		foreach ( $poll as $poll_id => $p )
			if ( isset( $p['grid'] ) )
				for ( $s=1; $s<5; $s++ )
					if ( isset( $p['grid'][$s] ) )
						foreach ( $p['grid'][$s] as $id => $c )
							if ( $contact[$id][$s] > 1 )
								$poll[$poll_id]['grid'][$s][$id]['dup'] = true;

	   $headings = array ( 1 => 'Shift&nbsp;1&nbsp;-&nbsp;7&nbsp;am&nbsp;to&nbsp;10&nbsp;am',
								  2 => 'Shift&nbsp;2&nbsp;-&nbsp;10&nbsp;am&nbsp;to&nbsp;1&nbsp;pm',
								  3 => 'Shift&nbsp;3&nbsp;-&nbsp;1&nbsp;pm&nbsp;to&nbsp;5&nbsp;pm',
								  4 => 'Shift&nbsp;4&nbsp;-&nbsp;5&nbsp;pm&nbsp;to&nbsp;8&nbsp;pm' );

		echo '<table><tr><td>Town</td><td>Location / Poll Captain</td>';

		foreach ( $headings AS $shift => $desc )
			echo '<td width=125>' . $desc . '</td>';

		echo '</tr>';

		foreach ( $poll AS $k => $i )
		{
			echo '<tr id=poll-' . $k . '><td>' . $i['town'] . '</td><td>';
			echo '<a href=# onclick="poll_select(' . $k . ',\'' . $i['location'] . '\')">';
			echo '<strong>' . $i['location'] . '</strong>';
			echo '</a>';
			echo '<br>' . $i['address'];

			if ( !empty( $i['capt_contact_id'] ) )
			{
				echo '<br>' . $i['capt_display_name'];
				if ( !empty( $i['capt_phone'] ) )
					echo '<br>' . $i['capt_phone'];
			}

			if ( !empty( $i['goal'] ) )
				echo '<br>Goal ' . $i['goal'];

			if ( $i['is_target'] )
				echo ' (Primary)';

			echo '</td>';

			foreach ( $i['grid'] AS $sid => $s )
			{
				echo '<td width=125>';

				foreach ( $s AS $cid => $j )
				{
					$dummy = ( $cid == 280078 || $cid == 280079 );
					echo '<table class=shift-status><tr>';

					foreach ( $j['shifts'] AS $shift => $h )
					{
						if ( !empty( $h ) )
							echo '<td bgcolor=black></td>';
						else
							echo '<td bgcolor=#E6E6DC></td>';
					}

					echo '</tr></table>';

					echo '<a class=pointer onclick="regpoll_edit_schedule(' . $cid . ',\'' . $k . '\')">';
					if ( isset( $j['dup'] ) && !$dummy ) echo '<font color=red>';
					echo $j['name'];
					if ( isset( $j['dup'] ) ) echo '</font>';
					if ( empty( $i['capt_contact_id'] ) ) echo ' ' . $j['pc'];
					if ( !empty( $types[$j['status_id']] ) && !$dummy )
						echo '<br><span class="status-' . $j['status_id'] . '">' . $types[$j['status_id']] . '</span>';
					if ( !empty( $j['phone'] ) ) echo '<br>' . $j['phone'];
					echo '</a>';
				}
				echo '</td>';
			}
			echo '</tr>';
		}

		echo '<tr><td colspan=2></td><td colspan=4 class="center">Shift Total: ' . number_format($num_shifts,0) . '</td></tr>';

		echo '</table>';
	
		CRM_Utils_System::civiExit( );
	}

	static function search( )
	{
		if ( empty( $_SESSION['CiviCRM']['userID'] ) )
		{
			echo 'Unauthorized';
			CRM_Utils_System::civiExit( );
		}

		$x = str_replace( ' ', '%', $_GET['x'] );
		$x = preg_replace( "/[^0-9A-Za-z._%@']/", '', $x );

		if ( strlen( $x ) < 3 )
			CRM_Utils_System::civiExit( );

		$results = array( );

		$dao = new CRM_Core_DAO( );

		$sql = 'SELECT c.id, c.display_name, e.email, p.phone, a.street_address, a.city FROM civicrm_contact c ';
		$sql .= 'LEFT JOIN civicrm_address a ON c.id = a.contact_id AND state_province_id=1018 ';
		$sql .= 'LEFT JOIN civicrm_email e ON c.id=e.contact_id ';
		$sql .= 'LEFT JOIN civicrm_phone p ON c.id=p.contact_id ';
		$sql .= 'WHERE c.is_deleted = 0 AND ( e.email LIKE "%' . $x . '%" OR a.street_address LIKE "%' . $x . '%" OR c.display_name LIKE "%' . $x . '%" OR p.phone LIKE "%' . $x . '%" ) ORDER BY display_name LIMIT 20';

		$dao->query( $sql );

		while ( $dao->fetch( ) )
		{
			$id = $dao->id;
			$email = $dao->email;
			$phone = $dao->phone;
			$addr = $dao->street_address;
			$city = $dao->city;
			$results[$id]['name'] = $dao->display_name;

		   if ( !isset( $results[$id]['email'] ) ) $results[$id]['email'] = array( );
		   if ( !isset( $results[$id]['phone'] ) ) $results[$id]['phone'] = array( );
		   if ( !isset( $results[$id]['addr'] ) ) $results[$id]['addr'] = array( );

			if ( !empty( $email ) && !in_array( $email, $results[$id]['email'] ) ) $results[$id]['email'][] = $email;
			if ( !empty( $phone ) && !in_array( $phone, $results[$id]['phone'] ) ) $results[$id]['phone'][] = $phone;
			if ( !empty( $addr ) && !empty( $city ) && !in_array( $addr . ', ' . $city, $results[$id]['addr'] ) ) $results[$id]['addr'][] = $addr . ', ' . $city;
		}

		$dao->free( );

		$html = '<table><tr><td>Name</td><td>Email</td><td>Phone</td><td>Address</td><td><a href=# onclick=hide_list()>Close</a></td></tr>';

		foreach ( $results as $id => $data )
		{
			$contact_name = str_replace( "'", '', $data['name'] );
			$html .= '<tr class=regpoll-row onclick="item_select(' . $id . ',\'' . $contact_name . '\')" onmouseover="item_hover(' . $id . ')" id=regpoll-' . $id . '>';
			$html .= '<td><p>' . $contact_name . '</p></td>';

			$html .= '<td>' . implode( '<br>', $data['email'] ) . '</td>';
			$html .= '<td>' . implode( '<br>', $data['phone'] ) . '</td>';
			$html .= '<td>' . implode( '<br>', $data['addr'] ) . '</td>';

			$html .= '<td></td></tr>';
		}

		$html .= '<tr><td colspan=5 class="center">If you can not find the person you are looking for, then <a href=# onclick="regpoll_add_contact()">add a new contact record</a>.</td></tr>';

		$html .= '</table>';

		echo $html;

		CRM_Utils_System::civiExit( );
	}

	static function contact( )
	{
		if ( empty( $_SESSION['CiviCRM']['userID'] ) )
		{
			echo 'Unauthorized';
			CRM_Utils_System::civiExit( );
		}

		if ( isset( $_POST['first_name'] ) )
			$first_name = preg_replace( '/[^A-Za-z -]/', '', $_POST['first_name'] );
		else
			$first_name = null;

		if ( isset( $_POST['last_name'] ) )
			$last_name = preg_replace( '/[^A-Za-z -]/', '', $_POST['last_name'] );
		else
			$last_name = null;

		if ( isset( $_POST['phone'] ) )
			$phone = preg_replace( '/[^0-9() -]/', '', $_POST['phone'] );
		else
			$phone = null;

		if ( isset( $_POST['email'] ) )
			$email = preg_replace( '/[^A-Za-z0-9@._+-]/', '', $_POST['email'] );
		else
			$email = null;

		if ( isset( $_POST['address'] ) )
			$address = preg_replace( '/[^A-Za-z0-9 -]/', '', $_POST['address'] );
		else
			$address = null;

		if ( isset( $_POST['city'] ) )
			$city = preg_replace( '/[^A-Za-z -]/', '', $_POST['city'] );
		else
			$city = null;

		if ( isset( $_POST['zip'] ) )
			$zip = preg_replace( '/[^0-9-]/', '', $_POST['zip'] );
		else
			$zip = null;

		if ( empty( $first_name ) || empty( $last_name ) || ( empty( $phone ) && empty( $email ) ) )
			CRM_Utils_System::civiExit( );

		$params['contact_type'] = 'Individual';
		$params['first_name'] = $first_name;
		$params['last_name'] = $last_name;

		if ( !empty( $phone ) )
			$params['phone'] = array( '1' => array(
				'location_type_id' => 1,
				'is_primary' => 1,
				'phone' => $phone ) );

		if ( !empty( $email ) )
			$params['email'] = array( '1' => array(
				'location_type_id' => 1,
				'is_primary' => 1,
				'email' => $email ) );

		if ( !empty( $address ) || !empty( $city ) || !empty( $zip ) )
			$params['address'] = array( '1' => array(
				'location_type_id' => 1,
				'is_primary' => 1,
				'street_address' => $address,
				'city' => $city,
				'postal_code' => $zip,
				'state_province_id' => 1018,
				'country_id' => 1228 ) );

		$contact = CRM_Contact_BAO_Contact::create( $params );

		echo $contact->id;
		
		CRM_Utils_System::civiExit( );
	}

	static function schedule( )
	{
		if ( empty( $_SESSION['CiviCRM']['userID'] ) )
		{
			echo 'Unauthorized';
			CRM_Utils_System::civiExit( );
		}

		$contact_id = preg_replace( '[\D]', '', $_GET['cid'] );
		$poll_id = preg_replace( '[\D]', '', $_GET['pid'] );
		$captain = ( isset( $_GET['capt'] ) && $_GET['capt'] == 'true' );

		$s1 = ( isset( $_GET['s1'] ) && $_GET['s1'] == 'true' );
		$s2 = ( isset( $_GET['s2'] ) && $_GET['s2'] == 'true' );
		$s3 = ( isset( $_GET['s3'] ) && $_GET['s3'] == 'true' );
		$s4 = ( isset( $_GET['s4'] ) && $_GET['s4'] == 'true' );
		
		if ( empty( $contact_id ) || empty( $poll_id ) || ( !$captain && !$s1 && !$s2 && !$s3 && !$s4 ) )
			CRM_Utils_System::civiExit( );

		$dao = new CRM_Core_DAO( );

		$dao->query( 'SELECT event_id, location, address, town FROM regpoll_avail_location WHERE poll_id="' . $poll_id . '"' );

		if ( !$dao->fetch( ) )
		{
			echo 'Error:  Unable to find polling location.';
			CRM_Utils_System::civiExit( );
		}

		$event_id = $dao->event_id;
		$location = $dao->location;
		$address = $dao->address;
		$town = $dao->town;

		$d1 = ( $s1 ? 'concat(char(1),"1",char(1),"2",char(1),"3",char(1))' : '""' );
		$d2 = ( $s2 ? 'concat(char(1),"1",char(1),"2",char(1),"3",char(1))' : '""' );
		$d3 = ( $s3 ? 'concat(char(1),"1",char(1),"2",char(1),"3",char(1),"4",char(1))' : '""' );
		$d4 = ( $s4 ? 'concat(char(1),"1",char(1),"2",char(1),"3",char(1))' : '""' );

		$dao->query( 'SELECT e.id FROM civicrm_participant p JOIN civicrm_value_election_day_tabling_54 e ON p.id=e.entity_id WHERE p.contact_id=' . $contact_id . ' AND p.event_id=' . $event_id . ' AND e.location_id_376="' . $poll_id . '" LIMIT 1' );
		if ( $dao->fetch( ) )
		{
			$record_id = $dao->id;
			$sql = 'UPDATE civicrm_value_election_day_tabling_54 SET ';
			$sql .= 'location_name_377="' . $location . ', ' . $town . '", ';
			$sql .= 'shift_1_378=' . $d1 . ',';
			$sql .= 'shift_2_379=' . $d2 . ',';
			$sql .= 'shift_3_380=' . $d3 . ',';
			$sql .= 'shift_4_381=' . $d4 . ' ';
			$sql .= 'WHERE id=' . $record_id;

			if ( $dao->query( $sql ) === false )
			{
				echo 'Error:  Unable to update shifts.';
				CRM_Utils_System::civiExit( );
			}
		}
		else
		{
			if ( $contact_id == 280078 || $contact_id == 280079 )
				$status_id = 5; // Pending
			else
				$status_id = 1; // Registered
			
			$sql = 'INSERT INTO civicrm_participant (contact_id, event_id, status_id, role_id, register_date) ';
			$sql .= 'VALUES (' . $contact_id . ',' . $event_id . ', ' . $status_id . ', 2, NOW())';

			if ( $dao->query( $sql ) === false )
			{
				echo 'Error:  Unable to add shifts.';
				CRM_Utils_System::civiExit( );
			}

			if ( $s1 || $s2 || $s3 || $s4 )
			{
				$sql = 'INSERT INTO civicrm_value_election_day_tabling_54 (entity_id, location_id_376, location_name_377, shift_1_378, shift_2_379, shift_3_380, shift_4_381) ';
				$sql .= 'VALUES ( LAST_INSERT_ID( ), "' . $poll_id . '", "' . $location . ', ' . $town . '", ' . $d1 . ',' . $d2 . ',' . $d3 . ',' . $d4. ')';

				if ( $dao->query( $sql ) === false )
				{
					echo 'Error:  Unable to save shift information.';
					CRM_Utils_System::civiExit( );
				}
			}
		}

		if ( $captain )
		{
			if ( $dao->query( 'UPDATE regpoll_poll SET contact_id=' . $contact_id . ' WHERE poll_id="' . $poll_id . '"' ) === false )
			{
				echo 'Error:  Unable to save poll captain information.';
				CRM_Utils_System::civiExit( );
			}
		}

		$dao->free( );

		CRM_Utils_System::civiExit( );
	}

	static function getsched( )
	{
		if ( empty( $_SESSION['CiviCRM']['userID'] ) )
		{
			echo 'Unauthorized';
			CRM_Utils_System::civiExit( );
		}

		$contact_id = preg_replace( '[\D]', '', $_GET['cid'] );
		$poll_id = preg_replace( '[\D]', '', $_GET['pid'] );

		if ( empty( $contact_id ) || empty( $poll_id ) )
			CRM_Utils_System::civiExit( );

		$dummy = ( $contact_id == 280078 || $contact_id == 280079 );

		$dao = new CRM_Core_DAO( );

		$dao->query( 'SELECT id, name FROM regpoll_status_type' );

		$types = [ ];

		while ( $dao->fetch( ) )
			$types[$dao->id] = $dao->name;

		$dao->query( 'SELECT status_id, poll_captain_id, s1a, s1b, s1c, s2a, s2b, s2c, s3a, s3b, s3c, s3d, s4a, s4b, s4c, display_name, location FROM regpoll_participant_edit WHERE contact_id=' . $contact_id . ' AND poll_id="' . $poll_id . '"' );
		if ( !$dao->fetch( ) )
			CRM_Utils_System::civiExit( );

		$display_name = $dao->display_name;
		$location = $dao->location;

		echo '<table><tr><td colspan=3>Volunteer: ';
		echo '<a target=_blank href="/civicrm/contact/view?cid=' . $contact_id . '">' . $display_name . '</a>';
		echo '</td><td colspan=2>';

		if ( $dummy )
			echo '<input hidden id="sid" value="' . $dao->status_id . '">';
		else
		{
			echo '<select id="sid">';

			foreach ( $types as $id => $name )
				echo '<option value="' . $id . '"' . ( $id == $dao->status_id ? ' SELECTED ' : '' ) . '>' . $name . '</option>';

			echo '</select>';
		}

		echo '</td></tr>';

		echo '<tr><td colspan=3>Location: ' . $location . '</td><td colspan=2>';

		if ( $dummy )
			echo '<input hidden id="pc">';
		else
			echo '<input id="pc" type=checkbox' . ( $contact_id == $dao->poll_captain_id ? ' checked' : '' ) . '> Poll Captain';

		echo '</td></tr>';
		echo '<tr><td>Shift 1: </td><td><input id=e1 type=checkbox';
		if ( $dao->s1a ) echo ' checked';
		echo '> 7 am to 8 am </td><td><input id=e2 type=checkbox';
		if ( $dao->s1b ) echo ' checked';
		echo '> 8 am to 9 am </td><td><input id=e3 type=checkbox';
		if ( $dao->s1c ) echo ' checked';
		echo '> 9 am to 10 am </td></tr><tr><td>Shift 2: </td><td><input id=e4 type=checkbox';
		if ( $dao->s2a ) echo ' checked';
		echo '> 10 am to 11 am </td><td><input id=e5 type=checkbox';
		if ( $dao->s2b ) echo ' checked';
		echo '> 11 pm to 12 pm </td><td><input id=e6 type=checkbox';
		if ( $dao->s2c ) echo ' checked';
		echo '> 12 pm to 1 pm </td></tr><tr><td>Shift 3: </td><td><input id=e7 type=checkbox';
		if ( $dao->s3a ) echo ' checked';
		echo '> 1 pm to 2 pm </td><td><input id=e8 type=checkbox';
		if ( $dao->s3b ) echo ' checked';
		echo '> 2 pm to 3 pm </td><td><input id=e9 type=checkbox';
		if ( $dao->s3c ) echo ' checked';
		echo '> 3 pm to 4 pm </td><td><input id=e10 type=checkbox';
		if ( $dao->s3d ) echo ' checked';
		echo '> 4 pm to 5 pm </td></tr><tr><td>Shift 4: </td><td><input id=e11 type=checkbox';
		if ( $dao->s4a ) echo ' checked';
		echo '> 5 pm to 6 pm </td><td><input id=e12 type=checkbox';
		if ( $dao->s4b ) echo ' checked';
		echo '> 6 pm to 7 pm </td><td><input id=e13 type=checkbox';
		if ( $dao->s4c ) echo ' checked';
		echo '> 7 pm to 8 pm</td></tr>';

		echo '<tr><td align=right colspan=5><input onclick="regpoll_edit_schedule_save(' . $contact_id . ',\'' . $poll_id . '\')" type=button value=Save>';
		echo '<input onclick="regpoll_edit_schedule_cancel()" type=button value=Cancel></td></tr></table>';
			
		$dao->free( );

		CRM_Utils_System::civiExit( );
	}

	static function putsched( )
	{
		if ( empty( $_SESSION['CiviCRM']['userID'] ) )
		{
			echo 'Unauthorized';
			CRM_Utils_System::civiExit( );
		}

		$contact_id = preg_replace( '[\D]', '', $_GET['cid'] );
		$poll_id = preg_replace( '[\D]', '', $_GET['pid'] );
		$new_status_id = preg_replace( '[\D]', '', $_GET['sid'] );
		$make_pc = preg_replace( '[\D]', '', $_GET['pc'] );

		if ( empty( $contact_id ) || empty( $poll_id ) )
			CRM_Utils_System::civiExit( );

		for ( $s=1; $s < 14; $s++)
			$shifts[$s] = ( isset( $_GET['s' . $s] ) && $_GET['s' . $s] == '1' ? 1 : 0 );

		$dao = new CRM_Core_DAO( );

		$dao->query( 'SELECT p.id as participant_id, p.status_id, e.id as custom_id, e.shift_1_378, e.shift_2_379, e.shift_3_380, e.shift_4_381 FROM civicrm_participant p JOIN civicrm_value_election_day_tabling_54 e ON p.id=e.entity_id JOIN regpoll_event m ON p.event_id=m.event_id WHERE p.contact_id=' . $contact_id . ' AND e.location_id_376="' . $poll_id . '" LIMIT 1' );
		if ( $dao->fetch( ) === false )
		{
			echo 'Error:  Unable to find shifts.';
			CRM_Utils_System::civiExit( );
		}

		$old_status_id = $dao->status_id;
		$participant_id = $dao->participant_id;
		$custom_id = $dao->custom_id;

		if ( $make_pc )
		{
			if ( $dao->query( 'UPDATE regpoll_poll SET contact_id=' . $contact_id . ' WHERE poll_id="' . $poll_id . '"' ) === false )
			{
				echo 'Error:  Unable to update poll captain.';
				CRM_Utils_System::civiExit( );
			}
		}
	   else
	   {
		   if ( $dao->query( 'UPDATE regpoll_poll SET contact_id=NULL WHERE poll_id="' . $poll_id . '" AND contact_id=' . $contact_id ) === false )
		   {
			   echo 'Error:  Unable to update poll captain.';
			   CRM_Utils_System::civiExit( );
		   }
		}

		if ( max( $shifts ) == 0 )
		{
			$dao->query( 'DELETE FROM civicrm_value_election_day_tabling_54 WHERE id=' . $custom_id );
			$dao->query( 'DELETE FROM civicrm_participant WHERE id=' . $participant_id );

			echo 'Registration deleted.';
			CRM_Utils_System::civiExit( );
		}

		if ( $new_status_id != $old_status_id )
		{
			$sql = 'UPDATE civicrm_participant SET status_id = ' . $new_status_id . ' WHERE id = ' . $participant_id;
			if ( $dao->query( $sql ) === false )
			{
				echo 'Error:  Unable to update shift status.';
				CRM_Utils_System::civiExit( );
			}
		}

		if ( !self::shifts_match( $shifts[1], $shifts[2], $shifts[3], 0, $dao->shift_1_378 ) ||
			  !self::shifts_match( $shifts[4], $shifts[5], $shifts[6], 0, $dao->shift_2_379 ) ||
			  !self::shifts_match( $shifts[7], $shifts[8], $shifts[9], $shifts[10], $dao->shift_3_380 ) ||
			  !self::shifts_match( $shifts[11], $shifts[12], $shifts[13], 0, $dao->shift_4_381 ) )
		{
			$d1 = self::format_shifts( $shifts[1],$shifts[2],$shifts[3],0 );
			$d2 = self::format_shifts( $shifts[4],$shifts[5],$shifts[6],0 );
			$d3 = self::format_shifts( $shifts[7],$shifts[8],$shifts[9],$shifts[10] );
			$d4 = self::format_shifts( $shifts[11],$shifts[12],$shifts[13],0 );

			$sql = 'UPDATE civicrm_value_election_day_tabling_54 SET ';
			$sql .= 'shift_1_378=' . $d1 . ',';
			$sql .= 'shift_2_379=' . $d2 . ',';
			$sql .= 'shift_3_380=' . $d3 . ',';
			$sql .= 'shift_4_381=' . $d4;
			$sql .= 'WHERE id=' . $custom_id;
			if ( $dao->query( $sql ) === false )
			{
				echo 'Error:  Unable to update shifts.';
				CRM_Utils_System::civiExit( );
			}
		}

		$dao->free( );
		
		CRM_Utils_System::civiExit( );
	}

	static function format_shifts( $s1, $s2, $s3, $s4 )
	{
		if ( $s1 || $s2 || $s3 || $s4 )
		{
			$rsp = 'concat(char(1)';
			if ( $s1 ) $rsp .= ',"1",char(1)';
			if ( $s2 ) $rsp .= ',"2",char(1)';
			if ( $s3 ) $rsp .= ',"3",char(1)';
			if ( $s4 ) $rsp .= ',"4",char(1)';
			$rsp .= ')';
		}
		else
			$rsp = '""';

		return( $rsp );
	}

	static function shifts_match( $s1, $s2, $s3, $s4, $db )
	{
		$s = '';

		if ( $s1 ) $s .= '1';
		if ( $s2 ) $s .= '2';
		if ( $s3 ) $s .= '3';
		if ( $s4 ) $s .= '4';

		return( $s == preg_replace( '[\D]', '', $db ) );
	}
}
