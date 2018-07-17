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


    <div class="sub-footer">

       <div class="col-full">

           <div class="footer-logo">
               <img src="/wp-content/uploads/2017/03/logo.png" alt="Fitt-In" width="120"><br><br>
               <p><strong>Movement breaks for the classroom</strong></p>
           </div>

           <div class="footer-menu">

                <a href="/#what">What is Fitt-in?</a><br>
                <a href="/#how">How does it work?</a><br>
                <a href="/#howmuch">How much will it cost?</a><br>
                <a href="/archives/category/uncategorized">Blog</a><br>
                <a href="/wp-admin">Login</a>

            </div>

        </div>

    </div>


	<footer id="footer" class="col-full">

		<?php woo_footer_inside(); ?>

		<div id="copyright" class="col-left">
			<?php woo_footer_left(); ?>
		</div>

		<div id="credit" class="col-right">
			<?php woo_footer_right(); ?>
		</div>

	</footer>

	<?php woo_footer_after(); ?>

	</div><!-- /#inner-wrapper -->

</div><!-- /#wrapper -->

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
