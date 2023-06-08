
(function($){
	$(document).ready(function(){
		$('#doc-template').change(function(){
			var $this = $(this);
			
			$('tr[data-id="' + $this.val() + '"]').removeClass('hidden');
			$('tr[data-id][data-id!="' + $this.val() + '"]').addClass('hidden');
		});
	});
}(jQuery));