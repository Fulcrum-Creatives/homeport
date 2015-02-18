<?php
/* single volunteer view template */
get_header(); ?>
<!-- single-volunteer.php -->
  <div id="page-wrap">
		<div id="primary" class="<?php ThemeTheme::chp_primary_class(); ?>">
			<div id="content" role="main" class="<?php ThemeTheme::chp_content_class(); ?>">
        
        <?php if ( have_posts() ) : ?>
          
          <?php while ( have_posts() ) : the_post(); 
            $custom = get_post_custom( get_the_ID() );
            $volunteer_interests = @unserialize($custom['volunteer_interests'][0]);
            $volunteer_avail = @unserialize($custom['volunteer_avail'][0]);
            $email = $custom['email'];
            $name = $custom['name'];
            
            // ThemeUtil::mpuke($volunteer_interests);
            // ThemeUtil::mpuke($volunteer_avail);
            
            ?>
            
            <!-- <header class="entry-header">
              <h1 class="entry-title"><?php the_title(); ?></h1>
            </header> -->
            	
            <h2 class="property-title"><?php the_title(); ?></h2>
            
            <div class="single-volunteer-content">
              <?php 
                $content = trim(get_the_content());
                if( ""==$content ){
                  echo "No volunteer comments is available.";
                } 
                else{
                  echo $content;
                }
              ?>
            </div>
            
            <?php // edit_post_link( __( 'Edit', 'chp' ), '<span class="edit-link">', '</span>' ); ?>
            
            
            
          <?php endwhile; ?>
          
        <?php endif; ?>

      </div><!-- #content -->
		</div><!-- #primary -->
      
    <?php get_sidebar(); ?>
    <div class="clearfix"></div>
  </div><!-- #page-wrap -->
<?php get_footer(); 

?>