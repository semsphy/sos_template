$j = jQuery.noConflict();

$j(document).ready(function() {
	var shareBar   = $j("#content #main-sidebar-container #main .really_simple_share");
	 var above      = 266
	 var entry	   = $j("#content #main-sidebar-container #main .entry");
	 var flag = false;
	$j(window).scroll(function() {

		var headerStop  = 212;	
		var upTop     = $j(this).scrollTop();
		var barPos    = shareBar.css('position');
		
		if(upTop > above) {
			if(upTop + 40 > headerStop && !flag) {
				if (barPos != 'absolute') shareBar.css({'position' : 'absolute', 'top' : 75});
				flag=true;
			}
			else if (barPos != 'fixed' && flag) {
				shareBar.css({'position' : 'fixed', 'top' : '-10px'});
			}
		}
		else if (barPos != 'static') {
			shareBar.css({"position":"static",'top':'-10px'});
			flag = false;
		}
		
	});
});