<?php defined('SYSPATH') or die('No direct script access.');

class Model_Pay extends ORM {
    
    protected $_table_name =  'pay';
    protected $_primary_key = 'id';
    
    
    


public function labels()
    {
        return array(
            'title' => 'Наименование',
        );
    }
 
   public function rules()
    {
        return array(
            'title' => array(
                array('not_empty'),
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
        );
    }
    
  
} 

