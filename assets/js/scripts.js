    jQuery(document).ready(function() {

    	$('.launch-modal').on('click', function(e){
    		e.preventDefault();
    		$( '#' + $(this).data('modal-id') ).modal();
    	});
        $('.registration-form input[type="text"], .registration-form input[type="password"], .registration-form input[type="select"], .registration-form textarea').on('focus', function() {
        	$(this).removeClass('input-error');
        });
        $('.registration-form').on('submit', function(e) {
        	
        	$(this).find('input[type="text"], input[type="password"], input[type="select"], textarea').each(function(){
        		if( $(this).val() == "" ) {
        			e.preventDefault();
        			$(this).addClass('input-error');
        		}
        		else {
        			$(this).removeClass('input-error');

        		}
        	});
        });    
    });
 


