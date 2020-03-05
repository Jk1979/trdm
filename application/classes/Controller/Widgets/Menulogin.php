<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Widgets_Menulogin extends Controller_Widgets 
{
	 // Главная страница
	
    public function action_index()
    {
     	$this->template->content = View::factory('widgets/w_menulogin');			
    } 
    
   
    
}//class    

