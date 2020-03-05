<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Меню Настройки"
 */
class Controller_Widgets_Menusettings extends Controller_Widgets {

    
    public function action_index()
    {
         $select = Request::initial()->controller();

        $menu = array(
            'Общие' => array('settings'),
            'Контакты' => array('contacts'),
            'Оплата' => array('pay'),
            'Доставка' => array('delivery'),
            'Скидки' => array('discount'),
            
        );
         // Вывод в шаблон
        $this->template->content = View::factory('widgets/w_menusettings',
                array('menu'=>$menu,'select'=>$select));
    }

}