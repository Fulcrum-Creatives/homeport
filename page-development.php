<?php
/**
 * Template Name: Development Page Template
 * Description: A Page Template that adds a sidebar to pages
 */

get_header(); ?>
<!-- page-development.php  -->
		<div id="page-wrap">
  		<div id="primary" class="<?php ThemeTheme::chp_primary_class(); ?>">
  			<div id="content" role="main" class="<?php ThemeTheme::chp_content_class(); ?>">

				<?php the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php // comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->
      
    <?php get_sidebar(); ?>
    <div class="clearfix"></div>
  </div><!-- #page-wrap -->
<?php get_footer(); ?>