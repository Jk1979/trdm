<?php defined('SYSPATH') or die('No direct script access.');

class Model_Product extends ORM {

    protected $_table_name = 'products';
    protected $_primary_key = 'prod_id';

    protected $_has_many = array(
        'comments' => array(
            'model' => 'Prodcomment',
            'foreign_key' => 'prod_id',
        ),
        'images' => array(
            'model' => 'Image',
            'foreign_key' => 'product_id',
        ),
        'categories' => array(
            'model' => 'Category',
            'foreign_key' => 'prod_id',
            'through' => 'prod_cats',
            'far_key' => 'cat_id',
        ),
         'optionvalues' => array(
            'model' => 'Froptionvalues',
            'foreign_key' => 'prod_id',
        ),
        'options' => array(
            'model' => 'Filteroptions',
            'foreign_key' => 'prod_id',
            'through' => 'froptionvalues',
            'far_key' => 'option_id',
        ),
    );
    
    protected $_belongs_to = array(
        'main_image' => array(
            'model' => 'Image',
            'foreign_key' => 'image_id',
          ),
        'brand' => array(
            'model' => 'Brand',
            'foreign_key' => 'brand_id',
          ),
        'serie' => array(
            'model' => 'Serie',
            'foreign_key' => 'series_id',
          ),
     );


    
public function rules()
    {
        return array(
            'title' => array(
                array('not_empty'),
            ),
            'price' => array(
                array('not_empty'),
                array('numeric'),
            ),
            'code' => array(
                array('not_empty'),
                array(array($this, 'uniq_alias'), array(':value', ':field')),
            ),
            'path' => array(
                array('not_empty'),
                array('alpha_dash'),
                array(array($this, 'uniq_alias'), array(':value', ':field')),
            ),
        );
    }


    public function labels()
    {
        return array(
            'title' => 'Наименование',
            'content' => 'Описание',
            'price' => 'Цена',
            'code' => 'Код товара',
            'path' => 'Адрес', 
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
            'code' => array(
                array('strip_tags'),
            ),
            'price' => array(
                array('strip_tags'),
            ),
        );
    }
    
 public function uniq_alias($value, $field)
    {
        $page = ORM::factory($this->_object_name)
                ->where($field, '=', $value)
                ->and_where($this->_primary_key, '!=', $this->pk())
                ->find();
        
        if ($page->pk())
        {
            return false;
        }
        
        
        return true;
    }  
} 
