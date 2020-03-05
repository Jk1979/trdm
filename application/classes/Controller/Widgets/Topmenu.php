<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Верхнее меню"
 */
class Controller_Widgets_Topmenu extends Controller_Widgets {
    

    public function action_index()
    {
         $this->template->content = View::factory('widgets/w_topmenu');
    }

}

