<?php

if ( !defined( 'ABSPATH' ) )
	exit;

add_action( 'admin_menu', function() {
	add_submenu_page(
		'edit.php?post_type=daytip',
		__( 'Import', 'daytip' ),
		__( 'Import', 'daytip' ),
		'edit_pages',
		'import',
		'daytip_import_callback'
	);
} );

function daytip_import_callback() {
?>
<div class="wrap">
	<h1><?= __( 'Import', 'daytip' ) ?></h1>
	<p><?= __( 'Upload a text file listing all tips to be imported into the database.', 'daytip' ) ?></p>
	<form method="post" action="<?= admin_url( 'admin-post.php' ) ?>" enctype="multipart/form-data">
		<input type="hidden" name="action" value="daytip_import" />
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="daytip-file"><?= __( 'Input file', 'daytip' ) ?></label></th>
					<td>
						<input type="file" name="daytip" id="daytip-file" class="regular-text" required="required" />
						<p class="description"><?= daytip_file_format() ?></p>
					</td>
				</tr>
			</tbody>
		</table>
		<?php submit_button( __( 'Import', 'daytip' ) ); ?>
	</form>
</div>
<?php
}

add_action( 'admin_notices', function() {
	$screen = get_current_screen();
	if ( $screen->id !== 'daytip_page_import' )
		return;
	if ( !array_key_exists( 'message', $_GET ) )
		return;
	daytip_notice( daytip_import_notice( $_GET['message'] ) );
} );

add_action( 'admin_post_daytip_import', function() {
	if ( !current_user_can( 'edit_pages' ) )
		exit;
	if ( !array_key_exists( 'daytip', $_FILES ) )
		daytip_import_redirect( 1 );
	$file = $_FILES['daytip'];
	if ( $file['error'] !== 0 )
		daytip_import_redirect( 2 );
	$lines = file( $file['tmp_name'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
	$tips = [];
	foreach ( $lines as $line ) {
		$tip = mb_split( "\t", $line, 2 );
		if ( count( $tip ) !== 2 || is_null( daytip_monthday::parse( $tip[0] ) ) )
			daytip_import_redirect( 3 );
		$tips[] = $tip;
	}
	foreach ( $tips as $tip )
		wp_insert_post( [
'post_content' => $tip[1],
'post_title' => $tip[0],
'post_status' => 'publish',
'post_type' => 'daytip',
		] );
	daytip_import_redirect();
} );

function daytip_import_redirect( int $id = 0 ) {
	$url = admin_url( sprintf( 'edit.php?post_type=daytip&page=import&message=%d', $id ) );
	header( 'location: ' . $url );
	exit;
}

function daytip_import_notice( int $id ) {
	switch ( $id ) {
		case  0: return [ 'class' => 'success', 'message' => __( 'Import was successful.', 'daytip' ) ];
		case  1: return [ 'class' => 'warning', 'message' => __( 'Input file not specified.', 'daytip' ) ];
		case  2: return [ 'class' => 'error', 'message' => __( 'Upload failed due to an error.', 'daytip' ) ];
		case  3: return [ 'class' => 'error', 'message' => __( 'File parsing failed.', 'daytip' ) ];
		default: return NULL;
	}
}
