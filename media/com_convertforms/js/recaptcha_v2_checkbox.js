!function(t,e){"use strict";e.addEventListener("ConvertFormsInit",function(){if("object"!=typeof grecaptcha){var t=e.querySelectorAll(".nr-recaptcha:not([data-recaptcha-widget-id])");0!=t.length&&t.forEach(function(t){t.innerHTML=Joomla.JText._("COM_CONVERTFORMS_RECAPTCHA_NOT_LOADED")})}}),e.addEventListener("ConvertFormsAfterSubmit",function(t){var e=t.detail.instance.selector.querySelector(".g-recaptcha");if(e){var a=e.getAttribute("data-size");if(e){var r=e.getAttribute("data-recaptcha-widget-id");r&&(grecaptcha.reset(r),"invisible"==a&&grecaptcha.execute(r))}}})}(window,document);
