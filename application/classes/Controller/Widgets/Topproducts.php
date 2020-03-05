<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Лучшие товары"
 */
class Controller_Widgets_Topproducts extends Controller_Widgets {
    
    public function action_index()
    {
        // Получаем список категорий
        $products = ORM::factory('Product')->where('top','=',1)->and_where('status', '=', 1)->find_all();
        $this->template->content = View::factory('widgets/w_topproducts_boot',
                array('products'=>$products));
    }

}