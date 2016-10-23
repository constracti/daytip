<?php

if ( !defined( 'ABSPATH' ) )
	exit;

add_action( 'admin_menu', function() {
	add_submenu_page(
		'edit.php?post_type=daytip',
		__( 'Export', 'daytip' ),
		__( 'Export', 'daytip' ),
		'edit_pages',
		'export',
		'daytip_export_callback'
	);
} );

function daytip_export_callback() {
	$url = admin_url( 'admin-post.php?action=daytip_export' );
	$url = wp_nonce_url( $url, 'daytip-export' );
?>
<div class="wrap">
	<h1><?= __( 'Export', 'daytip' ) ?></h1>
	<p><?= __( 'Download a text file listing all tips included in the database.', 'daytip' ) ?></p>
	<p><?= daytip_file_format() ?></p>
	<p><a href="<?= $url ?>" class="button button-primary"><?= __( 'Export', 'daytip' ) ?></a></p>
</div>
<?php
}

add_action( 'admin_post_daytip_export', function() {
	if ( !current_user_can( 'edit_pages' ) )
		exit;
	check_admin_referer( 'daytip-export' );
	header( 'Content-Type: text/plain; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="tips.txt"' );
	$posts = get_posts( [
		'nopaging' => TRUE,
		'order' => 'ASC',
		'orderby' => 'title',
		'post_type' => 'daytip',
		'post_status' => 'publish',
	] );
	foreach ( $posts as $post )
		echo $post->post_title . "\t" . mb_ereg_replace( '\r\n|\r|\n', "\t", $post->post_content, 'z' ) . "\n";
} );
