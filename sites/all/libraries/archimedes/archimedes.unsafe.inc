<?php

/**
 * @file Archimedes collection methods that maybe unsafe to run
 *       at regular cron intervals.
 */

class ArchimedesDrupalUnsafeCollection {

  /**
   * The collection owl Archimedes Class.
   */
  protected $archimedes;

  /**
   * Initialize class. Associate archimedes class.
   */
  public function __construct(Archimedes $archimedes) {
    $this->archimedes = $archimedes;
  }

  /**
   * Collect hostname information from the server running this Drupal install.
   */
  public function servername() {
    $hostname = $this->unix_exec("hostname -f");
    $values = array();
    $values[] = archimedes_value($hostname,'nodereference')
      ->addNode(array('title' => $hostname, 'type' => 'host'));

    $this->archimedes
      ->createField('field_webserver', $values)
      ->invokeFacet();

    return $this;
  }

  /**
   * Collect database storage metrics.
   */
  public function database_size() {
    $size = 0;
    global $databases;
    foreach ($databases as $target => $connection) {
      $db_type = $databases[$target]['default']['driver'];
      db_set_active($target);
      switch ($db_type) {
        case 'pgsql':
          $database_name = (string) $this->archimedes->getField('field_dbname');

          if (empty($database_name)) {
            throw Exception("No database name available to obtain PostgreSQL database size.");
          }

          $size += db_query("SELECT pg_database_size('" . $database_name . "')")->fetchField();
          break;

        case 'mysql':
        case 'mysqli':
          $rows = db_query('SHOW TABLE STATUS');
          foreach ($rows as $row) {
            foreach (array('data_length', 'Data_length', 'index_length', 'Index_length') as $value) {
              if (isset($row->$value)) {
                $size += $row->$value;
              }
            }
          }
          break;

      }
      db_set_active('default');
    }
    if ($size) {
      $this->archimedes
        ->createField('field_db_size', $size);
    }
    // Fail silently for other database types.
    return $this;
  }

  /**
   * Obtain storage usage data.
   */
  public function storage_usage() {
    $dataSize = preg_split('/[\s]+/', $this->unix_exec("du -bsL " . drupal_realpath('public://')));
    $rootSize = preg_split('/[\s]+/', $this->unix_exec("du -bsL " . DRUPAL_ROOT . base_path()));
    $this->archimedes->createField('field_sitedata', $dataSize[0]);
    $this->archimedes->createField('field_sitesize', $rootSize[0] - $dataSize[0]);
    return $this;
  }

  /**
   * Execute a shell command.
   *
   * Abstracted from Drush.
   */
  protected function unix_exec($cmd) {
    $args = func_get_args();

    //do not change the command itself, just the parameters.
    for ($x = 1; $x < sizeof($args); $x++) {
      $args[$x] = escapeshellarg($args[$x]);
    }
    $command = call_user_func_array('sprintf', $args);

    exec($command . ' 2>&1', $output, $result);

    return $output[0];
  }
}
