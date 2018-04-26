<?php

namespace controllers;

use components\Main as Main;

class DefaultController {
    
    public function actionIndex(){
        echo "index<br><br>";
        
        $str = '';
        foreach (Main::$app->ulrMenedger->url_data as $key => $val){
            $str .= htmlentities("[$key] => $val") . "<br/>"; 
        }
        echo $str;
        die;
    }
    
    public function actionAdminLogin(){
        echo "admin-login<br>";
        $str = '';
        foreach (Main::$app->ulrMenedger->url_data as $key => $val){
            $str .= htmlentities("[$key] => $val") . "<br/>"; 
        }
        echo $str;
        die;
    }
    
    public function actionNoteList(){
        echo "note-list<br>";
        $str = '';
        foreach (Main::$app->ulrMenedger->url_data as $key => $val){
            $str .= htmlentities("[$key] => $val") . "<br/>"; 
        }
        echo $str;
        die;
    }
    
}

