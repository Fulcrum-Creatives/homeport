<?php
/**
 * Template Name: Category Listing Template
 * Description: A Page Template that adds a sidebar to pages
 */

get_header(); 
global $post;
$old_post = $post;
?>
<!-- tag.php  -->
		<div id="page-wrap">
  		  <div id="page-wrap">
		<div id="primary" class=" primary-post primary-tag">
			<div id="content" role="main" class="content-post content-tag">

        <h1 class="page-title">
          <?php printf( __( 'Tag Archives: %s', 'chp' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?>
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
            $excerpt = trim(chp_search_excerpt( $post->post_content, '...', get_permalink( $post->ID ), 40 )); 
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
			
			</div><!-- #content -->
		</div><!-- #primary -->

    <?php get_sidebar(); ?>
    <div class="clearfix"></div>
  </div><!-- #page-wrap -->
<?php get_footer(); ?>