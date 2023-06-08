(function($) { 
  
  $(document).ready(function($) { 
    initMenuHover(); 
    initLastMenuChild(); 
    //initImgShadows(); 
    initRemovePTags(); 
    initListSplit(); 
    initTableBorder(); 
    initImageLightBox();
    initVideoLightbox(); 
    initPrettyForms(); 
  }); 
      
    //! Menu Hover - Add .hover class to Drop Down menu items on mouse hover 
    var initMenuHover = function() {         
        $('.main-menu li').hoverIntent({ 
            "over" : function() { 
                $(this).addClass('hover'); 
            }, 
            "out" : function() { 
                $(this).removeClass('hover');    
            }, 
            "timeout" : 500 
        }); 
    }; 
  
    //! Flag last tier one menu item 
    var initLastMenuChild = function() { 
        $('.main-menu > li:last-child').addClass('menu-item-last'); 
    }; 
  
    //! Warp img tags found in content in span to add inner shadow 
    var initImgShadows = function() { 
        $('.content img').each(function(){   
			if ( !$(this).hasClass('no-border') ) {
	            var imgClass = $(this).attr('class'); 
	  
	            if ($(this).is('.aligncenter ')) { 
	                $(this).wrap('<div class="img-wrap"><span class="img-shadow ' + imgClass + ' "></span></div>'); 
	            }else{ 
	                $(this).wrap('<span class="img-shadow ' + imgClass + ' "></span>'); 
	                $(this).attr('class' , ''); 
	            } 
			}
        }); 
  
    }; 
      
    //! Remove p tag wrap from image tags empty p tags and Remove empty p tags <p></p> 
    var initRemovePTags = function() { 
  
        //! remove empty p tags from content 
        $('.main').find('p:empty').remove(); 
  
        //! Remove p tag wrap from image tags 
        $('img').each(function(){ 
            var $this = $(this); 
            var $parent = $this.parents('p'); 
  
            if ( $parent.text().length ) return; 
              
            if ( $this.parent('a').length ){ 
                var $a = $this.parent(); 
                $a.addClass($this.attr('class')); 
                $this.removeAttr('class'); 
                $this = $a; 
            } 
              
            $this.addClass($parent.attr('class')); 
            $this.insertBefore($parent); 
            $parent.remove(); 
        }); 
    }; 
  
    //! Multi Column List 
	var initListSplit = function() { 
		$('ul.two-column, .gfield.two-column .ginput_container > ul').easyListSplitter({ colNumber: 2 });
        $('ul.three-column').easyListSplitter({ colNumber: 3 });
	} 
  
    //! Table .data class for alternating rows 
    var initTableBorder = function() { 
        $('table.data tr:nth-child(even) td').parent().addClass('table-row'); 
        $('table.data tr:nth-child(odd) td').parent().addClass('table-altrow'); 
    }; 
  
    //! Image Lightbox 
    var initImageLightBox = function() { 
        $('a[href*=".jpg"], a[href*=".gif"], a[href*=".png"]').colorbox({ 
            "close" : "", 
            "width" : "auto",  
            "height" : "auto", 
            "opacity" : 0.7 
        }); 
    }; 
  
    //! YouTube, Vimeo and Wistia Lightbox 
    var initVideoLightbox = function() { 
        $('[href*="youtube.com"],[href*="youtu.be"],[href*="vimeo.com"],[href*="wistia.com"]').click(function(e) {
            var width = $(window).width();
           	
           	if ( width > 550 ) {
	           	  
	            var $me = $(this); 
	            var href = $me.attr('href'); 
	      
	            if ( $me.parent().attr('data-yt') ) 
				{
					return;	
				} 
	  
				if ( width > 1050 ) {
					var cbWidth = 980,
						cbHeight = 551;
					var ctaWidth = 780,
						ctaHeight = 439;
				} else if ( width < 700 ) {
					var cbWidth = 500,
						cbHeight = 281;
					var ctaWidth = 400,
						ctaHeight = 225;
				} else if ( width < 900 ) {
					var cbWidth = 650,
						cbHeight = 365;
					var ctaWidth = 520,
						ctaHeight = 292;
				} else {
					var cbWidth = 853,
						cbHeight = 480;
					var ctaWidth = 710,
						ctaHeight = 400;
				}
				
				var isiPad = navigator.userAgent.match(/iPad/i) != null,
					isiPhone = navigator.userAgent.match(/iPhone/i) != null;
				if ( isiPad === true || isiPhone === true ) {
					return;
				}
	  
	            if ( href.indexOf('/watch') >= 0 ) 
	            { 
	                var hrefParts = href.split('?'); 
	                var qsParts = hrefParts[1].split('&'); 
	                var qs = []; 
	  
	                for ( i = 0; i < qsParts.length; i++ ) 
	                { 
	                    var tmp = qsParts[i].split('='); 
	                    qs[tmp[0]] = tmp[1]; 
	                } 
	  
	                href = 'http://www.youtube.com/embed/' + qs['v'] + '?autoplay=1&hd=1&rel=0'; 
	            } 
	  
	            if ( href.indexOf('youtu.be') >= 0 ) 
	            { 
	                var hrefParts = href.split('/'); 
	                href = 'http://www.youtube.com/embed/' + hrefParts[hrefParts.length - 1] + '?autoplay=1&hd=1&rel=0'; 
	            } 
	  
	            //! VIMEO 
	            if ( href.indexOf('vimeo.com') >= 0 ) 
	            { 
	                if ( href.indexOf('player.vimeo.com') >= 0 ) { 
	                    //don't change href 
	                } else { 
	                    var hrefParts = href.split('/'); 
	                    href = 'http://player.vimeo.com/video/' + hrefParts[hrefParts.length - 1] + '?autoplay=1' ; 
	                } 
	            } 
				
				//! WISTIA
	            if ( href.indexOf('wistia.com') ) 
	            {
		            if ( href.indexOf('fast.wistia.net') >= 0 ) {
	                    //don't change href
	                } else {
	                    var hrefParts = href.split('/');
	                    href = 'http://fast.wistia.net/embed/iframe/' + hrefParts[hrefParts.length - 1] + '?autoplay=1' ;
	                }                
	            }
	            
	            $.colorbox({                 
	                "href" : href, 
	                "close" : "", 
	                "iframe" : 1, 
	                "innerHeight" : cbHeight, 
	                "innerWidth" : cbWidth, 
	                "initialHeight" : 200, 
	                "initialWidth" : 200, 
	                "opacity" : 0.7 
	            }); 
	  
	            e.preventDefault(); 
            }
        }); 
    }; 
  
    var initPrettyForms = function() { 
      $('input[type="file"]').uniform(); 
      
      //! SELECT BOX WRAPPER
      $('select').each(function(){
        var $this = $(this),
            classes = 'selector';
        
        if ( $this.is('[multiple]') ) {
          classes += ' multi-select';
        }
        
        $this.wrap('<div class="' + classes + '">');
      }); 
    } 
      
}(jQuery));