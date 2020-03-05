<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Форма поиска"
 */
class Controller_Widgets_Search extends Controller_Widgets {
    

    public function action_index()
    {
         $this->template->content = View::factory('widgets/w_search');
    }
    
   

}