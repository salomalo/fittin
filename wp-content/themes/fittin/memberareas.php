<?php
/**
 * Template Name: Member Areas
 *
 * For all pages including login and logout & member dashboards
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
    <script>
  function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
  }
</script>
</head>
<body <?php body_class(); ?>>
<?php woo_top(); ?>


	<?php woo_header_before(); ?>

    <div class="member-nav" id="member-nav">

         <img src="<?php echo get_stylesheet_directory_uri() ?>images/member-icon.png" style="width: 25px; float: right;" id="member-nav-icon-close">

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

        <img src="<?php echo get_stylesheet_directory_uri() ?>images/member-icon-white.png" style="width: 25px; float: right; margin-right: 30px; margin-top: 5px;" id="member-nav-icon">

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
