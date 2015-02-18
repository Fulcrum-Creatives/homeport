<?php
/******************************************************************************/
/**************************** WARNING! DO NOT EDIT! ***************************/
/****** Editing the following code may result in the Website not Working ******/
/******************************************************************************/
$theme_folder = basename(dirname(dirname(__FILE__)));   // The name of the folder for your theme
$theme_domain = strtolower( str_replace( ' ', '-', wp_get_theme( '', 'name' ) ) );    // The name of the domain for your theme
/* ==========================================================================
  Declare Constant Variables
============================================================================= */
/**
  * Theme Name Constant
  *   The name constant of your theme
  * example: <?php echo THEME_NAME; ?>
  *
  */ 
  if ( !defined( 'THEME_NAME' ) ) {
    define( 'THEME_NAME', $theme_name );
  }
/**
  * Content Directory Path Constant
  *   The absolte path to your content folder
  * example: <?php include(CONTENT_DIR . 'file.php'); ?>
  */
  if ( !defined( 'CONTENT_DIR' ) ){
    define( 'CONTENT_DIR', ABSPATH . 'wp-content' );
  }
/**
  * Theme Directory Constant
  *   The absolte path to your theme folder
  * example: <?php include(THEME_DIR . 'file.php'); ?>
  */
  if ( !defined( 'THEME_DIR' ) ) {
    define( 'THEME_DIR', WP_CONTENT_DIR . '/themes/'. $theme_folder .'/' );
  }
/**
  * Theme ULR Constant
  *   The ULR to your theme folder
  * example: <img src="<?php echo THEME_URL; ?>images/image.jpg" />
  */
  if ( !defined( 'THEME_URL' ) ) {
    define( 'THEME_URL', WP_CONTENT_URL . '/themes/' . $theme_folder . '/' );
  }
/**
  * Theme Domain Constant
  *   The domain constant of your theme
  * example: <?php echo DOMAIN_SLUG; ?>
  */ 
  if ( !defined( 'DOMAIN' ) ) {
    define( 'DOMAIN', $theme_domain );
  } 
/******************************************************************************/  
/**************************** DO NOT EDIT ABOVE! ******************************/
/******************************************************************************/
?>