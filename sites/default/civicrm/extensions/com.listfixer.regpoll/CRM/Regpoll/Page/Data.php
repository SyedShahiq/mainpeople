<?php

/********************************************/
/* Copyright Michael Labriola (c) 2012-2017 */
/********************************************/

class CRM_Regpoll_Page_Data
{
	static function export( )
	{
		if ( empty( $_SESSION['CiviCRM']['userID'] ) )
		{
			echo 'Unauthorized';
			CRM_Utils_System::civiExit( );
		}

		$t[7] = '7 am';
		$t[8] = '8 am';
		$t[9] = '9 am';
		$t[10] = '10 am';
		$t[11] = '11 am';
		$t[12] = 'Noon';
		$t[13] = '1 pm';
		$t[14] = '2 pm';
		$t[15] = '3 pm';
		$t[16] = '4 pm';
		$t[17] = '5 pm';
		$t[18] = '6 pm';
		$t[19] = '7 pm';
		$t[20] = '8 pm';

		$dao = new CRM_Core_DAO( );

		$data = [ ];

		$dao->query( 'SELECT poll_id, contact_id, organizer, organizer_phone, organizer_email, county, clerk_name, clerk_address, clerk_phone, clerk_email, ' .
				'poll_captain_name, poll_name, poll_address, opens, poll_turnout, poll_goal, ' .
				'vol_name, vol_address, vol_csz, vol_phone, vol_email, status, vol_hrs, ' .
				's1a, s1b, s1c, s2a, s2b, s2c, s3a, s3b, s3c, s3d, s4a, s4b, s4c FROM regpoll_export' );

		while ( $dao->fetch( ) )
		{
			$x = [ ];

			$x[7] = $dao->s1a;
			$x[8] = $dao->s1b;
			$x[9] = $dao->s1c;
			$x[10] = $dao->s2a;
			$x[11] = $dao->s2b;
			$x[12] = $dao->s2c;
			$x[13] = $dao->s3a;
			$x[14] = $dao->s3b;
			$x[15] = $dao->s3c;
			$x[16] = $dao->s3d;
			$x[17] = $dao->s4a;
			$x[18] = $dao->s4b;
			$x[19] = $dao->s4c;

			$started = 0;

			$list = [ ];

			for ( $i = 7; $i <= 19; $i++ )
			{
				if ( $x[$i] == '1' && !$started )
					$started = $i;

				if ( $x[$i] == '0' && $started )
				{
					$list[] = $t[$started] . ' to ' . $t[$i];
			        $started = 0;
				}
			}

			if  ( $started )
				$list[] = $t[$started] . ' to ' . $t[$i];
			
			$data[] = [
					$dao->poll_id,
					$dao->contact_id,
					$dao->organizer,
					self::format_phone( $dao->organizer_phone ),
					$dao->organizer_email,
					$dao->county,
					$dao->clerk_name,
					$dao->clerk_address,
					self::format_phone( $dao->clerk_phone ),
					$dao->clerk_email,
					$dao->poll_captain_name,
					$dao->poll_name,
					$dao->poll_address,
					$dao->opens,
					$dao->poll_turnout,
					$dao->poll_goal,
					$dao->vol_name,
					$dao->vol_address,
					$dao->vol_csz,
					self::format_phone( $dao->vol_phone ),
					$dao->vol_email,
					$dao->status,
					$dao->vol_hrs,
					round( $dao->poll_goal * $dao->vol_hrs / 13 ),
					$dao->shifts = implode( ', ', $list )
					];
		}

		require_once 'CRM/Core/Report/Excel.php';

		$cols = [ 'poll_id', 'contact_id', 'organizer', 'organizer_phone', 'organizer_email', 'county', 'clerk_name', 'clerk_address', 'clerk_phone', 'clerk_email',
				'poll_captain_name', 'poll_name', 'poll_address', 'opens', 'poll_turnout', 'poll_goal',
				'vol_name', 'vol_address', 'vol_csz', 'vol_phone', 'vol_email', 'status', 'vol_hrs', 'vol_goal', 'shifts' ];

		CRM_Core_Report_Excel::writeCSVFile( 'Polling Location Data ' . date( 'Y m d' ), $cols, $data );

		CRM_Utils_System::civiExit( );
	}

	private static function format_phone( $phone )
	{
		if ( empty( $phone ) ) return;

		$just_digits = preg_replace( '[\D]', '', $phone );

		if ( strlen( $just_digits ) == 11 && substr( $just_digits, 0, 1 ) == '1' ) $just_digits = substr( $just_digits, 1 );

		if ( strlen( $just_digits ) == 7 ) $just_digits = '207' . $just_digits;

		return '(' . substr( $just_digits, 0, 3 ) . ') ' . substr( $just_digits, 3, 3 ) . '-' . substr( $just_digits, 6, 4 );
	}
}
