<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit; }

$options = array('snow','snowadvanced','snowtechnical');
$transients = array('snow_hello', 'snow_intro_general', 'snow_intro_advanced', 'snow_intro_technical');

foreach ( $options as $option ) {
	if ( get_option( $option ) ) {
		delete_option( $option );
	}
}

foreach ( $transients as $transient ) {
	if ( get_transient( $transient ) ) {
		delete_transient( $transient );
	}
}