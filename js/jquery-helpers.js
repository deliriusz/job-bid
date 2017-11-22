jQuery(document).ready(function($) {
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

      $.ajax({
        type: 'POST',
        url: '/PAI-proj/register',
        data: formData,
        dataType: 'json',
        encode: true
      })
        .done (function(data){
          if (data.success === false) {
            $(".error-placeholder").html ( "<div class=\"alert alert-danger\"><strong>Please correct registration form</strong><br/>"
            + data.errors.map (x => "<p><i class=\"fa fa-close\" aria-hidden=\"true\"></i> " + x + "</p>")
            + "</div>" );
          } else {
              location.href = "/PAI-proj/register/welcome";
          }

        });

        event.preventDefault();
    });

    $("#submitLoginForm").click (function (event){
      var formData = {
        'username': $('input[name=inputUsername]').val(),
        'password': $('input[name=inputPassword]').val()
      };

      $.ajax({
        type: 'POST',
        url: '/PAI-proj/login',
        data: formData,
        dataType: 'json',
        encode: true
      })
        .done (function(data){
            console.log(data);
          if (data.success === false) {
            $(".error-placeholder").html ( "<div class=\"alert alert-danger\"><strong><i class=\"fa fa-close\" aria-hidden=\"true\"></i> Username or password not matched</strong><br/>"
            + "</div>" );
          } else {
              location.href = data.rerouteAfterLogin;
          }

        });

        event.preventDefault();
    });

    $("#submitUpdateUserDataForm").click (function (event){
      var formData = {
        'first_name': $('input[name=inputFirstName]').val(),
        'last_name': $('input[name=inputLastName]').val(),
        'username': $('input[name=inputUsername]').val(),
        'email': $('input[name=inputEmail]').val(),
        'birth_date': $('input[name=inputBirthDate]').val()
      };

      $.ajax({
        type: 'POST',
        url: '/PAI-proj/user/account',
        data: formData,
        dataType: 'json',
        encode: true
      })
        .done (function(data){
          if (data.success === false) {
            $(".error-placeholder").html ( "<div class=\"alert alert-danger\"><strong>Please correct following errors:</strong><br/>"
            + data.errors.map (x => "<p><i class=\"fa fa-close\" aria-hidden=\"true\"></i> " + x + "</p>")
            + "</div>" );
          } else {
            $(".error-placeholder").html ( "<div class=\"alert alert-success\"><strong>Data successfully updated</strong><br/>"
            + "</div>" );
          }

        });

        event.preventDefault();
    });

    $("#submitUpdateUserPasswordForm").click (function (event){
      var formData = {
        'password': $('input[name=inputPassword]').val(),
        'repeated_password': $('input[name=inputRepeatPassword]').val()
      };

      $.ajax({
        type: 'POST',
        url: '/PAI-proj/user/account',
        data: formData,
        dataType: 'json',
        encode: true
      })
        .done (function(data){
          if (data.success === false) {
            $(".error-placeholder").html ( "<div class=\"alert alert-danger\"><strong>Please correct following errors:</strong><br/>"
            + data.errors.map (x => "<p><i class=\"fa fa-close\" aria-hidden=\"true\"></i> " + x + "</p>")
            + "</div>" );
          } else {
            $(".error-placeholder").html ( "<div class=\"alert alert-success\"><strong>Data successfully updated</strong><br/>"
            + "</div>" );
          }

        });

        event.preventDefault();
    });

    $("#placeBidForm").submit (function (event){
      event.preventDefault();
      var formData = {
        'bid': $('input[name=value]').val(),
        'job_id': $('input[name=jobid]').val()
      };

      $.ajax({
        type: 'POST',
        url: '/PAI-proj/bid',
        data: formData,
        dataType: 'json',
        encode: true
      })
        .done (function(data){
          if (data.success === false) {
            $(".error-placeholder").html ( "<div class=\"alert alert-danger\"><strong>Please correct following errors: </strong><br/>"
            + data.errors.map (x => "<p><i class=\"fa fa-close\" aria-hidden=\"true\"></i> " + x + "</p>")
            + "</div>" );
          } else {
              location.reload();
          }

        });

    });

    $(".master-list").click( function () {

        location.href = '/PAI-proj/user/account?tab=' + $(this).attr('data-option');

    });

    $(".notification-master-list").click( function () {

        location.href = '/PAI-proj/user/notifications?tab=' + $(this).attr('data-option');

    });

    $(".notification-switch-button").click( function (event) {
			event.preventDefault();

      var formData = {
        'notification-subscribe-type': $('input[name=notification-subscribe-type]').val(),
        'notification-subscribe-id': $('input[name=notification-subscribe-id').val()
      };

      $.ajax({
        type: 'POST',
        url: '/PAI-proj/notification/set',
        data: formData,
        dataType: 'json',
        encode: true
      })
        .done (function(data){
          if (data.success === false) {
						console.log('ERROR');
          } else {
              location.reload();
          }

        });

    });

		//$(".datetime-counter")
});
