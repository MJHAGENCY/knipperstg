jQuery(document).ready(function($) {
	var $tab = $('#media-upload-header li:has(a.current)');
	$('body').addClass($tab.attr('id'));
	$('form#filter').prepend('<input type="hidden" name="em-widget-upload-field" value="' + EM_WIDGET_FIELD + '" />');
	
	var emInterval = setInterval(function() {
		$('#media-items .media-item').each(function() {
			var $me = $(this);
			
			if ( $me.find('.em-widget-field-select').length || $me.find('.progress:visible').length ) { return; }
			
			if ( $tab.attr('id') == 'tab-library' )
			{
				$me.find('.slidetoggle').hide();
				$me.append('<input type="button" class="button em-widget-field-select" value="Select" />');
			}
			else
			{
				$me.find('.savesend input[type="submit"]').replaceWith('<input type="button" class="button em-widget-field-select" value="Select" />');
			}
			
			$me.find('.em-widget-field-select').click(function() {
				var $field = top.jQuery('#' + EM_WIDGET_FIELD);
				$field.val($(this).parents('.media-item').find('input.urlfield').val());
				top.tb_remove();
				return false;
			});
			
			clearInterval(emInterval);
		});
	}, 50);
});
