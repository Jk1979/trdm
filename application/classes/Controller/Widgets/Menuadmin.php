<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Меню админа"
 */
class Controller_Widgets_Menuadmin extends Controller_Widgets {

    
    public function action_index()
    {
        $select = Request::initial()->controller();

        $menu = array(
            'Главная' => array('main'),
            'Каталог' => array('products','categories','brands'),
            'Страницы' => array('pages', 'news', 'articles'),
            'Заказы' => array('orders'),
            'Пользователи' => array('users','buyers','subscribe'),
            'Настройки' => array('settings','pay','delivery','discount'),
        );

        // Вывод в шаблон
        $this->template->content = View::factory('widgets/w_menuadmin',
                array('menu'=>$menu,'select'=>$select));
    }

}