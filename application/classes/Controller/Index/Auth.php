<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Авторизация
 */
class Controller_Index_Auth extends Controller_Index {
    public function before()
    {
        parent::before();
        $this->template->left_block = null;
        $this->template->right_block = null;
       
    }

    public function action_index() {
        $this->action_login();
    }

    public function action_login() {
        
        
        if(Auth::instance()->logged_in('admin')) {
            $this->redirect('admin');
        }
        if(Auth::instance()->logged_in('login')) {
            $this->redirect('account');
        }
        

        if (isset($_POST['submit'])){
            $data = Arr::extract($_POST, array('username', 'password', 'remember'));
            $status = Auth::instance()->login($data['username'], $data['password'], (bool) $data['remember']);

            if ($status){
                if(Auth::instance()->logged_in('admin')) {
                     $this->redirect('admin');
                 }
                $this->redirect('account');
            }
            else {
                $errors = array(Kohana::message('auth/user', 'no_user'));
            }
        }

        $content = View::factory($this->theme . '/index/auth/v_auth_login')
                    ->bind('errors', $errors)
                    ->bind('data', $data);
        // Выводим в шаблон
        $this->template->page_title = 'Вход';
        $this->template->page_caption = '<center>Вход</center>';
        $this->template->center_block = array($content);
    }

    public function action_register() {

        if (isset($_POST['submit'])){
            $data = Arr::extract($_POST, array('username', 'password', 'first_name', 'password_confirm', 'email'));
           

            try {
                $users = ORM::factory('User')->create_user($_POST, array(
                    'username',
                    'first_name',
                    'password',
                    'email',
                ));
                //$err = $users->errors('validation');
                $role = ORM::factory('Role')->where('name', '=', 'login')->find();
                $users->add('roles', $role);
                $this->action_login();
                $this->redirect('account');
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('auth');
            }
        }

        $content = View::factory($this->theme . '/index/auth/v_auth_register')
                            ->bind('errors', $errors)
                           // ->bind('err', $err)
                            ->bind('data', $data);


        // Выводим в шаблон
        $this->template->page_title = 'Регистрация';
        $this->template->page_caption = '<center>Регистрация</center>';
        $this->template->center_block = array($content);
    }

    public function action_logout() {
        
        if (Auth::instance()->logout()) {
            $this->redirect();
        }

    }
    
}  