
jQuery(document).ready(function($){
	$(window).load(function() {
		var tabHeight = 0;
	var labelHeight = $('.tab-label:first').height();
	var hash =$(location).attr('hash');
	
	$('a[href*="#"]').click(function(){
		var $this = $(this);
		var href = $this.attr('href');
		var hrefParts = href.split('#');
		var $elm = $('#'+ hrefParts[1]);
		
		if ( $elm.length )
		{
			$elm.addClass('active').siblings('.tab').removeClass('active');
			var changeHeight = $('.tab.active .tab-content').height();
			$('.tab').height(changeHeight + labelHeight);
		}
	});
	
	$('.tab-label').click(function(){
		var $this = $(this);
		$this.parents('.tab').addClass('active').siblings('.tab').removeClass('active');
		
		var changeHeight = $('.tab.active .tab-content').height();
		$('.tab').height(changeHeight + labelHeight);
	});
	
	$('.tab').wrapAll('<div class="tabs"/>');
	$('.tab:first').addClass('active');
	
	if ( hash )
	{
		var $elm = $(hash);
		if ( $elm.length )
		{
			$elm.addClass('active').siblings('.tab').removeClass('active');
		}
	}
	
	var changeHeight = $('.tab.active .tab-content').height();
	$('.tab').height(changeHeight + labelHeight);
	});
});