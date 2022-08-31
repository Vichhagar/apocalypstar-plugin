<?php

/**
 * @package ApocalypstarPlugin
 */

namespace Inc\Base;

class AdminUsers
{
    private $wpdb;
    public $userLevel;
    public $userName;

    function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;

        $user_ID = get_current_user_id();
        // $results = $wpdb->get_results( "SELECT * FROM wp_users");
        $this -> userLevel = $wpdb->get_results("SELECT * from wp_usermeta WHERE user_id = $user_ID");
        var_dump($user_ID);
        $this -> userName = $wpdb -> get_results("SELECT user_login from wp_users WHERE ID = $user_ID");
        
    }

    public function getUserLevel() {
        return $this -> userLevel[12] -> meta_value;
    }

    public function getUserName() {
        return $this -> userName;
    }

    // public static function getUserLevel() {
        
    //     $results = $wpdb->get_results( "SELECT * FROM wp_users");
    //     $user = $wpdb->get_results("SELECT * from wp_usermeta WHERE user_id = $user_ID");
    //     return $user[12] -> meta_value;
    // }
}