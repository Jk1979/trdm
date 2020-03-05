<?php defined('SYSPATH') or die('No direct script access.');

class Model_Prodattribut extends ORM {
    
    protected $_table_name =  'prodattributes';
    protected $_primary_key = 'prod_id';
    


public function labels()
    {
        return array(
            
            'length' => 'Длина',
            'width' => 'Ширина',
            'thick' => 'Толщина',
            'color' => 'Цвет',
            'imitation' => 'Имитация',
            'material' => 'Материал',
            'form' => 'Форма',
            'surface' => 'Поверхность',
            'destination' => 'Назначение',
            'weight' => 'Вес',
            'country' => 'Страна производитель',
        );
    }
 
   /*public function rules()
    {
        return array(
            'title' => array(
                array('not_empty'),
            ),
            
        );
    }
    */
   public function filters()
    {
        return array(
            TRUE => array(
                array('trim'),
                array('strip_tags'),
            ),
               
        );
    }
   /* 
   public function uniq_alias($value, $field)
    {
        $page = ORM::factory($this->_object_name)
                ->where($field, '=', $value)
                ->and_where($this->_primary_key, '!=', $this->pk())
                ->find();
        
        if ($page->pk())
        {
            return false;
        }
        
        
        return true;
    } */  
} 

