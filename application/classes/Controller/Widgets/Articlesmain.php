<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Статьи"
 */
class Controller_Widgets_Articlesmain extends Controller_Widgets {

    public function action_index()
    {
       /* $cache = Cache::instance();
        $cacheKey = 'articlesmain';

        $content = $cache->get($cacheKey);
        if($content) {
            $this->template->content = $content;

        }
        else {*/
            // Получаем список категорий
            $articles = ORM::factory('Article')->where('mainpage', '=', 1)->order_by('date', 'DESC')->limit(10)->find_all()->as_array();
            $content = View::factory('widgets/w_articlesmain',array('articles'=>$articles));
            $this->template->content = $content;

            //$cache->set($cacheKey, $content, Date::MINUTE * 10000);
//        }



    }

}