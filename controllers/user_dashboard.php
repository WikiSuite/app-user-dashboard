<?php

/**
 * User dashboard controller.
 *
 * @category   apps
 * @package    user-dashboard
 * @subpackage controllers
 * @author     eGloo <developer@egloo.ca>
 * @copyright  2018 Avantech
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * User dashboard controller.
 *
 * @category   apps
 * @package    user-dashboard
 * @subpackage controllers
 * @author     eGloo <developer@egloo.ca>
 * @copyright  2018 Avantech
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 */

class User_Dashboard extends ClearOS_Controller
{
    /**
     * User dashboard default controller.
     *
     * @return view
     */

    function index()
    {
        // Load libraries
        //---------------

        $this->lang->load('user_dashboard');
        $this->load->library('user_dashboard/Dashboard_Helper');

        // Load the data 
        //-------------- 

        try {
            $widgets = $this->dashboard_helper->get_registered_widgets();

            if (!empty($widgets))
                $data['widgets'] = $this->page->view_controllers($widgets, lang('user_dashboard_app_name'), array('type' => MY_Page::TYPE_DASHBOARD_WIDGET));
            else
                $data['widgets'] = [];
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }

        // Load the views
        //---------------

        if (empty($data['widgets']))
            $this->page->view_form('user_dashboard/none', [], lang('user_dashboard_app_name'));
        else
            $this->page->view_form('user_dashboard/canvas', $data, lang('user_dashboard_app_name'));
    }
}
