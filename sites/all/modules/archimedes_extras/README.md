# Archimedes Extras

This module provides some additional Archimedes checks.

For more information, see https://fuzion.co.nz/node/123

## Included checks

* Git status - report on whether there are any repositories in the site root which have local modifications.
* CiviCRM status - report on the Git hash and branch of installed CiviCRM.
* CiviCRM last cron - report how many seconds since last cron run (should be < 3600, but we don't judge).

## Adding a check

* Modify or implement `hook_archimedes_alter()` and `hook_archimedes_rows_alter()`
* In `hook_archimedes_alter()`, `$owl->createField('name', $value)`.
* In `hook_archimedes_rows_alter()`, `$rows[] = array(t('Label'), $value)`.
* See `3ad8b4f` for an example.