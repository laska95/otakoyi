<?php

namespace components;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UrlMenedger {
    
    const CTRL = 'Controller';
    const BASE_DIR = 'controllers\\';
    
    public $patterns = [
        '<CTRL>' => '#^[a-zA-Z0-9\-\_]{1,}$#',
        '<ACT>' => '#^[a-zA-Z0-9\-\_]{1,}$#'
    ];


    public $rules = [];
    public $base_dir;
    public $url;


    public function __construct($params) {
        
        $this->base_dir = self::BASE_DIR;
        $this->url = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));
        foreach ($params as $key => $val){
            if (isset($this->$key)){
                $this->$key = $val;
            }
        }
    }
    
    public function getController(){

        foreach ($this->rules as $rule_pattern => $rule_result){
            $test = $this->testUrl($rule_pattern, $this->url);
            if ($test['value']){
                
                $ctrl_name = self::BASE_DIR . '-' . $this->getPropName('<CTRL>', $test['params'], $rule_result) . '-controller';
                $ctrl_name = $this->dashesToCamelCase($ctrl_name, FALSE);
                
                $act_name = 'action-' . $this->getPropName('<ACT>', $test['params'], $rule_result);
                $act_name = $this->dashesToCamelCase($act_name, FALSE);
                
                $ctrl_path = $this->getCtrlPath($rule_result, $ctrl_name);
                $ctrl_obj = new $ctrl_path();
                var_dump($ctrl_obj);
                die;
                var_dump($ctrl_name, $act_name);
                die;
//                $ctrl_path = str_replace('<CTRL>', $test['params']['<CTRL>'], $rule_result);
                
            }
//            var_dump($test);
//            if ($ret = ){
//                var_dump($re)
//            }
        }

        throw new \Exception('Incorrect url');
    }
    
    
    public function testUrl($rule_pattern, $url){
        
        $ret = [ 'value' => FALSE ];
        $ret_params = [];

        $arr_pttrn = array_values(array_filter(explode('/', $rule_pattern)));
        $arr_url = array_values(array_filter(explode('/', $url)));

        if (count($arr_pttrn) !== count($arr_url)){
            return $ret;
        }
                
        foreach ($arr_pttrn as $i => $one_pttrn){
            if (isset($this->patterns[$one_pttrn])){
                //це абрівіатура контроллера чи дії
                $test = preg_match($this->patterns[$one_pttrn], $arr_url[$i]);
                if ($test){
                    $ret_params[$one_pttrn] = $arr_url[$i];
                } else {
                    return $ret;    //false - правило не співпадає
                }
            } else {
                //це сталий вираз
                if ($one_pttrn !== $arr_url[$i]){
                    return $ret;    //false - правило не співпадає
                }
            }
        }
        
        $ret['value'] = TRUE;
        $ret['params'] = $ret_params;
        return $ret; //правило співпадає, всі параметри опізнано
    }
    
    private function getPropName($prop_name, $test_params, $rule_result){
        if (isset($test_params[$prop_name])){
            return $test_params[$prop_name];
        } 
        
        $pp = substr($prop_name, 0, -1) . '='; // '<CRTL>' ==> '<CTRL='
        $name_from_rule = preg_replace("#^.{0,}{$pp}#", '', $rule_result);
        $name_from_rule = preg_replace("#>.{0,}$#", '', $name_from_rule);
        return $name_from_rule; // '<CTRL=default>'  ==> return 'default'
    }
    
    /*
     * Видаляє всі абрівіатури крім <CTRL>. <CTRL> заміняє на назву контроллера
     */
    private function getCtrlPath($rule_result, $ctrl_name){
        $arr = array_filter(explode('/', $rule_result));
        $new_arr = [];
        var_dump($arr);
//        die;
        foreach ($arr as $one){
            if (preg_match("#(^<CTRL>$)|(^<CTRL=)#", $one)){
                $new_arr[] = $ctrl_name;
            } elseif (preg_match("#(^<[A-Z]{1,}>$)|(^<[A-Z]{1,}=)#u", $one)) {
                continue;   //інша абривіатура ('<ACT=index>')
            } else {
                $new_arr[] = $one;
            }
        }
        return implode('/', $new_arr);
    }
    
    private function dashesToCamelCase($string, $capitalizeFirstCharacter = false) 
    {

        $str = str_replace('-', '', ucwords($string, '-'));

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }
}
