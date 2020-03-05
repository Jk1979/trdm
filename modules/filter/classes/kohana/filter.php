<?php defined('SYSPATH') or die('No direct script access.'); // как обычно - защита от прямого доступа
 
class Kohana_Filter { // поехали!
 
    public static function factory(array $config = array()) // здесь происходит создание объекта
    {
        return new Filter($config); // создаем новый объект Filter с нашим конфигом
    }
 
    public function __construct(array $config = array()) // конструктор класса
    {
        $this->config = Kohana::$config->load('filter')->as_array(); // заносим в $this->config конфиг из папки с модулем и объединяем его с конфигом пользователя, елси он есть
    }
    public function loadFiltersOptions($category_id, $filters = array())
    {
        $filtersarr = ORM::factory('filters')->where('cat_id','=',$category_id)->find_all();
        $i = 0;
        foreach($filtersarr as $f)
        {
            $filters[$i]['title'] = $f->filter_title;
            $filters[$i]['options'] = $f->options->find_all()->as_array();
            $i++;
        }
       $this->filters = $filters;
          
    }
    public function render() // функция рисования комментариев
    {    
        $view = View::factory('filter/filter')->set('filters', $this->filters); // создаем переменную с нашим видом, в который передаем конфиг
        return $view->render(); // как результат вызова функции - возвращаем отрендеренный вид
    }
}

/////   Использование в контроллере:  
// $filter = Filter::factory();
// $this->template->content = $filter->render();