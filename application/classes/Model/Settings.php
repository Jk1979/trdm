<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Settings extends ORM
{
    protected $_table_name =  'settings';
    protected $_primary_key = 'id';
    
    public function labels()
    {
        return array(
            'email' => 'Эл.почта',
            
        );
    }
 
   public function rules()
    {
        return array(
            'email' => array(
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
           
        );
    }

}