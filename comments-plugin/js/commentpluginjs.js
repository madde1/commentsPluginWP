
jQuery(document).ready(
     function() {
     setInterval(function() {
     jQuery('#commentsWidgets').load(location.href + ' #commentsWidgetsWrap');
     }, 5000);  //Delay here = 5 seconds 
     });
