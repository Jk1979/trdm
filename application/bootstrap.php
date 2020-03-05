<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH.'classes/Kohana/Core'.EXT;

if (is_file(APPPATH.'classes/Kohana'.EXT))
{
	// Application extends the core
	require APPPATH.'classes/Kohana'.EXT;
}
else
{
	// Load empty core extension
	require SYSPATH.'classes/Kohana'.EXT;
}

/**
 * Set the default time zone.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/timezones
 */
date_default_timezone_set('Europe/Kiev');

/**
 * Set the default locale.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/function.setlocale
 */
//setlocale(LC_ALL, 'ru_ru.utf-8');
setlocale(LC_ALL, 'ru_ru');

/**
 * Enable the Kohana auto-loader.
 *
 * @link http://kohanaframework.org/guide/using.autoloading
 * @link http://www.php.net/manual/function.spl-autoload-register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Optionally, you can enable a compatibility auto-loader for use with
 * older modules that have not been updated for PSR-0.
 *
 * It is recommended to not enable this unless absolutely necessary.
 */
//spl_autoload_register(array('Kohana', 'auto_load_lowercase'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @link http://www.php.net/manual/function.spl-autoload-call
 * @link http://www.php.net/manual/var.configuration#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

/**
 * Set the mb_substitute_character to "none"
 *
 * @link http://www.php.net/manual/function.mb-substitute-character.php
 */
mb_substitute_character('none');

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('ru-ru'); 
//I18n::lang('en-us');

if (isset($_SERVER['SERVER_PROTOCOL']))
{
	// Replace the default protocol.
	HTTP::$protocol = $_SERVER['SERVER_PROTOCOL'];
}

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
 //Kohana::$environment = Kohana::PRODUCTION;
if (isset($_SERVER['KOHANA_ENV']))
{
	Kohana::$environment = constant('Kohana::'.strtoupper($_SERVER['KOHANA_ENV']));
}
Kohana::$environment = Kohana::DEVELOPMENT; //Kohana::PRODUCTION;
/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - integer  cache_life  lifetime, in seconds, of items cached              60
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 * - boolean  expose      set the X-Powered-By header                        FALSE
 */
Kohana::init(array(
	'base_url'   => '/',
	'index_file' => FALSE,
	//'errors' => FALSE,
        //'profile' => FALSE
));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

/**
 * Create cookie salt
 */
Cookie::$salt = '345987456098123';
Session::$default = 'cookie';

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
	   'auth'       => MODPATH.'auth',       // Basic authentication
	   'cache'      => MODPATH.'cache',      // Caching with multiple backends
	// 'codebench'  => MODPATH.'codebench',  // Benchmarking tool
	   'database'   => MODPATH.'database',   // Database access
	   'image'      => MODPATH.'image',      // Image manipulation
	// 'minion'     => MODPATH.'minion',     // CLI Tasks
	   'orm'        => MODPATH.'orm',        // Object Relationship Mapping
	// 'unittest'   => MODPATH.'unittest',   // Unit testing
	//'userguide'  => MODPATH.'userguide',  // User guide and API documentation
        'pagination'  => MODPATH.'pagination',  // User guide and API documentation
        'orm-mptt'    => MODPATH.'orm-mptt',
        //   'email'    => MODPATH.'email',
        'mail'    => MODPATH.'mail',
        'captcha'      => MODPATH.'captcha',      // Captcha
          // 'filter'    => MODPATH.'filter',
	));

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */

