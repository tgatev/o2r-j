(function($) {$(document).ready( function (){

        // if user profile is not selected as active then select it
        const user_profile_button = $('.menu-item-user-profile');
        if( !user_profile_button.hasClass('active') ) {
            user_profile_button.toggleClass('active');
        }

})})(jQuery);