<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Форма добавления комментариев к товарам"
 */
class Controller_Widgets_Prodcomments extends Controller_Widgets {
    
   // public $template = 'widgets/w_prodcomments';

    public function action_index()
    {
	$prod = $this->request->param('param');
        
        if(isset($_POST['add'])) {
           /************************************************************************
            $data = Arr::extract($_POST, array('author','date','content'));
			$data['author'] = strip_tags($data['author']);
            $data['author'] = htmlspecialchars($data['author']);
            $data['author'] = mysql_escape_string($data['author']);
			$data['content'] = strip_tags($data['content']);
            $data['content'] = htmlspecialchars($data['content']);
            $data['content'] = mysql_escape_string($data['content']);
			$data['date'] = strip_tags($data['date']);
            $data['date'] = htmlspecialchars($data['date']);
            $data['date'] = mysql_escape_string($data['date']);
            $product = ORM::factory('Product',$prod);
            
            $data['prod_id'] = $prod;
            $newcom = $product->comments->values($data);
            
            try {
                $newcom->save();
                //$this->response->status(302);
                $this->redirect(Request::detect_uri());
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
            /************************************************************************/
            $data = Arr::extract($_POST, array('author','date','content','prodid'));
            
            $product = ORM::factory('Product',$data['prodid']);
            $data['prod_id'] = $data['prodid'];
            $newcom = $product->comments->values($data);
            
            try {
                $newcom->save();
                $pk = $newcom->pk();
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
                $errors['error'] = 'error';
               
            }
        
            $uo = ORM::factory('Prodcomment')->where('comment_id','=',$pk)->find();
            $data = array(
              'date' => $uo->date,  
              'author' => $uo->author,
              'content' => $uo->content
            );
           
        }
        
        $this->template->content = View::factory('widgets/w_prodcomments',array('prodid'=>$prod));

    }

}