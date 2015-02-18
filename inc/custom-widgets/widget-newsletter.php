<?php

add_action( 'widgets_init', 'newsletter_widget' );

function newsletter_widget() {
	register_widget( 'Newsletter_Widget' );
}
class Newsletter_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'newsletter_widget', // Base ID
			'Newsletter Signup', // Name
			array( 'description' => __( 'Newsletter Signup', DOMAIN ), ) // Args
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
		$form_id = $instance['form_id'];
		$placeholder = $instance['placeholder'];

		echo $before_widget;
		if ( ! empty( $title ) ) echo $before_title . $title . $after_title;
		?>
<div class="newsletter-form-wrapper">
<form action="http://visitor.constantcontact.com/d.jsp" method="post" target="_blank">
	<div class="form_inner">
    <input type="hidden" name="m" value="<?php echo $form_id; ?>" />
    <input type="hidden" name="p" value="oi" />
    <input type="text" class="short newsletter-input" name="ea" id="ea" size="20" value="" onclick="clickclear(this, <?php echo $placeholder; ?>)" onblur="clickrecall(this, <?php echo $placeholder; ?>)" placeholder="<?php echo $placeholder; ?>"/>
    <input type="submit" name="go" value="Submit" class="submit newsletter-submit" /><div class="clear crush">&nbsp;</div>
    </div>
</form>
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
		$instance['form_id'] = strip_tags( $new_instance['form_id'] );
		$instance['placeholder'] = strip_tags( $new_instance['placeholder'] );
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
		     $form_id = esc_attr($instance['form_id']);
		} else {
		     $form_id = '';
		}
		if( $instance) {
		     $placeholder = esc_attr($instance['placeholder']);
		} else {
		     $placeholder = '';
		}
		// output the html for the custom fields
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'form_id' ); ?>"><?php _e( 'Form ID:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'form_id' ); ?>" name="<?php echo $this->get_field_name( 'form_id' ); ?>" type="text" value="<?php echo esc_attr( $form_id ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'placeholder' ); ?>"><?php _e( 'Placeholder Text:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'placeholder' ); ?>" name="<?php echo $this->get_field_name( 'placeholder' ); ?>" type="text" value="<?php echo esc_attr( $placeholder ); ?>" />
		</p>
		<?php 
	}

} // class Foo_Widget
?>