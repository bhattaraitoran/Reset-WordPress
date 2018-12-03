<?php
/**
 * Handles reset procedure
 *
 * @package    	Reset_Wordpress
 * @link        https://github.com/bhattaraitoran/Reset-Wordpress
 * Author:      Toran Bhattarai
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) die( 'Silence is golden!' );

class Reset_Wordpress_Handle{

	private $core_tables = array( 'commentmeta', 'comments', 'links', 'options', 'postmeta', 'posts', 'term_relationships', 'term_taxonomy', 'termmeta', 'terms', 'usermeta', 'users');
	private $backup_folder = 'reset-wordpress-backup';

	function __construct(){ 

		if(  ! $this->validate() ){

			return;
			
		}
				
	}

	
	function validate(){ 
		
		$reset     = $this->sanitize_text( 'reset');
		$confirmed = $this->sanitize_text( 'confirm-reset' );

		if( $confirmed != 'reset' && $reset ){

			add_action( 'admin_notices', function(){ Reset_WordPress_Admin_Notice::render( 'validation-error' ); } );

			return;
		}
		
		if( $reset && $confirmed ){

			$this->proceed_reset();
		}

		return false;
	}



	function proceed_reset(){
		
		$reactivate_theme   = $this->sanitize_text( 'reactivate-theme' );
		$reactivate_plugins = $this->sanitize_text( 'reactivate-plugins' );
		$delete_media_files = $this->sanitize_text( 'delete-media-files');
		$backup 			= $this->sanitize_text( 'backup-database' );
		$active_plugins 	= get_option('active_plugins');
    	$active_theme 		= wp_get_theme();
    	
    	$user_id = $this->reset();
    	
    	if( $user_id ){

    		// to do: notification and redirection

    		$this->reactivate_theme( $reactivate_theme, $active_theme );
    		$this->reactivate_plugins( $reactivate_plugins, $active_plugins );
    		$this->delete_media_files( $delete_media_files );
    		$this->backup( $backup );
      		
    	}

	}


	function sanitize_text( $key ){
		
		
		if( isset( $_POST['reset-wordpress'][$key] ) ){

			return sanitize_text_field( $_POST['reset-wordpress'][$key] );
		}

		return false;
	}




	function reset(){ 

		if ( ! current_user_can( 'administrator' ) ) {

	      return false;

	    } global $wp_rewrite; 
	    

		global $wpdb,$current_user,$wp_rewrite;

		if( ! $wp_rewrite ){

			+$GLOBALS['wp_rewrite'] = new WP_Rewrite();
		}

		//retrieve required data from database
		$blogname 		= get_option('blogname');
	    $blog_public 	= get_option('blog_public');
	    $wplang 		= get_option('wplang');
	    $siteurl 		= get_option('siteurl');
	    $home 			= get_option('home');

	    // drop existing tables
	    $this->drop_tables();

	    // fresh install wordpress
		$result = wp_install( $blogname, $current_user->user_login, $current_user->user_email, $blog_public, '', md5(rand()), $wplang);
	    
	    if( empty( $result['user_id'] ) ){

	    	add_action( 'admin_notices', function(){ Reset_WordPress_Admin_Notice::render( 'install-error' ); } );
	    	return false;

	    }else{

		    // restore user password
		    $query = $wpdb->prepare( "UPDATE {$wpdb->users} SET user_pass = %s, user_activation_key = '' WHERE ID = %d LIMIT 1", array( $current_user->user_pass, $result['user_id'] ));
		    $wpdb->query($query);
		    return $result['user_id'];

	    }
	    
	    return false;
	    

    
    }
	    
	




	function drop_tables(){

		global $wpdb;

		$custom_tables = $this->custom_tables();

		foreach ( $this->core_tables as $tbl) {

	      $wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix.$tbl );
	      
	    }

	    if( is_array( $custom_tables ) ){

			foreach ( $custom_tables as $tbl) {

		      $wpdb->query( 'DROP TABLE IF EXISTS ' . $tbl );

		    }
		}

	}
		
	

	function reactivate_theme( $reactivate_theme, $active_theme ){

		if( $reactivate_theme ){

			switch_theme( $active_theme->get_stylesheet() );
		}

		return;
	}



	function reactivate_plugins( $reactivate_plugins, $active_plugins ){

		if( $reactivate_plugins ){

			if ( ! empty( $active_plugins ) ) {

		      foreach ($active_plugins as $plugin_file) {

		        activate_plugin($plugin_file);

		      }

			}

		}

		return;

	}



 
	function reactivate_rw( $reactivate_rw ){ 

		if( ! $reactivate_rw ){ 

			deactivate_plugins( plugin_basename( __FILE__ ) );
		}

		return;
	} 




	function backup( $backup ){

			// to do: notifications

			if( ! $backup ) return;

			try {

			      $dumper = Shuttle_Dumper::create( array(
			        'host'     => DB_HOST,
			        'username' => DB_USER,
			        'password' => DB_PASSWORD,
			        'db_name' =>  DB_NAME,
			      ) );

			      $folder = wp_mkdir_p( trailingslashit( WP_CONTENT_DIR) . $this->backup_folder );
			      
			      if ( ! $folder ) {

			        return false;
			      }

			      $dumper->dump( trailingslashit( WP_CONTENT_DIR ) . $this->backup_folder . '/'.DB_NAME.'.sql.gz' );
	    
	    } catch(Shuttle_Exception $e) {

	      	return false;

	    }

	    return true;

	}



	function delete_media_files( $delete_media_files ){ 
		
		if( $delete_media_files ){

			$upload_dir = wp_get_upload_dir();
	    	$this->delete_folder( $upload_dir['basedir'], $upload_dir['basedir'] );

		}
		
	}



	function delete_folder( $folder, $base_folder ){

		// to do: notifications

		$files = array_diff( scandir( $folder ), array( '.', '..' ) );

		foreach ($files as $file) {

			if ( is_dir( $folder . DIRECTORY_SEPARATOR . $file ) ) {

				$this->delete_folder( $folder . DIRECTORY_SEPARATOR . $file, $base_folder );

			} else {

				$tmp = @unlink( $folder . DIRECTORY_SEPARATOR . $file );
			}
		} 

		if ( $folder != $base_folder ) {

			@rmdir( $folder );

		}

		return;
	}



	
   function custom_tables() {

    global $wpdb;

    $custom_tables = array();

    $table_status = $wpdb->get_results( 'SHOW TABLE STATUS' );

    if ( is_array( $table_status ) ) {

      foreach ( $table_status as $index => $table ) {

        if (0 !== stripos( $table->Name, $wpdb->prefix )) {

          continue;
        }

        if ( empty( $table->Engine ) ) {

          continue;
        }

        if ( false === in_array( $table->Name, $this->core_tables ) ) {

          $custom_tables[] = $table->Name;

        }

      } 

    }

    return $custom_tables;
  }

}
$handle = new Reset_Wordpress_Handle();