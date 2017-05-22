<?php
/**
 * Template Name: Log In or Log out
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
</head>
<body <?php body_class(); ?>>
<?php woo_top(); ?>
<div id="wrapper">

	<div id="inner-wrapper">

	<?php woo_header_before(); ?>


	<?php woo_header_after();
 global $woo_options;
?>
        <br><br>

        <center>
<a href="http://fittin.wpengine.com/" title="Online fitness tools for schools"><img src="http://fittin.wpengine.com/wp-content/uploads/2017/03/logo.png" alt="Fitt-In" width="154"></a>
<h2>Movement breaks for mind and body</h2>
</center>
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

<?php
/**
 * Footer Template
 *
 * Here we setup all logic and XHTML that is required for the footer section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options;

 woo_footer_top();
 	woo_footer_before();
?>

	<?php woo_footer_after(); ?>




	</div><!-- /#inner-wrapper -->

</div><!-- /#wrapper -->

    <!--<img src="http://fittin.wpengine.com/wp-content/themes/fittin/images/member-bg.png" style="width: 100%;">-->

<div class="fix"></div><!--/.fix-->

<?php wp_footer(); ?>
<?php woo_foot(); ?>

<script>
function goBack() {
    window.history.back();
}
</script>

</body>
</html>
