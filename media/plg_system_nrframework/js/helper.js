!function(e,o){"use strict";var t={serializeform:function(e){var t,n,o=[];if("object"==typeof e&&"FORM"==e.nodeName)for(var l=e.elements.length,i=0;i<l;i++)if((t=e.elements[i]).name&&!t.disabled&&"button"!=t.type&&"file"!=t.type&&"hidden"!=t.type&&"reset"!=t.type&&"submit"!=t.type)if("select-multiple"==t.type){n=e.elements[i].options.length;for(var r=0;r<n;r++)t.options[r].selected&&(o[o.length]=encodeURIComponent(t.name)+"="+encodeURIComponent(t.options[r].value))}else("checkbox"!=t.type&&"radio"!=t.type||t.checked)&&(o[o.length]=encodeURIComponent(t.name)+"="+encodeURIComponent(t.value));return o.join("&").replace(/%20/g,"+")},capitalizeFirstLetter:function(e){return e.charAt(0).toUpperCase()+e.slice(1)},loadScript:function(e,t){if(0<o.querySelectorAll('script[src="'+e+'"]').length)"function"==typeof t&&t();else{var n=o.createElement("script");n.src=e,o.head.appendChild(n),"function"==typeof t&&(n.onload=function(){t()})}},loadStyleSheet:function(e,t){if(0<o.querySelectorAll('link[href="'+e+'"]').length)"function"==typeof t&&t();else{var n=o.createElement("link");n.href=e,n.type="text/css",n.rel="stylesheet",o.getElementsByTagName("head")[0].prepend(n)}}};e.NRHelper=t}(window,document);
