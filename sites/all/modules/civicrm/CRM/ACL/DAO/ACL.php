<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2019
 *
 * Generated from xml/schema/CRM/ACL/ACL.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:5dae38d9d5e128062b634a01ccfad2b0)
 */

/**
 * Database access object for the ACL entity.
 */
class CRM_ACL_DAO_ACL extends CRM_Core_DAO {

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_acl';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = FALSE;

  /**
   * Unique table ID
   *
   * @var int
   */
  public $id;

  /**
   * ACL Name.
   *
   * @var string
   */
  public $name;

  /**
   * Is this ACL entry Allow  (0) or Deny (1) ?
   *
   * @var bool
   */
  public $deny;

  /**
   * Table of the object possessing this ACL entry (Contact, Group, or ACL Group)
   *
   * @var string
   */
  public $entity_table;

  /**
   * ID of the object possessing this ACL
   *
   * @var int
   */
  public $entity_id;

  /**
   * What operation does this ACL entry control?
   *
   * @var string
   */
  public $operation;

  /**
   * The table of the object controlled by this ACL entry
   *
   * @var string
   */
  public $object_table;

  /**
   * The ID of the object controlled by this ACL entry
   *
   * @var int
   */
  public $object_id;

  /**
   * If this is a grant/revoke entry, what table are we granting?
   *
   * @var string
   */
  public $acl_table;

  /**
   * ID of the ACL or ACL group being granted/revoked
   *
   * @var int
   */
  public $acl_id;

  /**
   * Is this property active?
   *
   * @var bool
   */
  public $is_active;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_acl';
    parent::__construct();
  }

  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  public static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Dynamic(self::getTableName(), 'entity_id', NULL, 'id', 'entity_table');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('ACL ID'),
          'description' => ts('Unique table ID'),
          'required' => TRUE,
          'where' => 'civicrm_acl.id',
          'table_name' => 'civicrm_acl',
          'entity' => 'ACL',
          'bao' => 'CRM_ACL_BAO_ACL',
          'localizable' => 0,
        ],
        'name' => [
          'name' => 'name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('ACL Name'),
          'description' => ts('ACL Name.'),
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_acl.name',
          'table_name' => 'civicrm_acl',
          'entity' => 'ACL',
          'bao' => 'CRM_ACL_BAO_ACL',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
        ],
        'deny' => [
          'name' => 'deny',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Deny ACL?'),
          'description' => ts('Is this ACL entry Allow  (0) or Deny (1) ?'),
          'required' => TRUE,
          'where' => 'civicrm_acl.deny',
          'default' => '0',
          'table_name' => 'civicrm_acl',
          'entity' => 'ACL',
          'bao' => 'CRM_ACL_BAO_ACL',
          'localizable' => 0,
          'html' => [
            'type' => 'Radio',
          ],
        ],
        'entity_table' => [
          'name' => 'entity_table',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('ACL Entity'),
          'description' => ts('Table of the object possessing this ACL entry (Contact, Group, or ACL Group)'),
          'required' => TRUE,
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_acl.entity_table',
          'table_name' => 'civicrm_acl',
          'entity' => 'ACL',
          'bao' => 'CRM_ACL_BAO_ACL',
          'localizable' => 0,
        ],
        'entity_id' => [
          'name' => 'entity_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Entity ID'),
          'description' => ts('ID of the object possessing this ACL'),
          'where' => 'civicrm_acl.entity_id',
          'table_name' => 'civicrm_acl',
          'entity' => 'ACL',
          'bao' => 'CRM_ACL_BAO_ACL',
          'localizable' => 0,
        ],
        'operation' => [
          'name' => 'operation',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('ACL Operation'),
          'description' => ts('What operation does this ACL entry control?'),
          'required' => TRUE,
          'maxlength' => 8,
          'size' => CRM_Utils_Type::EIGHT,
          'where' => 'civicrm_acl.operation',
          'table_name' => 'civicrm_acl',
          'entity' => 'ACL',
          'bao' => 'CRM_ACL_BAO_ACL',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'callback' => 'CRM_ACL_BAO_ACL::operation',
          ],
        ],
        'object_table' => [
          'name' => 'object_table',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('ACL Object'),
          'description' => ts('The table of the object controlled by this ACL entry'),
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_acl.object_table',
          'table_name' => 'civicrm_acl',
          'entity' => 'ACL',
          'bao' => 'CRM_ACL_BAO_ACL',
          'localizable' => 0,
        ],
        'object_id' => [
          'name' => 'object_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('ACL Object ID'),
          'description' => ts('The ID of the object controlled by this ACL entry'),
          'where' => 'civicrm_acl.object_id',
          'table_name' => 'civicrm_acl',
          'entity' => 'ACL',
          'bao' => 'CRM_ACL_BAO_ACL',
          'localizable' => 0,
        ],
        'acl_table' => [
          'name' => 'acl_table',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('ACL Table'),
          'description' => ts('If this is a grant/revoke entry, what table are we granting?'),
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_acl.acl_table',
          'table_name' => 'civicrm_acl',
          'entity' => 'ACL',
          'bao' => 'CRM_ACL_BAO_ACL',
          'localizable' => 0,
        ],
        'acl_id' => [
          'name' => 'acl_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('ACL Group ID'),
          'description' => ts('ID of the ACL or ACL group being granted/revoked'),
          'where' => 'civicrm_acl.acl_id',
          'table_name' => 'civicrm_acl',
          'entity' => 'ACL',
          'bao' => 'CRM_ACL_BAO_ACL',
          'localizable' => 0,
        ],
        'is_active' => [
          'name' => 'is_active',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('ACL Is Active?'),
          'description' => ts('Is this property active?'),
          'where' => 'civicrm_acl.is_active',
          'table_name' => 'civicrm_acl',
          'entity' => 'ACL',
          'bao' => 'CRM_ACL_BAO_ACL',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
          ],
        ],
      ];
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
  public static function &fieldKeys() {
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
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'acl', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'acl', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [
      'index_acl_id' => [
        'name' => 'index_acl_id',
        'field' => [
          0 => 'acl_id',
        ],
        'localizable' => FALSE,
        'sig' => 'civicrm_acl::0::acl_id',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
