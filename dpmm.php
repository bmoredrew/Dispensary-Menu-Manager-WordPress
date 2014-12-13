<?php
/**
 * Plugin Name: Dispensary Menu Manager
 * Plugin URI: http://wpdispensarypro.com
 * Description: Dispensary Menu Manager allows you to create, manage, and display a menu for your dispensary.
 * Version: 1.0
 * Author: Drew Poland
 * Author URI: http://baltimoredrew.com
 * Requires at least: 3.9
 * Tested up to: 4.0
 *
 */
if ( ! defined( 'ABSPATH' ) ) 
{
	exit;
}

include ( 'class-DPMM.php' );

foreach ( glob( dirname( __FILE__ ) . '/lib/*.php' ) as $file )
{
	include $file;
}