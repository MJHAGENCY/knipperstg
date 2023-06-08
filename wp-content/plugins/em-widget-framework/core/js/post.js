
jQuery(document).ready(function($){
	
	$('.em-page-widgets-list').sortable();
	
	$('#em_widget_framework_use_custom_sorting').change(function(){
		var $this = $(this);
		
		if ( $this.is(':checked') )
		{
			$this.parents('.inside').find('.em-show-hide').removeClass('hidden');
		}
		else
		{
			$this.parents('.inside').find('.em-show-hide').addClass('hidden');
		}
	});
	
});