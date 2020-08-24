jQuery(document).ready(
		function() {
			jQuery(".omr").on("click", 
					function() {
						if (confirm("Yes/No")) {
//							alert('hello world');
							jQuery.ajax({
							    url: 'index.php?option=com_ofrs&task=upOmr',
							    type: "post",
							    data: { 'elementId':event.target.id },
							    
							    success : function(response){
							        console.log(response);
							        location.reload();
							    },
							    
							    error: function(jqXhr, textStatus, errorMessage) { // error callback 
//							        $('p').append('Error: ' + errorMessage);
							    	console.log('error ');
							    	console.log(errorMessage);
							    }
							});
						}
					}
			);
		}
);