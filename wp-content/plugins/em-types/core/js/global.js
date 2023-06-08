

jQuery(document).ready(function($){
	
	$('.wp-list-table.types').find('.row-actions').find('.delete').click(function(){
		return confirm('Are you sure you want to delete this type? This action can not be undone!');	
	});
	
	$('#title').blur(function(){
		var val = $(this).val();
		var $slug = $('#em-types-slug');
		
		if ( $slug.val().length == 0 )
		{
			$slug.val(val.toLowerCase().replace(/ /g, '-').replace(/s$/, ''));
		}
	});
	
	$('#em-types-type').change(function(){
		var $this = $(this);
		var val = $this.val();
		var $form = $this.closest('form');
		
		$form.find('.hidden').show();
		
		switch ( val )
		{
			case 'post-type' :
				$form.find('tr[data-for="taxonomy"]').hide();
				break;
				
			case 'taxonomy' :
				$form.find('tr[data-for="post-type"]').hide();
				break;
				
			default :
				$form.find('.hidden').hide();
				break;
		}	
	});
	
	$('#em-types-form').validate({
		"rules" : {
			"em_types[slug]" : {
				"required" : true,
				"valid_slug" : true,
				"remote" : {
					"url" : ajaxurl,
					"type" : "post",
					"data" : {
						"type" : function(){
							return $('#em-types-type').val();
						},
						"slug" : function(){
							return $('#em-types-slug').val();
						},
						"action" : "em_types_check_slug"
					}
				}
			},
			"em_types[singular_name]" : "required",
			"em_types[menu_position]" : "required",
			"em_types[type]" : "required"
		},
		"submitHandler" : function(form){
			$(form).find('tr:hidden input').prop('disabled', true);
			form.submit();
		}
	});

	$.validator.addMethod('valid_slug', function(val, elm){
		var matches = val.match(/[^a-z\-]/g);
		return ( matches == null ) ? true : false;
	}, 'Slugs can contain lowercase letters and dashes (-) only.');	
});
