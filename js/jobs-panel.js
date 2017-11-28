jQuery(document).on('click', '.jobs-panel-heading span.clickable', function(e){
    var $this = $(this);
	if(!$this.hasClass('panel-collapsed')) {
		$this.parents('.jobs-panel').find('.jobs-filter-body').slideUp();
		$this.addClass('panel-collapsed');
		$this.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
	} else {
		$this.parents('.jobs-panel').find('.jobs-filter-body').slideDown();
		$this.removeClass('panel-collapsed');
		$this.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
	}
})

jQuery(document).ready(function($) {
$(":input").on ('input', function(event) {
        var formData = {
            'price_from': $('input[name=price_from]').val(),
            'price_to': $('input[name=price_to]').val(),
            'job_type': $('input[name=job_type]').val(),
            'date_from': $('input[name=date_from]').val(),
            'date_to': $('input[name=date_to]').val(),
            'location': $('input[name=location]').val()
        };

	$.ajax({
			type        : 'POST',
			url         : '/PAI-proj/jobs/filter',
			data        : formData,
      dataType    : 'json',
      encode      : true
	}).done(function(data) {
		// if (data.success === true) {
			// $('div.panel-jobs').empty().html(data.response);
			var parent = $('div.panel-jobs').parent();
			parent.find('div.panel-jobs').remove();
			parent.append(data.response);
		// } else {
			//TODO implement
		// }
	});
});
});
