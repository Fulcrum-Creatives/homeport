    </div><!-- /#wrap -->
  </div><!-- wrap-outer -->
  
  <div id="footer">
    <div id="footer-inner">
      <div id="address">
        <div class="inner">
          <p class="hp-info">Homeport | 562 East Main Street | Columbus, Ohio 43215 | 614.221.8889</p>
          <p>
          <a href="http://homeportohio.org/privacy-policy/">Terms of Use | Privacy Policy</a>
          </p>
      </div>
    </div>
    <div id="imgs">
        <img src="<?php bloginfo('stylesheet_directory');?>/images/bottom-logo-2.png" width="65" height="40" alt="BBB Accredited Charity" />
        <img src="<?php bloginfo('stylesheet_directory');?>/images/bottom-logo.png" width="135" height="40" alt="Neighborhood Works Charter Member" />
      </div>
  </div>
  </div>
  <div id="debug">
  
    <?php // ThemeAdmin::debug_dump(); ?>
  </div>
  <?php wp_footer(); ?>
  
  
  
 <!-- old google code <?php
    $ga_tracker = get_option( THEME_NICE_NAME . "_google_analytics_key", '' );
    if( !empty( $ga_tracker ) ):
    ?>
  		<script type="text/javascript"> 
      var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
      document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
      </script> 
      <script type="text/javascript"> 
      try {
      var pageTracker = _gat._getTracker("<?php echo $ga_tracker ?>");
      pageTracker._trackPageview();
      } catch(err) {}</script>
    <?php 
   endif; ?> -->
   
   
   <!-- Google Analytics -->
   
    <?php $ga_tracker = get_option( THEME_NICE_NAME . "_google_analytics_key", '' );
    if( !empty( $ga_tracker ) ):
    ?>
    
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', '<?php echo $ga_tracker ?>', 'auto');
ga('send', 'pageview');

</script>

 <?php 
   endif; ?>

<!-- End Google Analytics -->
   
   
</body>
</html>