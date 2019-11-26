<?php
/*
+--------------------------------------------------------------------+
| CiviCRM version 4.7                                                |
+--------------------------------------------------------------------+
| Copyright CiviCRM LLC (c) 2004-2017                                |
+--------------------------------------------------------------------+
| This file is a part of CiviCRM.                                    |
|                                                                    |
| CiviCRM is free software; you can copy, modify, and distribute it  |
| under the terms of the GNU Affero General Public License           |
| Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
|                                                                    |
| CiviCRM is distributed in the hope that it will be useful, but     |
| WITHOUT ANY WARRANTY; without even the implied warranty of         |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
| See the GNU Affero General Public License for more details.        |
|                                                                    |
| You should have received a copy of the GNU Affero General Public   |
| License and the CiviCRM Licensing Exception along                  |
| with this program; if not, contact CiviCRM LLC                     |
| at info[AT]civicrm[DOT]org. If you have questions about the        |
| GNU Affero General Public License or the licensing of CiviCRM,     |
| see the CiviCRM license FAQ at http://civicrm.org/licensing        |
+--------------------------------------------------------------------+
*/
/**
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2017
 *
 * Generated from xml/schema/CRM/SmsConversation/Contact.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:900b55f7a9ae47b8962ab3a9278006ec)
 */
require_once 'CRM/Core/DAO.php';
require_once 'CRM/Utils/Type.php';
/**
 * CRM_SmsConversation_DAO_Contact constructor.
 */
class CRM_SmsConversation_DAO_Contact extends CRM_Core_DAO {
  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  static $_tableName = 'civicrm_sms_conversation_contact';
  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var boolean
   */
  static $_log = true;
  /**
   * Unique SmsConversationContact ID
   *
   * @var int unsigned
   */
  public $id;
  /**
   * FK to civicrm_contact
   *
   * @var int unsigned
   */
  public $contact_id;
  /**
   * FK to SmsConversation ID
   *
   * @var int unsigned
   */
  public $conversation_id;
  /**
   * Conversation Status ID
   *
   * @var int unsigned
   */
  public $status_id;
  /**
   * FK to sms_conversation_question.id
   *
   * @var int unsigned
   */
  public $current_question_id;
  /**
   * Id of contact that started the conversation
   *
   * @var int unsigned
   */
  public $source_contact_id;
  /**
   * Record of all questions, answers
   *
   * @var longtext
   */
  public $conversation_record;
  /**
   * Date and time this SMS Conversation was scheduled.
   *
   * @var timestamp
   */
  public $scheduled_date;
  /**
   * Class constructor.
   */
  function __construct() {
    $this->__table = 'civicrm_sms_conversation_contact';
    parent::__construct();
  }
  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static ::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName() , 'contact_id', 'civicrm_contact', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName() , 'conversation_id', 'civicrm_sms_conversation', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName() , 'current_question_id', 'civicrm_sms_conversation_question', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName() , 'source_contact_id', 'civicrm_contact', 'id');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
  }
  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = array(
        'id' => array(
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => 'Unique SmsConversationContact ID',
          'required' => true,
          'table_name' => 'civicrm_sms_conversation_contact',
          'entity' => 'Contact',
          'bao' => 'CRM_SmsConversation_DAO_Contact',
          'localizable' => 0,
        ) ,
        'contact_id' => array(
          'name' => 'contact_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => 'FK to civicrm_contact',
          'required' => true,
          'table_name' => 'civicrm_sms_conversation_contact',
          'entity' => 'Contact',
          'bao' => 'CRM_SmsConversation_DAO_Contact',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
        ) ,
        'conversation_id' => array(
          'name' => 'conversation_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => 'FK to SmsConversation ID',
          'required' => true,
          'table_name' => 'civicrm_sms_conversation_contact',
          'entity' => 'Contact',
          'bao' => 'CRM_SmsConversation_DAO_Contact',
          'localizable' => 0,
          'FKClassName' => 'CRM_SmsConversation_DAO_Conversation',
        ) ,
        'status_id' => array(
          'name' => 'status_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => 'Conversation Status ID',
          'required' => true,
          'table_name' => 'civicrm_sms_conversation_contact',
          'entity' => 'Contact',
          'bao' => 'CRM_SmsConversation_DAO_Contact',
          'localizable' => 0,
          'pseudoconstant' => array(
            'optionGroupName' => 'sms_conversation_status_type',
            'optionEditPath' => 'civicrm/admin/options/sms_conversation_status_type',
          )
        ) ,
        'current_question_id' => array(
          'name' => 'current_question_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => 'FK to sms_conversation_question.id',
          'table_name' => 'civicrm_sms_conversation_contact',
          'entity' => 'Contact',
          'bao' => 'CRM_SmsConversation_DAO_Contact',
          'localizable' => 0,
          'FKClassName' => 'CRM_SmsConversation_DAO_Question',
        ) ,
        'source_contact_id' => array(
          'name' => 'source_contact_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => 'Id of contact that started the conversation',
          'table_name' => 'civicrm_sms_conversation_contact',
          'entity' => 'Contact',
          'bao' => 'CRM_SmsConversation_DAO_Contact',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
        ) ,
        'conversation_record' => array(
          'name' => 'conversation_record',
          'type' => CRM_Utils_Type::T_LONGTEXT,
          'title' => ts('Record of conversation') ,
          'description' => 'Record of all questions, answers',
          'table_name' => 'civicrm_sms_conversation_contact',
          'entity' => 'Contact',
          'bao' => 'CRM_SmsConversation_DAO_Contact',
          'localizable' => 0,
          'html' => array(
            'type' => 'Text',
          ) ,
        ) ,
        'scheduled_date' => array(
          'name' => 'scheduled_date',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => ts('SMS Conversation Scheduled Date') ,
          'description' => 'Date and time this SMS Conversation was scheduled.',
          'required' => false,
          'default' => 'CURRENT_TIMESTAMP',
          'table_name' => 'civicrm_sms_conversation_contact',
          'entity' => 'Contact',
          'bao' => 'CRM_SmsConversation_DAO_Contact',
          'localizable' => 0,
          'html' => array(
            'type' => 'Select Date',
            'formatType' => 'activityDateTime',
          ) ,
        ) ,
      );
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }
  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }
  /**
   * Returns the names of this table
   *
   * @return string
   */
  static function getTableName() {
    return self::$_tableName;
  }
  /**
   * Returns if this table needs to be logged
   *
   * @return boolean
   */
  function getLog() {
    return self::$_log;
  }
  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  static function &import($prefix = false) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'sms_conversation_contact', $prefix, array());
    return $r;
  }
  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  static function &export($prefix = false) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'sms_conversation_contact', $prefix, array());
    return $r;
  }
  /**
   * Returns the list of indices
   */
  public static function indices($localize = TRUE) {
    $indices = array();
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }
}
