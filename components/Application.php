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
    }

    public function run(){
        $url_params = $this->config['urlManager'];
        
       
        
        $this->ulrMenedger = new $url_params['class']($url_params['params']);
        $this->ulrMenedger->getController();
    }
    
}