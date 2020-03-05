<?php defined('SYSPATH') or die('No direct script access.');

class Model_Prodcats extends ORM {
    
    protected $_table_name =  'prod_cats';
    protected $_primary_key = 'cat_id';
    
   
  
 
   public function rules()
    {
        return array(
            'prod_id' => array(
                array('not_empty'),
            ),
            'cat_id' => array(
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
           
        );
    }
    

} 
