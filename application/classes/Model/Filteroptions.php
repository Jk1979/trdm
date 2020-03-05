<?php defined('SYSPATH') or die('No direct script access.');

class Model_Filteroptions extends ORM {

    protected $_table_name = 'filteroptions';
    protected $_primary_key = 'option_id';

    protected $_has_many = array(
        'products' => array(
            'model' => 'Product',
            'foreign_key' => 'option_id',
            'through' => 'froptionvalues',
            'far_key' => 'prod_id',
        ),
        
    );
    protected $_belongs_to = array(
        'filters' => array(
            'model' => 'Filters',
            'foreign_key' => 'filter_id',
        ),
    );
     
    public function rules() {
        return array (
            'option_title' => array (
                array('not_empty'),
                ),
            'option_path' => array(
               /* array('not_empty'),
                array('alpha_dash'),*/
                /*array(array($this, 'uniq_alias'), array(':value', ':field')),*/
            ),
        );
    }
    
    public function labels(){
        return array(
            'option_title' =>'Наименование',
            
        );
    }
    
    public function filters(){
        return array(
            TRUE => array (
                array('trim'),
            ),
            'option_title' => array (
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
