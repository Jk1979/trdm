<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Filteroptions extends Controller_Admin
{
   public function before() {
           parent::before();
           
        //$this->template->scripts[] = 'public/js/jquery-1.8.2.min.js';
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
        $filter = ORM::factory('Filters', $id);
        $options = $filter->options->order_by('option_orderid','ASC')->find_all();
        
        $content = View::factory($this->theme . '/admin/filteroptions/v_options_index')
                ->bind('id', $id)
                ->bind('options', $options)
                ->bind('errors', $errors);
        $cat = ORM::factory('Category')->where('cat_id','=',$filter->cat_id)->find();
        $this->breadcrumbs[] = array('name' => $cat->title . ' : фильтры', 'link' => '/admin/filters/index/' . $cat->cat_id);
        $this->breadcrumbs[] = array('name' => 'Опции фильтра : ' .  $filter->filter_title, 'link' => '/admin/categories/filters/index/' . $id);
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
         // Вывод в шаблон
        $this->template->page_title = 'Фильтры : опции';
        $this->template->page_caption = 'Фильтры : опции';
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
           $data = Arr::extract($_POST, array('option_title','option_path','option_color'));
           $data['filter_id'] = $id;
                $sql = DB::select(array(DB::expr('MAX(`option_orderid`)'), 'max_order'))
                 ->from('filteroptions')
                 ->where('filter_id', '=', $id);
                 $max_order = $sql->execute()->as_array();
                 if(!$max_order[0]['max_order']) $ord = 1;
                     else $ord = $max_order[0]['max_order'] + 1;
                $data['option_orderid'] = $ord;
                // work with images
                $image = explode('/',Arr::get($_POST, 'selectImage'));
                $data['option_image'] = $image[count($image)-1];
                unset ($data['selectImage']);
            
                $newoption = ORM::factory('Filteroptions') ;
                $newoption->values($data);
            try
            {
                $newoption->save();
                $this->redirect('admin/filteroptions/index/' . $id);
            }
            catch (ORM_Validation_Exception $e)
            {
                $errors = $e->errors('validation');
            }
        } 
        
        $content = View::factory($this->theme . '/admin/filteroptions/v_options_add')
                ->bind('id', $id)
                ->bind('errors', $errors)
                ->bind('data', $data);
        
        $filter = ORM::factory('Filters', $id);
        $cat = ORM::factory('Category')->where('cat_id','=',$filter->cat_id)->find();
        $this->breadcrumbs[] = array('name' => $cat->title . ' : фильтры', 'link' => '/admin/filters/index/' . $cat->cat_id);
        $this->breadcrumbs[] = array('name' => 'Опции фильтра : ' .  $filter->filter_title, 'link' => '/admin/filteroptions/index/' . $filter->filter_id);
        $this->breadcrumbs[] = array('name' => 'Добавить опцию', 'link' => '/admin/filteroptions/add/' . $filter->filter_id);
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
         // Вывод в шаблон
        $this->template->page_title = 'Фильтр : Добавть опцию';
        $this->template->page_caption = 'Фильтр : Добавить опцию';
        $this->template->center_block = array($content);
    }
    
     public function action_edit()
    {
        $id = (int) $this->request->param('id');

        if(!$id) {
            $this->redirect('admin/categories');
        }
        $option = ORM::factory('Filteroptions',$id);
        $data = $option->as_array();
        
         if (isset($_POST['save']))
        {
            $data = Arr::extract($_POST, array('option_title','option_path','option_color'));
                  // work with images
                $image = explode('/',Arr::get($_POST, 'selectImage'));
                $data['option_image'] = $image[count($image)-1];
                unset ($data['selectImage']);
            $option->values($data);
            try
            {
                $option->save();
                $this->redirect('admin/filteroptions/index/' . $option->filter_id);
            } catch (ORM_Validation_Exception $e) {
                    $errors = $e->errors('validation');
            }
             
        }
        $content = View::factory($this->theme . '/admin/filteroptions/v_options_edit')
                //->bind('id', $id)
                ->bind('errors', $errors)
                ->bind('data', $data);
        $filter = ORM::factory('Filters', $option->filter_id);
        $cat = ORM::factory('Category')->where('cat_id','=',$filter->cat_id)->find();
        $this->breadcrumbs[] = array('name' => $cat->title . ' : фильтры', 'link' => '/admin/filters/index/' . $cat->cat_id);
        $this->breadcrumbs[] = array('name' => 'Опции фильтра : ' .  $filter->filter_title, 'link' => '/admin/filteroptions/index/' . $filter->filter_id);
        $this->breadcrumbs[] = array('name' => $option->option_title, 'link' => '/admin/filteroptions/edit/' . $option->option_id);
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
         // Вывод в шаблон
        $this->template->page_title = 'Опции : Редактировать';
        $this->template->page_caption = 'Опции : Редактировать';
        $this->template->center_block = array($content);
    }
   public function action_delete()
    {
        $id = (int) $this->request->param('id');
        
         if ($id)
            {
                $option = ORM::factory('Filteroptions', $id);
                $f = $option->filter_id;
                $orderid = $option->option_orderid;
                $options = ORM::factory('Filteroptions')
                        ->where('option_orderid','>',$orderid)
                        ->and_where('filter_id','=',$f)
                        ->order_by('option_orderid','ASC')
                        ->find_all();
                foreach($options as $op)
                {
                    $op->option_orderid--;
                    $op->save();
                }

                $option ->delete();

            }

            $this->redirect('admin/filteroptions/index/' . $f);
        
    }
  
   public function action_moveup()
   {
       $id = (int) $this->request->param('id');
       if($id)
       {    
            $option = ORM::factory('Filteroptions',$id);
            $foid = $option->option_orderid;
            $option_up = ORM::factory('Filteroptions')->where('option_orderid','=',$foid-1)
                        ->find();
            if($option_up->loaded())
            {
            $option_up->option_orderid = $foid;
            $option_up->save();   
            $option->option_orderid--;
            $option->save();
            }
            $this->redirect('admin/filteroptions/index/'. $option->filter_id);
       }
   }
   public function action_movedown()
   {
        $id = (int) $this->request->param('id');
       if($id)
       {    
            $option = ORM::factory('Filteroptions',$id);
            $foid = $option->option_orderid;
            $option_up = ORM::factory('Filteroptions')->where('option_orderid','=',$foid+1)
                        ->find();
            if($option_up->loaded())
            {
            $option_up->option_orderid = $foid;
            $option_up->save();   
            $option->option_orderid++;
            $option->save();
            }
            $this->redirect('admin/filteroptions/index/'. $option->filter_id);
       }
   }
   
  
   
} // class