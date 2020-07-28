/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2020 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 *
 * When using the HTML5 required attribute for a group of checkboxes the browser forces the user to check all inputs.
 * This code below intends to fix this issue by toggling the required attribute whenever a checkbox value changes.
*/
!(function(document) {
	'use strict';

	ConvertForms.Helper.onReady(function() {
		// Fix attributes on page load
		document.querySelectorAll('.cf-list > .cf-checkbox-group-required:first-child input[type=checkbox]').forEach(function(checkbox) {
			fixRequiredAttribute(checkbox);
		});

		// Fix attributes when an input changes
		document.addEventListener('change', function(e) {
			fixRequiredAttribute(e.target);
		});
	});

	/**
	 * The helper method that fixes the required attribute
	 * 
	 * @param DOM Element el
	 */
	function fixRequiredAttribute(el) {
		// Make sure we're manipulating a Convert Forms checkbox.
		if (!el.classList.contains('cf-input') || (el.type !== 'checkbox')) {
			return;
		}
	
		// Verify element is part of a group
		var inputContainer = el.closest('.cf-control-input');
	
		// Continue only if we have more than 1 inputs
		if (inputContainer.querySelectorAll('.cf-checkbox-group-required').length < 2) {
			return;
		}
	
		// Get all inputs
		var inputs = inputContainer.querySelectorAll('input[type=checkbox]');
		var totalChecked = inputContainer.querySelectorAll('input[type=checkbox]:checked').length;
	
		if (totalChecked > 0) {
			inputs.forEach(function(input) {
				input.removeAttribute('required');
			});
		} else {
			inputs.forEach(function(input) {
				input.setAttribute('required', 'required');
			});
		}
	}
})(document);