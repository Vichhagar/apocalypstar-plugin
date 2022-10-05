<?php

/**
 * @package ApocalypstarPlugin
 */

namespace Inc\Base;

class AdminUsers
{
    public function getUserName() {
        return _wp_get_current_user() -> user_login;
    }

    public function userName() {
        var_dump($this -> getUserName());
        return $this -> getUserName();
    }
}