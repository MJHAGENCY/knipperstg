jQuery(document).ready(function($) {
	var $caller = '';
	var $wrapper = $('#wp-em-wysiwyg-widget-field-wrap');
	var $editor = $wrapper.find('.wp-editor-area');
	
	$wrapper.append('<div class="actions"><a class="button" href="javascript:;">Cancel Changes</a><a class="button-primary" href="javascript:;">Save Changes</a></div>');
	$wrapper.before('<div id="em-wysiwyg-overlay"></div>');
	
	var $overlay = $('#em-wysiwyg-overlay');
	
	// ! Media uploader fix for WP versions >= 3.5
	if ( typeof wp.media !== 'undefined' )
	{
		var send_attachment_original = wp.media.editor.send.attachment;
		wp.media.editor.send.attachment = function(props, attachment) {
			$overlay.show();
			$wrapper.show();
			return send_attachment_original.apply(this, [props, attachment]);
		}
	}
	
	// ! Show the WYSIWYG editor popup
	
	$('.widgets-holder-wrap').on('focus', 'textarea.wysiwyg', function() {
		$caller = $(this);
		$overlay.show();
		$wrapper.show();
		$wrapper.css('margin-left', $wrapper.width() / 2 * -1).css('margin-top', $wrapper.height() / 2 * -1);
		
		if ( $('#em-wysiwyg-widget-field').is(':visible') )
		{
			// TinyMCE is in text mode
			$('#em-wysiwyg-widget-field').val($caller.val()).focus();
		}
		else
		{
			// TinyMCE is in HTML mode
			tinyMCE.get('em-wysiwyg-widget-field').setContent($caller.val());
			tinymce.execCommand('mceFocus', true , 'em-wysiwyg-widget-field');
		}
	});
	
	// ! Close the WYSIWYG editor popup and save changes
	
	$('#wp-em-wysiwyg-widget-field-wrap .actions').on('click', '.button-primary', function() {
		if ( $('#em-wysiwyg-widget-field').is(':visible') )
		{
			// TinyMCE is in text mode
			$caller.val($('#em-wysiwyg-widget-field').val());
		}
		else
		{
			// TinyMCE is in HTML mode
			$caller.val(tinyMCE.get('em-wysiwyg-widget-field').getContent());
		}
		
		$overlay.hide();
		$wrapper.hide();
		tinyMCE.get('em-wysiwyg-widget-field').setContent('');
		$('#em-wysiwyg-widget-field').val('');
	});
	
	// ! Close the WYSIWYG editor popup and cancel changes
	
	$('#wp-em-wysiwyg-widget-field-wrap .actions').on('click', '.button', function() {
		$overlay.hide();
		$wrapper.hide();
		tinyMCE.get('em-wysiwyg-widget-field').setContent('');
		$('#em-wysiwyg-widget-field').val('');
	});
	
	// ! Show the media uploader
	
	$('.widgets-holder-wrap').on('click', 'input.upload-button', function() {
		tb_show('', 'media-upload.php?em-widget-upload-field=' + encodeURIComponent($(this).prev('.upload-field').attr('id')) + '&post_id=0&TB_iframe=1');
	});
	
	// ! Show the assignments thickbox
	
	$('.widgets-holder-wrap').on('click', 'a.assignments-link', function() {
		$caller = $(this);
		var ids = $caller.parent().find('.assignments-id').val();
		var types = $caller.parent().find('.assignments-type').val();
		
		tb_show('', '#TB_inline?width=640&height=600&inlineId=em-assignments-widget-popup');
		
		var $tbwin = $('#TB_window');
		
		$tbwin.css('height', 644);
		$tbwin.find('#TB_ajaxWindowTitle').html('Assignments');
		$tbwin.find('.assignments-type, .assignments-id').prop('checked', false);
		
		if ( types.length )
		{
			types = types.split(',');
			
			$tbwin.find('.assignments-type').each(function() {
				var $this = $(this);
				if ( $.inArray($this.val(), types) >= 0 )
				{
					$this.prop('checked', true);
				}
			});
		}
		
		if ( ids.length )
		{
			ids = ids.split(',');
			
			$tbwin.find('.assignments-id').each(function() {
				var $this = $(this);
				if ( $.inArray($this.val(), ids) >= 0 )
				{
					$this.prop('checked', true);
				}
			});
		}
		
		$tbwin.find('.accordion-content').each(function(){
			var $this = $(this);
			if ( $this.find(':checked').length )
			{
				$this.show().prev('.accordion-head').addClass('open');
			}
			else
			{
				$this.hide().prev('.accordion-head').removeClass('open');
			}
		});
		
		return false;
	});
	
	// ! Setup assignments thickbox accordions
	
	$('body').on('click', '.accordion-head a', function() {
		var $parent = $(this).parent();
		
		if ( $parent.hasClass('open') )
		{
			$parent.removeClass('open').next('.accordion-content').slideUp();
		}
		else
		{
			$parent.addClass('open').next('.accordion-content').slideDown();
		}
		
		return false;
	});
	
	// ! Add a checkbox to each item in the assignments thickbox
	
	$('#em-assignments-widget-popup .page_item a').each(function() {
		var $me = $(this);
		var val = $me.parent().attr('class').replace(/page_item page-item-/g, '');
		$me.before('<input type="checkbox" class="assignments-id" name="assignments-id[]" value="' + val + '" />');
		$me.click(function() {
			$me.prev('input').trigger('click');
			return false;
		});
	})
	
	// ! Get any selected checkboxes from the assignments thickbox and send values to widget fields
	
	$('#em-assignments-widget-popup form').submit(function() {
		var ids = '';
		var types = '';
		
		$(this).find('.assignments-id:checked').each(function() {
			ids += $(this).val() + ',';	
		});
		
		$(this).find('.assignments-type:checked').each(function() {
			types += $(this).val() + ',';	
		});
		
		$caller.parent().find('.assignments-id').val(ids.substr(0, ids.length -1));
		$caller.parent().find('.assignments-type').val(types.substr(0, types.length - 1));
		
		top.tb_remove();
		
		return false;
	});
});