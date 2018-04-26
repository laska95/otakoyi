<?php

namespace components;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Application {
    
    public $config;
    public $ulrMenedger;


    public function __construct($config) {
        $this->config = $config;
        $this->parseUrl();
    }

    public function run(){
        
        $ctrl = $this->ulrMenedger->getController();
        $act = $this->ulrMenedger->getAction();
                    
        if (!$ctrl || !$act){
            throw new \Exception('Incorrect url');
        }
        
        $ctrl->$act();
    }
    
    private function parseUrl(){
        $url_params = $this->config['urlManager'];
        $this->ulrMenedger = new $url_params['class']($url_params['params']);
    }
}