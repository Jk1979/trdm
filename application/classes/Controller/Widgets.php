<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Базовый класс виджетов
 */
class Controller_Widgets extends Controller_Template {
    
    public $template = 'widgets/w_widget';
    
    
    
    public function  before() {
        parent::before();

        if(Request::current()->is_initial())
        {
            $this->auto_render = FALSE;
        }
        $this->template->header = null;
        $this->template->content = null;
    }
}
