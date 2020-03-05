<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Вход"
 */
class Controller_Widgets_Login extends Controller_Widgets {
    

    public function action_index()
    {
        $auth = Auth::instance();
        $logged_in = $auth->logged_in('login');

        
        $this->template->content = View::factory('widgets/w_login')
                ->set('logged_in',$logged_in)
                ->set('auth',$auth)
                ->set('user',$auth->get_user() );
                
    }

}