<?php
/**
 * @package ApocalypstarPlugin
 */

 namespace Inc\Api\View;

use Inc\Base\BaseController;

 class ApocalypstarDashboard extends BaseController
 {
    public function apocalypstarDashboard() {
        return require_once($this -> plugin_path . '/templates/dashboard.php');
    }
 }