<?php defined('SYSPATH') or die('No direct script access.');

class Model_Froptionvalues extends ORM {

    protected $_table_name = 'froptionvalues';
    protected $_primary_key = 'option_id';

    protected $_belongs_to = array(
        'product' => array(
            'model' => 'Product',
            'foreign_key' => 'prod_id',
        ),
    );
     
    
    
   
} 
