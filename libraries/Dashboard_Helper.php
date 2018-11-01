<?php

/**
 * Dashboard helper class.
 *
 * @category   apps
 * @package    user-dashboard
 * @subpackage libraries
 * @author     eGloo <developer@egloo.ca>
 * @copyright  2018 Avantech
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Lesser General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// N A M E S P A C E
///////////////////////////////////////////////////////////////////////////////

namespace clearos\apps\user_dashboard;

///////////////////////////////////////////////////////////////////////////////
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// T R A N S L A T I O N S
///////////////////////////////////////////////////////////////////////////////

clearos_load_language('user_dashboard');

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

// Classes
//--------

use \clearos\apps\base\Engine as Engine;
use \clearos\apps\base\File as File;
use \clearos\apps\base\Software as Software;

clearos_load_library('base/Engine');
clearos_load_library('base/File');
clearos_load_library('base/Software');

// Exceptions
//-----------

use \clearos\apps\base\Engine_Exception as Engine_Exception;

clearos_load_library('base/Engine_Exception');

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Dashboard helper class.
 *
 * @category   apps
 * @package    user-dashboard
 * @subpackage libraries
 * @author     eGloo <developer@egloo.ca>
 * @copyright  2018 Avantech
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 */

class Dashboard_Helper extends Engine
{
    ///////////////////////////////////////////////////////////////////////////////
    // C O N S T A N T S
    ///////////////////////////////////////////////////////////////////////////////

    const CACHE_TIME_SECONDS = 60;
    const FILE_CACHE_USER_DASHBOARD_WIDGETS = 'user_dashboard_widgets';

    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Dashboard helper constructor.
     */

    function __construct()
    {
        clearos_profile(__METHOD__, __LINE__);
    }

    /**
     * Returns list of registered widgets.
     *
     * @return array list of registered widgets
     * @throws Engine_Exception
     */

    function get_registered_widgets()
    {
        clearos_profile(__METHOD__, __LINE__);

        $cache = new File(CLEAROS_CACHE_DIR . '/' . Dashboard_Helper::FILE_CACHE_USER_DASHBOARD_WIDGETS);

        if ($cache->exists())
            $lastmod = $cache->last_modified();
        else
            $lastmod = 0;

        if ($lastmod && (time() - $lastmod < Dashboard_Helper::CACHE_TIME_SECONDS))
            return $cache->get_contents_as_array();

        $app_list = clearos_get_apps();
        $widget_list = [];

        foreach ($app_list as $meta) {
            unset($app);
            $info_file = clearos_app_base($meta['basename']) . '/deploy/info.php';
            if (!file_exists($info_file))
                continue;

            include($info_file);

            // Re-init array
            if (!isset($app['user_dashboard_widgets']))
                continue;

            // Check that the UI package is installed (containing widget controller)
            $software = new Software("app-" . preg_replace("/_/", "-", $app['basename']));

            if (!$software->is_installed())
                continue;

            $widget_list = array_merge_recursive($widget_list, $app['user_dashboard_widgets']);
        }

        if (!$cache->exists())
            $cache->create('webconfig', 'webconfig', 644);

        $cache->dump_contents_from_array($widget_list);

        return $widget_list;
    }
}
