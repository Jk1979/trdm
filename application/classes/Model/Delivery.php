<?php defined('SYSPATH') or die('No direct script access.');

class Model_Delivery extends ORM {
    
    protected $_table_name =  'delivery';
    protected $_primary_key = 'id';
    
    
    


public function labels()
    {
        return array(
            'title' => 'Наименование',
            'price'=> 'стоимость'
        );
    }
 
   public function rules()
    {
        return array(
            'title' => array(
                array('not_empty'),
            ),
            'price' => array(
                array('numeric'),
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
            'price' => array(
                array('strip_tags'),
            ),
        );
    }
    
  
} 

