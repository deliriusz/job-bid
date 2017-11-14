<?php
/**
 * Created by PhpStorm.
 * User: kalinowr
 * Date: 11.11.2017
 * Time: 22:45
 */

class Users extends Controller
{

    private $f3;
    function setF3 ($f3) {
        $this->f3 = $f3;
    }
    function getUsers ($constrainsArr = NULL, $paginationSettings = NULL) {
        $usersMapper = new DB\SQL\Mapper($this->f3->get('DB'), 'user');
        $usersMapper->load($constrainsArr, $paginationSettings);
        $usersArray = array();
        for ($i =  0; $i < $usersMapper->loaded(); $i++) {
            $usersArray[$i] = $this->usersMapperToUsers($usersMapper);
            $usersMapper->next();
        }
        return $usersArray;
    }

    private function usersMapperToUsers ($m) {
        $u = new User(
            $m->id,
            $m->username,
            $m->first_name,
            $m->last_name,
            $m->birth_date,
            $m->email,
            $m->password,
            $m->salt
        );
        return $u;
    }
}

class User {
    public $id;
    public $username;
    public $first_name;
    public $last_name;
    public $birth_date;
    public $email;
    public $password;
    public $salt;

    /**
     * User constructor.
     * @param $id
     * @param $username
     * @param $first_name
     * @param $last_name
     * @param $birth_date
     * @param $email
     * @param $password
     * @param $salt
     */
    public function __construct($id = NULL,
                                $username = NULL,
                                $first_name = NULL,
                                $last_name = NULL,
                                $birth_date = NULL,
                                $email = NULL,
                                $password = NULL,
                                $salt = NULL)
    {
        $this->id = $id;
        $this->username = $username;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->birth_date = $birth_date;
        $this->email = $email;
        $this->password = $password;
        $this->salt = $salt;
    }

}
