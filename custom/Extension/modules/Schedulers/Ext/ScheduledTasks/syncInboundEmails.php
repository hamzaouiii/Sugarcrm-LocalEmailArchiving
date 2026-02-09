<?php

$job_strings[] = 'syncInboundEmails';

function syncInboundEmails()
{
  global $db;

  // ----------------------------------------------------
  // 1. Load inbound_email records (READ ONLY)
  // ----------------------------------------------------
  $sql = "SELECT id, name FROM inbound_email WHERE deleted = 0";
  $res = $db->query($sql);

  if (!$res) {
    return false;
  }

  $inboundEmails = [];
  while ($row = $db->fetchByAssoc($res)) {
    $inboundEmails[$row['id']] = $row['name'];
  }

  // ----------------------------------------------------
  // 2. Load gen_configs records linked to inbound_email
  // ----------------------------------------------------
  $sql = "
        SELECT id, sync_key, name
        FROM gen_configs
        WHERE deleted = 0
          AND sync_key IS NOT NULL
    ";

  $res = $db->query($sql);
  if (!$res) {
    return false;
  }

  $genConfigs = [];
  while ($row = $db->fetchByAssoc($res)) {
    $genConfigs[$row['sync_key']] = [
      'id'   => $row['id'],
      'name' => $row['name'],
    ];
  }

  // ----------------------------------------------------
  // 3. Create / update records
  // ----------------------------------------------------
  foreach ($inboundEmails as $inboundId => $inboundName) {
    if (isset($genConfigs[$inboundId])) {
      $gc = $genConfigs[$inboundId];

      if ($gc['name'] !== $inboundName) {
        $bean = BeanFactory::retrieveBean(
          'gen_configs',
          $gc['id'],
          ['disable_row_level_security' => true]
        );

        if ($bean) {
          $bean->name = $inboundName;
          $bean->save();
        }
      }
    } else {
      $bean = BeanFactory::newBean('gen_configs');
      $bean->name = $inboundName;
      $bean->sync_key = $inboundId;
      $bean->save();
    }
  }

  // ----------------------------------------------------
  // 4. Delete orphaned gen_configs
  // ----------------------------------------------------
  foreach ($genConfigs as $inboundId => $gc) {
    if (!isset($inboundEmails[$inboundId])) {
      $bean = BeanFactory::retrieveBean(
        'gen_configs',
        $gc['id'],
        ['disable_row_level_security' => true]
      );

      if ($bean) {
        $bean->mark_deleted($gc['id']);
      }
    }
  }

  return true;
}
