<?php defined('SYSPATH') or die('No direct script access.');

class Model_Prodoption extends ORM {
    
    protected $_table_name =  'prodoptions';
    protected $_primary_key = 'code';
    


public function labels()
    {
        return array(
            
            
            'color' => 'Цвет',
            'imitation' => 'Имитация',
            'material' => 'Материал',
            'form' => 'Форма',
            'surface' => 'Поверхность',
            'imagedecor' => 'Изображение на декоре',
            'moroz' => 'Морозоустойчивая',
            'rect' => 'Ректификат',
            'country' => 'Страна производитель',
            'antislip' => 'Антислип',
            'variativnost' => 'Вариативность',
            'klasstir' => 'Класc стираемости',
            'serie' => 'Коллекция',
            'destination' => 'Назначение',
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

