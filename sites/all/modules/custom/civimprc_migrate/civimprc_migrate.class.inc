<?php

abstract class MprcCiviMigrate extends Civimigration {

  protected $table;
  protected $query;
  protected $civiDB;
  public function __construct(array $arguments) {

    parent::__construct($arguments);
    $this->civiDB = 'mprc.';
    $this->issuePattern = 'http://redmine.fuzion.co.nz/issues/:id:';
    module_load_include('inc', 'civimigrate', 'destinations/civicrm_api');
  }

 }
