<?php defined('SYSPATH') or die('No direct script access.');

class Model_Orderprods extends ORM {
    
    protected $_table_name =  'order_prod';
    protected $_primary_key = 'id';
    
     protected $_belongs_to = array(
        'order' => array(
            'model' => 'Order',
            'foreign_key' => 'order_id',
        ),
    );
    
   
    
 
} 
