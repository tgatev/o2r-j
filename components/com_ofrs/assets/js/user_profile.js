function activateSaveSearch() {
	jQuery.ajax({
			    url: 'index.php?option=com_ofrs&task=upIsLogged',
			    type: "post",
			    data: { 'elementId':event.target.id },
			    
			    success : function(response){
			        console.log(response);
			    	var rsp = JSON.parse(response);
			    	if (rsp.status == 'Y') {
			    		var ss = jQuery("#saved_search_name");
			    		ss.val('');
			    		ss.show();
//			    		jQuery("#saved_search_name").show();
				    	jQuery("#save_and_apply_lnk").show();
				    	jQuery("#cancel_lnk").show();
				    	jQuery("#save_search_lnk").hide();
				    	jQuery("#apply_filters_lnk").hide();
				    	jQuery("#my_searches_lnk").hide();
//			    	else if (rsp.status == 'N')
//			    		jQuery("#" + rsp.elementId).attr('src','/images/icons/icon_heart_black.png');
			    	} else if (rsp.status == 'L')
			    		alert('Земи се логни!');
			    	else if (rsp.status == 'E')
			    		alert(rsp.message);
			    	else
			    		alert('Unknown satus ' + rsp.status);
			    },
			    
			    error: function(jqXhr, textStatus, errorMessage) { // error callback 
		//	        $('p').append('Error: ' + errorMessage);
			    	console.log('error ');
			    	console.log(errorMessage);
			    }
			});
}

function mySearches() {
	jQuery.ajax({
		    url: 'index.php?option=com_ofrs&task=upIsLogged',
		    type: "post",
		    data: { 'elementId':event.target.id },
		    
		    success : function(response){
		        console.log(response);
		    	var rsp = JSON.parse(response);
		    	if (rsp.status == 'Y') {
//		    		jQuery("#filter_saved_search_name").show();
//			    	jQuery("#save_and_apply_lnk").show();
			    	jQuery("#cancel_lnk").show();
		    		jQuery("#jform_sslist").show();
			    	jQuery("#save_search_lnk").hide();
			    	jQuery("#apply_filters_lnk").hide();
			    	jQuery("#redo_search_lnk").show();
			    	jQuery("#my_searches_lnk").hide();
	//	    	else if (rsp.status == 'N')
	//	    		jQuery("#" + rsp.elementId).attr('src','/images/icons/icon_heart_black.png');
		    	} else if (rsp.status == 'L')
		    		alert('Земи се логни!');
		    	else if (rsp.status == 'E')
		    		alert(rsp.message);
		    	else
		    		alert('Unknown satus ' + rsp.status);
		    },
		    
		    error: function(jqXhr, textStatus, errorMessage) { // error callback 
	//	        $('p').append('Error: ' + errorMessage);
		    	console.log('error ');
		    	console.log(errorMessage);
		    }
		});
}

function doCancel() {
	jQuery("#saved_search_name").hide();
	jQuery("#save_and_apply_lnk").hide();
	jQuery("#cancel_lnk").hide();
	jQuery("#save_search_lnk").show();
	jQuery("#apply_filters_lnk").show();
	jQuery("#my_searches_lnk").show();
	jQuery("#redo_search_lnk").hide();
	jQuery("#jform_sslist").hide();
}

function saveSearch() {
//	document.getElementById("saved_search_name1").value = "Johnny Bravo";
//	document.getElementById("saved_search_name2").value = "Bravo Johnny";
	var ss_name = jQuery("#saved_search_name").val();
	jQuery("#saved_search_name1").val(ss_name);
	jQuery("#saved_search_name2").val(ss_name);
//	alert(jQuery("#saved_search_name1").val());
//	alert(jQuery("#saved_search_name2").val());
	submitOffersForm('ofrs-srch-form');
}


jQuery(document).ready(
		function() {
			jQuery(".omt").on("click", 
					function() {
						jQuery.ajax({
						    url: 'index.php?option=com_ofrs&task=upOmt',
						    type: "post",
						    data: { 'elementId':event.target.id },
						    
						    success : function(response){
						        console.log(response);
						    	var rsp = JSON.parse(response);
						    	if (rsp.status == 'Y' || rsp.status == 'N') {
									toggleHearts(rsp.elementId, rsp.status);
								}else if (rsp.status == 'L') // not logged in
									throwMessage('To add the offer to Favorites and create an alert, log in, or create a free account.', [
										{
											text: 'SIGN IN', //set text
											class: '', // set classes
											type: 'submit',
											click: function () { // redirect on click

												window.location.href = "/index.php?option=com_uu&view=login&Itemid=3424"
											}// trigger callback on click
										},						{
											text: 'SIGN UP', //set text
											class: 'btn-white-grey', // set classes
											type: 'submit',
											click: function () { // redirect on click
												window.location.href = "/index.php?option=com_uu&view=registration&Itemid=3428"
											}// trigger callback on click
										},
									])
						    	else if (rsp.status == 'E')
						    		alert(rsp.message);
						    	else
						    		alert('Unknown satus ' + rsp.status);
						    },
						    
						    error: function(jqXhr, textStatus, errorMessage) { // error callback 
//						        $('p').append('Error: ' + errorMessage);
						    	console.log('error ');
						    	console.log(errorMessage);
						    }
						});
//						alert("hello"); 
					}
			);
			
//			jQuery(".proba").trigger("click");
		}
);

/**
 * switch hearts view
 * @param id
 * @returns {boolean}
 */
function toggleHearts(id, to_status){
	let element ;
	if(id){
		element = jQuery('#'+id);
	}else{
		return false;
	}
	// have no passed status
	if(to_status === null ){
		if(element.hasClass('fa-heart')) {
			element.removeClass('fa-heart red').addClass('fa-heart-o')
		}else if (element.hasClass('fa-heart-o')) {
			element.removeClass('fa-heart-o').addClass('fa-heart red')
		}
	} else if(to_status == 'Y'){
		element.removeClass('fa-heart-o').addClass('fa-heart red')
	}  else if(to_status == 'N'){
		element.removeClass('fa-heart red').addClass('fa-heart-o')
	}

	return true;
}
