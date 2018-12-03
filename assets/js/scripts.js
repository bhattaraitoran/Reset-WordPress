jQuery(document).ready(function( $ ){

	$('.reset-wordpress-options').on( 'submit' , function(){ 
		var resetconf = $('#reset-wordpress-confirm').val(); 
		if( 'reset' != resetconf ){
			alert('Please type "reset" to confirm!');
			return false;
		}

	});
});