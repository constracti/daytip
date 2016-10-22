<?php

if ( !defined( 'ABSPATH' ) )
	exit;

class daytip_widget extends WP_Widget {

	const INSTANCE = [
		'title' => NULL,
	];

	public function __construct() {
		$widget_ops = array(
			'classname' => __CLASS__,
			'description' => __( 'Displays a widget with a tip of the current day.', 'daytip' ),
		);
		parent::__construct( __CLASS__, __( 'Day Tip', 'daytip' ), $widget_ops );
	}

	public function widget( $args, $instance ) {
		if ( is_null( $instance ) || !is_array( $instance ) || empty( $instance ) )
			$instance = self::INSTANCE;
		echo $args['before_widget'] . "\n";
		if ( !is_null( $instance['title'] ) )
			echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'] . "\n";
		$title = daytip_monthday::today()->title();
		$posts = get_posts( [
			'orderby' => 'rand',
			'paged' => 1,
			'post_type' => 'daytip',
			'post_status' => 'publish',
			'posts_per_page' => 1,
			'title' => $title,
		] );
		if ( !empty( $posts ) )
			echo $posts[0]->post_content;
		echo $args['after_widget'] . "\n";
	}

	public function form( $instance ) {
		if ( is_null( $instance ) || !is_array( $instance ) || empty( $instance ) )
			$instance = self::INSTANCE;
?>
<p>
	<label>
		<span><?= esc_html__( 'Title', 'daytip' ) ?></span>
		<input class="widefat" id="<?= esc_attr( $this->get_field_id( 'title' ) ) ?>" name="<?= esc_attr( $this->get_field_name( 'title' ) ) ?>" type="text" value="<?= esc_attr( $instance['title'] ?? '' ) ?>" autocomplete="off" />
	</label>
</p>
<?php
	}

	public function update( $new_instance, $old_instance ) {
		if ( array_key_exists( 'title', $new_instance ) ) {
			$title = $new_instance['title'];
			if ( !is_null( $title ) && is_string( $title ) ) {
				$title = trim( preg_replace( '/\s+/', ' ', $title ) );
				if ( $title === '' ) {
					$title = NULL;
				}
			} else {
				$title = NULL;
			}
		} else {
			$title = NULL;
		}
		return [
			'title' => $title,
		];
	}
}

add_action( 'widgets_init', function() {
	register_widget( 'daytip_widget' );
} );
