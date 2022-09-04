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


       wp_enqueue_script('mypluginscriptgenesis', $this -> plugin_url . '/assets/js/genesis.js',__FILE__);
       wp_enqueue_script('mypluginscriptjquerybacondmin', $this -> plugin_url . '/assets/js/jquery.ba-cond.min.js',__FILE__);
       wp_enqueue_script('mypluginscriptcachot', $this -> plugin_url . '/assets/js/cachot.js',__FILE__);
       wp_enqueue_script('mypluginscriptjqueryslitslider', $this -> plugin_url . '/assets/js/jquery.slitslider.js',__FILE__);
       wp_enqueue_script('mypluginscriptmodernizrcustom79639', $this -> plugin_url . '/assets/js/modernizr.custom.79639.js',__FILE__);

       wp_enqueue_style('mypluginstyle', $this -> plugin_url . '/assets/css/genesis.css',__FILE__);
       wp_enqueue_style('mypluginstylehome', $this -> plugin_url . '/assets/css/home.css',__FILE__);
       wp_enqueue_style('mypluginstylemenu', $this -> plugin_url . '/assets/css/menu.css',__FILE__);

      // CDN jQuery and BootStrap
      // CSS
      // wp_register_style( 'bootstrapcdn', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css' );
      // wp_enqueue_style('bootstrapcdn');

      // wp_register_style( 'googleFonts', 'https://fonts.googleapis.com/css?family=IM+Fell+English+SC|IM+Fell+English:400,400i|Jim+Nightshade&display=swap&subset=latin-ext' );
      // wp_enqueue_style('googleFonts');

      // wp_register_style( 'googleFonts1', 'https://fonts.googleapis.com/css?family=Montserrat&display=swap' );
      // wp_enqueue_style('googleFonts1');

      // wp_register_style( 'fontAwesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
      // wp_enqueue_style('fontAwesome');

      // wp_register_style( 'googleFonts2', 'https://fonts.googleapis.com/css2?family=Darker+Grotesque:wght@500;600;700;800&display=swap' );
      // wp_enqueue_style('googleFonts2');

      // // JS
      // wp_register_script( 'jQuery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js', null, null, true );
      // wp_enqueue_script('jQuery');

      // wp_register_script( 'jsBootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js', null, null, true );
      // wp_enqueue_script('jsBootstrap');

      // wp_register_script( 'googletagmanager', 'https://www.googletagmanager.com/gtag/js?id=UA-148366650-6', null, null, true );
      // wp_enqueue_script('googletagmanager');
    }
}