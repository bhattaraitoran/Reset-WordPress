<?php
/**
 * Reset form
 *
 * @package    	Reset_Wordpress
 * @link        https://github.com/bhattaraitoran/Reset-Wordpress
 * Author:      Toran Bhattarai
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
if ( ! defined( 'ABSPATH' ) ) die( 'Silence is golden!' );
?>
<div class="wrap rw-wrap">
 <h1 class="title">&nbsp;</h1>
	<div class="logo-wrap"><image src="<?php echo RW_URI;?>assets/image/reset-wordpress-logo.png"/>
	</div>

	<div class="reset-info">


		<div class="reset-info-detail">
		<h2><?php _e( 'Please read carefully before resetting your wordpress installation.' , 'reset-wordpress' ); ?> </h2>

			<h3> <?php _e( 'you gonna loose:' , 'reset-wordpress' ); ?> </h3>

			<ul>

				<li> <?php _e( 'All pages, posts, custom post types, comments, media entries, users' , 'reset-wordpress' ); ?>

				<li> <?php _e( 'All default WP database tables and custom database tables' , 'reset-wordpress' ); ?>

				<li> <?php _e( 'All media files ( <code>uploads</code> folder ) - <strong>optional</strong>' , 'reset-wordpress' ); ?>

			</ul>



			<h3> <?php _e( 'Not to worry about:' , 'reset-wordpress' ); ?> </h3>

			<ul>

				<li> <?php _e( 'All themes and plugins files' , 'reset-wordpress' ); ?>

				<li> <?php _e( 'Site title, site addres, WordPress address and search engine visibility' , 'reset-wordpress' ); ?>

				<li> <?php _e( 'Currently logged in user' , 'reset-wordpress' ); ?>

			</ul>



		</div>



		<div class="reset-options">

		<form action="" class="reset-wordpress-options" method="post">

		<?php wp_nonce_field('reset-wordpress-nonce','reset-wordpress-nonce'); ?>

			  <h3><?php _e( 'Reset Options' , 'reset-wordpress' ); ?></h3>

			  <ul>

			    <li> 

			  		<label for="reactivate-theme">

			  	 		<input type="checkbox" name="reset-wordpress[reactivate-theme]" id="reactivate-theme"> 

			  	 		<?php _e( 'Reactivate currently active theme' , 'reset-wordpress' ); ?> - <strong> <?php echo wp_get_theme(); ?></strong>

			  	 	</label>

			    <li>

			  	 	<label for="reactivate-plugin">

			  	 		<input type="checkbox" name="reset-wordpress[reactivate-plugins]" id="reactivate-plugin"> 

			  	 		<?php _e( 'Reactivate currently active plugins' , 'reset-wordpress' ); ?>

			  	 	</label>

			    
			  	<li> <span> <?php _e( 'Media Files' , 'reset-wordpress' ); ?> </span>

			  	 	<label for="delete-media files">

						<input type="checkbox" name="reset-wordpress[delete-media-files]" id="delete-media files"> 

						<?php _e( 'Delete Media files ( <code> uploads </code> folder )' , 'reset-wordpress' ); ?>

			  	 	</label>

			  	 </li>

			  	 <li> <span> <?php _e( 'Backup database' , 'reset-wordpress' ); ?> </span>

			  	 	<label for="backup-database">

						<input type="checkbox" name="reset-wordpress[backup-database]" id="backuup-database"> 

						<?php _e( 'Secure a copy of sql dump. The backup file will be stored in <code>wp-content/reset-wordpress-backup</code> folder.' , 'reset-wordpress' ); ?>

			  	 	</label>

			  	 </li>

			  	 <li>

			  	 	<label for="reset-submit">

			  	 		<p><?php _e( 'Please type <strong>reset</strong> to confirm.' , 'reset-wordpress' ); ?></p>

			  	 		<input type="text" name="reset-wordpress[confirm-reset]" placeholder="reset" id="reset-wordpress-confirm"/>

			  	 		<input type="submit" name="reset-wordpress[reset]"  class="button button-primary" id="reset-wordpress-submit" value="<?php _e( 'Reset Now' , 'reset-wordpress' ); ?>">

			  	 	</label>

			  	 </li>



			 </ul>

		 </form>

		</div>

	</div>

</div>