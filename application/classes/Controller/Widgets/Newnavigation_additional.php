<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Widgets_Newnavigation extends Controller_Widgets 
{
	 // Главная страница
    
    public function action_index()
    {
        // Получаем список категорий
        $cats = array();
        $categories = ORM::factory('Category')->fulltree()->as_array();
        foreach($categories as $c)
        {
            $cats['cats'][] = $c;
           if($c->cat_id==56)
           {   
             $cats[$c->cat_id][0]['title']="Плитка для ванной";
             $cats[$c->cat_id][0]['path']="plitka_dlya_vannoi";
             $cats[$c->cat_id][0]['image']="trdm_icons_plitka_dlya_vannoi-min.jpg";
             $cats[$c->cat_id][1]['title']="Напольная плитка";
             $cats[$c->cat_id][1]['path']="napolnaya_plitka";
             $cats[$c->cat_id][1]['image']="trdm_icons_napolnaya_plitka-min.jpg";
             $cats[$c->cat_id][2]['title']="Плитка для кухни";
             $cats[$c->cat_id][2]['path']="plitka_dlya_kyhni";
             $cats[$c->cat_id][2]['image']="trdm_icons_plitka_dlya_kyxni-min.jpg";
             $cats[$c->cat_id][3]['title']="Керамогранит";
             $cats[$c->cat_id][3]['path']="keramogranit";
             $cats[$c->cat_id][3]['image']="trdm_icons_keramogranit-min.jpg";
           }
        }
        
        $this->template->content = View::factory('widgets/w_newnavigation')
                ->set('categories',$cats);
       
        
    } 
    
                        
    
}//class    

