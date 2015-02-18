<?php

add_action( 'widgets_init', 'social_links_widget' );

function social_links_widget() {
	register_widget( 'Social_links_Widget' );
}
class Social_links_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'social_links_widget', // Base ID
			'Social Links', // Name
			array( 'description' => __( 'Social Network Links', DOMAIN ), ) // Args
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
		$facebook_url = $instance['facebook_url'];
		$twitter_url = $instance['twitter_url'];
		$linkedin_url = $instance['linkedin_url'];
		$youtube_url = $instance['youtube_url'];
		$flikr_url = $instance['flikr_url'];
		$rss_url = $instance['rss_url'];

		echo $before_widget;
		?>
		<div class="social-links-widget">
			<?php
			if ( ! empty( $title ) )
				echo $before_title . $title . $after_title;
			?>
			<ul class="social-links-list">
				<?php if ( !empty( $facebook_url )) : ?>
					<li class="social-links-item">
						<a href="<?php echo $facebook_url; ?>" class="facebook-icon"></a>
					</li>
				<?php endif; ?>
				<?php if ( !empty( $twitter_url )) : ?>
					<li class="social-links-item">
						<a href="<?php echo $twitter_url; ?>" class="twitter-icon"></a>
					</li>
				<?php endif; ?>
				<?php if ( !empty( $linkedin_url )) : ?>
					<li class="social-links-item">
						<a href="<?php echo $linkedin_url; ?>" class="linkedin-icon"></a>
					</li>
				<?php endif; ?>
				<?php if ( !empty( $youtube_url )) : ?>
					<li class="social-links-item">
						<a href="<?php echo $youtube_url; ?>" class="youtube-icon"></a>
					</li>
				<?php endif; ?>
				<?php if ( !empty( $flikr_url )) : ?>
					<li class="social-links-item">
						<a href="<?php echo $flikr_url; ?>" class="flikr-icon"></a>
					</li>
				<?php endif; ?>
				<?php if ( !empty( $rss_url )) : ?>
					<li class="social-links-item">
						<a href="<?php echo $rss_url; ?>" class="rss-icon"></a>
					</li>
				<?php endif; ?>
			</ul>
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
		$instance['facebook_url'] = strip_tags( $new_instance['facebook_url'] );
		$instance['twitter_url'] = strip_tags( $new_instance['twitter_url'] );
		$instance['linkedin_url'] = strip_tags( $new_instance['linkedin_url'] );
		$instance['youtube_url'] = strip_tags( $new_instance['youtube_url'] );
		$instance['flikr_url'] = strip_tags( $new_instance['flikr_url'] );
		$instance['rss_url'] = strip_tags( $new_instance['rss_url'] );
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
		     $facebook_url = esc_attr($instance['facebook_url']);
		     $twitter_url = esc_attr($instance['twitter_url']);
		     $linkedin_url = esc_attr($instance['linkedin_url']);
		     $youtube_url = esc_attr($instance['youtube_url']);
		     $flikr_url = esc_attr($instance['flikr_url']);
		     $rss_url = esc_attr($instance['rss_url']);
		} else {
		     $title = '';
		     $facebook_url = '';
		     $twitter_url = '';
		     $linkedin_url = '';
		     $youtube_url = '';
		     $flikr_url = '';
		     $rss_url = '';
		}
		// output the html for the custom fields
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'facebook_url' ); ?>"><?php _e( 'Facebook:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'facebook_url' ); ?>" name="<?php echo $this->get_field_name( 'facebook_url' ); ?>" type="text" value="<?php echo esc_attr( $facebook_url ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'twitter_url' ); ?>"><?php _e( 'Twitter:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'twitter_url' ); ?>" name="<?php echo $this->get_field_name( 'twitter_url' ); ?>" type="text" value="<?php echo esc_attr( $twitter_url ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'linkedin_url' ); ?>"><?php _e( 'LinkedIn:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'linkedin_url' ); ?>" name="<?php echo $this->get_field_name( 'linkedin_url' ); ?>" type="text" value="<?php echo esc_attr( $linkedin_url ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'youtube_url' ); ?>"><?php _e( 'Youtube:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'youtube_url' ); ?>" name="<?php echo $this->get_field_name( 'youtube_url' ); ?>" type="text" value="<?php echo esc_attr( $youtube_url ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'flikr_url' ); ?>"><?php _e( 'Flikr:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'flikr_url' ); ?>" name="<?php echo $this->get_field_name( 'flikr_url' ); ?>" type="text" value="<?php echo esc_attr( $flikr_url ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'rss_url' ); ?>"><?php _e( 'RSS:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'rss_url' ); ?>" name="<?php echo $this->get_field_name( 'rss_url' ); ?>" type="text" value="<?php echo esc_attr( $rss_url ); ?>" />
		</p>
		<?php 
	}

} // class Foo_Widget
?>