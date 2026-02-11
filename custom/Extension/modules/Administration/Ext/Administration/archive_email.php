<?php

$admin_option_defs = [];
$admin_option_defs['Administration']['ArchiveEmailPanel'] = [
  'Administration',
  'LBL_ARCHIVE_EMAIL_TITLE',
  'LBL_ARCHIVE_EMAIL_DESC',
  'javascript:void(parent.SUGAR.App.router.navigate("gen_configs", {trigger: true}));'
];
$admin_group_header[] = [
  'LBL_ARCHIVE_EMAIL_HEADER',
  '',
  false,
  $admin_option_defs,
];
