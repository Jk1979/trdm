<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Descbrandcat extends ORM
{
    protected $_table_name =  'descbrandcat';
    protected $_primary_key = 'id';
    public function rules() {
        return array (
           
            'description' => array (
                array('not_empty'),
                array('min_length',array(':value',10)),
                ),
        );
    }
    
    public function labels(){
        return array(
            'description' =>'Текст статьи',
        );
    }
    
    public function filters(){
        return array(
            TRUE => array (
                array('trim'),
            ),
        );
    }

}