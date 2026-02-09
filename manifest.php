<?php

$manifest = array(
  'built_in_version' => '25.1.0',
  'acceptable_sugar_versions' => array(
    'regex_matches' => array(
      '25\..*',
    ),
  ),
  'acceptable_sugar_flavors' => array(
    'ENT',
    'ULT',
    'SELL',
    'SERVE',
  ),
  'readme' => '',
  'key' => 'gen',
  'author' => 'Simo Hamzaoui - Genius4u',
  'description' => 'Local Email Archiving with logic hooks and scheduler',
  'icon' => '',
  'is_uninstallable' => true,
  'name' => 'Local Email Archiving',
  'published_date' => '2026-02-09',
  'type' => 'module',
  'version' => '0.9',
  'remove_tables' => 'prompt',
);

$installdefs = array(
  'id' => 'LocalEmailArchiving',

  'beans' => array(
    array(
      'module' => 'gen_configs',
      'class' => 'gen_configs',
      'path' => 'modules/gen_configs/gen_configs.php',
      'tab' => true,
    ),
  ),

  'copy' => array(
    array(
      'from' => '<basepath>/SugarModules/modules/gen_configs',
      'to' => 'modules/gen_configs',
    ),
    array(
      'from' => '<basepath>/custom',
      'to' => 'custom',
    ),
  ),

  'language' => array(
    array(
      'from' => '<basepath>/SugarModules/language/application/en_us.lang.php',
      'to_module' => 'application',
      'language' => 'en_us',
    ),
  ),

  'image_dir' => '<basepath>/icons',

  // 🔹 Automation
  'post_install' => array(
    '<basepath>/scripts/post_install.php',
  ),
  'post_uninstall' => array(
    '<basepath>/scripts/post_uninstall.php',
  ),
);
