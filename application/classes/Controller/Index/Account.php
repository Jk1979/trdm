<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Личный кабинет
 */
class Controller_Index_Account extends Controller_Index {

    public function before(){
        parent::before();

        if (!$this->auth->logged_in('login')) {
            $this->redirect('login');
        }
        $account_menu = Widget::load('menuaccount');
        
         // Выводим в шаблон
        $this->template->right_block = null;
        $this->template->left_block = array($account_menu);
    }

    public function action_index() {
        
        $content = View::factory($this->theme . '/index/account/v_account_index');

        // Выводим в шаблон
        $this->template->page_title = 'Личный кабинет';
        $this->template->page_caption = 'Личный кабинет';
        $this->template->center_block = array($content);
    }

    public function action_orders() {
        
        $content = View::factory($this->theme . '/index/account/v_account_orders');
        
        // Выводим в шаблон
        $this->template->page_title = 'Заказы';
        $this->template->page_caption = 'Заказы';
        $this->template->center_block = array($content);
    }

    public function action_profile() {

        if (isset($_POST['submit'])) {
                    $users = ORM::factory('User');

                    try {
                        $users->where('id', '=', $this->user->id)
                                ->find()
                                ->update_user($_POST, array(
                                    'username',
                                    'first_name',
                                    'email',
                                ));

                        $this->redirect('account/profile');
                    }
                    catch (ORM_Validation_Exception $e) {
                        $errors = $e->errors('auth');
                    }
                }


        $content = View::factory($this->theme . '/index/account/v_account_profile')
                        ->bind('user', $this->user)
                        ->bind('errors', $errors);

        // Выводим в шаблон
        $this->template->page_title = 'Профиль';
        $this->template->page_caption = 'Профиль';
        $this->template->center_block = array($content);
    }


}