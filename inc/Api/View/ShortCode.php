<?php
/**
 * @package ApocalypstarPlugin
 */

 namespace Inc\Api\View;

use Inc\Base\BaseController;

 class ShortCode extends BaseController
 {
    public function register() {
        add_shortcode('genesis_reservation', [$this, 'genesis_reservation']);
        add_shortcode('manoir_reservation', [$this, 'manoir_reservation']);
        add_shortcode('cachot_reservation', [$this, 'cachot_reservation']);
        add_shortcode('payment_option', [$this, 'payment_option']);
    }

    public function genesis_reservation() {
        ob_start();
        require_once($this -> plugin_path . '/templates/genesisReservation.php');
        return ob_get_clean();
    }

    public function manoir_reservation() {
        ob_start();
        require_once($this -> plugin_path . '/templates/manoirReservation.php');
        return ob_get_clean();
    }

    public function cachot_reservation() {
        ob_start();
        require_once($this -> plugin_path . '/templates/cachotReservation.php');
        return ob_get_clean();
    }

    public function payment_option() {
        ob_start();
        require_once($this -> plugin_path . '/templates/traitementreserved.php');
        return ob_get_clean();
    }
 }