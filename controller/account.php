<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 19.11.2017
 * Time: 11:55
 */

class Account extends Controller
{
    function view ($f3) {
        $userController = new Users();
        $userController->setF3($this->f3);
        $users = $userController->getUsers(array('username = ?', $f3->get('SESSION.username')), NULL);

        $f3->set('user', $users[0]);

        $f3->set('content', 'account.html');
    }

    //ajax request
    function validateUpdateUserDataForm ($f3) {
        $errors = array();
        $returnData = array();

        if (!$f3->exists('POST.password')) {
            if (!$f3->exists('SESSION.username')) {
                array_push($errors, 'you have to be signed in to perform this operation');
            }

            if (!preg_match('/^[A-Z]{1}[a-z]{0,}$/', $f3->get('POST.first_name'))) {
                array_push($errors, 'first name should contain only letters and start with upper case character');
            }

            if (!preg_match('/^[A-Z]{1}[a-z]{0,}$/', $f3->get('POST.last_name'))) {
                array_push($errors, 'last name should contain only letters and start with upper case character');
            }

            if (!preg_match('/^[1-2]{1}[0-9]{3}-[0-9]{2}-[0-9]{2}$/', $f3->get('POST.birth_date'))) {
                array_push($errors, 'Birth date is not valid');
            }

            if (!filter_var($f3->get('POST.email'), FILTER_VALIDATE_EMAIL)) {
                array_push($errors, 'email is not valid');
            }
        } else {
            if (!preg_match('/^.{6,}$/', $f3->get('POST.password'))) {
                array_push($errors, 'password should be at least 6 characters long');
            }

            if ($f3->get('POST.password') !== $f3->get('POST.repeated_password')) {
                array_push($errors, 'password confirmation does not match pasword');
            }
        }

        $errorsFromUpdate = $this->updateUserData();
        foreach ($errorsFromUpdate as $key => $value) {
            array_push($errors, $value);
        }

        $this->doRender = false;
        $isSuccess = empty($errors);

        $returnData['success'] = $isSuccess;

        if (!$isSuccess) {
            $returnData['errors'] = $errors;
            echo json_encode ($returnData);
        } else {
            echo json_encode ($returnData);
        }
    }

    function updateUserData () {
        $saltLength = random_int(170, 180);
        $saltForUser = bin2hex(random_bytes($saltLength));
        $pass = $_POST['password'];
        $user = $this->f3->get('SESSION.username');
        $email = $_POST['email'];
        $calculatedPass = hash('sha256', $saltForUser . $pass);
        $errors = array();

        $userMapper = new DB\SQL\Mapper($this->db, 'user');
        $userMapper->load(array('username=?', $user));

        if ($userMapper->loaded() === 0) {
            array_push($errors, 'You have to be logged in to perform this operation');
        }

        if (empty ($errors)) {

            if ($pass !== NULL) {
                $userMapper->password = $calculatedPass;
                $userMapper->salt = $saltForUser;
            } else {
                $userMapper->email = $email;
                $userMapper->birth_date = $_POST['birth_date'];
                $userMapper->first_name = $_POST['first_name'];
                $userMapper->last_name = $_POST['last_name'];
            }

            $userMapper->save();
        }

        return $errors;
    }
}
