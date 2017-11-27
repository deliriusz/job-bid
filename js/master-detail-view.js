jQuery(window).on('resize', function() {
	alignMasterViewIcons();
})

jQuery(window).load(function() {
	alignMasterViewIcons();
})

function alignMasterViewIcons () {
	var masterViewList = $('.master-view');
	if ($(window).width() < 992) {
		masterViewList.removeClass ('btn-group-vertical');
		masterViewList.addClass ('btn-group-horizontal');
	} else {
		masterViewList.removeClass ('btn-group-horizontal');
		masterViewList.addClass ('btn-group-vertical');
	}
}
