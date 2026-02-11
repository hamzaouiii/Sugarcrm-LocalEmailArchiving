<?php

if (!defined('sugarEntry') || !sugarEntry) {
  die('Not A Valid Entry Point');
}

require_once 'modules/Schedulers/Scheduler.php';

$jobName   = 'Sync Inbound Emails (Local Archive)';
$jobString = 'function::syncInboundEmails';

// --------------------------------------------------
// Prevent duplicates (important!)
// --------------------------------------------------
$existing = BeanFactory::getBean('Schedulers')->get_full_list(
  '',
  "schedulers.deleted = 0 AND schedulers.job = '{$jobString}'"
);

if (!empty($existing)) {
  foreach ($existing as $job) {
    $job->status = 'Active';
    $job->save();
  }
  return;
}

// --------------------------------------------------
// Create scheduler
// --------------------------------------------------
$scheduler = BeanFactory::newBean('Schedulers');
$scheduler->name = $jobName;
$scheduler->job = $jobString;
$scheduler->status = 'Active';
$scheduler->date_start_time = '2005-01-01 09:15:00';
$scheduler->job_interval = '*/1::*::*::*::*';
$scheduler->catch_up = 1;
$scheduler->save();
