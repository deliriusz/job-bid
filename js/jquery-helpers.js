jQuery(document).ready(function($) {

		updateFooterOnHeightChange();
		$(window).on('resize', updateFooterOnHeightChange);

    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });

    $("#submitRegisterForm").click (function (event){
        var formData = {
            'first_name': $('input[name=inputFirstName]').val(),
            'last_name': $('input[name=inputLastName]').val(),
            'username': $('input[name=inputUsername]').val(),
            'email': $('input[name=inputEmail]').val(),
            'password': $('input[name=inputPassword]').val(),
            'repeated_password': $('input[name=inputRepeatPassword]').val(),
            'birth_date': $('input[name=inputBirthDate]').val()
        };

        postForm('post', '/register', formData, '/register/welcome', 'Please correct following errors: ', listErrorsOnDoneWithReroute);

        event.preventDefault();
    });

    $("#submitLoginForm").click (function (event){
        var formData = {
            'username': $('input[name=inputUsername]').val(),
            'password': $('input[name=inputPassword]').val()
        };

        // postForm('post', '/login', formData, data.rerouteAfterLogin, 'Username or password not matched', listErrorsOnDoneWithReroute);
        postForm('post', '/login', formData, '/', 'Username or password not matched', listErrorsOnDoneWithReroute);

        event.preventDefault();
    });

		$("#submitProfileImageUpload").submit (function(event) {
			event.preventDefault();
			var form = $(this);
							 var formdata = false;
							 if(window.FormData){
									 formdata = new FormData(form[0]);
							 }

							 var formAction = form.attr('action');

							 $.ajax({
									 type        : 'POST',
									 url         : form.attr('action'),
									 cache       : false,
									 data        : formdata ? formdata : form.serialize(),
									 contentType : false,
									 processData : false,
							 }).done(function(data) {
								 if (data.success === true) {
									 $('#uploadImgModal').modal('hide');
			            //location.reload();
								 } else {
									 listErrorsOnDoneWithReroute(data, '', 'There was an error during sending file');
								 }
							 });
		});

    $("#placeBidForm").submit (function (event){
        event.preventDefault();
        var formData = {
            'bid': $('input[name=value]').val(),
            'job_id': $('input[name=jobid]').val()
        };

        postForm('post', '/bid', formData, '', 'Please correct following errors: ', listErrorsOnDoneWithReroute);

    });

    $(".master-list").click( function () {

        location.href = location.pathname + '?tab=' + $(this).attr('data-option');

    });

    $(".notification-switch-button").click( function (event) {
        event.preventDefault();

        var formData = {
            'notification-subscribe-type': $('input[name=notification-subscribe-type]').val(),
            'notification-subscribe-id': $('input[name=notification-subscribe-id]').val()
        };

        postForm('post', '/eventsubscriber/set', formData, '', '', reloadPageOnDone);

    });


    $(".select-winning-user").click( function (event) {
        event.preventDefault();

        var formData = {
        };

        $(":button.select-winning-user").remove();

        postForm('post', $(this).attr('data-href'), formData, '', '', reloadPageOnDone);

    });
});

function postForm (method, url, formData, urlondone, title, doneCallback) {
    $.ajax({
        type: method,
        url: url,
        data: formData,
        dataType: 'json',
        encode: true
    })
        .done (function(data) {
            doneCallback(data, urlondone, title);
        });
}

function reloadPageOnDone (data, url, title) {
    if (data.success === false) {
        console.log('ERROR');
    } else {
        location.reload();
    }
}

function listErrorsOnDoneWithReroute (data, url, title) {
    if (data.success === false) {
        $(".error-placeholder").html ( "<div class=\"alert alert-danger\"><strong>" + title + "</strong><br/>"
            + (data.errors ? data.errors.map (x => "<p><i class=\"fa fa-close\" aria-hidden=\"true\"></i> " + x + "</p>") : "")
            + "</div>" );
    } else {
        if (data.rerouteurl) {
            location.href = data.rerouteurl;
        } else if (url.length === 0) {
            location.reload();
        } else {
            location.href = url;
        }
    }
}

function listErrorsOnDoneWithSettingSucess (data, url, title) {
    if (data.success === false) {
        $(".error-placeholder").html ( "<div class=\"alert alert-danger\"><strong>" + title + "</strong><br/>"
            + (data.errors ? data.errors.map (x => "<p><i class=\"fa fa-close\" aria-hidden=\"true\"></i> " + x + "</p>") : "")
            + "</div>" );
    } else {
        $(".error-placeholder").html ( "<div class=\"alert alert-success\"><strong>Data successfully updated</strong><br/>"
            + "</div>" );
    }
}

function updateFooterOnHeightChange () {
    var docHeight = $(window).height();
    var footerHeight = $('footer').height();
    var footerTop = $('footer').position().top + footerHeight;

    if (footerTop < docHeight) {
        $('footer').css('margin-top', 10 + (docHeight - footerTop) + 'px');
    }
}
