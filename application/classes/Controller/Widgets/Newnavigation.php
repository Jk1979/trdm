<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Widgets_Newnavigation extends Controller_Widgets 
{
	 // Главная страница
    
    public function action_index()
    {
        $cache = Cache::instance();
        $cacheKey = 'newnavigation';
        $content = $cache->get($cacheKey);
        if($content) {
            $this->template->content = $content;
        }
        else {
            // Получаем список категорий
            $categories = ORM::factory('Category')->fulltree()->as_array();
            $content = View::factory('widgets/w_newnavigation')->set('categories', $categories);
            $this->template->content = $content;
            $cache->set($cacheKey, $content, Date::MINUTE * 10000);
        }
        
    } 
    
                        
    
}//class    

