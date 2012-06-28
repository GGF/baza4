<?php
/**
 * Description of Auth_model
 *
 * @author Игорь
 */

class Auth_model {
    //put your code here
    public function getUserById($id) {
        $sql="SELECT * FROM users WHERE id='{$id}'";
        $user = sql::fetchOne($sql);
        return $user;
    }
}

?>
