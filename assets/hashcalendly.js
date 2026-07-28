(function () {
	if ( typeof HashCalendlyConfig === 'undefined' || ! HashCalendlyConfig.calendlyUrl ) {
		return;
	}

	/* Load Calendly's official widget script once. */
	function loadCalendlyScript( callback ) {
		if ( window.Calendly ) {
			callback();
			return;
		}
		var script = document.createElement( 'script' );
		script.src = 'https://assets.calendly.com/assets/external/widget.js';
		script.async = true;
		script.onload = callback;
		document.head.appendChild( script );
	}

	/* Open popup on any link with href="#calendly" */
	document.addEventListener( 'DOMContentLoaded', function () {
		document.body.addEventListener( 'click', function ( e ) {
			var link = e.target.closest( 'a[href="#calendly"], a[href$="#calendly"]' );
			if ( ! link ) {
				return;
			}
			e.preventDefault();

			loadCalendlyScript( function () {
				window.Calendly.initPopupWidget( {
					url: HashCalendlyConfig.calendlyUrl,
				} );
			} );
		} );
	} );
} )();
