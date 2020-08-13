(function () {
    jQuery(".formDatePicker").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "d-m-Y",
        minDate: new Date().setDate(2),
    });
    jQuery(".formTimePicker").flatpickr({
		enableTime: true,
		noCalendar: true,
		dateFormat: "H:i",
        altInput: true,
        altFormat: "F j, Y",
        time_24hr: true
    });

	function formatState(state) {
		if(!state.id) {
			return state.text;
		}
		return $(
			'<span><img alt="destination type icon" class="select-destination-icon" src="../img/destination_' + $(state.element).data('type') + '.svg">' + state.text + '</span>'
		);
	}

	jQuery(".selectize_dropdown").select2({
		theme             : "bootstrap4",
		templateResult    : formatState,
		templateSelection : formatState
	});
})(window.jQuery);
