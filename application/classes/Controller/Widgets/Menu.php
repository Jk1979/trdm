<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Widgets_Menu extends Controller_Widgets 
{
	 // Главная страница
	
    public function action_index()
    {
        $menu = Model::factory('Menu')->get_menu();

	$this->template->content = View::factory('widgets/w_menu',array('menu'=>$menu));			
    } 
    
   
    
}//class    

