<?php
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// Delete Options
delete_option( 'vsfm-setting' );
delete_option( 'vsfm-setting-ios' );
delete_option( 'vsfm-setting-ms-image' );
delete_option( 'vsfm-setting-ms-color' );


// For site options in Multisite
delete_site_option( 'vsfm-setting' );
delete_site_option( 'vsfm-setting-ios' );
delete_site_option( 'vsfm-setting-ms-image' );
delete_site_option( 'vsfm-setting-ms-color' );
