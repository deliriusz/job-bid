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
        $users = $userController->getUsers(array('username = ?', $f3->get('PARAMS.username')), NULL);

        $f3->set('user', $users[0]);

        $f3->set('content', 'account.html');
    }

    //ajax request
    function validateUpdateUserDataForm ($f3) {
        Login::handleUserShouldBeLogged($f3);
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

		//ajax request
		function upsertProfilePicture ($f3) {
      $this->doRender = false;
			$overwrite = false; // set to true, to overwrite an existing file; Default: false
			$slug = true; // rename file to filesystem-friendly version
			$returnData = array();
			$errors = array();

			if ($f3->get('PARAMS.userid') !== $f3->get('SESSION.userid')) {
				array_push($errors, 'you cannot change other user\'s profile picture');
			} else {
			$files = $web->receive(function($file,$formFieldName){
			        var_dump($file);
			        /* looks like:
			          array(5) {
			              ["name"] =>     string(19) "csshat_quittung.png"
			              ["type"] =>     string(9) "image/png"
			              ["tmp_name"] => string(14) "/tmp/php2YS85Q"
			              ["error"] =>    int(0)
			              ["size"] =>     int(172245)
			            }
			        */
			        // $file['name'] already contains the slugged name now

							$allowed=$f3->get('allowed');
							if (!(is_array($allowed) && in_array($file['type'],$allowed)) ) {
								array_push($errors, 'following image type is not accepted');
							}

			        if($file['size'] > (2 * 1024 * 1024)) { // if bigger than 2 MB
								array_push($errors, 'image should be 2MBs at max');
							}

				        return empty($errors);
				    },
			    $overwrite,
			    $slug
				);
			}

			echo json_encode($returnData);

		}

		function getProfilePicture ($f3) {
      $this->doRender = false;
			$userid = $f3->get('PARAMS.userid');

      $imageMapper = new DB\SQL\Mapper($this->db, 'profile_image');
			$imageMapper->load (array('user_id = ?', $userid));
			if ($imageMapper->loaded() === 0) {
				$imageMapper->load (array('id = 1'));
			}
			// echo 'data:image/bmp;base64,' .  base64_encode($imageMapper->image);
			echo $imageMapper->image;
		}

    function updateUserData () {
        Login::handleUserShouldBeLogged($this->f3);
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
                $userMapper->info = $_POST['info'];
            }

            $userMapper->save();
        }

        return $errors;
    }
}
