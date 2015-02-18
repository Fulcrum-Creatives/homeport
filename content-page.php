<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
 $post_titles_to_suppress = array(
   'Events'
 );
 // echo get_the_title();
?>
<!-- content-page.php  -->
<article id="post-<?php the_ID(); ?>" <?php post_class('content'); ?>>
	<header class="entry-header">
		<!-- <h1 class="entry-title"><?php the_title(); ?></h1> -->
		<?php if( !in_array(get_the_title(), $post_titles_to_suppress )) :?>
		<?php the_post_thumbnail('main-size'); ?>
  		<h1 class="entry-title"><?php the_title(); ?></h1>
    <?php endif; ?>
	</header><!-- .entry-header -->
	<!---
	<ul class="subnav">
	<?php
		$args = array(
			'child_of'     => get_the_ID(),
			'title_li'     => null,
		);
		wp_list_pages( $args );
	?>
	</ul>
	-->
	<div class="entry-content">
		<?php the_content(); ?>
        
             
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'chp' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	<footer class="entry-meta">
		<?php edit_post_link( __( 'Edit', 'chp' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
