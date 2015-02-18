<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>
  <div id="page-wrap">
		<div id="primary" class="<?php ThemeTheme::chp_primary_class(); ?>">
			<div id="content" role="main" class="<?php ThemeTheme::chp_content_class(); ?>">
        
        <?php // get_search_form(); ?>
        
        <?php chp_content_nav( 'nav-above' ); ?>
        
        <h1 class="page-title">
          <?php printf( __( 'Search Results for: %s', 'chp' ), '<span>' . get_search_query() . '</span>' ); ?>
        </h1>
        <div class="search-results">
    			<?php  while ( have_posts() ) : the_post();
            // $pid = get_the_ID(); $post = get_post( $pid ); 
    			  ?>
			
    			  <div class="search-result">
                 
    			    <h1>
    			      <a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a>
    			    </h1>
                    <div class="entry-meta">Posted on: <?php chp_posted_on(); ?>  <div class="cats">Posted in: <?php the_category(', '); ?></div>  <div class="tags"><?php the_tags( 'Tagged: ',', ' ); ?></div> </div>
                     <?php
            $excerpt = trim(chp_search_excerpt( $post->post_content, 'Read More...', get_permalink( $post->ID ), 40 )); 
  	        if(''!=$excerpt){
  	          echo $excerpt;
  	        }
  	        else{
  	          echo 'No description is available.';
  	        }
            ?>
            
           
            
    			  </div>
			
    			<?php endwhile; ?>
  			</div>
			  <?php chp_content_nav( 'nav-below' ); ?>
			  
			</div><!-- #content -->
		</div><!-- #primary -->

    <?php get_sidebar(); ?>
    <div class="clearfix"></div>
  </div><!-- #page-wrap -->
<?php get_footer(); ?>

<?php

/*
<?php if ( have_posts() ) : ?>

	<header class="page-header">
		<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'chp' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
	</header>
  <textarea style="width:100%;height:400px">
	<?php // chp_content_nav( 'nav-above' ); ?>

	<?php 
	  while ( have_posts() ) : the_post(); ?>

      <h1><?php $title = get_the_title(); echo $title; ?></h1>
			<?php
				// get_template_part( 'content', get_post_format() );
		endwhile
		?>
  </textarea>
	<?php // chp_content_nav( 'nav-below' ); ?>

<?php else : ?>

	<article id="post-0" class="post no-results not-found">
		<header class="entry-header">
			<h1 class="entry-title"><?php _e( 'Nothing Found', 'chp' ); ?></h1>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'chp' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->
	</article><!-- #post-0 -->

<?php endif; ?>

*/

?>