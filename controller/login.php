<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 07.11.2017
 * Time: 19:46
 */

class Login extends Controller
{
    function lostPassword ($f3) {
        $f3->set('content', 'lostpassword.html');
    }

    function view ($f3) {
        $f3->set('content', 'login.html');
    }

    //ajax request
    function validateLoginForm ($f3) {
        $returnData = array();
        $username = $f3->get('POST.username');
        $password = $f3->get('POST.password');

        $this->doRender = false;
        $user = $this->getUserData($username);
        $calculatedPass = hash('sha256', $user->salt . $password);

        if ($user->username === $username &&
            $user->password === $calculatedPass) {

            $f3->set('SESSION.username', $user->username);
            $f3->set('SESSION.userid', $user->id);

            $returnData['success'] = true;

        } else {
            $returnData['success'] = false;
        }

        $returnData['rerouteAfterLogin'] = ($f3->exists('rerouteAfterLogin') ? $f3->get('rerouteAfterLogin') : '/');
        $f3->clear('rerouteAfterLogin');

        echo json_encode ($returnData);
    }

    function handleLogout ($f3)  {
        $f3->clear('SESSION');
        $f3->reroute('/');
    }

    //TODO does not reroute properly
    static function handleUserShouldBeLogged ($f3) {
        if (!$f3->exists('SESSION.username')) {
            $f3->set('rerouteAfterLogin', $_SERVER['REQUEST_URI']);
            Logger::log("Server path is " . $f3->get('rerouteAfterLogin'));
            $f3->reroute ('/login');
        }
    }

    private function getUserData ($username) {
        $userMapper = new DB\SQL\Mapper($this->db, 'user');
        return $userMapper->load(array('username=?', $username));
    }
}
