<?php

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2010
 *            $Id$
 *
 */

class CRM_Report_Form_Contribute_PledgeCommissionDetail extends CRM_Report_Form_Contribute_PledgeCommission {
  protected $_addressField = false;
  protected $_emailField = false;
  protected $_summary = null;
  // The Entity defined in the line determines what custom fields are added to the report
  // protected $_customGroupExtends = array( 'Contribution' );
  function __construct() {
    $this->_columns = array(
      'civicrm_contact' => array(
        'dao' => 'CRM_Contact_DAO_Contact',
        'fields' => array(
          'display_name' => array(
            'title' => ts('Contact Name'),
            'required' => true,
            'no_repeats' => false
          ),
          'id' => array(
            'no_display' => false,
            'required' => true
          )
        ),
        'filters' => array(
          'sort_name' => array(
            'title' => ts('Contact Name'),
            'operator' => 'like'
          ),
          'id' => array(
            'title' => ts('Contact ID'),
            'no_display' => true
          )
        ),
        'grouping' => 'contact-fields'
      ),

      'civicrm_contribution' => array(
        'dao' => 'CRM_Contribute_DAO_Contribution',
        'fields' => array(
          'id' => array(
            'default' => true,
            'required' => true,
            'no_display' => false,
            'title' => ts('Contribution ID')
          ),
          'contribution_type_id' => array(
            'title' => ts('Contribution Type'),
            'default' => true
          ),
          'trxn_id' => null,
          'receive_date' => array(
            'default' => true
          ),
          'receipt_date' => null,
          'total_amount' => array(
            'default' => true
          )
        )
        ,
        'filters' => array(
          'receive_date' => array(
            'operatorType' => CRM_Report_Form::OP_DATE
          ),
          'contribution_type_id' => array(
            'title' => ts('Contribution Type'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Contribute_PseudoConstant::financialType()
          ),
          'contribution_status_id' => array(
            'title' => ts('Contribution Status'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Contribute_PseudoConstant::contributionStatus(),
            'default' => array(
              1
            )
          ),
          'total_amount' => array(
            'title' => ts('Contribution Amount')
          )
        ),
        'grouping' => 'contri-fields'
      ),

      'civicrm_group' => array(
        'dao' => 'CRM_Contact_DAO_GroupContact',
        'alias' => 'cgroup',
        'filters' => array(
          'gid' => array(
            'name' => 'group_id',
            'title' => ts('Group'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'group' => true,
            'options' => CRM_Core_PseudoConstant::group()
          )
        )
      )
    )
    ;

    $this->_tagFilter = true;
    parent::__construct();
  }
  function preProcess() {
    parent::preProcess();
  }
  function groupBy() {
    $this->_groupBy = " GROUP BY  contribution_civireport.id";
  }
  function orderBy() {
    $this->_orderBy = " ORDER BY contribution_civireport.receive_date ";
  }
  function select() {
    parent::select();
    $this->_select .= ", contribution_civireport .id as civicrm_contribution_id";
    $this->_columnHeaders['civicrm_contribution_id'] = array(
      'title' => 'Contribution ID'
    )
    ;
  }
  function postProcess() {
    // get the acl clauses built before we assemble the query
    $this->buildACLClause($this->_aliases['civicrm_contact']);
    parent::postProcess();
  }
  function where() {
    parent::where();
    $worker = CRM_Utils_Array::value('worker', $_GET);
    if ($worker) {
      $clause = "AND worker_select_51 LIKE '%$worker%'";
      $this->_where = $this->_where . $clause;
    }
  }
  function addFilters() {
    $this->_filters['civicrm_value_contribution_data_3'] = array(
      'worker_select_51' => Array(

        'title' => 'Worker',
        'operator' => 'like',
        'name' => 'worker_select_51',
        'type' => 2,
        'maxlength' => 128,
        'size' => 45,
        'export' => 1,
        'where' => 'p.worker_select_51',

        'alias' => 'civicrm_value_pledge_4_worker_select_51',
        'dbAlias' => 'contact_civireport.sort_name'
      )
    );
    parent::addFilters();
  }
  function alterDisplay(&$rows) {
    // custom code to alter rows
    $checkList = array();
    $entryFound = false;
    $display_flag = $prev_cid = $cid = 0;
    $financialTypes = CRM_Contribute_PseudoConstant::financialType();

    foreach ($rows as $rowNum => $row) {

      // handle state province
      if (array_key_exists('civicrm_address_state_province_id', $row)) {
        if ($value = $row['civicrm_address_state_province_id']) {
          $rows[$rowNum]['civicrm_address_state_province_id'] = CRM_Core_PseudoConstant::stateProvince($value, false);

          $url = CRM_Report_Utils_Report::getNextUrl('contribute/detail', "reset=1&force=1&" . "state_province_id_op=in&state_province_id_value={$value}", $this->_absoluteUrl, $this->_id);
          $rows[$rowNum]['civicrm_address_state_province_id_link'] = $url;
          $rows[$rowNum]['civicrm_address_state_province_id_hover'] = ts("List all contribution(s) for this State.");
        }
        $entryFound = true;
      }

      // handle country
      if (array_key_exists('civicrm_address_country_id', $row)) {
        if ($value = $row['civicrm_address_country_id']) {
          $rows[$rowNum]['civicrm_address_country_id'] = CRM_Core_PseudoConstant::country($value, false);

          $url = CRM_Report_Utils_Report::getNextUrl('contribute/detail', "reset=1&force=1&" . "country_id_op=in&country_id_value={$value}", $this->_absoluteUrl, $this->_id);
          $rows[$rowNum]['civicrm_address_country_id_link'] = $url;
          $rows[$rowNum]['civicrm_address_country_id_hover'] = ts("List all contribution(s) for this Country.");
        }

        $entryFound = true;
      }

      // convert display name to links
      if (array_key_exists('civicrm_contact_display_name', $row) && CRM_Utils_Array::value('civicrm_contact_display_name', $rows[$rowNum]) && array_key_exists('civicrm_contact_id', $row)) {
        $url = CRM_Utils_System::url("civicrm/contact/view", 'reset=1&cid=' . $row['civicrm_contact_id'], $this->_absoluteUrl);
        $rows[$rowNum]['civicrm_contact_display_name_link'] = $url;
        $rows[$rowNum]['civicrm_contact_display_name_hover'] = ts("View Contact Summary for this Contact.");
      }

      if ($value = CRM_Utils_Array::value('civicrm_contribution_contribution_type_id', $row)) {
        $rows[$rowNum]['civicrm_contribution_contribution_type_id'] = $financialTypes[$value];
        $entryFound = true;
      }
      if ($value = CRM_Utils_Array::value('civicrm_contribution_id', $row)) {
        $url = CRM_Utils_System::url("civicrm/contact/view/contribution?reset=1&id=" . $row['civicrm_contribution_id'] . "&cid=" . $row['civicrm_contact_id'] . "&action=view&context=contribution&selectedChild=contribute", 'reset=1&cid=' . $row['civicrm_contact_id'], $this->_absoluteUrl);
        $rows[$rowNum]['civicrm_contribution_id_link'] = $url;
        $rows[$rowNum]['civicrm_contribution_id_hover'] = ts("View Details of this Contribution.");
        $entryFound = true;
      }
      // skip looking further in rows, if first row itself doesn't
      // have the column we need
      if (!$entryFound) {
        break;
      }
      $lastKey = $rowNum;
    }
  }
}
