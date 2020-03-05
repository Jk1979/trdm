<?php defined('SYSPATH') or die('No direct script access.');

class Model_Filters extends ORM {

    protected $_table_name = 'filters';
    protected $_primary_key = 'filter_id';

    protected $_has_many = array(
        'options' => array(
            'model' => 'Filteroptions',
            'foreign_key' => 'filter_id',
        ),
    );
    protected $_belongs_to = array(
        'category' => array(
            'model' => 'Category',
            'foreign_key' => 'cat_id',
        ),
    );
     
    public function rules() {
        return array (
            'filter_title' => array (
                array('not_empty'),
                ),
        );
    }
    
    public function labels(){
        return array(
            'filter_title' =>'Наименование',
            
        );
    }
    
    public function filters(){
        return array(
            TRUE => array (
                array('trim'),
            ),
            'filter_title' => array (
              array('strip_tags'),  
            ),
        );
    }
    
    
    
   
} 
