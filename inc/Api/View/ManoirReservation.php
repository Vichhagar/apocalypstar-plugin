<?php
/**
 * @package ApocalypstarPlugin
 */

 namespace Inc\Api\View;

use Inc\Base\BaseController;

 class ManoirReservation extends BaseController
 {
    public function manoirReservation() {
        return require_once($this -> plugin_path . '/templates/manoirReservationAdmin.php');
    }
 }