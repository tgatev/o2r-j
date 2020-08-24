var vertList = new function() {
	this.add = function() {
		jQuery('#addvertlnk').hide();
		jQuery('#jform_vertlist').show();
		jQuery('#savevertlnk').show();
	}
	
	this.save = function() {
		jQuery('#addvertlnk').show();
		jQuery('#jform_vertlist').hide();
		jQuery('#savevertlnk').hide();
	}
};


var adnetList = new function() {
	this.add = function() {
		jQuery('#addnetlnk').hide();
		jQuery('#jform_adnetlist').show();
		jQuery('#savenetlnk').show();
	}
	
	this.save = function() {
		jQuery('#addnetlnk').show();
		jQuery('#jform_adnetlist').hide();
		jQuery('#savenetlnk').hide();
	}
}


var ssList = new function() {
	this.add = function() {
		jQuery('#addsslnk').hide();
		jQuery('#jform_sslist').show();
		jQuery('#savesslnk').show();
	}
	
	this.save = function() {
		jQuery.ajax({
		    url: 'index.php?option=com_ofrs&task=upSms',
		    type: "post",
		    data: { 'elementId':'sms' + jQuery('#jform_sslist :selected').val() },
		    
		    success : function(response) {
		        console.log(response);
		        location.reload();
		    },
		    
		    error: function(jqXhr, textStatus, errorMessage) { // error callback 
//		        $('p').append('Error: ' + errorMessage);
		    	console.log('error ');
		    	console.log(errorMessage);
		    }
		});
//		jQuery('#addsslnk').show();
//		jQuery('#jform_sslist').hide();
//		jQuery('#savesslnk').hide();
	}
}


jQuery(document).ready(
			// Verticals
		function() {
			jQuery("#savevertlnk").on("click", 
					function() {
							jQuery.ajax({
							    url: 'index.php?option=com_ofrs&task=upVms',
							    type: "post",
							    data: { 'elementId':'vms' + jQuery('#jform_vertlist :selected').val() },
							    
							    success : function(response) {
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
			);
			
			
			jQuery(".vmr").on("click",
					function() {
							jQuery.ajax({
							    url: 'index.php?option=com_ofrs&task=upVmr',
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

				// AdNet
			jQuery("#savenetlnk").on("click", 
					function() {
							jQuery.ajax({
							    url: 'index.php?option=com_ofrs&task=upNms',
							    type: "post",
							    data: { 'elementId':'nms' + jQuery('#jform_adnetlist :selected').val() },
							    
							    success : function(response) {
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
			);
			
			jQuery(".nmr").on("click",
					function() {
							jQuery.ajax({
							    url: 'index.php?option=com_ofrs&task=upNmr',
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
			
			jQuery(".smr").on("click",
					function() {
							jQuery.ajax({
							    url: 'index.php?option=com_ofrs&task=upSmr',
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