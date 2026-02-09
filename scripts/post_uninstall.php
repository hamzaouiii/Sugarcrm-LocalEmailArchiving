<?php

if (!defined('sugarEntry') || !sugarEntry) {
  die('Not A Valid Entry Point');
}

$jobString = 'syncInboundEmails';

$jobs = BeanFactory::getBean('Schedulers')->get_full_list(
  '',
  "schedulers.deleted = 0 AND schedulers.job = '{$jobString}'"
);

if (!empty($jobs)) {
  foreach ($jobs as $job) {
    $job->mark_deleted($job->id);
  }
}
