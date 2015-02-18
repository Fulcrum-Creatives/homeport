<?php

add_action( 'widgets_init', 'rental_info_widget' );

function rental_info_widget() {
	register_widget( 'Rental_Info_Widget' );
}
class Rental_Info_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'rental_info_widget', // Base ID
			'Rental Information', // Name
			array( 'description' => __( 'Rental Information Widget', DOMAIN ), ) // Args
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
		echo $before_widget;
		if (have_posts()) : while (have_posts()) : the_post();
		$custom = get_post_custom( $post->ID );
		$management_company = $custom["management_company"][0];
    	$management_phone = $custom["management_phone"][0];
    	$management_web = $custom["management_web"][0];
		$neighborhood  = $custom["neighborhood"][0];
		$address = $custom["address"][0];
		$occupancy_type = $custom["occupancy_type"][0];
		$num_beds = $custom["num_beds"][0];
		$price = $custom["price"][0];
		
		$sqfootage = $custom['square_footage'][0];
		$stories = $custom['stories'][0];
		$num_baths = $custom['num_baths'][0];
		$garage = $custom['garage'][0];
		
		?>
		<div class="rental-property-info">
			<h3 class="property-info-heading"><?php echo $title; ?></h3>

			<div class="pi-management-image">
				<a href="<?php echo $management_web; ?>">
					<img src="<?php echo wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>" />
				</a>
			</div>

			<?php if( $management_company ) : ?>
			<div class="pi-management info-block">
				<p><strong>Managed by:</strong></p>
				<p><a href="<?php echo $management_web; ?>"><?php  echo $management_company; ?></a></p>
			</div>
			<?php endif; ?>

			<?php if( $management_phone ) : ?>
			<div class="pi-management-phone info-block">
				<p><strong>Phone:</strong></p>
				<p><?php echo $management_phone ?></p>
			</div>
			<?php endif; ?>

			<?php if( $neighborhood ) : ?>
				<div class="pi-part-of-town info-block">
					<p><strong>Part of Town:</strong></p>
					<p><?php echo $neighborhood; ?></p>
				</div>
			<?php endif; ?>

			<?php if( $address ) : ?>
			<div class="pi-address info-block">
				<p><strong>Address:</strong></p>
				<p><?php echo $address; ?></p>
			</div>
			<?php endif; ?>

			<?php if( $occupancy_type ) : ?>
			<div class="pi-dwelling info-block">
				<p><strong>Type of Dwelling:</strong></p>
				<p><?php echo $occupancy_type; ?></p>
			</div>
			<?php endif; ?>
			
			
			<?php if( $sqfootage ) : ?>
			<div class="pi-dwelling info-block">
				<p><strong>Square Footage:</strong> <?php echo $sqfootage; ?></p>
			</div>
			<?php endif; ?>
			
			<?php if( $stories ) : ?>
			<div class="pi-dwelling info-block">
				<p><strong>Stories:</strong> <?php echo $stories; ?></p>
			</div>
			<?php endif; ?>
			

			<?php if( $num_beds ) : ?>
			<div class="pi-bedrooms info-block">
				<p><strong>Bedrooms:</strong> <?php echo $num_beds; ?></p>
			</div>
			<?php endif; ?>
			
				<?php if( $num_baths ) : ?>
			<div class="pi-bathrooms info-block">
				<p><strong>Bathrooms:</strong> <?php echo $num_baths; ?></p>
			</div>
			<?php endif; ?>
			
			<?php if( $garage ) : ?>
			<div class="pi-dwelling info-block">
				<p><strong>Garage:</strong> <?php echo $garage; ?></p>
			</div>
			<?php endif; ?>
			

			<?php if( $price ) : ?>
			<div class="pi-price info-block">
				<p><strong>Price:</strong> $<?php echo $price; ?></p>
			</div>
			<?php endif; ?>
		</div>
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
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
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
		if( $instance) {
		     $title = esc_attr($instance['title']);
		} else {
		     $title = '';
		}
		// output the html for the custom fields
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

} // class Foo_Widget
?>