jQuery(document).ready(function($) {

  //https://github.com/sparksuite/simplemde-markdown-editor
  var simplemdeditor = new SimpleMDE({
    autofocus: false,
    element: $(".markdown-textarea")[0],
    showIcons: ["code", "table", "strikethrough", "heading", "heading-smaller", "heading-bigger", "horizontal-rule"],
    insertTexts: {
      horizontalRule: ["", "\n\n-----\n\n"],
      image: ["![](http://", ")"],
      link: ["[", "](http://)"],
      table: ["", "\n\n| Column 1 | Column 2 | Column 3 |\n| -------- | -------- | -------- |\n| Text     | Text      | Text     |\n\n"],
    },
    lineWrapping: true,
    styleSelectedText: true,
    tabSize: 4,
  });

  $("#submitUpdateUserDataForm").click (function (event){
    var formData = {
      'first_name': $('input[name=inputFirstName]').val(),
      'last_name': $('input[name=inputLastName]').val(),
      'username': $('input[name=inputUsername]').val(),
      'email': $('input[name=inputEmail]').val(),
      'info': simplemdeditor.value(),
      'birth_date': $('input[name=inputBirthDate]').val()
    };

    postForm('post', '/PAI-proj/user/account', formData, '', 'Please correct following errors: ', listErrorsOnDoneWithSettingSucess);

    event.preventDefault();
  });

  $("#submitUpdateUserPasswordForm").click (function (event){
    var formData = {
      'password': $('input[name=inputPassword]').val(),
      'repeated_password': $('input[name=inputRepeatPassword]').val()
    };

    postForm('post', '/PAI-proj/user/account', formData, '', 'Please correct following errors: ', listErrorsOnDoneWithSettingSucess);

    event.preventDefault();
  });

  $("#submitNewJobForm").submit (function (event){
    event.preventDefault();
    var formData = {
      'jobName': $('input[name=jobName]').val(),
      'jobStartDate': $('input[name=jobStartDate]').val(),
      'jobEndDate': $('input[name=jobEndDate]').val(),
      'jobInitialPayment': $('input[name=jobInitialPayment]').val(),
      'jobDescription': simplemdeditor.value()
    };

    postForm('post', '/PAI-proj/jobs/add', formData, '', 'Please correct following errors: ', listErrorsOnDoneWithReroute);

  });

  $("#submitEditJobForm").submit (function (event){
    event.preventDefault();
    console.log($('textarea[name=jobDescription]').val());
    var formData = {
      'jobName': $('input[name=jobName]').val(),
      'jobStartDate': $('input[name=jobStartDate]').val(),
      'jobEndDate': $('input[name=jobEndDate]').val(),
      'jobInitialPayment': $('input[name=jobInitialPayment]').val(),
      'jobDescription': simplemdeditor.value()
    };

    postForm('post', $(this).attr('action'), formData, '', 'Please correct following errors: ', listErrorsOnDoneWithReroute);

  });
});
