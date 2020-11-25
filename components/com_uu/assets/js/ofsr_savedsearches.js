jQuery(document).ready(
			// Verticals
		function() {
			jQuery(".ssr").on("click",
					function() {
							jQuery.ajax({
							    url: 'index.php?option=com_ofrs&task=upSsr',
							    type: "post",
							    data: { 'elementId':event.target.id },
							    
							    success : function(response) {
							        console.log(response);
							        location.reload();
							    },
							    
							    error: function(jqXhr, textStatus, errorMessage) { // error callback 
			//				        $('p').append('Error: ' + errorMessage);
							    	console.log('error ');
							    	console.log(errorMessage);
							    }
							});
						}
			);
		}
);