<?php defined('SYSPATH') or die('No direct script access.');

class Model_News extends ORM {

    protected $_table_name = 'news';

    public function rules() {
        return array (
            'date' => array (
                array('not_empty'),
                array('date'),
                ),
            'title' => array (
                array('not_empty'),
                array('min_length',array(':value',5)),
                ),
            'content' => array (
                array('not_empty'),
                array('min_length',array(':value',10)),
                ),
            'path' => array (
                array('not_empty'),
                array('alpha_dash'),
                array(array($this, 'uniq_alias'), array(':value', ':field')),
                ),
        );
    }
    
    public function labels(){
        return array(
            'title' =>'Наименование',
            'content' =>'Текст статьи',
            'date' =>'Дата',
            'path' => 'Адрес',
        );
    }
    
    public function filters(){
        return array(
            TRUE => array (
                array('trim'),
            ),
            'title' => array (
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
