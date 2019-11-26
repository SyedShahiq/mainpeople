<?php

/**
 * @file
 */

/**
 * Identify the deployed Git branch and hash, and a remote URL.
 */
function archimedes_extras_git_info() {
  // Get remotes, then get the URL of the first remote named
  // 'fuzion' or 'origin'.
  $output = array();
  exec('git config remote.origin.url', $output);
  if (!empty($output)) {
    $info['remote_url'] = reset($output);
  }
  else {
    exec('git config remote.fuzion.url', $output);
    if (!empty($output)) {
      $info['remote_url'] = reset($output);
    }
  }

  // Get the deployed state (commit and branch).
  $cmds = array(
    'commit_hash' => 'git rev-parse --short HEAD',
    'branch' => 'git rev-parse --abbrev-ref HEAD',
  );
  foreach ($cmds as $key => $cmd) {
    $output = array();
    // Get current commit hash.
    exec($cmd, $output);
    if (!empty($output)) {
      $info[$key] = reset($output);
    }
  }

  return $info;
}

/**
 * Find Git repos in the site root, report if there are unstaged /
 * uncommitted changes.
 */
function archimedes_extras_git_status() {
  $values = array();
  // Some tests. We ignore filemode changes where relevant.
  $tests = array(
    // Files not yet part of the repo.
    'new' => 'git ls-files --others --exclude-standard',
    // Files modified but not yet staged for commit.
    'unstaged' => 'git -c core.fileMode=false diff-files --name-status -r --ignore-submodules',
    // Files staged for commit but not committed.
    'uncommitted' => 'git -c core.fileMode=false diff-index --cached --name-status -r --ignore-submodules HEAD',
  );

  $prev_path = getcwd();
  $command = 'find . -name ".git" -type d';

  // Only show top level repo unless configured to show others.
  if (!variable_get('archimedes_extras_git_subrepos', FALSE)) {
    $command .= ' -maxdepth 1';
  }

  exec("$command 2>/dev/null", $output);
  foreach ($output as $path) {
    $found[] = realpath(dirname($path));
  }
  sort($found);
  foreach ($found as $directory) {
    $output = array();
    chdir($directory);
    foreach ($tests as $label => $test) {
      $output = array();
      exec($test, $output);
      if (!empty($output)) {
      /*
        $owners = array();
        foreach ($output as $file) {
          $owner = posix_getpwuid(fileowner($file));
          if (!in_array($owner, $owners)) {
            $owners[] = $owner;
          }
        }
      */
        $message = '<strong>!type files in !directory</strong>';
        $result[$directory][$label]['header'] = t($message, array(
                                                    '!directory' => $directory,
                                                    '!type' => $label,
                                                    // '%owners' => implode(', ', $owners),
                                                  ));
        $result[$directory][$label]['body'] = $output;
      }
    }
  }
  chdir($prev_path);
  if (!empty($result)) {
    foreach ($result as $directory => $reports) {
      foreach ($reports as $label => $report) {
        $values[] = $report['header'] . '<pre>' . implode("\n", $report['body']) . '</pre>';
      }
    }
  }
  return $values;
}

/**
 * Gets CiviCRM Git hash.
 */
function archimedes_extras_civicrm_gitinfo() {
  if (module_exists('civicrm')) {
    civicrm_initialize();
    global $civicrm_root;
    if (file_exists($civicrm_root . '/.git')) {
      $prev_path = getcwd();
      chdir($civicrm_root);

      $cmds = array(
        'commit_hash' => 'git rev-parse --short HEAD',
        'branch' => 'git rev-parse --abbrev-ref HEAD',
      );
      foreach ($cmds as $key => $cmd) {
        $output = array();
        // Get current commit hash.
        exec($cmd, $output);
        if (!empty($output)) {
          $info[$key] = reset($output);
        }
      }

      chdir($prev_path);
    }
  }
  if (!empty($info)) {
    return $info;
  }
}

/**
 * Report time since previous CiviCRM cron run.
 */
function archimedes_extras_civicrm_lastcron() {
  if (module_exists('civicrm')) {
    civicrm_initialize();

    $since = -1;
    if (class_exists('CRM_Core_DAO_StatusPreference')) {
      // I have no idea why the class would exist and not the table.
      // Maybe Fuzion 4.6-ish does this?
      $checkTableSql = "SHOW TABLES LIKE 'civicrm_status_pref'";
      $foundName = CRM_Core_DAO::singleValueQuery($checkTableSql);
      if ($foundName == 'civicrm_status_pref') {
        $statusPreference = new CRM_Core_DAO_StatusPreference();
        $statusPreference->domain_id = CRM_Core_Config::domainID();
        $statusPreference->name = 'checkLastCron';
        if ($statusPreference->find(TRUE) && !empty($statusPreference->check_info)) {
          $lastCron = $statusPreference->check_info;
          $since = time() - $lastCron;
        }
        if ($since > 3600 || $since === -1) {
          $when = ($since == -1) ? 'never' : date('c', $lastCron);
          watchdog('archimedes_extras', 'CiviCRM cron may not be running? Last execution was @time.', array('@time' => $when), WATCHDOG_WARNING);
        }
      }
    }
  }
  return $since;
}