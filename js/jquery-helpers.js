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
        url: '/register/formsubmit',
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
              location.href = "/register/welcome";
          }

        });

        event.preventDefault();
    });
});
