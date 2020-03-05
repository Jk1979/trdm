<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller
{
    public function before() {
        parent::before();
      if ( ! $this->request->is_ajax())
      {
         $this->redirect(URL::base());
         die;
      }
        
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
        $filters = ORM::factory('Filters')->where('cat_id','=',$cat_id)->find_all();
		if(count($filters)>0)
		{
			foreach($filters as $filter)
			{       
                                
				$str .= '<p style="font-size:16px; margin:10px 3px;">' . $filter->filter_title . '</p>';
                                $str .= '<ul style="overflow-y: scroll;max-height:150px;">';
				$options = $filter->options->find_all();
				
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
//                            $data['error'] = 'Ошибка добавления комментария';
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
        
        $cat = $category->cat_id;
        $idarr = explode(";",$str);
        if(!count($idarr)) die;
        foreach ($idarr as $id)
        {
           
         $atval = ORM::factory('Attributesvalues',$id);
         if(!$atval->loaded()) continue;
         $qw="";
         $path = explode(":",$atval->path);
         if(strpos($path[1],'-') !== FALSE )
                        {
                            $diapazon = explode('-', $path[1]);
                            $qw= "(`".$path[0]."` >= '" . $diapazon[0] . "' AND `".$path[0]."` <= '" . $diapazon[1] . "')"; 
                            
                        } 
                        else {
                            $qw= "`".$path[0] ."` = '" . $path[1] . "'";
                        }
        $q = "SELECT COUNT(a.`prod_id`)  as c FROM `jk_prodattributes` as a JOIN `jk_products` as p on a.prod_id = p.prod_id WHERE " . $qw . " AND p.cat_id = " . $cat;
        
        $qresult=DB::query(Database::SELECT,$q)->execute()->as_array();
        
        $kol =  $qresult[0]['c']; 
        if(!$kol) {
            $data[] = $id;
        }
         
        }
                 
        echo json_encode($data);
        die;
    }
}//class