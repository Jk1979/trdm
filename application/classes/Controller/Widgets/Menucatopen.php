<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Widgets_Menucatopen extends Controller_Widgets 
{
	 // Главная страница
	
    public function action_index()
    {
		
	$select = Request::initial()->param('cat');

        // Получаем список категорий
        $categories = ORM::factory('Category')->fulltree()->as_array();
        
        foreach($categories as $cat)
        {
            if($cat->parent_id !=1)
            $cat->title = str_repeat('-', 1 * $cat->lvl) . $cat->title;
        }
        
        //$categories = $categories->fulltree();
        
        $this->template->content = View::factory('widgets/w_menucatopen')
                ->set('categories',$categories);
    } 
    
   

    
}//class    

