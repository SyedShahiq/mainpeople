<?php

/********************************************/
/* Copyright Michael Labriola (c) 2012-2017 */
/********************************************/

require_once "CRM/Core/Form.php";

class CRM_Regpoll_Form_RegPollReport extends CRM_Core_Form
{
	function buildQuickForm( )
	{
		if ( empty( $_SESSION['CiviCRM']['userID'] ) )
		{
			echo 'Unauthorized';
			CRM_Utils_System::civiExit( );
		}

		$cols = [ 1 => 'Registered', 15 => 'Confirmed', 16 => 'Confirmed Twice', 5 => 'Staff' ];
				
		$dao = new CRM_Core_DAO( );

		$dao->query( 'SELECT organizer_id, organizer_name, shift_goal, status_id, shifts FROM regpoll_report_shifts_3 ORDER BY organizer_name' );

		$orgs = [ ];
		
		while ( $dao->fetch( ) )
		{
			$orgs[$dao->organizer_id]['name'] = $dao->organizer_name;
			$orgs[$dao->organizer_id]['goal'] = $dao->shift_goal;

			if ( !empty( $dao->shifts ) )
				$orgs[$dao->organizer_id]['shifts'][$dao->status_id] = $dao->shifts;
		}

		$html1 = $this->shift_table( $cols, $orgs );

		$html2 = '<h2>Polling Location Coverage</h2><table><thead><tr><td>Organizer</td><td class="center">Number of<br>Locations</td>';
		$html2 .= '<td class="center">No Shifts</td><td class="center">1 Shift</td><td class="center">2 Shifts</td><td class="center">3 Shift</td><td class="center">4 Shifts</td></tr></thead>';

		$dao->query( 'SELECT organizer_id, organizer_name, location_goal, 0shift, 1shift, 2shift, 3shift, 4shift FROM regpoll_report_sigs_4 ORDER BY organizer_name' );

		$orgs = [ ];

		$goal_total = $total[0] = $total[1] = $total[2] = $total[3] = $total[4] = 0;

		while ( $dao->fetch( ) )
		{
			$html2 .= '<tr><td>' . $dao->organizer_name . '</td><td class="center">' . number_format( $dao->location_goal ) . '</td>';
			$goal_total += $dao->location_goal;

			for ( $i = 0; $i <= 4; $i++ )
				if ( empty( $dao->{$i . 'shift'} ) )
					$html2 .= '<td></td>';
				else
				{
					$html2 .= '<td class="center">' . number_format( $dao->{$i . 'shift'} ) . '<br>' . ( empty( $dao->location_goal ) ? '' : number_format( 100 * $dao->{$i . 'shift'} / $dao->location_goal ) . '%' ) . '</td>';
					$total[$i] += $dao->{$i . 'shift'};
				}
		}

		$html2 .= '<tr><td>Total</td><td class="center">' . number_format( $goal_total ) . '</td>';

		for ( $i = 0; $i <= 4; $i++ )
			$html2 .= '<td class="center">' . number_format( $total[$i] ) . '<br>' . number_format( 100 * $total[$i] / $goal_total ) . '%</td>';

		$html2 .= '</table>';

		$dao->free( );

		$html3 = '<h2>Anticipated Signatures Based on Location Coverage</h2><table><thead><tr><td>Organizer</td><td class="center">Goal</td><td class="center">Signatures</td><td class="center">% of Goal</td></tr></thead>';

		$dao->query( 'SELECT organizer_id, organizer_name, goal, sigs FROM regpoll_report_sigs_4 ORDER BY organizer_name' );

		$orgs = [ ];

		$goal_total = $sig_total = 0;

		while ( $dao->fetch( ) )
		{
			$html3 .= '<tr><td>' . $dao->organizer_name . '</td><td class="center">' . number_format( $dao->goal ) . '</td>';
			$goal_total += $dao->goal;

			if ( empty( $dao->sigs ) )
				$html3 .= '<td></td><td></td>';
			else
			{
				$html3 .= '<td class="center">' . number_format( $dao->sigs ) . '</td><td class="center">' . ( empty( $dao->goal ) ? '' : number_format( 100 * $dao->sigs / $dao->goal ) . '%' ) . '</td>';
				$sig_total += $dao->sigs;
			}
		}

		$html3 .= '<tr><td>Total</td><td class="center">' . number_format( $goal_total ) . '</td>';
		$html3 .= '<td class="center">' . number_format( $sig_total ) . '</td><td class="center">' . number_format( 100 * $sig_total / $goal_total ) . '%</td>';

		$html3 .= '</table>';

		$dao->free( );

		$this->assign( 'html1', $html1 );
		$this->assign( 'html2', $html2 );
		$this->assign( 'html3', $html3 );

		$title = 'Polling Location Summary';

		CRM_Utils_System::setTitle( $title );
	}

	private function shift_table( $cols, $orgs )
	{
		$html = '<h2>Scheduled Shifts</h2><table><thead><tr><td>Organizer</td><td class="center">Shift Goal</td>';

		foreach ( $cols as $id => $col )
			$html .= '<td class="center">' . $col . '</td>';

		$html .= '<td class="center">Total Shifts</td></tr></thead>';

		$total_goal = 0;
		$total_total = 0;

		foreach ( $cols as $id => $col )
			$total_col[$id] = 0;
		
		foreach ( $orgs as $org_id => $org )
		{
			$html .= '<tr><td>' . $org['name'] . '</td><td class="center">' . number_format( $org['goal'], 0 ) . '</td>';

			$total = 0;

			foreach ( $cols as $id => $col )
				if ( !empty( $org['shifts'][$id] ) )
				$total += $org['shifts'][$id];

			foreach ( $cols as $id => $col )
				if ( empty( $org['shifts'][$id] ) )
					$html .= '<td></td>';
				else
				{
					$html .= '<td class="center">' . number_format( $org['shifts'][$id] ) . ( empty( $org['goal'] ) ? '' : '<br>' . number_format( 100 * $org['shifts'][$id] / $org['goal'], 0 ) . '%' ) . '</td>';
					$total_col[$id] += $org['shifts'][$id];
				}

			$html .= '<td class="center">' . number_format( $total ) . ( empty( $org['goal'] ) ? '' : '<br>' . number_format( 100 * $total / $org['goal'], 0 ) . '%' ) . '</td>';
			$html .= '</tr>';

			$total_goal += $org['goal'];
			$total_total += $total;
		}

		$html .= '<tr><td>Total</td><td class="center">' . number_format( $total_goal, 0 ) . '</td>';

		foreach ( $cols as $id => $col )
			if ( empty( $total_col[$id] ) )
				$html .= '<td></td>';
			else
				$html .= '<td class="center">' . number_format( $total_col[$id] ) . '<br>' . number_format( 100 * $total_col[$id] / $total_goal, 0 ) . '%</td>';

		$html .= '<td class="center">' . number_format( $total_total, 0 ) . '<br>' . number_format( 100 * $total_total / $total_goal, 0 ) . '%</td>';

		$html .= '</tr></table>';

		return $html;
	}
}

