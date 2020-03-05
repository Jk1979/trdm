<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Filters extends Controller_Admin
{
   public function before() {
           parent::before();
           
        $this->template->scripts[] = 'public/js/jquery-1.8.2.min.js';
        $this->template->scripts[] = 'public/js/genpath.js';
        $this->template->scripts[] = 'public/js/uploadFromPage.js';
      
        $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/admin');
        $this->breadcrumbs[] = array('name' => 'Категории', 'link' => '/admin/categories');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->submenu = Widget::load('menuproducts');
    }
    

    public function action_index()
    {
         $id = (int) $this->request->param('id');

        if(!$id) {
            $this->redirect('admin/categories');
        }
        $category = ORM::factory('Category', $id);
        $filters = $category->filters->order_by('filter_orderid','ASC')->find_all();
        
        $content = View::factory($this->theme . '/admin/filters/v_filters_index')
                ->bind('cat_id', $id)
                ->bind('filters', $filters)
                ->bind('errors', $errors);
        $this->breadcrumbs[] = array('name' => $category->title . ' : фильтры', 'link' => '/admin/filters/index/' . $id);
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
         // Вывод в шаблон
        $this->template->page_title = 'Категории : Фильтры';
        $this->template->page_caption = 'Категории : Фильтры';
        $this->template->center_block = array($content); 
    }

    public function action_add()
    {
        $id = (int) $this->request->param('id');
        if(!$id) {
            $this->redirect('admin/categories');
        }
        
       if (isset($_POST['add']))
        {
           $data = Arr::extract($_POST, array('filter_title'));
           $data['cat_id'] = $id;
                $sql = DB::select(array(DB::expr('MAX(`filter_orderid`)'), 'max_order'))
                 ->from('filters')
                 ->where('cat_id', '=', $id);
                 $max_order = $sql->execute()->as_array();
                 if(!$max_order[0]['max_order']) $ord = 1;
                     else $ord = $max_order[0]['max_order'] + 1;
                $data['filter_orderid'] = $ord;
                $newfilter = ORM::factory('Filters') ;
                $newfilter->values($data);
            try
            {
                $newfilter->save();
                $this->redirect('admin/filters/index/' . $id);
            }
            catch (ORM_Validation_Exception $e)
            {
                $errors = $e->errors('validation');
            }
        } 
        
        $content = View::factory($this->theme . '/admin/filters/v_filters_add')
                ->bind('id', $id)
                ->bind('errors', $errors)
                ->bind('data', $data);
        $cat = ORM::factory('Category',$id);
        $this->breadcrumbs[] = array('name' => $cat->title . ' : фильтры', 'link' => '/admin/filters/index/' . $id);
        $this->breadcrumbs[] = array('name' => 'добавить фильтр', 'link' => '/admin/filters/add/' . $id);
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
         // Вывод в шаблон
        $this->template->page_title = 'Фильтр : Добавть';
        $this->template->page_caption = 'Фильтр : Добавить';
        $this->template->center_block = array($content);
    }
    
     public function action_edit()
    {
        $id = (int) $this->request->param('id');

        if(!$id) {
            $this->redirect('admin/categories');
        }
        $filter = ORM::factory('Filters',$id);
        $data = $filter->as_array();
        
         if (isset($_POST['save']))
        {
            $data = Arr::extract($_POST, array('filter_title'));
            $filter->values($data);
            try{
                $filter->save();
                $this->redirect('admin/filters/index/' . $filter->cat_id);
            } catch (ORM_Validation_Exception $e) {
                    $errors = $e->errors('validation');
            }
        }
        $content = View::factory($this->theme . '/admin/filters/v_filters_edit')
                ->bind('id', $id)
                ->bind('errors', $errors)
                ->bind('data', $data);
        $cat = ORM::factory('Category',$filter->cat_id);
        $this->breadcrumbs[] = array('name' => $cat->title . ' : фильтры', 'link' => '/admin/filters/index/' . $cat->cat_id);
        $this->breadcrumbs[] = array('name' => $filter->filter_title, 'link' => '/admin/filters/edit/' . $id);
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
         // Вывод в шаблон
        $this->template->page_title = 'Фильтр : Редактировать';
        $this->template->page_caption = 'Фильтр : Редактировать';
        $this->template->center_block = array($content);
    }
    public function action_delete()
    {
        $fid = (int) $this->request->param('id');
        
         if ($fid)
            {
                $filter = ORM::factory('Filters', $fid);
                $fcat = $filter->cat_id;
                $forderid = $filter->filter_orderid;
                $filters = ORM::factory('Filters')
                        ->where('filter_orderid','>',$forderid)
                        ->and_where('cat_id','=',$fcat)
                        ->order_by('filter_orderid','ASC')
                        ->find_all();
                foreach($filters as $f)
                {
                    $f->filter_orderid--;
                    $f->save();
                }
                $options = $filter->options->find_all();
                
                foreach($options as $o)
                {
                    $del = DB::query(Database::DELETE, "DELETE FROM `jk_froptionvalues` WHERE `option_id`=". $o->option_id)->execute();
                }
                $filter->delete();
            }

            $this->redirect('admin/filters/index/' . $fcat);
        
    }
  
   public function action_moveup()
   {
       $id = (int) $this->request->param('id');
       if($id)
       {    
            $filter = ORM::factory('Filters',$id);
            $foid = $filter->filter_orderid;
            $filter_up = ORM::factory('Filters')->where('filter_orderid','=',$foid-1)
                        ->find();
            if($filter_up->loaded())
            {
            $filter_up->filter_orderid = $foid;
            $filter_up->save();   
            $filter->filter_orderid--;
            $filter->save();
            }
            $this->redirect('admin/filters/index/'. $filter->cat_id);
       }
   }
   public function action_movedown()
   {
        $id = (int) $this->request->param('id');
       if($id)
       {    
            $filter = ORM::factory('Filters',$id);
            $foid = $filter->filter_orderid;
            $filter_up = ORM::factory('Filters')->where('filter_orderid','=',$foid+1)
                        ->find();
            if($filter_up->loaded())
            {
            $filter_up->filter_orderid = $foid;
            $filter_up->save();   
            $filter->filter_orderid++;
            $filter->save();
            }
            $this->redirect('admin/filters/index/'. $filter->cat_id);
       }
   }
   
  
   
} // class