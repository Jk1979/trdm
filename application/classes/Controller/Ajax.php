<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller
{
    public function before()
    {
        parent::before();
//      if ( ! $this->request->is_ajax())
//      {
//         $this->redirect(URL::base());
//         die;
//      }

    }

    public function action_checkavail(){
        $avail = '0';
        $code = $this->request->param('param1') ? $this->request->param('param1') :null;
        if(!$code){
            echo 'Наличие уточнайте';
            die;
        }
        require_once('application/classes/Aggr/index.php');
        if($avail == '0') echo 'Нет в наличии';
        elseif(!$avail) echo 'Наличие уточняйте';
        else echo 'Наличие: '.$avail;
        die;
    }

    public function action_whriteback(){
        $data = !empty($_POST)? $_POST : [];
        $data['name'] = strip_tags($data['name']);
        $data['name'] = htmlspecialchars($data['name']);
        $data['email'] = strip_tags($data['email']);
        $data['email'] = htmlspecialchars($data['email']);
        $validate = Validation::factory($data);
        $validate -> rule(TRUE, 'not_empty')
            -> rule('email', 'email');
        if(!$validate -> check()) { exit('Ошибки в форме<br>' . json_encode($validate->errors())); }
        //$validate->rule('type_error', 'regex', array(':value','/[1-2]+/'));
        $cPattern = '(?:(?:(?:http[s]?):\/\/)|(?:www.))(?:[-_0-9a-z]+.)+[-_0-9a-z]{2,4}[:0-9]*[\/]*'; //шаблон регулярного выражения
        mb_regex_encoding('UTF-8');             //кодировка строки
        $vRegs = array();                       //массив с подстроками
        mb_eregi($cPattern,  $data['subject'], $vRegs);   //поиск подстрок в строке pValue
        if (count($vRegs)>0 || (stripos($data['subject'], 'html')!== false))
        {
            echo 'Запрещенные символы в форме';
            exit();

        }
        //$data['text'] = mysql_real_escape_string($data['text']);
        $data['subject'] = preg_replace("/href|url|http|www|\.ru|\.com|\.net|\.info|\.org|\.ua/i", "", $data['subject']);
        $data['subject'] = strip_tags($data['subject']);
        $email_admin = Kohana::$config->load('config.email_admin');
        $sitename = Kohana::$config->load('config.sitename');
        $message = View::factory('/boot/index/page/v_mail')
            ->bind('name', $data['name'])
            ->bind('email', $data['email'])
            ->bind('message', $data['subject']);
        $message = str_replace('\n', "\n", $message);
        $message = nl2br($message);
        $to = $email_admin;
        $subject = 'Сообщение с сайта !!!';
        $from = $data['email'];
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $code = @mail($to, $subject, $message, $headers);
      echo 'Спасибо. Мы свяжемся с вами в ближайшее время';
        exit();
    }
    public static function regex($value, $expression)
    {
        return (bool) preg_match($expression, (string) $value);
    }

    public function action_getsubbrands()
    {
        $brand_id = $this->request->param('param1');
        if(!$brand_id) die;
        $series = ORM::factory('Brand',$brand_id)->series->order_by('title','ASC')->find_all();
         $str = '';
        foreach ($series as $s)
        {
            $str .='<option value="'. $s->serie_id .'">'. $s->title .'</option>';
        }
       echo $str;
        die;
    }
    
	public function action_getfilteroptions()
	{
		
		$str = '<div>';
		$cat_id = $this->request->param('param1');
		$prod_id = $this->request->param('param2');
        if(!$cat_id) die;
        $category = ORM::factory('Category',$cat_id);
        if($category->parent_id == 53) $cat_id = 53;
        $filters = ORM::factory('Filters')->where('cat_id','=',$cat_id)->find_all();
		if(count($filters)>0)
		{
			foreach($filters as $filter)
			{       
                                
				$str .= '<p style="font-size:16px; margin:10px 3px;">' . $filter->filter_title . '</p>';
                                $str .= '<ul style="overflow-y: scroll;max-height:150px;">';
				$options = $filter->options->order_by('option_title','ASC')->find_all();
				
				if(count($options)>0)
				{
					$sel = '';
					foreach($options as $opn)
					{	
						if($prod_id) {
							$opnval = ORM::factory('Froptionvalues')
							->where('option_id','=',$opn->option_id)
							->and_where('prod_id','=',$prod_id)
							->find();
							if ($opnval->option_value == 1) {
								$sel = 'checked';
							}	
							else $sel = '';
						}
						$str .= '<li><label><input type="checkbox" name="foptions[]" value="' . $opn->option_id . '" '. $sel .'><span>' . $opn->option_title . '</span></li></label>';
					}
				}
                             $str .= '</ul>';   
			}
			$str .= '</div>';
		}
		else {
			$str .= '<div><p>Нет фильтров для данной категории.</p></div>';
		}
         echo $str;
         die; 
	}
    public function action_createnewcomment()
    {
        if($_POST)
        {
            $spam_test_field = trim($_POST['scomment']);
            if(!empty($spam_test_field)) {
                echo json_encode(array('error'=>array('Спаммер!')));
                exit();
            }
            $filter='/(\.\s?ru|ua|net|com|www\s?\.|http:|http)/i';
            if (preg_match($filter, $_POST['message'])) {
                echo json_encode(array('error'=>array('Спаммер!')));
                exit();
            }
                $data = Arr::extract($_POST, array('name','email','message'));
                $data['article_id'] = $_POST['comment_post_ID'];
                $data['parent_id'] = $_POST['comment_parent'];

                $data['user_id'] = (Auth::instance()->get_user() !== NULL) ? Auth::instance()->get_user()->id : NULL;
                $comment = ORM::factory('Comment');
                $comment->values($data);
                try {
                    $comment->save();
                    $data['id']=$comment->pk();
                    $data['email'] = (!empty($data['email']))? $data['email'] : '';
                    if( $data['email']) $data['hash'] = md5($data['email']);

                    $view_comment = View::factory('boot/index/articles/one_comment_ajax',array('data'=>$data))->render();
                    echo json_encode(array('success'=>TRUE,'comment'=>$view_comment, 'data'=>$data));
                    exit();
                }
                catch (ORM_Validation_Exception $e) {
                    $errors = $e->errors('validation');
                    echo json_encode(array('error'=>$errors));
                    exit();
                }

        }


    }
    public function action_createComment()
    {
        
        if($_POST)
        {
         $uo = ORM::factory('Comment');

           $data['user'] = $_POST['user'];
           $data['email'] = $_POST['email'];
           $data['article_id'] = $_POST['articleid'];
           $data['message'] = $_POST['message'];
        $uo->values($data);
            try{
                $uo->save();
                $pk = $uo->pk();
            }  catch  (ORM_Validation_Exception $e) 
                        {
                            $errors = $e->errors('validation');
                            $errors['error'] = 'error';
                            echo json_encode($errors);
                            die;
                        }
        
            $uo = ORM::factory('Comment',$pk);
            $data = array(
              'user' => $uo->user,  
              'email' => $uo->email,
              'message' => $uo->message
            );
            echo json_encode($data);
      }
   
      die;
    } // action 
    
    public function action_createProdcomment()
    {
        
        if($_POST)
        {
            $data = Arr::extract($_POST, array('author','date','content','prodid'));
			$data['author'] = strip_tags($data['author']);
            $data['author'] = htmlspecialchars($data['author']);
            $data['author'] = mysql_escape_string($data['author']);
			$data['content'] = strip_tags($data['content']);
            $data['content'] = htmlspecialchars($data['content']);
            $data['content'] = mysql_escape_string($data['content']);
			$data['date'] = strip_tags($data['date']);
            $data['date'] = htmlspecialchars($data['date']);
            $data['date'] = mysql_escape_string($data['date']);
            
            
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
                echo json_encode($errors);
                die;
            }
            
            $com = ORM::factory('Prodcomment')->where('comment_id','=',$pk)->find();
            $data = array(
              'author' => $com->author,
              'content' => $com->content,
              
            );
            echo json_encode($data);
      } 
      die;
    } // action
    public function action_getbrand()
    {
        $title = $this->request->param('param1');
        $brand = ORM::factory('Brand')->where('title',"LIKE", "%$title%")->find_all()->as_array();
        $data = array();$i=0;
        foreach($brand as $b)
        {    
              $data[$i]['title']= $b->title;
              $data[$i]['id']= $b->brand_id;
                $i++;
        }
            
            echo json_encode($data);
        die;
    } 
    public function action_getprod()
    {
        $title = $this->request->param('param1');
        $prods = ORM::factory('Product')
//            ->where('title',"LIKE", "%$title%")
            ->where('code',"=", $title)
            ->or_where('prod_id',"=", $title)
            ->find_all()
            ->as_array();
        $data = array();$i=0;
        foreach($prods as $p)
        {    
              $data[$i]['title']= $p->title;
              $data[$i]['id']= $p->prod_id;
                $i++;
        }
            
            echo json_encode($data);
        die;
    }
     public function action_getindexprods()
    {
         
        $title = $this->request->param('param1');
        $prods = ORM::factory('Product')
            ->where('title',"LIKE", "%$title%")
            ->or_where('code',"=", $title)
//            ->or_where('prod_id',"=", $title)
            ->limit(5)
            ->find_all();
        $data = array();$i=0;
        foreach($prods as $p)
        {    
              $data[$i]['title']= $p->title;
              $data[$i]['path']= $p->path;
//              $data[$i]['image']= $p->images->find();
                $i++;
        }
            
            echo json_encode($data);
        die;
    }
    public function action_getfilterprods()
    {
        $data = array();
        $str = $this->request->param('param2');
        $cat = $this->request->param('param1');
        
        $category = ORM::factory('Category')->where('path','=',$cat)->find();
        if(!$category->loaded()) die;
        $catids = $this->get_where_cats($category->path);
        $cat = $category->cat_id;
        $idarr = explode(";",$str);
        if(!count($idarr)) die;
        foreach ($idarr as $id)
        {
           
         //$atval = ORM::factory('Froptionvalues')->where('prod_id','=',$id)->find();
         $q = "SELECT COUNT(a.`prod_id`)  as c FROM `jk_froptionvalues` as a JOIN `jk_products` as p on a.prod_id = p.prod_id WHERE a.option_id = ". $id ." AND p.cat_id IN (" . implode(',',$catids) . ")";
        
        $qresult=DB::query(Database::SELECT,$q)->execute()->as_array();
        //print_r($qresult);
        $kol =  $qresult[0]['c']; 
        if(!$kol) {
            $data[] = $id;
        }
         
        }
                 
        echo json_encode($data);
        die;
    }
    public function action_callback()
    {
        $name="";$phone="";$formData="Заявка с сайта";
        
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cbuser'])) {$name = $_POST['cbuser'];}
    if (isset($_POST['cbphone'])) {$phone = $_POST['cbphone'];}
    if (isset($_POST['formData'])) {$formData = $_POST['formData'];}
 
    $to = "jk280679@gmail.com"; /*Укажите адрес, га который должно приходить письмо*/
    $sendfrom   = "info@trademag.com.ua"; /*Укажите адрес, с которого будет приходить письмо, можно не настоящий, нужно для формирования заголовка письма*/
    $headers  = "From: " . strip_tags($sendfrom) . "\r\n";
    $headers .= "Reply-To: ". strip_tags($sendfrom) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html;charset=utf-8 \r\n";
    $subject = "$formData";
    $message = "$formData <b>Имя отправителя:</b> $name <b>Телефон:</b> $phone";
       $send = mail ($to, $subject, $message, $headers);
       if ($send == 'true')
       {
       echo 'Спасибо за отправку вашего сообщения!';
       }
       else    {   echo 'Ошибка. Сообщение не отправлено!';}
       } else {
       http_response_code(403);
       echo "Попробуйте еще раз";
    }
    
       die;
}
    
    protected function get_where_cats($catpath)
{
    $catids = array();
    $cats_chs = array();
   
    $cat = ORM::factory('Category')->where('path', '=', $catpath)->find();
    if(!$cat->loaded()){
        return null;
    }
    if($cat->has_children()) 
    {
        $cats_chs = $cat->children();
    
        if(count($cats_chs))
        {    
            $catids[] = $cat->cat_id;
            foreach($cats_chs as $c)
            {
                $catids[] = $c->cat_id; 
            }
            $this->catids = $catids;
            return $catids;
        }
    }
    else{
        $catids[] = $cat->cat_id;
        return $catids;
    }
    $this->catids = null;
    return null;
}
}//class