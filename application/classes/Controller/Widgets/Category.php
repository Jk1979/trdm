<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Widgets_Category extends Controller_Widgets 
{
	 // Главная страница
    
    public function action_index()
    {
		
	$select = Request::initial()->param('cat');
        if(!$select)
        {
             $id =  Request::initial()->param('id');
             $id = mysql_real_escape_string ($id);
             $product = ORM::factory('Product')->where('path', '=', $id)->and_where('status', '!=', 0)->find();
             $category = $product->categories->find();
             $select = $category->path;
        }
        // Получаем список категорий
        $categories = ORM::factory('Category')->fulltree()->as_array();
        
        foreach($categories as $cat)
        {
            if($cat->parent_id !=1)
            $cat->title = str_repeat('&nbsp;', 1 * $cat->lvl) . $cat->title;
        }
        
        //$this->template->header = 'Категории';
        $this->template->content = View::factory('widgets/w_category')
                ->set('categories',$categories)
                ->set('select',$select);
    } 
    
   
   
         
        

              
                        
    
}//class    

