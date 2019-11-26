<?php

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2010
 *            $Id$
 *
 */

class CRM_Report_Form_Contribute_CommissionDetail extends CRM_Report_Form {
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
            'no_repeat' => true
          ),
          'id' => array(
            'no_display' => true,
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
            'title' => ts('Contribution ID (links to contribution record)')
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
          ),
          'id' => array(
            'default' => true,
            'title' => ts('Contribution ID')
          )
        ),
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
  function select() {
    $select = array();

    $this->_columnHeaders = array();
    // add worker first so it comes first
    $this->_columnHeaders['civicrm_value_contribution_data_3_worker_select_50'] = array(
      'title' => 'Worker'
    )
    ;
    foreach ($this->_columns as $tableName => $table) {
      if (array_key_exists('fields', $table)) {
        foreach ($table['fields'] as $fieldName => $field) {
          if (CRM_Utils_Array::value('required', $field) || CRM_Utils_Array::value($fieldName, $this->_params['fields'])) {
            if ($tableName == 'civicrm_address') {
              $this->_addressField = true;
            }
            else if ($tableName == 'civicrm_email') {
              $this->_emailField = true;
            }

            // only include statistics columns if set
            if (CRM_Utils_Array::value('statistics', $field)) {
              foreach ($field['statistics'] as $stat => $label) {
                switch (strtolower($stat)) {
                  case 'sum' :
                    $select[] = "SUM({$field['dbAlias']}) as {$tableName}_{$fieldName}_{$stat}";
                    $this->_columnHeaders["{$tableName}_{$fieldName}_{$stat}"]['title'] = $label;
                    $this->_columnHeaders["{$tableName}_{$fieldName}_{$stat}"]['type'] = $field['type'];
                    $this->_statFields[] = "{$tableName}_{$fieldName}_{$stat}";
                    break;
                  case 'count' :
                    $select[] = "COUNT({$field['dbAlias']}) as {$tableName}_{$fieldName}_{$stat}";
                    $this->_columnHeaders["{$tableName}_{$fieldName}_{$stat}"]['title'] = $label;
                    $this->_statFields[] = "{$tableName}_{$fieldName}_{$stat}";
                    break;
                  case 'avg' :
                    $select[] = "ROUND(AVG({$field['dbAlias']}),2) as {$tableName}_{$fieldName}_{$stat}";
                    $this->_columnHeaders["{$tableName}_{$fieldName}_{$stat}"]['type'] = $field['type'];
                    $this->_columnHeaders["{$tableName}_{$fieldName}_{$stat}"]['title'] = $label;
                    $this->_statFields[] = "{$tableName}_{$fieldName}_{$stat}";
                    break;
                }
              }
            }
            else {
              $select[] = "{$field['dbAlias']} as {$tableName}_{$fieldName}";
              $this->_columnHeaders["{$tableName}_{$fieldName}"]['title'] = $field['title'];
              $this->_columnHeaders["{$tableName}_{$fieldName}"]['type'] = CRM_Utils_Array::value('type', $field);
            }
          }
        }
      }
    }
    // add custom fields manually here
    $this->_columnHeaders['civicrm_value_contribution_data_3_commission__dollars__183'] = array(
      'title' => 'Commission',
      'type' => 1024
    );
    $select[] = "value_contribution_data_3_civireport.commission__dollars__183 as civicrm_value_contribution_data_3_commission__dollars__183";
    $select[] = "value_contribution_data_3_civireport.worker_select_50 as civicrm_value_contribution_data_3_worker_select_50";
    $this->_select = "SELECT " . implode(', ', $select) . " ";
  }
  function from() {
    $this->_from = null;

    $this->_from = "
        FROM  civicrm_contact      {$this->_aliases['civicrm_contact']} {$this->_aclFrom}
              INNER JOIN civicrm_contribution {$this->_aliases['civicrm_contribution']}
                      ON {$this->_aliases['civicrm_contact']}.id = {$this->_aliases['civicrm_contribution']}.contact_id AND {$this->_aliases['civicrm_contribution']}.is_test = 0
              INNER JOIN civicrm_value_contribution_data_3 value_contribution_data_3_civireport
                      ON contribution_civireport.id = value_contribution_data_3_civireport.entity_id

";
  }
  function groupBy() {
  }
  function orderBy() {
    $this->_orderBy = " ORDER BY contribution_civireport.receive_date ";
  }
  function statistics(&$rows) {
    $statistics = parent::statistics($rows);

    $select = "
        SELECT COUNT(value_contribution_data_3_civireport .commission__dollars__183  ) as count,
               SUM( value_contribution_data_3_civireport .commission__dollars__183 ) as amount,
               ROUND(AVG(value_contribution_data_3_civireport.commission__dollars__183), 2) as avg
        ";

    $sql = "{$select} {$this->_from} {$this->_where}";
    $dao = CRM_Core_DAO::executeQuery($sql);

    if ($dao->fetch()) {
      $statistics['counts']['amount'] = array(
        'value' => $dao->amount,
        'title' => 'Total Amount',
        'type' => CRM_Utils_Type::T_MONEY
      );
      $statistics['counts']['avg'] = array(
        'value' => $dao->avg,
        'title' => 'Average',
        'type' => CRM_Utils_Type::T_MONEY
      );
    }

    return $statistics;
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
      $clause = "AND worker_select_50 LIKE '%$worker%'";
      $this->_where = $this->_where . $clause;
    }
  }
  function addFilters() {
    $this->_filters['civicrm_value_contribution_data_3'] = array(
      'worker_select_50' => Array(

        'title' => 'Worker',
        'operator' => 'like',
        'name' => 'worker_select_50',
        'type' => 2,
        'maxlength' => 128,
        'size' => 45,
        'export' => 1,
        'where' => 'civicrm_value_contribution_data_3.worker_select_50',

        'alias' => 'value_contribution_data_3_civireport',
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
      if (!empty($this->_noRepeats) && $this->_outputMode != 'csv') {
        // don't repeat contact details if its same as the previous row
        if (array_key_exists('civicrm_contact_id', $row)) {
          if ($cid = $row['civicrm_contact_id']) {
            if ($rowNum == 0) {
              $prev_cid = $cid;
            }
            else {
              if ($prev_cid == $cid) {
                $display_flag = 1;
                $prev_cid = $cid;
              }
              else {
                $display_flag = 0;
                $prev_cid = $cid;
              }
            }

            if ($display_flag) {
              foreach ($row as $colName => $colVal) {
                if (in_array($colName, $this->_noRepeats)) {
                  unset($rows[$rowNum][$colName]);
                }
              }
            }
            $entryFound = true;
          }
        }
      }

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
