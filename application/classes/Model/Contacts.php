<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Contacts extends ORM
{
    protected $_table_name =  'contacts';
    protected $_primary_key = 'id';
    
    public function labels()
    {
        return array(
            'title' => 'Название',
            'content' => 'Описание',
            'map' => 'Блок карты'
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