<?php

namespace components;

class Request {
    
    private static $__obj;
    
    public static function init(){
        if (!self::$__obj){
            self::$__obj = new self();
        }
        return self::$__obj;
    }

    private $_raw_post;
    private $_raw_get;
    private $_raw_session;
    private $_raw_coocie;

    private function __construct() {
        $this->_raw_post = $_POST;
        $this->_raw_get = $_GET;
        $this->_raw_session = $_SESSION;
        $this->_raw_coocie = $_COOKIE;
    }
    
    public function getRawPost(){
        return $this->_raw_post;
    }
    
    public function getRawGet(){
        return $this->_raw_get;
    }
    
    public function getRawSession(){
        return $this->_raw_session;
    }
    
    public function getRawCookie(){
        return $this->_raw_coocie;
    }
}
