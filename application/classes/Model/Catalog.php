<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Catalog extends Model
{
     // Все продукты
    public function all_products()
    {
        return array(
                'Душевая кабина Appolo' => 100,
                'Газовый котел' => 200,
                'Декор' => 300,
                'Стекломозаика' => 400,
            );
    }
     // 3 лучших товара по числу покупок
    public function top_products()
    {
        return array(
                'Душевая кабина Appolo',
                'Газовый котел',
                'Декор',
            );
    }

    

}
