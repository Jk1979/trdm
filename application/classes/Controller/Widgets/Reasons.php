<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Лучшие товары"
 */
class Controller_Widgets_Reasons extends Controller_Widgets {
    
    public function action_index()
    {
        // Получаем список категорий
        $this->template->content = View::factory('widgets/w_reasons');
    }

}