jQuery(document).ready(function($) {

    $(".read-notification").click(function() {
        event.preventDefault();
        var formData = { };

        postForm('post', $(this).attr('data-href'), formData, '', '', reloadPageOnDone);
    });

    $(".react-to-job-won").click(function() {
        event.preventDefault();
        var formData = { };

        postForm('post', $(this).attr('data-href'), formData, '', '', reloadPageOnDone);
    });

    $(".notification-master-list").click( function () {

        location.href = '/PAI-proj/user/notifications?tab=' + $(this).attr('data-option');

    });

    $(".delete-notification").click( function (event) {
        event.preventDefault();

        var formData = { };

        postForm('delete', $(this).attr('data-href'), formData, '', '', reloadPageOnDone);

    });

});
