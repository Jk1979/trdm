<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Меню товары"
 */
class Controller_Widgets_Menuproducts extends Controller_Widgets {

    
    public function action_index()
    {
        $select = Request::initial()->controller();
        

        $menu = array(
            'Товары' => array('products'),
            'Атрибуты' => array('attributes'),
            'Категории' => array('categories'),
            'Производители' => array('brands'),
            'Импорт/Экспорт' => array('import'),
        );
        //if($select == 'Series') $menu['Серии'] = array('series');
         // Вывод в шаблон
         $this->template->content = View::factory('widgets/w_menuproducts',
                array('menu'=>$menu,'select'=>$select));
    }

}