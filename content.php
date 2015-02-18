<?php
/**
 * The default template for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
  $custom = get_post_custom( get_the_ID() );
  $has_rsvp = $custom['has_rsvp'][0];
  $event_type = $custom['event_type'][0];
  $is_event = $custom['is_event'][0];
  $event_date = $custom['event_date'][0];

  $chp_month_num = $custom['chp_month_num'][0];
  $chp_month_name = $custom['chp_month_name'][0];
  $chp_day_num = $custom['chp_day_num'][0];
  $chp_day_name = $custom['chp_day_name'][0];
  $chp_year = $custom['chp_year'][0];
  $event_hours = $custom['event_hours'][0];
 
 
	if( $custom['is_event_multiday'][0] ) {
		$end_date = $custom['event_date_to'][0];
		$end_date_time = strtotime($end_date);
		$end_date_day = date("j",$end_date_time);
		$end_date_month = date("F",$end_date_time);
		$end_date_year = date("Y",$end_date_time);
		$start_date = $custom['event_date'][0];
		$start_date_time = strtotime($start_date);
		$start_date_day = date("j",$start_date_time);
		$start_date_month = date("F",$start_date_time);
		$start_date_year = date("Y",$start_date_time);
		
		if($end_date_year == $start_date_year) {
			if($end_date_month == $start_date_month) {
				$d = $start_date_month . ' ' . $start_date_day . "-" . $end_date_day . ', ' . $start_date_year;
			}
			else {
				$d = $start_date_month . ' ' . $start_date_day . " to " . $end_date_month . ' ' . $end_date_day . ', ' . $start_date_year;
			}
		}
		else {
			$d = $start_date_month . ' ' . $start_date_day . ", " . $start_date_year . ' to ' . $end_date_month . ' ' . $end_date_day . ', ' . $end_date_year;
		}
	}
	else {
		$d = $custom['chp_month_name'][0] . ' ' . $custom['chp_day_num'][0] . ", " . $custom['chp_year'][0] . "<br />" . $custom['event_hours'][0];
	}
		  
  
  // ThemeUtil::mpuke( $custom );
  ?>
  <!-- content.php -->
  <?php postSingleViewImages(get_the_ID(), 'main-size' ) ?>
  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header <?php if(postHasImages(get_the_ID())) echo "has-slider"; ?>"">
		  
		  
			<?php if ( is_sticky() ) : ?>
				<hgroup>
					<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'chp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
					<h3 class="entry-format"><?php _e( 'Featured', 'chp' ); ?></h3>
				</hgroup>
			<?php else : ?>
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'chp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
			<?php endif; ?>
      
			<?php if ( 'post' == get_post_type() ) : 
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'chp' ) );
				if ( $categories_list && !is_event ):
			?>
			<div class="entry-meta">
				<?php chp_posted_on(); ?>
				<span class="cat-links">
  				<?php printf( __( '<span class="%1$s">Posted in</span> %2$s', 'chp' ), 'entry-utility-prep entry-utility-prep-cat-links', $categories_list );
  				$show_sep = true; ?>
  			</span>
			</div><!-- .entry-meta -->
			<?php endif; ?>

      <?php /* ?>
			<?php if ( comments_open() && ! post_password_required() ) : ?>
			<div class="comments-link">
				<?php comments_popup_link( '<span class="leave-reply">' . __( 'Reply', 'chp' ) . '</span>', _x( '1', 'comments number', 'chp' ), _x( '%', 'comments number', 'chp' ) ); ?>
			</div>
			<?php endif; ?>
			<?php */ ?>
		</header><!-- .entry-header -->
    
    
    
		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content">
		   <?php if ( $is_event ) : ?>
            <!-- <h3>Event Details</h3> -->

            <div class="event-date">
            <h3>
			Event Date: <?php echo $d; ?><br />
                </h3>
            </div>
              <?php 
            endif;
                // ThemeUtil::mpuke($custom); 
              ?>
      
      
      
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'chp' ) ); ?>
			
			<?php if ( $is_event ) : ?>
          <!-- <h3>Event Details</h3> -->

            <!-- <div class="event-date-big">
              <div style="font-size:11px!important;margin:0 0 6px 0"><?php echo $custom['chp_day_name_short'][0]?></div>
              <?php echo $custom['chp_month_name_short'][0]?><br><?php echo $custom['chp_day_num'][0]?><br>
              <div style="font-size:14px!important;margin:0 0 6px 0">
                <?php echo $custom['chp_year'][0]?>             </div>
              <div style="font-size:11px!important;margin:0 0 -2px 0">
                <?php echo $custom['event_hours'][0]?>              </div>
            </div> -->
            <?php 

              // ThemeUtil::mpuke($custom); 
            ?>

        

      <?php endif; ?>
			
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'chp' ) . '</span>', 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
		<?php endif; ?>

		<footer class="entry-meta">
			<?php $show_sep = false; ?>
			
			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'chp' ) );
				if ( $tags_list ):
				if ( $show_sep ) : ?>
			<span class="sep"> | </span>
				<?php endif; // End if $show_sep ?>
			<span class="tag-links">
				<?php printf( __( '<span class="%1$s">Tagged</span> %2$s', 'chp' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list );
				$show_sep = true; ?>
			</span>
			<?php endif; // End if $tags_list ?>
			<?php endif; // End if 'post' == get_post_type() ?>

      <?php /* ?>
			<?php if ( comments_open() ) : ?>
			<?php if ( $show_sep ) : ?>
			<span class="sep"> | </span>
			<?php endif; // End if $show_sep ?>
			<span class="comments-link"><?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a reply', 'chp' ) . '</span>', __( '<b>1</b> Reply', 'chp' ), __( '<b>%</b> Replies', 'chp' ) ); ?></span>
			<?php endif; // End if comments_open() ?>
      <?php */ ?>
			<?php edit_post_link( __( 'Edit', 'chp' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- #entry-meta -->
	</article><!-- #post-<?php the_ID(); ?> -->
