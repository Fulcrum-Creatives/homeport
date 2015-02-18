<?php
/**
 * Template Name: Rent Page
 */
?>
<?php get_header(); ?>
<div id="page-wrap" class="cf">
    <div id="primary" class="<?php ThemeTheme::chp_primary_class(); ?>">
        <div id="content" role="main" class="<?php ThemeTheme::chp_content_class(); ?>">
            <?php ThemeTheme::front_page_slider(); ?>
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <div class='content'>
                <h1 class="entry-title"><?php the_title(); ?></h1>
                <div class='entry-content'><?php the_content(); ?></div>
            </div>
        </div>
    </div>
    <?php endwhile; endif; ?>
    <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>