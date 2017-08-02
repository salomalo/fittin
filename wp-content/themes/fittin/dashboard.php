<?php
/**
 * Template Name: Dashboard
 *
 * For all pages including login and logout & member dashboards
 *
 * @package WooFramework
 * @subpackage Template
 */

 ?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
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

         <img src="<?php echo get_stylesheet_directory_uri() ?>/images/member-icon.png" style="width: 25px; float: right;" id="member-nav-icon-close">

        <ul>
            <li><a href="/home-1/">Dashboard</a></li>
            <li><a href="/videos/">All Videos</a></li>
            <li><a href="/myaccount/">My Account</a></li>
            <li><a href="/home-1/#stats">Statistics</a></li>
            <li><a href="/logout/">Logout</a></li>
        </ul>

    </div>

	<header id="header" class="member-head">

        <div id="wrapper">

	<div id="inner-wrapper">


		<a href="/" title="Online fitness tools for schools"><img src="/wp-content/uploads/2017/03/logo.png" alt="Fitt-In" width="100" style="float: left;"></a>

        <div class="logo-strap">Movement breaks for mind and body</div>

        <img src="<?php echo get_stylesheet_directory_uri() ?>/images/member-icon-white.png" style="width: 25px; float: right; margin-right: 30px; margin-top: 5px;" id="member-nav-icon">

		<div style="clear:both;"></div>

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
            <?php woo_main_before(); ?>
            <section id="main" class="col-left">

			<?php
                if ( is_home() && is_active_sidebar( 'homepage' ) ) {
                    dynamic_sidebar( 'homepage' );
                } else {
                    get_template_part( 'loop', 'index' );
                }
            ?>

            </section><!-- /#main -->
            <?php woo_main_after(); ?>



            <?php


                $favs = get_user_favorites($user_id = null, $site_id = null, $filters = null);

                $test = reset($favs);





                if (is_array($test))  {

                    echo "<p>You don't have any favourite's yet? Head to the video library now!</p>";

                     } else {
					echo '<div class="favourites">';
                    $vidcounts = 0;

                    foreach($favs as $fav) {

						$thumb_id = get_post_thumbnail_id($fav);
						$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail-size', true);
						$thumb_url = $thumb_url_array[0];
						$vimid = get_field("vimeo_id_number", $fav);
						$videotitle = get_the_title($fav);
						$vidlink = get_permalink($fav);

						if ( $vidcounts < 3 ) {

							echo do_shortcode('[fourcol_one]<div class="video-card" style="margin-bottom:15px;">[video_lightbox_vimeo5 video_id="'.$vimid.'" width="640" height="480" anchor="'.$thumb_url.'"]</div><h5><a href="'.$vidlink.'">'.$videotitle.'</a></h5>[/fourcol_one]');

							$vidcounts = $vidcounts + 1;

                        } else {
							$vidcounts = 0;
							echo do_shortcode('[fourcol_one_last]<div class="video-card" style="margin-bottom:15px;">[video_lightbox_vimeo5 video_id="'.$vimid.'" width="640" height="480" anchor="'.$thumb_url.'"]</div><h5><a href="'.$vidlink.'">'.$videotitle.'</a></h5>[/fourcol_one_last]');
                        }
                    } // foreach
					echo '</div><!--favourites-->';
                }
            ?>



			<div style="clear: both;"></div>
			<p style="text-align: right;"><a href="/videos/">View All</a></p>


			<hr />

			&nbsp;
			<h5>Recommended Videos</h5>
			<div style="clear: both;"></div>
			<?php echo do_shortcode('[ess_grid alias="fav"]'); ?>
			<div style="clear: both;"></div>
			<p style="text-align: right;"><a href="/videos/">View All</a></p>


			<hr />

			&nbsp;
			<h5 id="stats">Statistics</h5>
			<?php include( 'lib/display-stats.php' ); ?>


		</div><!-- /#main-sidebar-container -->



    </div><!-- /#content -->
	<?php woo_content_after(); ?>

<!-- FOOTER -->

 <img src="<?php echo get_stylesheet_directory_uri() ?>/images/member-bg.png" style="width: 100%;">


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
