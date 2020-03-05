<?php defined('SYSPATH') or die('No direct script access.');

class Model_Attributes extends ORM {

    protected $_table_name = 'attributes';
    protected $_primary_key = 'attr_id';

    protected $_has_many = array(
        'attributesvalues' => array(
            'model' => 'Attributesvalues',
            'foreign_key' => 'attr_id',
          ),
        
    );
    
    public function rules() {
        return array (
            'name' => array (
                array('not_empty'),
                ),
            'path' => array(
                array('not_empty'),
                array('alpha_dash'),
                array(array($this, 'uniq_alias'), array(':value', ':field')),
            ),
        );
    }
    
    public function labels(){
        return array(
            'name' =>'Наименование',
            
        );
    }
    
    public function filters(){
        return array(
            TRUE => array (
                array('trim'),
            ),
            'name' => array (
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
