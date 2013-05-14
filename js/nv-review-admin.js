// JavaScript Document
jQuery(document).ready(function($) {
	var regex = /(smLink\[)(\d+)(\]+)/i;
	var smLinkIndex = $("#smLinks").children().length;
	
	$("body").on("click", '.btnRemove', function(){
		$(this).parents(".form-row").remove();
		return false;
	});
	
	$("#addSMLink").click( function(){
		$("#smLinks").children(":first").clone()
			.appendTo("#smLinks")
			.find("input").each(function() {
				$(this).attr( "name", function( i, attr ) {
					if ( typeof attr == 'string' ) {
						$(this).attr( "value", "" );
						return attr.replace(regex, '$1'+smLinkIndex+'$3');
					}
				});
			});
		smLinkIndex++;
	});
	
	/*$("body").on( 'mouseup', '#TB_closeWindowButton', function() {
		// do ajax load of attached images excluding the featured image and display them in a meta box
	});*/
	
	// media manager stuff
	$(document.body).on( 'click.nvrOpenMediaManager', '.nvr-open-media', function(e) {
		e.preventDefault();
		var tmpShortcode = '[gallery ids="' + $('#nvr_galImages').val() + '" ]';
		var nv_reviews_frame;
		nv_reviews_frame = wp.media.gallery.edit( tmpShortcode ).on('update',function( obj ) {
			var galImgList = [];
			$.each(obj.models, function( id, val ) {
				galImgList.push( val.id );
			});
			$('#nvr_galImages').val( galImgList.join(',') );
			console.log( $('#nvr_galImages').val() );
		});
	});
	
});
