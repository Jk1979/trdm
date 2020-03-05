<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Comment extends ORM
{
   protected $_belongs_to = array(
        'article' => array(
            'model' => 'Article',
            'foreign_key' => 'article_id',
        ),
    );
    
    public function labels()
    {
        return array(
            'email' => 'e-mail',
            'name' => 'Автор',
            'message' => 'Текст комментария'
        );
    }
 
   public function rules()
    {
        return array(
            'name' => array(
                array('not_empty'),
                array('min_length', array(':value', 2)),
                array('max_length', array(':value', 20)),
            ),
            'message' => array(
                array('not_empty'),
                array('max_length', array(':value', 500)),
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
            'message' => array(
                array('strip_tags'),
            ),
            'name' => array(
                array('strip_tags'),
            ),
        );
    }
//    /**
//     * Get comments for article
//     * @return array
//     */
    public function get_comments($article_id)
    {
        $query=$this->where('article_id','=',$article_id)->find_all();
        
        if($query)
            return $query;
        else
            return array();                       
    }
// 
//    /**
//     * Create new comment
//     */
    public function create_comment($article_id, $user, $message)
    {
        $this->article_id = $article_id;
        $this->name = $user;
        $this->message=$message;
        $this->create();
        
    }
}