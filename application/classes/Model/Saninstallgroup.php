<?php defined('SYSPATH') or die('No direct script access.');

class Model_Saninstallgroup extends ORM {
    
    protected $_table_name =  'saninstallgroups';
    protected $_primary_key = 'id';
    
    protected $_has_many = array(
        'services' => array(
            'model' => 'Saninstall',
            'foreign_key' => 'group_id',
        ),

    );
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
