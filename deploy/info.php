<?php

/////////////////////////////////////////////////////////////////////////////
// General information
/////////////////////////////////////////////////////////////////////////////

$app['basename'] = 'user_dashboard';
$app['version'] = '2.5.4';
$app['vendor'] = 'WikiSuite';
$app['packager'] = 'WikiSuite';
$app['license'] = 'GPLv3';
$app['license_core'] = 'LGPLv3';
$app['description'] = lang('user_dashboard_app_description');

/////////////////////////////////////////////////////////////////////////////
// App name and categories
/////////////////////////////////////////////////////////////////////////////

$app['name'] = lang('user_dashboard_app_name');
$app['category'] = lang('base_category_system');
$app['subcategory'] = lang('base_subcategory_my_account');
$app['user_access'] = TRUE;

/////////////////////////////////////////////////////////////////////////////
// Controllers
/////////////////////////////////////////////////////////////////////////////

$app['controllers']['user_dashboard']['title'] = $app['name'];

/////////////////////////////////////////////////////////////////////////////
// Packaging
/////////////////////////////////////////////////////////////////////////////

$app['requires'] = array(
    'app-accounts',
    'app-groups',
    'app-users',
);

$app['core_requires'] = array(
    'app-accounts-core',
    'app-groups-core',
    'app-users-core',
);

$app['core_file_manifest'] = array(
   'user_dashboard.acl' => array( 'target' => '/var/clearos/base/access_control/authenticated/user_dashboard' ),
);
