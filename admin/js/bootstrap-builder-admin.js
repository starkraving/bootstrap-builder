(function( $ ) {
	'use strict';

	$( window ).load(function() {
		
		var formTitleSection = $('#titlewrap');
		var inputButton = $('#wp-admin-bar-boostrap-builder-adminbar a');
		var inputPostID = $('input#post_ID')

		if ( formTitleSection && inputButton && inputPostID ) {
			inputButton.click(function(evt){
				var inputTitle = $('input#title');
				if ( inputTitle.val().length === 0 ) {
					alert('Please add a title to the page before editing');
				} else {
					$.ajax({
						url: inputButton.attr('href'),
						method: 'POST',
						data: {post_title: inputTitle.val()},
						success: function(resp) {
							console.log(resp);
							if ( resp.success && resp.redirect ) {
								$(window).off("beforeunload.edit-post");
								document.location.href = resp.redirect;
							}
						}
					});
				}
				evt.preventDefault();
				evt.stopPropagation();
			});
			inputButton.attr('class', 'button button-primary button-large').attr('href', inputButton.attr('href')+inputPostID.val()).appendTo(formTitleSection);
		}
	});

})( jQuery );
