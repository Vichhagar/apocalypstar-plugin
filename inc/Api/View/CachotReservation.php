<?php
/**
 * @package ApocalypstarPlugin
 */

 namespace Inc\Api\View;

use Inc\Base\BaseController;

 class CachotReservation extends BaseController
 {
    public function cachotReservation() {
        return require_once($this -> plugin_path . '/templates/cachotReservationAdmin.php');
    }
 }