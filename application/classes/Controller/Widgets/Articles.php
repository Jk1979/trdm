<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Статьи"
 */
class Controller_Widgets_Articles extends Controller_Widgets {

    public function action_index()
    {
        $category = Controller_Index_Catalog::$category;
        // Получаем список категорий


            $articles = ORM::factory('Article')->where('cat_id','=',$category->cat_id)->and_where('status', '=', 1)->find_all()->as_array();
            $content = View::factory('widgets/w_articles',array('articles'=>$articles));
            $this->template->content = $content;
        

    }

}