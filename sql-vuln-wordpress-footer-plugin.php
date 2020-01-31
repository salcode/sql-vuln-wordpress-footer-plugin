<?php
/**
 * Plugin Name: SQL Vulnerable Footer
 * Plugin URI: https://github.com/salcode/sql-vuln-wordpress-footer-plugin
 * Description: A WordPress plugin designed to have an SQL Injection Vulnerability for demonstration purposes.
 * Version: 0.1.0
 * Author: Sal Ferrarello
 * Author URI: http://salferrarello.com/
 * Text Domain: sql-vuln-wordpress-footer-plugin
 * Domain Path: /languages
 *
 * @package sql-vuln-wordpress-footer-plugin
 */

namespace salcode\SQLVuln\Footer;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'get_footer', __NAMESPACE__ . '\output_user_display_name' );

add_action( 'admin_notices', __NAMESPACE__ . '\warning' );

/**
 * Output Display Name based on URL parameter 'id'.
 */
function output_user_display_name() {
	$id = $_GET['id'];
	if ( ! $id ) {
		// If no 'id' is given, do not output anything.
		return;
	}
	echo 'Display Name is ';
	echo bad_get_display_name( $id );
	// echo better_get_display_name( $id );
	// echo best_get_display_name( $id );
}

/**
 * Bad (vulnerable) get_display_name
 *
 * @param int $id User ID.
 * @return string User display name.
 */
function bad_get_display_name( $id ) {
	global $wpdb;
	$sql    = "SELECT display_name from {$wpdb->users} WHERE id=" . $id;
	$result = $wpdb->get_results( $sql );
	return $result[0]->display_name;
}

/**
 * Better (not vulnerable) get_display_name
 *
 * @param int $id User ID.
 * @return string User display name.
 */
function better_get_display_name( $id ) {
	global $wpdb;
	$sql    = $wpdb->prepare(
		"SELECT display_name from {$wpdb->users} WHERE id=%d",
		$id
	);
	$result = $wpdb->get_results( $sql );
	return $result[0]->display_name;
}

/**
 * Best (follows best practices) get_display_name
 *
 * This function uses built-in WordPress functionality to retrieve the
 * information instead of querying the database directly.
 *
 * @param int $id User ID.
 * @return string User display name.
 */
function best_get_display_name( $id ) {
	$userdata = get_userdata( $id );
	return $userdata->display_name;
}

/**
 * Display Warning that this plugin has an SQL Vulnerability.
 *
 * @since 0.1.0
 */
function warning() {
	printf(
		'<div class="error"><p><strong>%s:</strong> %s</p></div>',
		esc_html__(
			'Vulnerability',
			'sql-vuln-wordpress-footer-plugin'
		),
		esc_html__(
			'The SQL Vulnerable Footer plugin is for demonstration purposes and contains an SQL Injection vulnerability. Please run on local development sites ONLY.',
			'sql-vuln-wordpress-footer-plugin'
		)
	);
}
