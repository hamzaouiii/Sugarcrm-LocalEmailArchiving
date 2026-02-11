<?php

$admin_option_defs = [];
$admin_option_defs['Administration']['ArchiveEmailPanel'] = [
  'Administration',
  'LBL_ARCHIVE_EMAIL_TITLE',
  'LBL_ARCHIVE_EMAIL_DESC',
  '/#gen_configs'
];
$admin_group_header[] = [
  'LBL_ARCHIVE_EMAIL_HEADER',
  '',
  false,
  $admin_option_defs,
];
