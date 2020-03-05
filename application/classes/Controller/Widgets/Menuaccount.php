<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Меню личного кабинета"
 */
class Controller_Widgets_Menuaccount extends Controller_Widgets {

    
    public function action_index()
    {
    $this->template->content = View::factory('widgets/w_menuaccount');

    }

}