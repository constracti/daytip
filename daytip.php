<?php
/**
 * Plugin Name: Day Tip
 * Plugin URI: https://github.com/constracti/daytip
 * Description: Display a unique tip each day of the year.
 * Author: constracti
 * Version: 1.0
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: daytip
 * Domain Path: /languages
 */

if ( !defined( 'ABSPATH' ) )
	exit;

require_once plugin_dir_path( __FILE__ ) . 'monthday.php';
require_once plugin_dir_path( __FILE__ ) . 'post-type.php';
require_once plugin_dir_path( __FILE__ ) . 'widget.php';
require_once plugin_dir_path( __FILE__ ) . 'import.php';
require_once plugin_dir_path( __FILE__ ) . 'export.php';
require_once plugin_dir_path( __FILE__ ) . 'shift.php';

add_action( 'plugins_loaded', function() {
	load_plugin_textdomain( 'daytip', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
} );

add_action( 'admin_notices', function() {
	$screen = get_current_screen();
	if ( $screen->post_type !== 'daytip' )
		return;
	$notice = [
		'class'   => 'info',
		'message' => __( 'Tip title follows the format <b>MM-DD</b>, where <b>MM</b> is a 2-digit month and <b>DD</b> is a 2-digit day.', 'daytip' ),
	];
	daytip_notice( $notice, FALSE, 'info' );
} );

function daytip_notice( $notice, bool $is_dismissible = TRUE, $dashicon = NULL ) {
	if ( is_null( $notice ) || !is_array( $notice ) )
		return;
?>
<div class="notice notice-<?= $notice['class'] ?><?= $is_dismissible ? ' is-dismissible' : '' ?>">
	<p<?= !is_null( $dashicon ) ? sprintf( ' class="dashicons-before dashicons-%s"', $dashicon ) : '' ?>><?= $notice['message'] ?></p>
</div>
<?php
}

function daytip_file_format(): string {
	return sprintf( '<span><b>%s</b>: %s</span>', __( 'Encoding', 'daytip' ), 'UTF-8' ) . "\n" .
	'<br >' . "\n" .
	sprintf( '<span><b>%s</b>: %s</span>', __( 'Structure', 'daytip' ), __( 'Each line of the file consists of a title and the lines of the tip, separated by tab characters.', 'daytip' ) ) . "\n";
}
