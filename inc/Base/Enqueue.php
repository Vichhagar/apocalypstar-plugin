<?php

namespace Inc\Base;

use \Inc\Base\BaseController;

/**
 * @package ApocalypstarPlugin
 */

 class Enqueue extends BaseController
 {
    public function register() {
        add_action('admin_enqueue_scripts',[$this,'enqueue']);
        add_action('wp_enqueue_scripts',[$this,'frontendEnqueue']);
    }

    function enqueue() {
       // enqueue all our scripts
       wp_enqueue_style('mypluginstyle', $this -> plugin_url . '/assets/css/style.css',__FILE__);

       wp_enqueue_style('mypluginstyle1', $this -> plugin_url . '/assets/css/style_admin_old.css',__FILE__);
       wp_enqueue_style('mypluginstyle2', $this -> plugin_url . '/assets/css/style_admin_responsive_old.css',__FILE__);
       wp_enqueue_style('mypluginstyle3', $this -> plugin_url . '/assets/css/style_admin_responsive.css',__FILE__);
       wp_enqueue_style('mypluginstyle4', $this -> plugin_url . '/assets/css/style_admin.css',__FILE__);

       wp_enqueue_script('mypluginscript', $this -> plugin_url . '/assets/js/script.js',__FILE__);
       wp_enqueue_script('mypluginscript1', $this -> plugin_url . '/assets/js/script_admin.js',__FILE__);
    }

    function frontendEnqueue() {
       // enqueue all our scripts
       wp_enqueue_style('mypluginstyle', $this -> plugin_url . '/assets/css/genesis.css',__FILE__);
       wp_enqueue_style('mypluginstylehome', $this -> plugin_url . '/assets/css/home.css',__FILE__);
       wp_enqueue_style('mypluginstylemenu', $this -> plugin_url . '/assets/css/menu.css',__FILE__);

       wp_enqueue_script('mypluginscriptjquerybacondmin', $this -> plugin_url . '/assets/js/jquery.ba-cond.min.js',__FILE__);
       wp_enqueue_script('mypluginscriptgenesis', $this -> plugin_url . '/assets/js/genesis.js',__FILE__);
       wp_enqueue_script('mypluginscriptcachot', $this -> plugin_url . '/assets/js/cachot.js',__FILE__);
       wp_enqueue_script('mypluginscriptjqueryslitslider', $this -> plugin_url . '/assets/js/jquery.slitslider.js',__FILE__);
       wp_enqueue_script('mypluginscriptmodernizrcustom79639', $this -> plugin_url . '/assets/js/modernizr.custom.79639.js',__FILE__);
    }
}