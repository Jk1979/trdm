<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Меню Пользователи"
 */
class Controller_Widgets_Menuusers extends Controller_Widgets {

    
    public function action_index()
    {
        $select = Request::initial()->controller();

        $menu = array(
            'Все' => array('users'),
            'Покупатели' => array('buyers'),
            'Рассылка' => array('suscribe'),
            
        );
         // Вывод в шаблон
        $this->template->content = View::factory('widgets/w_menuusers',
                array('menu'=>$menu,'select'=>$select));
    }

}