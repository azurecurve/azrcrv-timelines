( function () {
	'use strict';

	// Show/hide the "Flag width" field depending on whether the
	// "Flags / Nearby Integration" checkbox is ticked. Written in vanilla
	// JS (no jQuery) per the plugin's coding standard.
	document.addEventListener( 'DOMContentLoaded', function () {

		var integrateCheckbox = document.querySelector( 'input[name="integrate-with-flags-and-nearby"]' );
		if ( ! integrateCheckbox ) {
			return;
		}

		var flagWidthInput = document.querySelector( 'input[name="flag-width"]' );
		if ( ! flagWidthInput ) {
			return;
		}

		var flagWidthLabel = flagWidthInput.closest( 'label' );

		function toggleFlagWidthVisibility() {
			var isVisible = integrateCheckbox.checked;
			if ( flagWidthLabel ) {
				flagWidthLabel.style.display = isVisible ? '' : 'none';
			}
		}

		integrateCheckbox.addEventListener( 'change', toggleFlagWidthVisibility );
		toggleFlagWidthVisibility();
	} );

} )();
