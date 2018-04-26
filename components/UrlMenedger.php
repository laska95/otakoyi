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
        '<CTRL>' => '[a-zA-Z0-9\-\_]{1,}',
        '<ACT>' => '[a-zA-Z0-9\-\_]{1,}',
        '<PGN>' => '[0-9]{1,}', //page number
        '<ID>' => '[0-9]{1,}'
    ];


    public $rules = [];
    public $base_dir;
    public $url;

    public $url_data;
    public $url_rule;


    public function __construct($params) {
        $this->base_dir = self::BASE_DIR;
        $q_index = strpos($_SERVER['REQUEST_URI'], '?');
        $this->url = ($q_index > -1) ? substr($_SERVER['REQUEST_URI'], 0, $q_index) : $_SERVER['REQUEST_URI'];
                
        foreach ($params as $key => $val){
            if (isset($this->$key)){
                $this->$key = $val;
            }
        }
        
        $this->url_data = $this->parseUrl();
    }
    
    public function parseUrl(){
        
        $ret = [];
        $test_ret = FALSE;
        $rule_result = '';
        
        foreach ($this->rules as $rule_pattern => $rule_rslt){
            $test = $this->testUrl($rule_pattern, $this->url);
            if ($test['value']){
                $test_ret = $test['params'];
                $rule_result = $rule_rslt;
                $this->url_rule = $rule_pattern;
                break;
            }
        }

        if ($test_ret !== FALSE){
            foreach ($this->patterns as $key => $reg){
                $ret[$key] = $this->getPropValue($key, $test_ret, $rule_result);
            }
            return $ret;
        }
        
        return [];
    }

    public function getController(){

        if (isset($this->url_data['<CTRL>'])){
            $ctrl_name = self::BASE_DIR . '-' . $this->url_data['<CTRL>'] . '-controller';
            $ctrl_name = $this->dashesToCamelCase($ctrl_name, FALSE);
            $ctrl_path = $this->getCtrlPath($this->rules[$this->url_rule], $ctrl_name);
            $ctrl_obj = new $ctrl_path();
            return $ctrl_obj;
        } else {
            return NULL;
        }
        
    }
    
    public function getAction(){
        if (isset($this->url_data['<ACT>'])){
            $act_name = 'action-' . $this->url_data['<ACT>'];
            $act_name = $this->dashesToCamelCase($act_name, FALSE);
            return $act_name;
        } else {
            return NULL;
        }
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
            preg_match("#\<[A-Z]{1,10}\>#", $one_pttrn, $matches); 
            if (!empty($matches) && isset($this->patterns[$matches[0]])){
                //це абрівіатура контроллера чи дії
                $prefix = substr($one_pttrn, 0, strpos($one_pttrn, '<'));
                $suffix = substr($one_pttrn, strpos($one_pttrn, '>') + 1);
                $reg_pattern = "#^" . $prefix . $this->patterns[$matches[0]] . $suffix . "$#";
                $test = preg_match($reg_pattern, $arr_url[$i]);
                if ($test){
                    $value = preg_replace("#(^{$prefix})|({$suffix}$)#", '', $arr_url[$i]);
                    $ret_params[$matches[0]] = $value;
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
        return $ret; //правило співпадає, всі параметри розпізнано
    }
    
    private function getPropValue($prop_name, $test_params, $rule_result){
        if (isset($test_params[$prop_name])){
            return $test_params[$prop_name];
        } 
        
        $pp = substr($prop_name, 0, -1) . '='; // '<CRTL>' ==> '<CTRL='
        if (preg_match("#$pp#", $rule_result)){
            $name_from_rule = preg_replace("#^.{0,}{$pp}#", '', $rule_result);
            $name_from_rule = preg_replace("#>.{0,}$#", '', $name_from_rule);
            return $name_from_rule; // '<CTRL=default>'  ==> return 'default'
        } else {
            return NULL;
        }
        
        
    }
    
    /*
     * Видаляє всі абрівіатури крім <CTRL>. <CTRL> заміняє на назву контроллера
     */
    private function getCtrlPath($rule_result, $ctrl_name){
        $arr = array_filter(explode('/', $rule_result));
        $new_arr = [];
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
