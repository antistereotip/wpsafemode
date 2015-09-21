$(document).ready(function(){
	if(localStorage.getItem('safemode_contact_email')!=undefined && localStorage.getItem('safemode_contact_email')!= ''){
window.intercomSettings = {
   app_id: "j703bsqj",
   name: localStorage.getItem('safemode_contact_name'), // Full name
   email: localStorage.getItem('safemode_contact_email'), // Email address
   created_at: localStorage.getItem('safemode_contact_created') // Signup date as a Unix timestamp
    };	
	}else{
	window.intercomSettings = {
   app_id: "j703bsqj"
   };	
   	
	}
	if(window.intercomSettings.app_id!=undefined){
	 (function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/jpx4hovz';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();		
	}

	
	$('.signup-form').submit(function(e){
		e.preventDefault();
	    if($('.contact-email').length > 0){
		  var contact_email = $('.contact-email').val();
		  var contact_name = $('.contact-name').val();
		  
		  if(contact_email!='' && contact_email!=undefined && contact_name!='' && contact_name!=undefined){
		  	var contact_date = Math.floor(Date.now() / 1000);
		  	 	localStorage.setItem('safemode_contact_email', contact_email);
			   	localStorage.setItem('safemode_contact_created',contact_name);
			   	localStorage.setItem('safemode_contact_name', contact_date);
		  		window.intercomSettings = {								
				                    name: contact_name,						
								    email: contact_email,	
								    created_at:  contact_date,
								    app_id: "j703bsqj",
								  };
		  	
		   (function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/jpx4hovz';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();
		   
		   $('.signup-form').html('<p class="small version">' + 'Thank you for signing up. We will keep you informed about new updates, and you can contact us for support. Your Cloud Industry Team'+ '</p>');
		  }
		}	
	   		
	});
	
});