<?php
/**
 * Plugin Name:  Learndash Custom Tabs
 * Description:  Add custom tab to learndash plugin
 * Version:      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! class_exists( 'LDCT' ) ) :
    
   
    class LDCT {

        private static $instance = null;

        public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

        public static $plugin_path = '';

        public function __construct() {
            self::include_files();

        }

        public static function get_path() {
            return self::$plugin_path;
        }
        
       

        public function include_files(){
            require_once(self::$plugin_path. 'functions/functions.php');   
        }

    }
endif;

new LDCT();
