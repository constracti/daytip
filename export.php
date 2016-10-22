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
?>
<div class="wrap">
	<h1><?= __( 'Export', 'daytip' ) ?></h1>
	<p><?= __( 'Download a text file listing all tips included in the database.', 'daytip' ) ?></p>
	<p><?= daytip_file_format() ?></p>
	<p><a href="<?= admin_url( 'admin-post.php?action=daytip_export' ) ?>" class="button button-primary"><?= __( 'Export', 'daytip' ) ?></a></p>
</div>
<?php
}

add_action( 'admin_post_daytip_export', function() {
	if ( !current_user_can( 'edit_pages' ) )
		exit;
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
		echo $post->post_title . "\t" . $post->post_content . "\n";
} );
