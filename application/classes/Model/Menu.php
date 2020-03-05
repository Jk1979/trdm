<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Menu extends Model
{
    function get_menu()
    {
        return array (
           URL::site() =>'Главная',
           URL::site('articles') => 'Статьи',
           'sub' => array (
                        URL::site('articles/1-about_framework') => 'Статья 1',
                        URL::site('articles/2-Yii') => 'Статья 2',
                        URL::site('articles/3-Symfony') => 'Статья 3',
                        ),
           URL::site('contacts.html') => 'Контакты',
           URL::site('about.html') => 'Про нас',
        );
    }

}
