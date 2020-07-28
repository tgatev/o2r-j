

const order_icons = {
    asc: ' <i class="fa fa-sort-numeric-asc dropdown-icon" aria-hidden="true"></i> ',
    desc: ' <i class="fa fa-sort-numeric-desc dropdown-icon " aria-hidden="true"></i> ',
};

// Drop down multiselect functionality
const DropdownsMap2 = {
    dd_count_per_page: {
        enableFiltering: false,
        enableClickableOptGroups: false,
        enableCaseInsensitiveFiltering: false,
        includeResetOption: false,
        onChange: function(option, checked, select){
            jQuery('input#count_per_page[type=hidden]').val(
                jQuery(option).val()
            );
            jQuery('input#count_per_page[type=hidden]')[0].form.submit();
        }
    },
    dd_sort_by: {
        nonSelectedText: 'Sort By ...', // Multiselect Only
        enableFiltering: false,
        enableClickableOptGroups: false,
        enableCaseInsensitiveFiltering: false,
        includeResetOption: false,
        enableHTML: true,
        onChange: function(option, checked, select){
            jQuery('input#sort_by[type=hidden]').val(
                jQuery(option).val()
            );
            console.log( jQuery('input#sort_by[type=hidden]').val());
            jQuery('input#sort_by[type=hidden]')[0].form.submit();
        },
        optionLabel: function(element){
            let el = jQuery(element);
            let direction = jQuery(el).attr('direction')
            if(direction){
                el.append(order_icons[direction]);
            } ;
            return  el.attr('label') || el.html();
        },
    },
};