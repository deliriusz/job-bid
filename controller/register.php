<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 07.11.2017
 * Time: 22:20
 */


class Register extends Controller
{
    function view ($f3) {
        $f3->set('content', 'register.html');
        $this->doRender = true;
    }

    //ajax request
    function validateNewUserForm ($f3) {
      $errors = array();
      $returnData = array();
      $username = $f3->get('POST.username');

      if (!preg_match('/^[0-9a-zA-Z]{0,}$/', $username)) {
        array_push($errors, 'username should contain only characters and numbers');
      }

      if (!preg_match('/^.{5,}$/', $username)) {
        array_push($errors, 'username should be at least five characters long');
      }

      if (!preg_match('/^[A-Z]{1}[a-z]{0,}$/', $f3->get('POST.first_name'))) {
        array_push($errors, 'first name should contain only letters and start with upper case character');
      }

      if (!preg_match('/^[A-Z]{1}[a-z]{0,}$/', $f3->get('POST.last_name'))) {
        array_push($errors, 'last name should contain only letters and start with upper case character');
      }

      if (!preg_match('/^.{6,}$/', $f3->get('POST.password'))) {
        array_push($errors, 'password should be at least 6 characters long');
      }

      if (!preg_match('/^[1-2]{1}[0-9]{3}-[0-9]{2}-[0-9]{2}$/', $f3->get('POST.birth_date'))) {
        array_push($errors, 'Birth date is not valid');
      }

      if ($f3->get('POST.password') !== $f3->get('POST.repeated_password')) {
        array_push($errors, 'password confirmation does not match pasword');
      }

      if (!filter_var($f3->get('POST.email'), FILTER_VALIDATE_EMAIL)) {
        array_push($errors, 'email is not valid');
      }

      $this->doRender = false;
      $isSuccess = empty($errors);

      $returnData['success'] = $isSuccess;

      if (!$isSuccess) {
        $returnData['errors'] = $errors;
        echo json_encode ($returnData);
      } else {
        echo json_encode ($returnData);
        $this->registerNewUser($f3);
      }
    }

    function registerNewUser ($f3) {
        $saltLength = random_int(170, 180);
        $saltForUser = bin2hex(random_bytes($saltLength));
        $pass = $_POST['password'];
        $user = $_POST['username'];
        $email = $_POST['email'];
        $calculatedPass = hash('sha256', $saltForUser . $pass);

        $f3->get ('DB')->exec ('INSERT INTO user (username, password, email, salt, birth_date, first_name, last_name) values (?, ?, ?, ?, ?, ?, ?)',
            array (
                $user,
                $calculatedPass,
                $email,
                $saltForUser,
                $_POST['birth_date'],
                $_POST['first_name'],
                $_POST['last_name']
            ));
    }

    function welcome ($f3) {
        $f3->set('content', 'start.html');
        $this->doRender = true;
    }
}
