<?php
/**
 * @package ApocalypstarPlugin
 */

 namespace Inc\Api\View;

use Inc\Base\BaseController;

 class GenesisReservation extends BaseController
 {
    public function genesisReservation() {
        return require_once($this -> plugin_path . '/templates/admin/genesisReservationAdmin.php');
    }
 }