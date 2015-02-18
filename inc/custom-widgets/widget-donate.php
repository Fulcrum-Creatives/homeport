<?php

add_action( 'widgets_init', 'donate_widget' );

function donate_widget() {
	register_widget( 'Donate_Widget' );
}
class Donate_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'donate_widget', // Base ID
			'Donate Button', // Name
			array( 'description' => __( 'Donate Button', DOMAIN ), ) // Args
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
		$title = apply_filters( 'widget_title', $instance['title'] );
		$url = $instance['url'];

		echo $before_widget;
		?>
		<div class="donate-btn">
		  <a href="<?php echo $url; ?>" target="_blank">
		    <p><?php if ( ! empty( $title ) ) echo $title  ?></p>
		  </a>
		</div>
		<?php
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
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['url'] = strip_tags( $new_instance['url'] );
		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		// This is for custom field for the widget
		// 
		// check is field is empty. This is needed for every field
		if( $instance) {
		     $title = esc_attr($instance['title']);
		} else {
		     $title = '';
		}
		if( $instance) {
		     $url = esc_attr($instance['url']);
		} else {
		     $url = '';
		}
		// output the html for the custom fields
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'URL:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" type="text" value="<?php echo esc_attr( $url ); ?>" />
		</p>
		<?php 
	}

} // class Foo_Widget
?>