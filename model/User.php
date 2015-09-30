<?php

/**
 * This class represents one user.
 * A user can have a username and password.
 * This class contains only getter and setter methods.
 */
class User {

    private $username;
    private $password;

    /**
     * Get username for this user
     * @return $username
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Sets a new username for this user
     * @param $username
     */
    public function setUsername($username){
        $this->username = $username;
    }

    /**
     * Get password belonging to this user
     * @return $password
     */
    public function getPassword(){
        return $this->password;
    }

    /**
     * Sets a new password for this user
     * @param $password
     */
    public function setPassword($password){
        $this->password = $password;
    }

}