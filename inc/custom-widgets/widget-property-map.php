<?php

add_action( 'widgets_init', 'property_map_widget' );

function property_map_widget() {
	register_widget( 'Property_Map_Widget' );
}
class Property_Map_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'property_map_widget', // Base ID
			'Porperty Map', // Name
			array( 'description' => __( 'Property Map Widget', DOMAIN ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		// This is the outputted HTML and PHP

		echo $before_widget;
		if (have_posts()) : while (have_posts()) : the_post();
		$custom = get_post_custom( $post->ID );
		$lat = $custom["lat"][0];
	  	$lng = $custom["lng"][0];
	  	$city = $custom["city"][0];
	  	$state = $custom["state"][0];
	  	$postal_code = $custom["postal_code"][0];
		?>
		<div id="mapCanvas-wrap">
			<div id="mapCanvas" class="mapCanvas"></div>
		</div>
		<div id="mapDebug"></div>
		<input type="hidden" name="lat" id="lat" value="<?php echo $lat; ?>"/>
		<input type="hidden" name="lng" id="lng" value="<?php echo $lng; ?>"/>
		<input type="hidden" name="city" id="city" value="<?php echo $city; ?>"/>
		<input type="hidden" name="state" id="state" value="<?php echo $state; ?>"/>
		<input type="hidden" name="postal_code" id="postal_code" value="<?php echo $postal_code; ?>"/>
  		<?php
  		endwhile; endif; wp_reset_query();
		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
	}

} // class Foo_Widget
?>