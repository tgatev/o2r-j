function checkUpdates() {
    var url = "index.php?option=com_uu&task=cpanel.checkUpdates&format=raw&tmpl=component";
    var update = jQuery("#uu-update-progress").empty();
    update.html("<span class='red'>"+progress_msg+"</span>");
    jQuery.ajax({
            dataType: "json",
            url: url,
            method : 'get',
            success: function(response) {
                update.empty();
                updateUpdates(response);
            },
            error : function(xhr) {
                update.empty();
                updateUpdates('Server not responding for Updates check');
            }
        }
    );
}

function updateUpdates(response) {
    if (response.update == "true") {
        var lastversion = jQuery("#uu-last-version").empty();
        lastversion.html("<span class='update-msg-new'> "+response.version+" </span><span class='update-msg-new'>"+response.message+"</span>");
    } else {
        //remove check button and put the version
        var lastversion = jQuery("#uu-last-version").empty();
        lastversion.html(response.version+" <span class='update-msg-info'>"+response.message+"</span>");
    }
}