<?php defined('SYSPATH') or die('No direct script access.');

class Model_Order extends ORM {
    
    protected $_table_name =  'orders';
    protected $_primary_key = 'order_id';
    
    protected $_has_many = array(
        'products' => array(
            'model' => 'Orderprods',
            'foreign_key' => 'order_id',
        ),
    );
     protected $_belongs_to = array(
        'user' => array(
            'model' => 'User',
            'foreign_key' => 'user_id',
        ),
    );
     
    
   public function labels()
    {
        return array(
            'name' => 'Имя',
            'phone' => 'Телефон', 
            'email' => 'email', 
            'adress' => 'Адрес', 
            
        );
    }
 
   public function rules()
    {
        return array(
            'name' => array(
                array('not_empty'),
            ),
            'phone' => array(
                array('not_empty'),
            ),
            'email' => array(
                array('not_empty'),
                array('email'),
            ),
            
            
        );
    }
    
   public function filters()
    {
        return array(
            TRUE => array(
                array('trim'),
            ),
            'name' => array(
                array('strip_tags'),
            ),
            'phone' => array(
                array('strip_tags'),
            ),
        );
    }
    
 
} 
