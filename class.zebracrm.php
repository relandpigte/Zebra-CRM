<?php
/*
Plugin Name: Zebra CRM
Plugin URI: http://digitalreland.com
Description: Just another plugin. Simple but flexible.
Author: Reland Pigte
Author URI: http://digitalreland.com/
Text Domain: zebracrm
License: GPLv2 or later
Version: 1.0.0
*/

// Make sure we don't expose any info if called directly
if ( ! function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

define( 'ZEBRACRM_VERSION', '1.0.0' );
define( 'ZEBRACRM__MINIMUM_WP_VERSION', '3.2' );
define( 'ZEBRACRM__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

add_action( 'init', array( 'ZebraCRM', 'init' ) );
require_once( ZEBRACRM__PLUGIN_DIR . 'class.zebracrm-functions.php' );

