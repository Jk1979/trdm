<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Новости"
 */
class Controller_Widgets_Mainnews extends Controller_Widgets {
    

    public function action_index()
    {
        // Получаем список категорий
        $news = ORM::factory('News') 
                ->where('status','=',1)
                ->order_by('date', 'ASC')
                ->find_all();
        $this->template->news = $news;
        $this->template->content = View::factory('widgets/w_mainnnews',
                array('news'=>$news));
    }

}