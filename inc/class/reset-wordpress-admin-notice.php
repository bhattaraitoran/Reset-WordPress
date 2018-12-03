<?php
/**
 * Renders admin notices
 *
 * @package    	Reset_Wordpress
 * @link        https://github.com/bhattaraitoran/Reset-Wordpress
 * Author:      Toran Bhattarai
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
if ( ! defined( 'ABSPATH' ) ) die( 'Silence is golden!' );

class Reset_WordPress_Admin_Notice{

	function __construct(  ){

		

	}

	function render( $key ){
		
		$messages = array(
					'validation-error' => array( 'class' => 'notice notice-error' , 'message' => __( 'Please type "reset" and try again.' ) ),
					'install-error' => array( 'class' => 'notice notice-error' , 'message' => __( 'Reset Failed!' ) ),
					'install-success' => array( 'class' => 'notice notice-success' , 'message' => __( 'Reset Success!' ) )
				);

		if( isset( $messages[$key ] ) ){

			$notice = $messages[$key ];

			echo '<div class="'.$notice['class'].'"><p>'.$notice['message'].'</p></div>';
		}
		return false;
	}

	

}