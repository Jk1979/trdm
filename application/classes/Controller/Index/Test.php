<?php defined('SYSPATH') or die('No direct script access.');


class Controller_Index_Test  extends Controller_Index {

    
    public function  action_index() {

        $this->auto_render = false;
        $var = $this->request->initial();
        echo Debug::vars($var);
        
    }
    
   
}