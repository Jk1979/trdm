<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Attributes extends Controller_Admin
{
   public function before() {
         parent::before();
         $this->template->scripts[] = 'public/js/jquery-1.8.2.min.js';  
         $this->template->scripts[] = 'public/js/genpath.js';
         //$this->template->scripts[] = 'public/js/uploadFromPage.js';
         $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/admin');
         $this->breadcrumbs[] = array('name' => 'Атрибуты', 'link' => '/admin/attributes');
         $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
         // Вывод в шаблон
         $this->template->submenu = Widget::load('menuproducts');
    }
    

    public function action_index()
    {
        $count = ORM::factory('Attributes')->count_all();
        $pagination = Pagination::factory(array('total_items'=>$count,'items_per_page'=>30))
                ->route_params(array(
                  'controller' => Request::current()->controller(),
                   'action' => Request::current()->action(),
                                      ));
        $attributes = ORM::factory('Attributes')
		->limit($pagination->items_per_page)
                ->offset($pagination->offset)
		->order_by('sort','ASC')
                ->find_all();
        $content = View::factory($this->theme . '/admin/attributes/v_attributes_index')
                ->bind('attributes', $attributes)
                ->bind('pagination', $pagination);

        // Вывод в шаблон
        $this->template->page_title = 'Атрибуты товара';
        $this->template->page_caption = 'Атрибуты товара';
        $this->template->center_block = array($content);
    }  
    
    public function action_add() {
      
        $categories = ORM::factory('Category');
        $cats = array();
        $categories = $categories->fulltree()->as_array();
        foreach($categories as $cat) {
           $cats[$cat->cat_id]= str_repeat('&nbsp;', 2 * $cat->lvl) . $cat->title;
        }   
        
       if(isset($_POST['add'])) {
            $data = Arr::extract($_POST, array('name','path','categories'));
            $sql = DB::select(array(DB::expr('MAX(`sort`)'), 'max_order'))->from('attributes');
                 $max_order = $sql->execute()->as_array();
                 if(!$max_order[0]['max_order']) $ord = 1;
                     else $ord = $max_order[0]['max_order'] + 1;
                 $data['sort'] = $ord;   
            $attributes = ORM::factory('Attributes');
            if(is_array($data['categories']))
            {
                $catstr = "";
                foreach ($data['categories'] as $cat)
                {
                   $catstr .= $cat . ',';
                }   
                if(strpos($catstr,',')!== false)  $catstr = substr($catstr, 0, -1);
                $data['catids'] = $catstr; 
            }
            $attributes->values($data);
            try {
                $attributes->save();
                $this->redirect('admin/attributes');
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
       }
       
        
           
       $content = View::factory($this->theme . '/admin/attributes/v_attributes_add')
            ->bind('errors',$errors)
            ->bind('cats',$cats)   
            ->bind('data',$data);
       
       $this->breadcrumbs[] = array('name' => 'Добавить атрибут', 'link' => '/admin/attributes/add');
       $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
       $this->template->page_title = 'Добаивть атрибут';
       $this->template->page_caption = 'Добавить атрибут';
       $this->template->center_block = array($content);
        
    }
    
public function action_edit()
    {
        $id = (int) $this->request->param('id');

        if(!$id) {
            $this->redirect('admin/attributes');
        }
        $categories = ORM::factory('Category');
        $cats = array();
        $categories = $categories->fulltree()->as_array();
        foreach($categories as $cat) {
           $cats[$cat->cat_id]= str_repeat('&nbsp;', 2 * $cat->lvl) . $cat->title;
        }   

        $attribute = ORM::factory('Attributes', $id);
        $data = $attribute->as_array();
        $data['categories'] = explode(',',$attribute->catids);

        // Редактирование
        if (isset($_POST['save'])) {
            $data = Arr::extract($_POST, array('name','path','categories'));
            if(is_array($data['categories']))
            {
                $catstr = "";
                foreach ($data['categories'] as $cat)
                {
                   $catstr .= $cat . ',';
                }   
                if(strpos($catstr,',')!== false)  $catstr = substr($catstr, 0, -1);
                $data['catids'] = $catstr; 
            }
            $attribute->values($data);

            try {
                $attribute->save();
                $this->redirect('admin/attributes');
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
        }

        $content = View::factory($this->theme . '/admin/attributes/v_attributes_edit')
                ->bind('id', $id)
                ->bind('errors', $errors)
                ->bind('cats',$cats) 
                ->bind('data', $data);
        
        
         $this->breadcrumbs[] = array('name' => $attribute->name, 'link' => '/admin/attributes/edit/' . $id);
         $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
	$this->template->page_title = 'Атрибуты : Редактировать';
        $this->template->page_caption = 'Атрибуты : Редактировать';
        $this->template->center_block = array($content);
    }
    
    
    public function action_delete() {
        $id = (int) $this->request->param('id');

        if(!$id) {
            $this->redirect('admin/attributes');
            
        }
        
        $values = ORM::factory('Attributesvalues')
            ->where('attr_id','=',$id)
            ->find_all();
        foreach($values as $v)
        {
            $v->delete();
        }
        $attribute = ORM::factory('Attributes', $id);
        $orderid = $attribute->sort;
        $attribute->delete();
        $attributes = ORM::factory('Attributes')
                        ->where('sort','>',$orderid)
                        ->order_by('sort','ASC')
                        ->find_all();
                foreach($attributes as $at)
                {
                    $at->sort--;
                    $at->save();
                }
        
        $this->redirect('admin/attributes');
    }	
public function action_attributesvalues()
{
    $id = (int) $this->request->param('id');

    if(!$id) {
        $this->redirect('admin/attributes');
    }
        $attribute = ORM::factory('Attributes', $id);
        $values = ORM::factory('Attributesvalues')->where('attr_id','=',$attribute->attr_id);
        $count = $values->reset(FALSE)->count_all();
        $pagination = Pagination::factory(array('total_items'=>$count,'items_per_page'=>30))
                ->route_params(array('controller' => Request::current()->controller(),
                                     'action' => Request::current()->action()));
        $values = $values->limit($pagination->items_per_page)
                    ->offset($pagination->offset)
                    ->order_by('sort','ASC')
                    ->find_all();
        
        $content = View::factory($this->theme . '/admin/attributes/v_attributesvalues_index')
                ->bind('attribute', $attribute)
                ->bind('values', $values)
                ->bind('pagination', $pagination);
         $this->breadcrumbs[] = array('name' => 'Атрибут ' . $attribute->name . ' значения', 'link' => '/admin/attributes/attributesvalues/' . $attribute->attr_id);
         $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->page_title = 'Значения атрибута - ' . $attribute->name;
        $this->template->page_caption = 'Значения атрибута - ' . $attribute->name;
        $this->template->center_block = array($content);
}
public function action_attributesvaluesadd()
{
    $id = (int) $this->request->param('id');

    if(!$id) {
        $this->redirect('admin/attributes');
    }
    $attribute = ORM::factory('Attributes',$id);
    if(isset($_POST['add'])) {
            $data = Arr::extract($_POST, array('title','path'));
            if($data['path']) $data['path'] = $attribute->path . ':' . $data['path'];
            $data['attr_id']=$id;
            $sql = DB::select(array(DB::expr('MAX(`sort`)'), 'max_order'))->from('attributesvalues')->where('attr_id', '=', $id);
                 $max_order = $sql->execute()->as_array();
                 if(!$max_order[0]['max_order']) $ord = 1;
                     else $ord = $max_order[0]['max_order'] + 1;
                 $data['sort'] = $ord;   
            $newvalue = ORM::factory('Attributesvalues');
            $newvalue->values($data);
           try {
                $newvalue->save();
                $this->redirect('admin/attributes/attributesvalues/'.$id);
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
       }
            
       $content = View::factory($this->theme . '/admin/attributes/v_attributesvalues_add')
           ->bind('errors',$errors)
            ->bind('attr_id',$id)
            ->bind('data',$data);
       
       $this->breadcrumbs[] = array('name' => 'Добавить значение атрибута - '. $attribute->name, 'link' => '');
       $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
       $this->template->page_title = 'Добавить значение атрибута - ' . $attribute->name;
       $this->template->page_caption = 'Добавить значение атрибута - '. $attribute->name;
       $this->template->center_block = array($content);
    
}
public function action_attributesvaluesedit()
{
    $id = (int) $this->request->param('id');
    $realpathar = array();
    if(!$id) {
        $this->request->redirect('admin/attributes');
    }
    $value = ORM::factory('Attributesvalues',$id);
    $data = $value->as_array();
    if($data['path']) { 
        $realpathar = explode(':',$data['path']); 
        $realpath = $realpathar[1];  
        $data['path'] = $realpath;
    }
    $attr_id = $value->attr_id;
    $attribute = ORM::factory('Attributes',$attr_id);
    if(isset($_POST['save'])) {
            $data = Arr::extract($_POST, array('title','path'));
            if($data['path']) $data['path'] = $attribute->path . ':' . $data['path'];
            $data['attr_id']=$attr_id;
            $newvalue = ORM::factory('Attributesvalues',$id);
            $newvalue->values($data);
           try {
                $newvalue->save();
                $this->redirect('admin/attributes/attributesvalues/'.$attr_id);
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
            }
       }
            
      $content = View::factory($this->theme . '/admin/attributes/v_attributesvalues_edit')
           ->bind('errors',$errors)
            ->bind('id',$id)
            ->bind('data',$data);
       
       $this->breadcrumbs[] = array('name' => 'Сохранить значение атрибута - '. $attribute->name, 'link' => '');
       $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
       $this->template->page_title = 'Сохранить значение атрибута - ' . $attribute->name;
       $this->template->page_caption = 'Сохранить значение атрибута - '. $attribute->name;
       $this->template->center_block = array($content);
}
 public function action_attributesvaluesdelete() {
        $id = (int) $this->request->param('id');

        if(!$id) {
            $this->redirect('admin/attributes');
            
        }
        $atrvalue = ORM::factory('Attributesvalues', $id);
        $attr_id = $atrvalue->attr_id;
        $orderid = $atrvalue->sort;
        $atrvalue->delete();
        $attributes = ORM::factory('Attributesvalues')
                        ->where('sort','>',$orderid)
                        ->and_where('attr_id', '=', $attr_id)
                        ->order_by('sort','ASC')
                        ->find_all();
                foreach($attributes as $at)
                {
                    $at->sort--;
                    $at->save();
                }
        $this->redirect('admin/attributes/attributesvalues/'.$attr_id);
    } 

}// class