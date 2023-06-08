
jQuery(document).ready(function($){
  	// WRAP ALL ADJACENT '.accordion' ELEMENTS IN '.accordions' WRAPPER
		var collection = [],
		    totalAccordions = $('.accordion').length - 1;
		
		$('.accordion').each(function(index, value){
			var $this = $(this);
			    
			if ( $this.closest('.accordions').length )
				return;
			
			var nextAccordion = $this.next().hasClass('accordion');
	    collection.push($this);
	    
	    if( !nextAccordion ) {
        //! CREATE ACCORDIONS CONTAINER AND INSERT BEFORE FIRST OCCURANCE
        var container = $('<div class="accordions"></div>');
        container.insertBefore(collection[0]);
        
        //! ADD ACCORDIONS TO CURRENT CONTAINER
        for( i=0; i<collection.length; i++ ) {
            collection[i].appendTo(container);
        }
        //! RESET THE COLLECTION ARRAY
        collection = [];
	    }
		});
		
		$('.accordion-label').click(function(e){
			e.preventDefault();
			
			var $this = $(this);
			
			if( $this.parents('.tab-content') )
			{
				if ( $this.next('.accordion-content').is(':hidden') )
				{
					$this.addClass('open').next('.accordion-content').css('display', 'block');
				}
				else
				{
					$this.removeClass('open').next('.accordion-content').css('display', 'none');
				}
				
				var labelHeight = $('.tab-label:first').height();
				var changeHeight = $('.tab.active .tab-content').height();
				$('.tab').height(changeHeight + labelHeight);
			}
			else 
			{
				if ( $this.next('.accordion-content').is(':hidden') )
				{
					$this.addClass('open').next('.accordion-content').slideDown(250).find('p:empty').remove();
				}
				else
				{
					$this.removeClass('open').next('.accordion-content').slideUp(250)
				}
			}
		});
});