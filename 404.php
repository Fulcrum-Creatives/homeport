<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>
<!-- 404.php -->
  <div id="page-wrap">
		<div id="primary" class="<?php ThemeTheme::chp_primary_class(); ?>">
			<div id="content" role="main" class="<?php ThemeTheme::chp_content_class(); ?>">

        <div id="four_oh_four">
				  <h1>File Not Found</h1>
          <p>You 
          <?php
          #some variables for the script to use
          #if you have some reason to change these, do.  but wordpress can handle it
          $adminemail = get_option('admin_email'); #the administrator email address, according to wordpress
          $website = get_bloginfo('url'); #gets your blog's url from wordpress
          $websitename = get_bloginfo('name'); #sets the blog's name, according to wordpress

            if (!isset($_SERVER['HTTP_REFERER'])) {
              #politely blames the user for all the problems they caused
                  echo "tried going to "; #starts assembling an output paragraph
          	$casemessage = "All is not lost!";
            } elseif (isset($_SERVER['HTTP_REFERER'])) {
              #this will help the user find what they want, and email me of a bad link
          	echo "clicked a link to"; #now the message says You clicked a link to...
                  #setup a message to be sent to me
          	$failuremess = "A user tried to go to $website"
                  .$_SERVER['REQUEST_URI']." and received a 404 (page not found) error. ";
          	$failuremess .= "It wasn't their fault, so try fixing it.  
                  They came from ".$_SERVER['HTTP_REFERER'];
            // mail($adminemail, "Bad Link To ".$_SERVER['REQUEST_URI'],
            //                   $failuremess, "From: $websitename <noreply@$website>"); #email you about problem
          	$casemessage = "An administrator has been emailed 
                  about this problem, too.";#set a friendly message
            }
            echo " ".$website.$_SERVER['REQUEST_URI']; ?> 
          and it doesn't exist. <?php echo $casemessage; ?>  You can click back 
          and try again or search for what you're looking for:
            <?php include(TEMPLATEPATH . "/searchform.php"); ?>
          </p>
				</div>
				
			</div><!-- #content -->
		</div><!-- #primary -->
      
    <?php get_sidebar(); ?>
    <div class="clearfix"></div>
  </div><!-- #page-wrap -->
<?php get_footer(); ?>