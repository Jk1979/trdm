<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Categories extends Controller_Admin
{
   public function before() {
           parent::before();
           
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
        $categories = ORM::factory('Category');
        //$filters = $categories->filters->find_all();
        $cat = Arr::get($_POST, 'cat');
        $errors = array();

        
        if (isset($_POST['delete']))
        {
            $category = ORM::factory('Category',$cat);
            if(!$category->loaded()) {
                $this->redirect('admin/categories');
            }
            if($category->has_children())
            {
                $errors[]="В категории есть подкатегории!!! Нельзя удалить!!!";
            }
            else {
                
                $category->delete();
                $this->redirect('admin/categories');
            }
        }

        $categories = $categories->fulltree()->as_array();
        
        $content = View::factory($this->theme . '/admin/categories/v_categories_index')
                ->bind('categories', $categories)
                ->bind('errors', $errors);
                

        
         // Вывод в шаблон
        $this->template->page_title = 'Категории';
        $this->template->page_caption = 'Категории';
        $this->template->center_block = array($content);
        
    }

    public function action_add()
    {
        $categories = ORM::factory('Category');
        $cat = Arr::get($_POST, 'cat');
        
       if (isset($_POST['add']))
        {
            $data['title'] = Arr::get($_POST, 'title');
            $data['path'] = Arr::get($_POST, 'path');
            $data['description'] = Arr::get($_POST, 'description');
            $data['meta_title'] = Arr::get($_POST, 'meta_title');
            $data['meta_keywords'] = Arr::get($_POST, 'meta_keywords');
            $data['meta_description'] = Arr::get($_POST, 'meta_description');
            
             $categories->title = $data['title'];
             $categories->path = $data['path'];
             $categories->description = $data['description'];
             $categories->meta_title = $data['meta_title'];
             $categories->meta_keywords = $data['meta_keywords'];
             $categories->meta_description = $data['meta_description'];
             $image = explode('/',Arr::get($_POST, 'selectImage'));
             $categories->image = $image[count($image)-1];

            try
            {
                if (!$cat)
                {
                    $categories->make_root();
                }
                else
                {
                     
                    $categories->insert_as_last_child($cat);
                }
                
                $categories->save();
               
                $this->redirect('admin/categories');
            }
            catch (ORM_Validation_Exception $e)
            {
                $errors = $e->errors('validation');
            }
        } 
        $categories = $categories->fulltree()->as_array();
        
        $content = View::factory($this->theme . '/admin/categories/v_categories_add')
                ->bind('categories', $categories)
                ->bind('errors', $errors)
                ->bind('data', $data);
        $this->breadcrumbs[] = array('name' => 'Добавление категории', 'link' => '/admin/categories/add');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
         // Вывод в шаблон
        $this->template->page_title = 'Категории : Добавть';
        $this->template->page_caption = 'Категории : Добавить';
        $this->template->center_block = array($content);
    }
    
     public function action_edit()
    {
        $id = (int) $this->request->param('id');

        if(!$id) {
            $this->redirect('admin/categories');
        }
        $categories = ORM::factory('Category');
        $cat = Arr::get($_POST, 'cat');
        
        $category = ORM::factory('Category', $id);
        $data = $category->as_array();
        
       if (isset($_POST['save']))
        {
            $data['title'] = Arr::get($_POST, 'title');
            $data['path'] = Arr::get($_POST, 'path');
            $data['description'] = Arr::get($_POST, 'description');
            $data['meta_title'] = Arr::get($_POST, 'meta_title');
            $data['meta_keywords'] = Arr::get($_POST, 'meta_keywords');
            $data['meta_description'] = Arr::get($_POST, 'meta_description');
             $category->title = $data['title'];
             $category->path = $data['path'];
             $category->description = $data['description'];
             $category->meta_title = $data['meta_title'];
             $category->meta_keywords = $data['meta_keywords'];
             $category->meta_description = $data['meta_description'];
             $image = explode('/',Arr::get($_POST, 'selectImage'));
             $category->image = $image[count($image)-1];
			 $children = array();
			 $childs = array();
					if($category->has_children() && $category->parent_id != $cat)
					{
						$i = 0;
						$children = $category->children();
						foreach($children as $child)
						{
							$childs[$i]['cat_id'] = $child->cat_id;
							$childs[$i]['title'] = $child->title;
							$childs[$i]['path'] = $child->path;
							$childs[$i]['description'] = $child->description;
							$childs[$i]['meta_title'] = $child->meta_title;
							$childs[$i]['meta_keywords'] = $child->meta_keywords;
							$childs[$i]['meta_description'] = $child->meta_description;
							$childs[$i]['image'] = $child->image; 
							$i++;
						}
						
					}

            try
            {
                if (!$cat && $category->parent_id != $cat)
                {
					
                    $newcat = ORM::factory('Category'); 
                    $newcat->title = $category->title;
                    $newcat->path = $category->path;
                    $newcat->description = $category->description;
                    $newcat->cat_id = $category->cat_id;
                    $newcat->meta_title = $category->meta_title;
                    $newcat->meta_keywords =  $category->meta_keywords;
                    $newcat->meta_description = $category->meta_description;
                    $newcat->image = $category->image;

                    $category->delete();
                    $newcat->make_root(); 
                    $newcat->save();
                    if(count($childs)>0)
                    {
                            foreach($childs as $chs => $ch)
                            {
                                    $newchild = ORM::factory('Category');
                                    $newchild->title = $ch['title'];
                                    $newchild->path = $ch['path'];
                                    $newchild->description = $ch['description'];
                                    $newchild->cat_id = $ch['cat_id'];
                                    $newchild->meta_title = $ch['meta_title'];
                                    $newchild->meta_keywords =  $ch['meta_keywords'];
                                    $newchild->meta_description = $ch['meta_description'];
                                    $newchild->image = $ch['image'];
                                    $newchild->insert_as_last_child($newcat);
                            }
                    }
                }
                    elseif($category->parent_id == $cat)
                    {
                            $category->save();
                    }
                else
                {
                    $ct = ORM::factory('Category', $cat);
                    if($ct->parent_id == 0)
                    {
                        $newcat = ORM::factory('Category'); 
                        $newcat->title = $category->title;
                        $newcat->path = $category->path;
                        $newcat->description = $category->description;
                        $newcat->cat_id = $category->cat_id;
                        $newcat->meta_title = $category->meta_title;
                        $newcat->meta_keywords =  $category->meta_keywords;
                        $newcat->meta_description = $category->meta_description;
                        $newcat->image = $category->image;
						$category->delete();
                        
                        $newcat->insert_as_last_child($ct); 
                        $newcat->save();
                        if(count($childs)>0)
						{
							foreach($childs as $chs => $ch)
							{
								$newchild = ORM::factory('Category');
								$newchild->title = $ch['title'];
								$newchild->path = $ch['path'];
								$newchild->description = $ch['description'];
								$newchild->cat_id = $ch['cat_id'];
								$newchild->meta_title = $ch['meta_title'];
								$newchild->meta_keywords =  $ch['meta_keywords'];
								$newchild->meta_description = $ch['meta_description'];
								$newchild->image = $ch['image'];
								$newchild->insert_as_last_child($newcat);
							}
						}
						
                    }
                    else
                    {
                        $category->move_to_last_child($cat);
                        $category->save();
                    }    
                  
                }
                
                
               
                $this->redirect('admin/categories');
            }
            catch (ORM_Validation_Exception $e)
            {
                $errors = $e->errors('validation');
            }
        } 
        $categories = $categories->fulltree()->as_array();
        
        $content = View::factory($this->theme . '/admin/categories/v_categories_edit')
                ->bind('categories', $categories)
                ->bind('errors', $errors)
                ->bind('data', $data)
                ->bind('childs', $childs);
        $this->breadcrumbs[] = array('name' => $category->title, 'link' => 'admin/categories/edit/' . $id);
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
         // Вывод в шаблон
        $this->template->page_title = 'Категории : Редактировать';
        $this->template->page_caption = 'Категории : Редактировать';
        $this->template->center_block = array($content);
    }
    public function action_delete()
    {
        $cat = (int) $this->request->param('id');
        $category = ORM::factory('Category', $cat);
        if($category->has_children())
            {
                $this->redirect('admin/categories');
            }
        $prod_cats = ORM::factory('Prodcats')->where('cat_id','=',$cat)->find_all();
        if (count($prod_cats)){
             foreach ($prod_cats as $prc)
             {
                 $prc->delete();
             }
        }
        if ($cat)
            {
                $category->delete();
            }

        $this->redirect('admin/categories');
        
    }
  
   public function action_catmoveup()
   {
       $cat = (int) $this->request->param('id');
       $category = ORM::factory('Category',$cat);
       $rgt = $category->lft-1;
       $target = ORM::factory('Category')->where('rgt','=',$rgt)->and_where('parent_id', '=', $category->parent_id)->find(); 
       if($target->loaded())
            $category->move_to_prev_sibling($target);
       $this->redirect('admin/categories');
   }
   public function action_catmovedown()
   {
       $cat = (int) $this->request->param('id');
       $category = ORM::factory('Category',$cat);
       $lft = $category->rgt+1;
       $target = ORM::factory('Category')->where('lft','=',$lft)->and_where('parent_id', '=', $category->parent_id)->find(); 
       if($target->loaded())
            $category->move_to_next_sibling($target);
       $this->redirect('admin/categories');
   }
   
   
   
} // class