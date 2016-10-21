<?php
/**
 * Plugin Name: Day Tip
 * Plugin URI: https://github.com/constracti/daytip
 * Description: Display a unique tip each day of year.
 * Author: constracti
 * Version: 1.0
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: daytip
 * Domain Path: /languages
 */

if ( !defined( 'ABSPATH' ) )
	exit;

require_once plugin_dir_path( __FILE__ ) . 'post-type.php';
require_once plugin_dir_path( __FILE__ ) . 'widget.php';
require_once plugin_dir_path( __FILE__ ) . 'import.php';
require_once plugin_dir_path( __FILE__ ) . 'export.php';

add_action( 'plugins_loaded', function() {
	load_plugin_textdomain( 'daytip', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
} );

add_action( 'admin_notices', function() {
	$screen = get_current_screen();
	if ( $screen->post_type !== 'daytip' )
		return;
	$notice = __( 'Tip title follows the format <b>MM-DD</b>, where <b>MM</b> is a 2-digit month and <b>DD</b> is a 2-digit day.', 'daytip' );
?>
<div class="notice notice-info">
	<p class="dashicons-before dashicons-info"><?= $notice ?></p>
</div>
<?php
} );

function daytip_file_format(): string {
	return sprintf( '<span><b>%s</b>: %s</span>', __( 'Encoding', 'daytip' ), 'UTF-8' ) . "\n" .
	'<br >' . "\n" .
	sprintf( '<span><b>%s</b>: %s</span>', __( 'Structure', 'daytip' ), __( 'Each line consists of a title and the tip, separated by a tab character.', 'daytip' ) ) . "\n";
}
