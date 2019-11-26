<?php

class MprcCiviMigrateContributionPayment extends MprcCiviMigrate {
  public function __construct(array $arguments) {

    // Always call the parent constructor first for basic setup.
    parent::__construct($arguments);

    // Human-friendly description of your migration process.
    $this->description = t('Migrate matches pledges for Recurring contributions & puts data in right places');

    // Create a map object for tracking the relationships between source rows
    $this->map = new MigrateSQLMap($this->machineName,
        array(
          'id' => array(
            'type' => 'int',
            'length' => 10,
            'unsigned' => TRUE,
            'not null' => TRUE,
            'alias'   => 'c',
          )
        ),
        MigrateDestinationTerm::getKeySchema()
      );

    // We are getting data from tables in the Drupal default database - first,
    // set up a query for this data.
    $query = db_select($this->civiDB . 'civicrm_contribution', 'c')
      ->fields('c', array(
        'id', 'contribution_status_id', 'total_amount'
      ));

    $query->leftJoin($this->civiDB . 'civicrm_contribution_recur', 'cr', 'cr.id = c.contribution_recur_id AND c.contribution_status_id = 1');
    $query->leftJoin($this->civiDB . 'civicrm_pledge_payment', 'pp', 'c.id = pp.contribution_id');
    $query->fields('pv', array('entity_id'));
    $query->leftJoin($this->civiDB . 'civicrm_value_pledge_4', 'pv', 'cr.id = pv.recurring_credit_card_payment_198');
    $query->distinct();
    $query->where("start_date > '2011-08-01'");
    // Adding condition to join  was slow.
    $query->isNotNull('c.contribution_recur_id');
    // Adding condition to join  was slow.
    $query->isNull('pp.pledge_id');

    // Create a MigrateSource object, which manages retrieving the input data.
    $this->source = new MigrateSourceSQL($query);
    $this->dependencies = array('MprcCiviMigratePledge');
    // Set up our destination.
    $this->destination = new MigrateDestinationCivicrmApi('pledge_payment');
    $this->addFieldMapping('contribution_id', 'id');
    $this->addFieldMapping('pledge_id', 'entity_id');
    $this->addFieldMapping('actual_amount', 'total_amount');
    $this->addFieldMapping('status_id','contribution_status_id');
  }
  /*
   * Inwards handling on address creation
   */
  public function prepare(&$entity, &$row) {

  }
}
