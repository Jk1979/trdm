<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Базовый класс главной страницы
 */
class Controller_Admin_Choiceimg extends Controller_Template {

    public $template = 'v_choiceimg';

    public function  before() {
        parent::before();
		
        $this->template->styles = array();
        $this->template->scripts = array();
        // Вывод в шаблон
        $this->template->scripts[] = 'public/js/jquery-1.8.2.min.js';
        $this->template->scripts[] = 'public/js/uploadFromServer.js';
        
    }
    
    public function action_index()
    {
        $path = $this->request->param('catname');
        $filename = $this->request->param('filename');
        if(!$filename) $filename = "";
        
        $dir = $_SERVER['DOCUMENT_ROOT'].'/public/uploads/';
        $webdir = '/public/uploads/';
        
        if (!preg_match("/^[a-zA-Z0-9_]+$/", $path))
         return;
        if (!is_dir($dir.$path.'/'))
         return;
        $dir = $dir.$path.'/';
        $webdir = $webdir.$path.'/';
        if ($handle = opendir($dir))
        {
         $files = array();
         while (false !== ($file = readdir($handle)))
          if ($file != '.' && $file != '..')
           $files[] = $file;
         sort($files);
        }
        
        $this->template->set('files',$files);
        $this->template->set('filename',$filename);
        $this->template->set('webdir',$webdir);
        
    }
    public function action_uploadimage()
    {
        $catname = $this->request->param('catname');
        if($catname == 'imgarticle') {
            $imgsize = Kohana::$config->load("config.articles_imagesize");
        }
        else {
            $imgsize = Kohana::$config->load("config.cat_imagesize");
        }
            $imgwidth = $imgsize['width'];
            $imgheight = $imgsize['height'];
            // Все загруженные файлы помещаются в эту папку
            $uploaddir = 'public/uploads/' . $catname . '/';
            $uploaddir_small = 'public/uploads/'. $catname .'_small/';

            // Вытаскиваем необходимые данные
            $file = $_POST['value'];
            $name = $_POST['name'];

            // Получаем расширение файла
            $getMime = explode('.', $name);
            $mime = end($getMime);

            // Выделим данные
            $data = explode(',', $file);

            // Декодируем данные, закодированные алгоритмом MIME base64
            $encodedData = str_replace(' ','+',$data[1]);
            $decodedData = base64_decode($encodedData);

            // Вы можете использовать данное имя файла, или создать произвольное имя.
           // $randomName = substr_replace(sha1(microtime(true)), '', 12).'.'.$mime;
            $randomName = $name;
            $success = file_put_contents($uploaddir.$randomName, $decodedData);
            
            $filepath = $uploaddir.$randomName;
            if(file_exists($filepath))
            {
                $im = Image::factory($filepath);
               // if($im->width > $imgwidth)
               // {
                 $im->resize($imgwidth,$imgheight);
               // }
                $im->save($uploaddir_small . "small_$randomName");
            }
            // Создаем изображение на сервере
            if($success) {
               
                echo $randomName.":загружен успешно";
            }
            else {
               // Показать сообщение об ошибке, если что-то пойдет не так.
               echo "Что-то пошло не так. Убедитесь, что файл не поврежден!";
            }
            die;
    }
 public function action_deleteimage()
 {
   $filedir = $this->request->param('catname');
   $filename = $this->request->param('filename');
   $uploaddir_small = 'public/uploads/'. $filedir .'_small/';
   if(!$filedir || !$filename)
   {
       echo "Не задан файл или каталог";
       die;
   }
   $dir = $_SERVER['DOCUMENT_ROOT'].'/public/uploads/';
	
	if (!preg_match("/^[a-zA-Z0-9_\.\-]+$/", $filename))
	 return;
	elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $filedir))
	 return;
	elseif (!is_dir($dir.$filedir))
	 return;
	elseif (!file_exists($dir.$filedir.'/'.$filename))
	 return;
           unlink($dir.$filedir.'/'.$filename);
            if(file_exists($uploaddir_small."small_$filename")){
                    unlink($uploaddir_small."small_$filename");
            }
         echo "Файл удален";   
	 die;
   
 }

 
} //class