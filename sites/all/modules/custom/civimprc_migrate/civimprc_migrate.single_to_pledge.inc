<?php

class MprcCiviMigratePledgeSingle extends MprcCiviMigrate {
  public function __construct(array $arguments) {

    // Always call the parent constructor first for basic setup.
    parent::__construct($arguments);

    // Human-friendly description of your migration process.
    $this->description = t('Migrate creates / matches pledges for Recurring contributions & puts data in right places');

    // Create a map object for tracking the relationships between source rows
    $this->map = new MigrateSQLMap($this->machineName,
        array(
          'id' => array(
            'type' => 'int',
            'length' => 10,
            'unsigned' => TRUE,
            'not null' => TRUE,
            'alias'   => 'c',
          ),
        ),
        MigrateDestinationTerm::getKeySchema()
      );

    // We are getting data from tables in the Drupal default database - first,
    // set up a query for this data.
    $query = db_select($this->civiDB . 'civicrm_contribution', 'c')->fields(
      'c',
      array(
        'id',
        'contact_id',
        'total_amount',
        'receive_date',
        'source',
        'financial_type_id',
      ));

    $query->leftJoin($this->civiDB . 'civicrm_pledge_payment', 'pp', 'c.id = pp.contribution_id AND c.contribution_status_id = 1');
    $query->where(
      "receive_date > '2011-01-01'
      AND is_test = 0
      AND financial_type_id NOT IN('12', '14', '13', '19', '15', '4', '6')
      AND c.contribution_status_id <> 3"
    );
    // Adding condition to join was slow.
    $query->isNull('c.contribution_recur_id');
    // Adding condition to join was slow.
    $query->isNull('pp.pledge_id');
    $query->leftJoin($this->civiDB . 'civicrm_value_contribution_data_3', 'vc', 'c.id = vc.entity_id');
    $query->leftJoin($this->civiDB . 'civicrm_value_contribution_source', 'vs', 'c.id = vs.entity_id');
    // Create a MigrateSource object, which manages retrieving the input data.
    $this->source = new MigrateSourceSQL($query);

    // Set up our destination
    $this->destination = new MigrateDestinationCivicrmApi('pledge');
    $this->addFieldMapping('id', 'pledge_id')
                         ->issueGroup("Implementer Issues")
                         ->description("May not be available to map");

    $this->addFieldMapping('contact_id', 'contact_id');
    $this->addFieldMapping('pledge_financial_type_id', 'financial_type_id');
    $this->addFieldMapping('frequency_interval')->defaultValue('1');
    $this->addFieldMapping('frequency_unit')->defaultValue('month');
    $this->addFieldMapping('installments')->defaultValue('1');
    $this->addFieldMapping('start_date', 'receive_date');
    $this->addFieldMapping('pledge_create_date', 'receive_date');
    // Belt & braces.
    $this->addFieldMapping('create_date', 'receive_date');
    $this->addFieldMapping('currency')->defaultValue('USD');

    $this->addFieldMapping('pledge_original_installment_amount', 'total_amount');

    $this->addFieldMapping('custom_51', 'worker_select_50');
    $this->addFieldMapping('custom_57', 'donation_increase_197');
    $this->addFieldMapping('custom_55', 'commission_26');
    $this->addFieldMapping('custom_185', 'donee_organization_184');
    $this->addFieldMapping('custom_187', 'department_188');

    $this->addFieldMapping('custom_202', 'campaign_source_code');
    $this->addFieldMapping('custom_203', 'campaign_method');
  }

  /*
   * Inwards handling on address creation
   */
  public function prepare(&$entity, &$row) {

    $entity->original_installment_amount = $entity->pledge_original_installment_amount;
    $entity->amount = $entity->installments * $entity->original_installment_amount;

    // Calculate 'Donation increased By'.
    $prevPledges = civicrm_api('Pledge','Get',array('version' => 3,
                                                     'contact_id' => $entity->contact_id,
                                                     'sort' => 'start_date DESC',
                                                     'rowCount' => 2 ,
                                                     'sequential' => 1));
    if (isset($entity->id) && $prevPledges['values'][0]['pledge_id'] == $entity->id) {
      $prevPledge = $prevPledges['values'][1];
    }
    else {
     $prevPledge = isset($prevPledges['values'][0]) ? $prevPledges['values'][0] : array();
    }
 $prevPledgeAmount = isset($prevPledge['pledge_amount']) ? $prevPledge['pledge_amount'] : 0;
     if($prevPledgeAmount < $entity->amount && strtotime($prevPledge['pledge_create_date']) < strtotime($entity->pledge_create_date)){
       $entity->custom_57 = $entity->amount - $prevPledgeAmount;
       watchdog ("civimprcmigrate", "<pre>" . print_r($entity,true). print_r($prevPledges, true). "</pre>");
     }
     elseif($prevPledgeAmount = $entity->amount &&   strtotime($prevPledge['pledge_create_date']) < strtotime($params['pledge_create_date'])){
        $entity->custom_57 = 0;
     }
     if($prevPledges['count'] == 1 && $prevPledges['values'][0]['pledge_id'] == $params['id']){
        $entity->custom_57 = 0;
     }
     foreach ($entity as $field => $value){
       if (is_null($value)) {
         unset ($entity->$field);
       }
     }

    }
}
