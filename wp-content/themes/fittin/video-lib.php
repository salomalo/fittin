<?php
/**
 * Template Name: Video Library
 *
 * 
 *
 * @package WooFramework
 * @subpackage Template
 */

 ?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>" />
<title><?php woo_title(); ?></title>
<?php woo_meta(); ?>
<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>" />
<?php wp_head(); ?>
<?php woo_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php woo_top(); ?>


	<?php woo_header_before(); ?>
    
    <div class="member-nav" id="member-nav">
    
         <img src="/images/member-icon.png" style="width: 25px; float: right;" id="member-nav-icon-close">
        
        <ul>
            <li><a href="/home-1/">Dashboard</a></li>
            <li><a href="/videos/">Videos</a></li>
            <li><a href="/myaccount/">My Account</a></li>
            <li><a href="/home-1/#stats">Statistics</a></li>
            <li><a href="/logout/">Logout</a></li>
        </ul>
     
    </div>

	<header id="header" class="member-head">
        
        <div id="wrapper">

	<div id="inner-wrapper">
        

		<a href="/" title="Online fitness tools for schools"><img src="/wp-content/uploads/2017/03/logo.png" alt="Fitt-In" width="65"></a>
        
        
        
         <img src="/wp-content/themes/fittin/images/member-icon-white.png" style="width: 25px; float: right; margin-right: 30px; margin-top: 5px;" id="member-nav-icon">

	</header>
	<?php woo_header_after(); ?>
            
            </div>
        
        </div>
 

    <div id="wrapper">

	<div id="inner-wrapper">
    
<? global $woo_options;
?>      
    
    <!-- #content Starts -->
	<?php woo_content_before(); ?>
    <div id="content" class="col-full">
    
    	<div id="main-sidebar-container">    
            
            
		
            <!-- #main Starts -->
            
            <section id="main" class="col-left">
                
                <h4><b>Welcome to the Video Library,</b> make your selection below...</h4>
                <br><br>
            
                <?php
                
                // WP_Query arguments
                $args = array(
                    'post_type'              => array( 'fitt_in_videos' ),
                );

                // The Query
                $vidquery = new WP_Query( $args );

                // The Loop

                $vidcounts = 0;

                if ( $vidquery->have_posts() ) {
                    while ( $vidquery->have_posts() ) {
                        $vidquery->the_post();
                        
                        $thumb_id = get_post_thumbnail_id();
                        $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail-size', true);
                        $thumb_url = $thumb_url_array[0];
                        $vimid = get_field("vimeo_id_number");
                        $videotitle = get_the_title();
                        
                        
                        if ( $vidcounts < 3 ) {
                        
                            echo do_shortcode('[fourcol_one]<div class="video-card" style="margin-bottom:15px;">[video_lightbox_vimeo5 video_id="'.$vimid.'" width="640" height="480" anchor="'.$thumb_url.'"]</div><h5>'.$videotitle.'</h5>[/fourcol_one]'); 
                        
                            $vidcounts = $vidcounts + 1;    
                        
                        } else {
                        
                            $vidcounts = 0;
                            
                             echo do_shortcode('[fourcol_one_last]<div class="video-card" style="margin-bottom:15px;">[video_lightbox_vimeo5 video_id="'.$vimid.'" width="640" height="480" anchor="'.$thumb_url.'"]</div><h5>'.$videotitle.'</h5>[/fourcol_one_last]');
                              
                            }
                        
                                
                                
                    }
                    
                } else {
                    // no posts found
                }

                // Restore original Post Data
                wp_reset_postdata(); ?>
                     
                
                <div style="clear:both;"></div>
                
			
                    
            </section><!-- /#main -->
            <?php woo_main_after(); ?>
    
            <?php get_sidebar(); ?>
    
		</div><!-- /#main-sidebar-container -->         

		<?php get_sidebar( 'alt' ); ?>       

    </div><!-- /#content -->
	<?php woo_content_after(); ?>
		
<!-- FOOTER -->


 <img src="/wp-content/themes/fittin/images/member-bg.png" style="width: 100%;">


	</div><!-- /#inner-wrapper -->

</div><!-- /#wrapper -->

<div class="fix"></div><!--/.fix-->

<?php wp_footer(); ?>
<?php woo_foot(); ?>
    
    
<script>
   
    jQuery.noConflict();
    (function ($) {
        function readyFn() {
            $( "#member-nav-icon" ).mouseover(function() {
              $( "#member-nav" ).toggle();
            });
        }

        $(document).ready(readyFn); 
    })(jQuery);
    
     jQuery.noConflict();
    (function ($) {
        function readyFn() {
            $( "#member-nav" ).mouseleave(function() {
              $( "#member-nav" ).toggle();
            });
        }

        $(document).ready(readyFn); 
    })(jQuery);

</script>    
    
   
       
</body>
</html>
        
        