<?php

namespace Inc\Pages;

use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
use \Inc\Api\View\CachotReservation;
use \Inc\Api\View\ManoirReservation;
use \Inc\Api\View\GenesisReservation;
use \Inc\Api\View\ApocalypstarDashboard;

/**
 * @package ApocalypstarPlugin
 */

 class Admin extends BaseController
 {
    public $settings;
    public $apocalypstarDashboard;
    public $genesisReservation;
    public $manoirReservation;
    public $cachotReservation;

    public $pages = [];
    public $subpages = [];

    public function register() {
        $this -> settings  = new SettingsApi();
        $this -> apocalypstarDashboard  = new ApocalypstarDashboard();
        $this -> genesisReservation  = new GenesisReservation();
        $this -> manoirReservation  = new ManoirReservation();
        $this -> cachotReservation  = new CachotReservation();

        $this -> setPages();
        $this -> setSubpages();

        $this -> settings -> addPages( $this -> pages ) -> withSubPage('Dashboard') -> addSubPages($this -> subpages) -> register();
    }

    public function setPages() {
        $this -> pages = [
            [
                'page_title' => 'Apocalypstar Plugin',
                'menu_title' => 'Apocalypstar',
                'capability' => 'manage_options',
                'menu_slug' => 'apocalypstar_plugin',
                'callback' => [$this -> apocalypstarDashboard, 'apocalypstarDashboard'],
                'icon_url' => 'dashicons-store',
                'position' => 110
            ]
        ];
    }

    public function setSubpages() {
        $this -> subpages = [
            [
                'parent_slug' => 'apocalypstar_plugin',
                'page_title' => 'Genesis',
                'menu_title' => 'Genesis',
                'capability' => 'manage_options',
                'menu_slug' => 'apocalypstar_genesis',
                'callback' => [$this -> genesisReservation, 'genesisReservation'],
            ],
            [
                'parent_slug' => 'apocalypstar_plugin',
                'page_title' => 'Manoir',
                'menu_title' => 'Manoir',
                'capability' => 'manage_options',
                'menu_slug' => 'apocalypstar_manoir',
                'callback' => [$this -> manoirReservation, 'manoirReservation'],
            ],
            [
                'parent_slug' => 'apocalypstar_plugin',
                'page_title' => 'Cachot',
                'menu_title' => 'Cachot',
                'capability' => 'manage_options',
                'menu_slug' => 'apocalypstar_cachot',
                'callback' => [$this -> cachotReservation, 'cachotReservation'],
            ],
        ];
    }
 }