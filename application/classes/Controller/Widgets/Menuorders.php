<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Меню заказов"
 */
class Controller_Widgets_Menuorders extends Controller_Widgets {

    
    public function action_index()
    {
        $select = Request::initial()->controller();

        $menu = array(
            'Новые' => array('orders'),
            'В работе' => array('workorders'),
            
        );
        $this->template->content = View::factory('widgets/w_menuorders',
                array('menu'=>$menu,'select'=>$select));
    }

}