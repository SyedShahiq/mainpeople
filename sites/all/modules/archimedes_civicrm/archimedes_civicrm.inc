<?php

/**
 * @file
 */

/**
 * Report usage of (enabled?) CiviCRM extensions.
 */
function archimedes_civicrm_get_extensions() {
  if (module_exists('civicrm')) {
    $values = array();

    civicrm_initialize();
    $mapper = CRM_Extension_System::singleton()->getMapper();
    $manager = CRM_Extension_System::singleton()->getManager();
    $enabled = array_keys(array_filter($manager->getStatuses(), function($status) { return $status == 'installed'; }));

    foreach ($enabled as $key) {
      try {
        $obj = $mapper->keyToInfo($key);
        $data = CRM_Admin_Page_Extensions::createExtendedInfo($obj);
        $node = [
          'title' => '(unknown)',
          'body' => '',
        ];
        $mapping = [
          // Archimedes field => CiviCRM field.
          'title' => 'name',
          'name' => 'name',
          'type' => 'type',
          'field_key' => 'key',
          'field_version' => 'version',
          'field_stage' => 'develStage',
          'field_status' => 'status',
          'field_release_date' => 'releaseDate',
          'body' => 'description',
          // 'compatibility' => 'compatibility',
          // 'urls' => 'urls',
        ];
        foreach ($mapping as $k => $v) {
          $node[$k] = isset($data[$v]) ? $data[$v] : '';
        }

        // Deeper values.
        if (isset($data['compatibility'])) {
          $node['body'] .= PHP_EOL . PHP_EOL;
          if (is_array($data['compatibility']['ver'])) {
            $node['compatibility'] = implode(', ', $data['compatibility']['ver']);
            // @TODO Better in Drupal as field values not a commasep string?
            $node['field_compatibility'] = implode(', ', $data['compatibility']['ver']);
          }
          else {
            $node['compatibility'] = $data['compatibility']['ver'];
            $node['field_compatibility'] = $data['compatibility']['ver'];
          }
          $node['body'] .= "* CiviCRM versions supported: ${node['compatibility']}" . PHP_EOL;
        }

        if (isset($data['urls']) && is_array($data['urls'])) {
          $node['body'] .= PHP_EOL . PHP_EOL;
          foreach ($data['urls'] as $k => $v) {
            $node['body'] .= "* ${k}: ${v}" . PHP_EOL;
          }
        }
        $values[] = archimedes_value($node['title'], 'civicrm_extension')
          ->addNode($node);

      }
      catch (CRM_Extension_Exception $ex) {
        watchdog('archimedes_civicrm', 'Failed to read extension info for %key.', array('%key' => $key));
        continue;
      }
    }
    if (!empty($values)) {
      return $values;
    }
  }
}

/**
 * Class Archimedes_civicrm_extension
 */
class Archimedes_civicrm_extension extends Archimedes_nodereference {

    public function __construct($value) {
        $this->ns = 'monitor-plugin:civicrm-extension';
        parent::__construct($value);
    }

    public function toArray() {
        return array(
          'name' => (string) $this->value,
          'version' => $this->getAttributeNS('node:field_version'),
          'desc' => $this->getAttributeNS('node:body')
        );
    }

}
