<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Article extends ORM
{
    protected $_has_many = array(
        'comments' => array(
            'model' => 'Comment',
            'foreign_key' => 'article_id',
        ));
    public function rules() {
        return array (
            'date' => array (
                array('not_empty'),
                array('date'),
                ),
            'title' => array (
                array('not_empty'),
                array('min_length',array(':value',5)),
                ),
            'content_full' => array (
                array('not_empty'),
                array('min_length',array(':value',10)),
                ),
        );
    }
    
    public function labels(){
        return array(
            'title' =>'Наименование',
            'content_full' =>'Текст статьи',
            'date' =>'Дата',
        );
    }
    
    public function filters(){
        return array(
            TRUE => array (
                array('trim'),
            ),
        );
    }

}