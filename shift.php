<?php

if ( !defined( 'ABSPATH' ) )
	exit;

add_action( 'admin_menu', function() {
	add_submenu_page(
		'edit.php?post_type=daytip',
		__( 'Shift', 'daytip' ),
		__( 'Shift', 'daytip' ),
		'edit_pages',
		'shift',
		'daytip_shift_callback'
	);
} );

function daytip_shift_callback() {
	$today = daytip_monthday::today()->title();
?>
<div class="wrap">
	<h1><?= __( 'Shift', 'daytip' ) ?></h1>
	<p><?= __( 'Circularly shift tips to fill in the selected date range.', 'daytip' ) ?></p>
	<form method="post" action="<?= admin_url( 'admin-post.php' ) ?>">
		<input type="hidden" name="action" value="daytip_shift" />
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="daytip-beg"><?= __( 'From', 'daytip' ) ?></label></th>
					<td>
						<input type="text" name="beg" value="<?= $today ?>" id="daytip-beg" class="regular-text" required="required" autocomplete="off" />
						<p class="description"><?= sprintf( '%s: %s (%s)', __( 'example', 'daytip' ), $today, __( 'today', 'daytip' ) ) ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="daytip-end"><?= __( 'To', 'daytip' ) ?></label></th>
					<td>
						<input type="text" name="end" value="<?= $today ?>" id="daytip-end" class="regular-text" required="required" autocomplete="off" />
						<p class="description"><?= sprintf( '%s: %s (%s)', __( 'example', 'daytip' ), $today, __( 'today', 'daytip' ) ) ?></p>
					</td>
				</tr>
			</tbody>
		</table>
		<?php submit_button( __( 'Shift', 'daytip' ) ); ?>
	</form>
</div>
<?php
}

add_action( 'admin_post_daytip_shift', function() {
	if ( !current_user_can( 'edit_pages' ) )
		exit;
	if ( !array_key_exists( 'beg', $_POST ) || !array_key_exists( 'end', $_POST ) )
		daytip_shift_redirect( 1 );
	$beg = daytip_monthday::parse( $_POST['beg'] );
	$end = daytip_monthday::parse( $_POST['end'] );
	if ( is_null( $beg ) || is_null( $end ) )
		daytip_shift_redirect( 2 );
	$posts = get_posts( [
		'nopaging' => TRUE,
		'order' => 'ASC',
		'orderby' => 'title',
		'post_type' => 'daytip',
		'post_status' => 'publish',
	] );
	$len = count( $posts );
	$cnt = 1;
	for ( $cur = $beg; $cur->comp( $end ); $cur = $cur->next() )
		$cnt++;
	if ( $len < $cnt )
		daytip_shift_redirect( 3 );
	daytip_shift_redirect();
} );

add_action( 'admin_notices', function() {
	$screen = get_current_screen();
	if ( $screen->id !== 'daytip_page_shift' )
		return;
	if ( !array_key_exists( 'message', $_GET ) )
		return;
	daytip_notice( daytip_shift_notice( $_GET['message'] ) );
} );

function daytip_shift_redirect( int $id = 0 ) {
	$url = admin_url( sprintf( 'edit.php?post_type=daytip&page=shift&message=%d', $id ) );
	header( 'location: ' . $url );
	exit;
}

function daytip_shift_notice( int $id ) {
	switch ( $id ) {
		case  0: return [ 'class' => 'success', 'message' => __( 'Circular shift was successful.', 'daytip' ) ];
		case  1: return [ 'class' => 'warning', 'message' => __( 'Date range not specified.', 'daytip' ) ];
		case  2: return [ 'class' => 'error', 'message' => __( 'Date limits are not valid.', 'daytip' ) ];
		case  3: return [ 'class' => 'error', 'message' => __( 'There are not enough tips in the database to cover this date range.', 'daytip' ) ];
		default: return NULL;
	}
}
