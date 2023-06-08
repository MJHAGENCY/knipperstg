(function($){

	$(document).ready(function() {
		insertShortcode();
		initSearch();
	});
	
	var insertShortcode = function() {
		$('#em-docman-shortcode-form .button-primary').click(function(e) {
			e.preventDefault();
			var $me = $(this);
			var cats = '';
			
			$me.parents('form').find('input:checkbox:checked').each(function() {
				cats += $(this).val() + ',';
			}).prop('checked', false);
			
			window.send_to_editor('[document_list cats="' + cats.slice(0, -1) + '"]');
		});
		
		$('#em-docman-link-form .button-primary').click(function(e) {
			e.preventDefault();
			var $this = $(this);
			var insertVal = '';
			var selection = tinyMCE.activeEditor.selection.getContent({ "format" : "text" });
			var type = $('select[name="link_type"]').val();
			var linkText = '';
			
			if ( selection.length == 0 )
			{
				if ( type == 1 )
				{
					linkText = 'Details';
				}
				else
				{
					linkText = 'Download';
				}
			}
			else
			{
				linkText = selection;
			}
			
			$this.parents('form').find('input:radio:checked').each(function() {
				var $radio = $(this);
				var linkUrl = $radio.val();
				
				if ( type == 2 )
				{
					linkUrl += '?dl=1';
				}
				
				insertVal += '<a href="' + linkUrl + '">' + linkText + '</a>';
			}).prop('checked', false);
			
			window.send_to_editor(insertVal);
		});
	};
	
	var initSearch = function() {
		$('#em-docman-link-form input[type="text"]').keyup(function() {
			var $input = $(this);
			var inputVal = $input.val().toLowerCase().replace(' ', '');
			
			$input.next('table').find('label').each(function() {
				var $label = $(this);
				var text = $label.html().toLowerCase().replace(' ', '');
				var filename = $label.parents('tr').find('td.filename').html().toLowerCase().replace(' ', '');

				if ( text.indexOf(inputVal) < 0 && filename.indexOf(inputVal) < 0 )
				{
					$label.parents('tr').hide();
				}
				else
				{
					$label.parents('tr').show();
				}
			});
		});
	};
	
}(jQuery));