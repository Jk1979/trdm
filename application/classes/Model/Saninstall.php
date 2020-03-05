<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Saninstall extends ORM
{
    protected $_table_name =  'saninstall_price';
    protected $_primary_key = 'id';


    public function rules() {
        return array (

            'title' => array (
                array('not_empty'),
                array('min_length',array(':value',3)),
                ),

        );
    }
    
    public function labels(){
        return array(
            'title' =>'Наименование',
            'price' =>'Цена',

        );
    }
    
    public function filters(){
        return array(
            TRUE => array (
                array('trim'),
            ),
        );
    }
    protected $_belongs_to = array(
        'group' => array(
            'model' => 'Saninstallgroup',
            'foreign_key' => 'group_id',
        ),
    );

}