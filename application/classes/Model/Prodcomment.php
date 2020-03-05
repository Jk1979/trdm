<?php defined('SYSPATH') or die('No direct script access.');

class Model_Prodcomment extends ORM {
    
    protected $_table_name  = 'prodcomments';

    protected $_belongs_to = array(
        'product' => array(
            'model' => 'Product',
            'foreign_key' => 'prod_id',
        ),
    );
    
    public function labels()
    {
        return array(
            'date' => 'Дата',
            'author' => 'Автор',
            'content' => 'Текст комментария'
        );
    }
 
   public function rules()
    {
        return array(
            'date' => array(
                array('date'),
                array('not_empty'),
            ),
            'author' => array(
                array('not_empty'),
            ),
            'content' => array(
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
            TRUE => array(
                array('strip_tags'),
            ),
        );
    }
} 
