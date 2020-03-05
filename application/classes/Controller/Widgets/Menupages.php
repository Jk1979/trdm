<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Меню заказов"
 */
class Controller_Widgets_Menupages extends Controller_Widgets {

    public function action_index()
    {
        $select = Request::initial()->controller();

        $menu = array(
            'Страницы' => array('pages'),
            'Новости' => array('news'),
            'Статьи' => array('articles'),
            'Установка сантехники' => array('saninstall'),

        );
         // Вывод в шаблон
        $this->template->content = View::factory('widgets/w_menupages',
                array('menu'=>$menu,'select'=>$select));

    }

}