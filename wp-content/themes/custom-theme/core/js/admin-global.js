(function() {

    tinymce.PluginManager.add( 'Shortcodes' , function( editor ){
	    editor.addButton('Shortcodes', {
	        type: 'listbox',
	        text: 'eMagine Shortcodes',
	        onselect: function(e) {
	            var v = e.control._value,
	            	shortcodePieces = v.split('|');
	            
	            tinyMCE.activeEditor.selection.setContent( '[' + shortcodePieces[0] + '] PUT YOUR CONTENT HERE [/' + shortcodePieces[1] + ']' );
	        },
	        values: [
	            {text: 'Add Accordion', value: 'accordion title="ADD YOUR LABEL HERE"|accordion'},
	            {text: 'Add Tab', value: 'tab title="ADD YOUR LABEL HERE"|tab'}
	        ]
	    });
	});
})();