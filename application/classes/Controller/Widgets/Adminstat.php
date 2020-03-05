<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Статистика магазина"
 */
class Controller_Widgets_Adminstat extends Controller_Widgets {

    
    public function action_index()
    {
        $count['news'] = ORM::factory('News')->count_all();
        $count['articles'] = ORM::factory('Article')->count_all();
        $count['products'] = ORM::factory('Product')->count_all();
        $count['orders'] = ORM::factory('Order')->count_all();
        $count['users'] = ORM::factory('User')->count_all();

        // Вывод в шаблон
        
        $this->template->content = View::factory('widgets/w_adminstat',array('count'=>$count));
    }

}