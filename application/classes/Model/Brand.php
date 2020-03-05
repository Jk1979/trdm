<?php defined('SYSPATH') or die('No direct script access.');

class Model_Brand extends ORM {
    
    protected $_table_name =  'brands';
    protected $_primary_key = 'brand_id';
    
protected $_has_many = array(
        'series' => array(
            'model' => 'Serie',
            'foreign_key' => 'brand_id',
        ),  
     
    'products' => array(
            'model' => 'Product',
            'foreign_key' => 'brand_id',
        ),  
);

public function labels()
    {
        return array(
            'title' => 'Наименование',
            'path' => 'Адрес', 
        );
    }
 
   public function rules()
    {
        return array(
            'title' => array(
                array('not_empty'),
            ),
            'path' => array(
                array('not_empty'),
                array('alpha_dash'),
                array(array($this, 'uniq_alias'), array(':value', ':field')),
            ),
        );
    }
    
   public function filters()
    {
        return array(
            TRUE => array(
                array('trim'),
            ),
            'title' => array(
                array('strip_tags'),
            ),
            'path' => array(
                array('strip_tags'),
            ),
        );
    }
    
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
    }   
} 