if ( ! Route::cache())
{
 // Set routes here
         
			
Route::set('auth', '<action>', array('action' => 'login|logout|register'))
	->defaults(array(
                'directory'  => 'index',
		'controller' => 'auth',
	));

Route::set('cartajaxadd', 'cart/<action>(/<id>(/<count>))',array('action' => 'addElement|ElementEdit|reload|ElementDelete|getcartinfo', 'count'=>'.+'))
	->defaults(array(
            'directory'  => 'index',
            'controller' => 'cart',
	)); 

Route::set('productajaxcheckcount', 'cat/<action>(/<id>(/<count>))',array('action' => 'checkcount|checkpiece', 'count'=>'.+'))
	->defaults(array(
            'directory'  => 'index',
            'controller' => 'catalog',
	));
Route::set('static', '<action>.html', array('action' => 'about|contacts'))
	->defaults(array(
            'directory'  => 'index',
            'controller' => 'page',
	));


Route::set('delivery', 'delivery')
	->defaults(array(
                'directory'  => 'index',
                'action' => 'delivery',
		        'controller' => 'page',
	));
Route::set('page', 'page(/<page_alias>)')
	->defaults(array(
                'directory'  => 'index',
                'action' => 'index',
		'controller' => 'page',
	));
Route::set('search', 'search(/page<page>)')
            ->defaults(array(
            'directory'  => 'index',
            'action'     => 'index',
            'controller' => 'search',
            )); 
Route::set('filterseries', 'cat/<cat>-<brand>/series-<serie>/filter=<filters>(/page<page>)',
                                                    array('cat' => '.+'),
                                                    array('brand' => '.+'), 
                                                    array('serie' => '.+'),
                                                    array('filters' => '.+'))
            ->defaults(array(
            'directory'  => 'index',
            'action'     => 'serie',
            'controller' => 'catalog',
            ));
Route::set('series', 'cat/<cat>-<brand>/series-<serie>(/page<page>)',
                                                    array('cat' => '.+'),
                                                    array('brand' => '.+'), 
                                                    array('serie' => '.+'))
            ->defaults(array(
            'directory'  => 'index',
            'action'     => 'serie',
            'controller' => 'catalog',
            ));
/*Route::set('Onlybrand', 'brand/<brand>(/page<page>)',
                                                    array('brand' => '.+'))
            ->defaults(array(
            'directory'  => 'index',
            'action'     => 'onlybrand',
            'controller' => 'catalog',
            )); */
Route::set('filterBrand', 'cat/<cat>-<brand>/filter=<filters>(/page<page>)',
                                                    array('cat' => '.+'),
                                                    array('brand' => '.+'), 
                                                    array('filters' => '.+'))    
            ->defaults(array(
            'directory'  => 'index',
            'action'     => 'brand',
            'controller' => 'catalog',
            ));
Route::set('filter', 'cat/<cat>/filter=<filters>(/page<page>)',
                                                    array('cat' => '.+'),
                                                    array('filters' => '.+')) 
            ->defaults(array(
            'directory'  => 'index',
            'action'     => 'cat',
            'controller' => 'catalog',
            ));
Route::set('brand', 'cat/<cat>-<brand>(/page<page>)',
                                                    array('cat' => '.+'),
                                                    array('brand' => '.+'))
            ->defaults(array(
            'directory'  => 'index',
            'action'     => 'brand',
            'controller' => 'catalog',
            )); 	


Route::set('cat', 'cat/<cat>(/page<page>)')
            ->defaults(array(
            'directory'  => 'index',
            'action'     => 'cat',
            'controller' => 'catalog',
            )); 	
Route::set('paginat_catalog', 'catalog/<action>(/<cat>(/page<page>)(/<id>))')
            ->defaults(array(
            'directory'  => 'index',
            'action'     => 'cat',
            'controller' => 'catalog',
            )); 	

Route::set('catalog', 'catalog(/<action>(/<cat>(/<id>)))')
	->defaults(array(
                'directory'  => 'index',
                'action' => 'index',
		'controller' => 'catalog',
	));
Route::set('product', 'product/<id>')
	->defaults(array(
                'directory'  => 'index',
                'action' => 'view',
		'controller' => 'catalog',
	));
/*Route::set('search', 'search')
	->defaults(array(
                'directory'  => 'index',
		'controller' => 'search',
	));*/
	
Route::set('comments', 'comments/<id>', array('id' => '.+'))
	->defaults(array(
                'directory'  => 'index',
		'controller' => 'comments',
		'action'     => 'index',		
	));

Route::set('articles_paginat', 'articles(/page<page>)')
	->defaults(array(
                'directory'  => 'index',
		'controller' => 'articles',
		'action'     => 'index',		
	));
Route::set('news_paginat', 'news(/page<page>)')
	->defaults(array(
                'directory'  => 'index',
		'controller' => 'news',
		'action'     => 'index',		
	));
/*Route::set('articles', '<articles>/<id>-<path>', array('id' => '[0-9]+'), array('path' => '.+'))
	->defaults(array(
                'directory'  => 'index',
		'controller' => 'articles',
		'action'     => 'article',		
	));*/
    Route::set('articles', 'articles/<path>', array('path' => '.+'))
        ->defaults(array(
            'directory'  => 'index',
            'controller' => 'articles',
            'action'     => 'article',
        ));

Route::set('news_get', 'news/<id>')
	->defaults(array(
        'directory'  => 'index',
		'controller' => 'news',
		'action'     => 'get',		
	));
Route::set('widgets', 'widgets(/<controller>(/<param>))', array('param' => '.+'))
	->defaults(array(
            'directory'  => 'widgets',
            'action'     => 'index',
	));

// Роут для ajax запросов

Route::set('ajax', 'ajax/<action>(/<param1>(/<param2>))',array('param2' => '.+'))
	->defaults(array(
            'controller' => 'ajax',
	));
Route::set('ajaximport', 'ajaximport/<action>(/<param1>(/<param2>))')
	->defaults(array(
            'controller' => 'ajaximport',
	));
//Route::set('adminusers', 'admin/users(/page<page>)')
//	->defaults(array(
//                'directory'  => 'admin',
//                'controller' => 'users',
//		'action'     => 'index',
//	));

// Маршруты для админки

Route::set('setfilters', 'admin/ajaxprods/<action>(/<param1>(/<param2>))',
                                                    array('param2' => '.+'))
	->defaults(array(
            'directory'  => 'admin',
            'controller' => 'products',
            
	));
Route::set('choiceimg', 'admin/choiceimg(/<action>(/<catname>(/<filename>)))',
                                                    array('filename' => '.+'))
            ->defaults(array(
            'directory'  => 'admin',
            'controller' => 'choiceimg',
            'action'     => 'index',
            )); 
Route::set('importfile', 'admin/importfile(/<action>(/<catname>(/<filename>)))',
                                                    array('filename' => '.+'))
            ->defaults(array(
            'directory'  => 'admin',
            'controller' => 'importfile',
            'action'     => 'index',
            )); 

Route::set('serie_adm', 'admin/series/<id>(/page<page>)')
            ->defaults(array(
            'directory'  => 'admin',
            'controller' => 'series',
            'action'     => 'index',
            )); 
Route::set('pag_adminproducts', 'admin/products/index/(<id>)(/page<page>)')
            ->defaults(array(
            'directory'  => 'admin',
            'action'     => 'index',
            'controller' => 'products',
            )); 	
Route::set('paginat_admin', 'admin(/<controller>(/<action>(/page<page>)(/<id>)))')
            ->defaults(array(
            'directory'  => 'admin',
            'controller' => 'main',
            'action'     => 'index',
            )); 

Route::set('admin', 'admin(/<controller>(/<action>(/<id>)))')
            ->defaults(array(
            'directory'  => 'admin',
            'controller' => 'main',
            'action'     => 'index',
            )); 

//  Дефолтный маршрут
Route::set('default', '(<controller>(/<action>(/page<page>)(/<id>)))')
	->defaults(array(
                'directory'  => 'index',
		'controller' => 'main',
		'action'     => 'index',
	));
    
    
Route::cache(TRUE);
}