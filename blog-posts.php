<?php
/**
 * Template Name: News Posts Page
 */
get_header(); 
global $post;
$old_post = $post;
?>
<!-- blog-posts.php  -->
		<div id="page-wrap">
  		<div id="primary" class="<?php ThemeTheme::chp_primary_class(); ?>">
  			<div id="content" role="main" class="<?php ThemeTheme::chp_content_class(); ?>">
            
            <?php
            
              $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
              
              if( !((int)$paged > 1) ):
                $found_posts = 0;
            
                $args = array(
              		'post_type'=>'post',
              		'meta_key'=>'featured_post',
              		'meta_value'=>'1',
              		'orderby'=> 'ID',
              		'order'=> 'DESC'
              	);
              	$saved_features = get_posts($args);
          	  
                foreach( $saved_features as $feature ){
                
                  $the_tags = wp_get_post_tags( $feature->ID );
                  foreach( $the_tags as $t ){
                    ThemeUtil::mpuke($t);
                  }
          
		
		            <?php
		          endif;
		  // show title/content only on specific pages
		  // this would probably be better off as a "hide content on specific pages"
		  // since the only pages it won't show up is on the home page and any specially
		  // coded pages like the property search.  If even that isn't needed, just delete
		  // this conditional altogether and always display title and content.
		  $id = get_the_ID();
		  $suppress_content_ids = array(
		//  2, 	// rent
		//  241 	// learn
		  );
		  if(!in_array($id,$suppress_content_ids)) {
		  ?>
			<div class='content'>
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<div class='entry-content'><?php the_content(); ?></div>
			</div>
		<?php
		}
		?>
        <?php // ThemeUtil::mpuke( $post ); ?>
        
				<?php // get_template_part( 'content', 'page' ); ?>
				<div id="masonry-container">
				<?php
				
          // ThemeUtil::mpuke( $category );
				  $cat_id = get_category_by_slug( $category );
				  
          // $args = array(
          //            'post_type'=>'post',
          //            'orderby'=> 'ID',
          //            'order'=> 'DESC',
          //            'category' => $cat_id->term_id
          //          );
          //          
          //          $this_cat_posts = get_posts($args);
          
          
          $loop = new WP_Query( 
            array(
              'post_type' => 'post',
              'posts_per_page' => 6,
              'orderby'=> 'ID',
              'order'=> 'DESC',
              'cat' => $cat_id->term_id,
              'paged'=>$paged
            ) 
          );
          
          // if(!$paged):
  				  $cnt=0;
  				  if ( $loop->have_posts() ) :
  				    while ( $loop->have_posts() ) : $loop->the_post();
  				      $tcp = get_post(get_the_ID());
  				  // foreach( $this_cat_posts as $k=>$tcp ){ 
  				    ?>
				    
  				    <div class="masonry-box <?php if ($cnt%2==0){ echo " add-rm "; }?>">
  				      <h2 class="avenir <?php if (postHasImages($tcp->ID)) echo " has-slider"; ?>">
                  <a href="<?php echo get_permalink($tcp->ID); ?>"><?php echo $tcp->post_title; ?></a>
                </h2>
                <div style="clear:both"></div>
                <div class="masonry-date">
                  <?php chp_posted_on(); ?>
                </div>
                <div style="clear:both"></div>
                  <?php
                  $attachments = get_children( 
                		array(
                			'post_parent' 		=> $tcp->ID, 
                			'post_status' 		=> 'inherit', 
                			'post_type' 		  => 'attachment', 
                			'post_mime_type'	=> 'image', 
                			'order' 		  	  => 'ASC', 
                			'orderby' 			  => 'menu_order ID',
                			'numberposts'     => 1
                		) 
                	);

                	$size = 'masonry-size';
                	if( count( $attachments ) ): ?>
                    <?php foreach( $attachments as $att ):
              		    $src = wp_get_attachment_image_src( $att->ID );
              		    $img_args['id'] = $att->ID;
              			  $img = wp_get_attachment_image( $att->ID, $size, false, $img_args );
              		    $img_src = wp_get_attachment_image_src( $att->ID, $size ); ?>

              		    <a href="<?php echo get_permalink($tcp->ID); ?>"><img 
              		      src="<?php echo $img_src[0]; ?>" class="masonry-img" title="<?php echo $att->post_title; ?>"
              		      id="masonry-img-<?php echo $att->ID; ?>" /></a>

              		  <?php endforeach; ?>

              	  <?php endif; ?>
				      
  				      <?php 
  				        $excerpt = trim(chp_excerpt( $tcp->post_content, '<div class="more-right">more</div>', get_permalink( $tcp->ID), 30 )); 
  				        if(''!=$excerpt){
  				          echo $excerpt;
  				        }
  				        else{
  				          'No description is available.';
  				        }
  				      ?>
  				      <div style="clear:both"></div>
  				    </div>

  				    <?php
  				    $cnt++;
  				    endwhile;
  				  endif;
  				// endif;
				?>
        </div>
        
        <div class="nagivation">
          <?php if(function_exists('wp_pagenavi')) { wp_pagenavi( array( 'query' => $loop ) ); } ?>
        </div>
        <?php edit_post_link( __( 'Edit', 'chp' ), '<span class="edit-link">', '</span>' ); ?>
        
				<?php // comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->
      
    <?php 
      $post = $old_post;
      get_sidebar(); 
    ?>
    <div class="clearfix"></div>
  </div><!-- #page-wrap -->
<?php get_footer(); ?>

<?php

/* 
get_header(); ?>
  <div id="page-wrap">
		<div id="primary" class="<?php ThemeTheme::chp_primary_class(); ?>">
			<div id="content" role="main" class="<?php ThemeTheme::chp_content_class(); ?>">

			<?php if ( have_posts() ) : ?>

				<?php chp_content_nav( 'nav-above' ); ?>

	
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', get_post_format() ); ?>

				<?php endwhile; ?>

				<?php chp_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'chp' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'chp' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

			</div><!-- #content -->
		</div><!-- #primary -->

    <?php get_sidebar(); ?>
  </div><!-- #page-wrap -->
<?php get_footer(); ?>
<?php */ ?>