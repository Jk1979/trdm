<?php defined('SYSPATH') or die('No direct script access.');
 
abstract class Controller_Common extends Controller_Template {
 
    protected $user;
    protected $auth;
    protected $session;
    protected $theme;
   
    
    public function before()
    {
        I18n::lang('ru');
        parent::before();
	    $config = Kohana::$config->load('config');
        $this->auth = Auth::instance();
        $this->user = $this->auth->get_user();
        $this->session = Session::instance();
        View::set_global('main_title', $config->get('title'));
        View::set_global('sitename', $config->get('sitename'));
        View::set_global('description', $config->get('description'));
        View::set_global('keywords', $config->get('keywords'));
		$this->theme = $config->get('theme');
        $this->template->styles = array();
        $this->template->scripts = array();
        $this->template->left_block = null;
        $this->template->right_block = null;
        $this->template->center_block = null;
        $this->template->slider = null;
        $this->template->page_title = null;
        $this->template->page_caption = null;
        $this->template->filter = null;
        
        
    }
 
   
} // End Common