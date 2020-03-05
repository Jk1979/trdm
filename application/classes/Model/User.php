<?php defined('SYSPATH') or die('No direct script access.');

class Model_User extends Model_Auth_User {

    protected $_has_many = array(
		'user_tokens' => array('model' => 'User_Token'),
		'roles'       => array('model' => 'Role', 'through' => 'roles_users'),
                'orders'      => array('model' => 'order', 'foreign_key' => 'user_id'),  
	);
  public function labels()
    {
        return array(
            'username' => 'Логин',
            'email' => 'E-mail',
            'first_name' => 'ФИО',
            'password' => 'Пароль',
            'password_confirm' => 'Повторить пароль',
        );
    }

    public function rules()
	{
		return array(
			'username' => array(
				array('not_empty'),
				array('max_length', array(':value', 32)),
				array(array($this, 'unique'), array('username', ':value')),
			),
                        'first_name' => array(
				array('not_empty'),
				array('min_length', array(':value', 2)),
				array('max_length', array(':value', 32)),
			),
			'password' => array(
				array('not_empty'),
			),
			'email' => array(
				array('not_empty'),
				array('email'),
				array(array($this, 'unique'), array('email', ':value')),
			),
		);
	}
        
        public function filters()
	{
		return array(
			'password' => array(
				array(array(Auth::instance(), 'hash'))
			),
                        TRUE => array (
                            array('strip_tags')
                        )
		);
	}
} 
