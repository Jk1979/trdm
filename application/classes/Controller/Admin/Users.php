<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Users extends Controller_Admin 
{
    public function before() {
        parent::before();
        $submenu = Widget::load('menuusers');
        $this->template->submenu = $submenu;
        $this->template->page_title = 'Пользователи';
        $this->template->page_caption = 'Пользователи';
        $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/admin');
        $this->breadcrumbs[] = array('name' => 'Пользователи', 'link' => '/admin/users');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
    }

    // Обработка пользователей
    public function action_index()
    {   
        $count = ORM::factory('User')->count_all();
        $pagination = Pagination::factory(array('total_items'=>$count,'items_per_page'=>20))
                ->route_params(array(
                  'controller' => Request::current()->controller(),
                   'action' => Request::current()->action(),
                                      ));
      
       
        
        $users = ORM::factory('User')
                ->limit($pagination->items_per_page)
                ->offset($pagination->offset)
                ->find_all();     
        
  
   
        foreach($users as $user) {
            $roles[$user->id] = $user->roles->find_all();
            
        }
        $content = View::factory($this->theme . '/admin/users/v_users_index')
                ->bind('roles',$roles)
                ->bind('users',$users)
                ->bind('paginator',$pagination);

        // Вывод в шаблон
        
        $this->template->center_block = array($content);
        
    }  
	
     public function action_add()
    {
       
       $roles_all = ORM::factory('Role')->find_all();
           foreach($roles_all as $role) {
               $roles[$role->id]= $role->name;
           }
           
       if(isset($_POST['add'])) {
            $data = Arr::extract($_POST, array('username','first_name','email','password',
                'password_confirm','roles'));
            if(!is_array($data['roles']) || !in_array(1,$data['roles'])) $data['roles'][] = 1;     
            //$users = ORM::factory('User');
            //$users->values($data);
            
            

            try {
                //$users->save();
                 $users = ORM::factory('User')->create_user($data, array(
                    'username',
                    'first_name',
                    'password',
                    'email',
                ));
                $users->add('roles', $data['roles']);
                $this->redirect('admin/users');
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('auth');
                //$errors = $e->errors('validation');
            }
       }
       
        
           
       $content = View::factory($this->theme . '/admin/users/v_users_add')
            ->bind('roles', $roles)
            ->bind('errors',$errors)
            ->bind('data',$data);
       $this->breadcrumbs[] = array('name' => 'Добавить нового пользователя', 'link' => '/admin/users/add');
       $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
       $this->template->page_title = 'Добаивть нового пользователя';
       $this->template->page_caption = 'Добаивть нового пользователя';
       $this->template->center_block = array($content);
    }
    
    public function action_edit()
    {
        $id = (int) $this->request->param('id');

        if(!$id) {
            $this->redirect('admin/users');
        }
        $roles_all = ORM::factory('Role')->find_all();
           foreach($roles_all as $role) {
               $roles[$role->id]= $role->name;
           }
           
        $users = ORM::factory('User', $id);
        $data = $users->as_array();
        $data['roles'] = $users->roles->find_all()->as_array();
        
        if(isset($_POST['save'])) {
            $data = Arr::extract($_POST, array('username','first_name','email','password',
                'password_confirm','roles'));

            //$users->values($data);

            try {
                $users->update_user($data, array(
                                    'username',
                                    'first_name',
                                    'password',
                                    'email',
                                ));
                $users->remove('roles');
                $users->add('roles', $data['roles']);
                $this->redirect('admin/users');
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('auth');
            }
       }
       
       $content = View::factory($this->theme . '/admin/users/v_users_edit')
            ->bind('id',$id)   
            ->bind('roles',$roles)
            ->bind('errors',$errors)
            ->bind('data',$data);
       $this->breadcrumbs[] = array('name' => $users->username, 'link' => '/admin/users/edit/' . $id);
       $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
       $this->template->page_title = 'Редактировать пользователя';
       $this->template->page_caption = 'Редактировать пользователя';
       $this->template->center_block = array($content); 
     }
     
     public function action_delete() {
        $id = (int) $this->request->param('id');

        if(!$id) {
            $this->redirect('admin/users');
            
        }
        
        $users = ORM::factory('User', $id);
        if(!$users->loaded()){
           $this->redirect('admin/users');
        }
        $users->remove('roles');
        $users->delete();
        $this->redirect('admin/users');
    }

}