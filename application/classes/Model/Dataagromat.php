<?php defined('SYSPATH') or die('No direct script access.');

class Model_Dataagromat extends ORM {
    
    protected $_table_name =  'dataagromat';
    protected $_primary_key = 'code';
    


public function labels()
    {
        return array(
            
            'code' => 'Код',
            'price' => 'Цена',
            'unit' => 'Ед измерения',
            'size' => 'размер',
            'pieces' => 'шт в уп',
            'meters' => 'м в уп',
          
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

