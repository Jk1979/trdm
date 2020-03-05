<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Index_Comments extends Controller {
  

    public function action_index()
    {
        if(Request::initial() === Request::current())
		
	HTTP::redirect(URL::site('articles/' . $id));
 
        $article_id =  (int) $this->request->param('id');
 
 
        if($_POST)
        {
            $_POST = Arr::map('trim', $_POST);
            
            $post = Validation::factory($_POST);
                        $post -> rule('user', 'not_empty')  
                              -> rule('user', 'min_length', array(':value', 2))
                              -> rule('user', 'max_length', array(':value', 20))
                              -> rule('email', 'email')
                              -> rule('message', 'not_empty')
                              -> rule('message', 'max_length', array(':value', 100));
					  
            if($post -> check())
            {

                    $comment = ORM::factory('Comment')
                            ->create_comment($article_id, $_POST['user'], $_POST['message']);
                    
                    $this->response->status(302);

            }
            else
                    $errors = $post -> errors('validation');
        }
		
        $content = View::factory('/comments/v_comments')
                                ->bind('comments', $comments)
                                ->bind('errors', $errors);
			
        $comments = ORM::factory('Comment')->get_comments($article_id);
        
        $this->response->body($content);  
        
        }
 
        
    

} // Comments