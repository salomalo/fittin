    <?php
/**
 * Single Post Template
 *
 * This template is the default page template. It is used to display content when someone is viewing a
 * singular view of a post ('post' post_type).
 * @link http://codex.wordpress.org/Post_Types#Post
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

         <img src="http://fittin.wpengine.com/wp-content/themes/fittin/images/member-icon.png" style="width: 25px; float: right;" id="member-nav-icon-close">

        <ul>
            <li><a href=" https://fittin.wpengine.com/home-1/">Dashboard</a></li>
            <li><a href="https://fittin.wpengine.com/videos/">Videos</a></li>
            <li><a href="https://fittin.wpengine.com/myaccount/">My Account</a></li>
            <li><a href="https://fittin.wpengine.com/home-1/#stats">Statistics</a></li>
            <li><a href="https://fittin.wpengine.com/logout/">Logout</a></li>
        </ul>

    </div>

    <header id="header" class="member-head">

          <div id="wrapper">

  	<div id="inner-wrapper">


  		<a href="http://fittin.wpengine.com/" title="Online fitness tools for schools"><img src="http://fittin.wpengine.com/wp-content/uploads/2017/03/logo.png" alt="Fitt-In" width="100" style="float: left;"></a>

          <div class="logo-strap">Movement breaks for mind and body</div>

          <img src="http://fittin.wpengine.com/wp-content/themes/fittin/images/member-icon-white.png" style="width: 25px; float: right; margin-right: 30px; margin-top: 5px;" id="member-nav-icon">

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

        <a onclick="goBack()" ><i class="fa fa-chevron-left" aria-hidden="true"></i> &nbsp;Back</a>

            <!-- #main Starts -->
            <?php woo_main_before(); ?>
            <section id="main">


<?php
	woo_loop_before();

        $vimid = get_field("vimeo_id_number");
        $videotitle = get_the_title();



    echo '<center><iframe src="https://player.vimeo.com/video/'.$vimid.'" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen class="vimeo-card"></iframe><br></center><br><h3>'.$videotitle.'</h3>';

the_favorites_button($post_id, $site_id);

echo '<br><h6>Rating:</h6><br>';

echo  do_shortcode('[ec_stars_rating]');

echo '<br>';


	if (have_posts()) { $count = 0;
		while (have_posts()) { the_post(); $count++;

			woo_get_template_part( 'content', get_post_type() ); // Get the post content template file, contextually.
		}
	}

	woo_loop_after();
?>
            </section><!-- /#main -->
            <?php woo_main_after(); ?>

            <?php get_sidebar(); ?>

		</div><!-- /#main-sidebar-container -->

		<?php get_sidebar('alt'); ?>

    </div><!-- /#content -->
	<?php woo_content_after(); ?>

<!-- FOOTER -->






 <img src="http://fittin.wpengine.com/wp-content/themes/fittin/images/member-bg.png" style="width: 100%;">


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


    function goBack() {
        window.history.back();
    }

</script>




</body>
</html>
