<?php

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2010
 *            $Id$
 *
 */
class CRM_Report_Form_Contribute_BookkeepingModified extends CRM_Report_Form {
  protected $_addressField = false;
  protected $_emailField = false;
  protected $_summary = null;
  protected $_customGroupExtends = array(
    'Pledge'
  );
  function __construct() {
    $this->_columns = array(
      'civicrm_contact' => array(
        'dao' => 'CRM_Contact_DAO_Contact',
        'fields' => array(
          'display_name' => array(
            'title' => ts('Contact Name'),
            'required' => TRUE,
            'no_repeat' => TRUE
          ),
          'id' => array(
            'no_display' => TRUE,
            'required' => TRUE
          )
        ),
        'filters' => array(
          'sort_name' => array(
            'title' => ts('Contact Name'),
            'operator' => 'like'
          ),
          'id' => array(
            'title' => ts('Contact ID'),
            'no_display' => TRUE
          )
        ),
        'grouping' => 'contact-fields'
      ),

      'civicrm_membership' => array(
        'dao' => 'CRM_Member_DAO_Membership',
        'fields' => array(
          'id' => array(
            'title' => ts('Membership #'),
            'no_display' => TRUE,
            'required' => TRUE,
          ),
        ),
      ),

      'civicrm_contribution' => array(
        'dao' => 'CRM_Contribute_DAO_Contribution',
        'fields' => array(
          'receive_date' => array(
            'default' => TRUE,
            'type' => CRM_Utils_Type::T_DATE,
          ),
          'total_amount' => array(
            'title' => ts('Amount'),
            'required' => TRUE,
            'statistics' => array(
              'sum' => ts('Amount'),
            ),
          ),
          'financial_type_id' => array(
            'title' => ts('Financial Type'),
            'default' => TRUE,
          ),
          'trxn_id' => array(
            'title' => ts('Trans #'),
            'default' => TRUE,
          ),
          'invoice_id' => array(
            'title' => ts('Invoice ID'),
            'default' => TRUE,
          ),
          'check_number' => array(
            'title' => ts('Cheque #'),
            'default' => TRUE,
          ),
          'payment_instrument_id' => array(
            'title' => ts('Payment Instrument'),
            'default' => TRUE,
          ),
          'contribution_status_id' => array(
            'title' => ts('Status'),
            'default' => TRUE,
          ),
          'id' => array(
            'title' => ts('Contribution #'),
            'default' => true
          )
        ),
        'filters' => array(
          'receive_date' => array(
            'operatorType' => CRM_Report_Form::OP_DATE,
          ),
          'financial_type_id' => array(
            'title' => ts('Financial Type'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Contribute_PseudoConstant::financialType(),
          ),
          'contribution_status_id' => array(
            'title' => ts('Contribution Status'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Contribute_PseudoConstant::contributionStatus(),
            'default' => array(
              1,
            ),
          ),
          'total_amount' => array(
            'title' => ts('Contribution Amount')
          ),
        ),
        'grouping' => 'contri-fields',
      ),
      'civicrm_pledge' => array(
        'dao' => 'CRM_Pledge_DAO_Pledge',
        'fields' => array(
          'amount' => array(
            'title' => ts('Total Amount'),
            'required' => FALSE,
          ),
        ),
      ),
      'civicrm_financial_trxn' => array(
        'dao' => 'CRM_Financial_DAO_FinancialTrxn',
        'fields' => array(
          'payment_processor_id' => array(
            'title' => ts('Payment Processor'),
          ),
        ),
        'filters' => array(
          'payment_processor_id' => array(
            'title' => ts('Payment Processor'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Utils_Array::collect('name', CRM_Financial_BAO_PaymentProcessor::getAllPaymentProcessors('live')),
            'default' => NULL,
            'type' => CRM_Utils_Type::T_STRING,
          ),
        ),
      ),

    );

    parent::__construct();
  }
  public function preProcess() {
    parent::preProcess();
  }
  function select() {
    $select = array();

    $this->_columnHeaders = array();
    foreach ($this->_columns as $tableName => $table) {
      if (array_key_exists('fields', $table)) {
        foreach ($table['fields'] as $fieldName => $field) {
          if (CRM_Utils_Array::value('required', $field) || CRM_Utils_Array::value($fieldName, $this->_params['fields'])) {

            $select[] = "{$field['dbAlias']} as {$tableName}_{$fieldName}";
            $this->_columnHeaders["{$tableName}_{$fieldName}"]['title'] = $field['title'];
            $this->_columnHeaders["{$tableName}_{$fieldName}"]['type'] = CRM_Utils_Array::value('type', $field);
          }
        }
      }
    }

    $this->_select = "SELECT " . implode(', ', $select) . " ";
  }

  /**
   * From Clause
   */
  public function from() {
    $this->_from = null;

    $this->_from = "
        FROM  civicrm_contact      {$this->_aliases['civicrm_contact']} {$this->_aclFrom}
              INNER JOIN civicrm_contribution {$this->_aliases['civicrm_contribution']}
                      ON {$this->_aliases['civicrm_contact']}.id = {$this->_aliases['civicrm_contribution']}.contact_id AND {$this->_aliases['civicrm_contribution']}.is_test = 0
              LEFT JOIN civicrm_membership_payment payment
                        ON ( {$this->_aliases['civicrm_contribution']}.id = payment.contribution_id )
              LEFT JOIN civicrm_membership {$this->_aliases['civicrm_membership']}
                    ON payment.membership_id = {$this->_aliases['civicrm_membership']}.id
              LEFT JOIN civicrm_pledge_payment pp on pp.contribution_id = {$this->_aliases['civicrm_contribution']}.id
               LEFT JOIN civicrm_pledge {$this->_aliases['civicrm_pledge']} on  {$this->_aliases['civicrm_pledge']}.id = pp.pledge_id  ";
    $this->addFinancialTrxnFromClause();
  }

  /**
   * Add Financial Transaction into From Table if required.
   */
  public function addFinancialTrxnFromClause() {
    if ($this->isTableSelected('civicrm_financial_trxn')) {
      $this->_from .= "
         LEFT JOIN civicrm_entity_financial_trxn eftcc
           ON ({$this->_aliases['civicrm_contribution']}.id = eftcc.entity_id AND
             eftcc.entity_table = 'civicrm_contribution' AND {$this->_aliases['civicrm_contribution']}.total_amount = eftcc.amount)
         LEFT JOIN civicrm_financial_trxn {$this->_aliases['civicrm_financial_trxn']}
           ON {$this->_aliases['civicrm_financial_trxn']}.id = eftcc.financial_trxn_id \n";
    }
  }

  function groupBy() {
    $this->_groupBy = "";
  }
  function orderBy() {
    $this->_orderBy = " ORDER BY {$this->_aliases['civicrm_contribution']}.id ";
  }
  function postProcess() {
    // get the acl clauses built before we assemble the query
    $this->buildACLClause($this->_aliases['civicrm_contact']);
    parent::postProcess();
  }
  function statistics(&$rows) {
    $statistics = parent::statistics($rows);

    $select = "
        SELECT COUNT({$this->_aliases['civicrm_contribution']}.total_amount ) as count,
               SUM( {$this->_aliases['civicrm_contribution']}.total_amount ) as amount,
               ROUND(AVG({$this->_aliases['civicrm_contribution']}.total_amount), 2) as avg
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
  function alterDisplay(&$rows) {
    // custom code to alter rows
    $checkList = array();
    $entryFound = false;
    $display_flag = $prev_cid = $cid = 0;
    $financialTypes = CRM_Contribute_PseudoConstant::financialType();
    $paymentInstruments = CRM_Contribute_PseudoConstant::paymentInstrument();

    foreach ($rows as $rowNum => $row) {

      // convert display name to links
      if (array_key_exists('civicrm_contact_display_name', $row) && CRM_Utils_Array::value('civicrm_contact_display_name', $rows[$rowNum]) && array_key_exists('civicrm_contact_id', $row)) {
        $url = CRM_Utils_System::url("civicrm/contact/view", 'reset=1&cid=' . $row['civicrm_contact_id'], $this->_absoluteUrl);
        $rows[$rowNum]['civicrm_contact_display_name_link'] = $url;
        $rows[$rowNum]['civicrm_contact_display_name_hover'] = ts("View Contact Summary for this Contact.");
      }

      // handle contribution status id
      if (array_key_exists('civicrm_contribution_contribution_status_id', $row)) {
        if ($value = $row['civicrm_contribution_contribution_status_id']) {
          $rows[$rowNum]['civicrm_contribution_contribution_status_id'] = CRM_Contribute_PseudoConstant::contributionStatus($value);
        }
        $entryFound = TRUE;
      }

      // handle payment processor id
      if (array_key_exists('civicrm_financial_trxn_payment_processor_id', $row)) {
        if ($value = $row['civicrm_financial_trxn_payment_processor_id']) {
          $rows[$rowNum]['civicrm_financial_trxn_payment_processor_id'] = CRM_Core_DAO::getFieldValue('CRM_Financial_DAO_PaymentProcessor', $value, 'name');
        }
        $entryFound = TRUE;
      }

      // handle payment instrument id
      if (array_key_exists('civicrm_contribution_payment_instrument_id', $row)) {
        if ($value = $row['civicrm_contribution_payment_instrument_id']) {
          $rows[$rowNum]['civicrm_contribution_payment_instrument_id'] = $paymentInstruments[$value];
        }
        $entryFound = TRUE;
      }

      if ($value = CRM_Utils_Array::value('civicrm_contribution_financial_type_id', $row)) {
        $rows[$rowNum]['civicrm_contribution_financial_type_id'] = $financialTypes[$value];
        $entryFound = TRUE;
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
