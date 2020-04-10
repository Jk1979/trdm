<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Import extends Controller_Admin
{
   public $log;
//    protected $brandsnull = '(31,63,29,9,64,83,41,127,74,47,112,89,108,184)';
   protected $brandsnull = '(31,64,83,127,47,112,89,108,184,191,192,199,198)';
   protected $max22 = '(29,63,127,197,47)';
   protected $max18 = '(31,64,83,74,76,196,27)';
   public function before() {
         parent::before();
         $this->template->scripts[] = 'public/js/importjs.js';
         $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/admin');
         $this->breadcrumbs[] = array('name' => 'Импорт', 'link' => '/admin/import');
         $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        // Вывод в шаблон
        $this->template->submenu = Widget::load('menuproducts');
       if(!setlocale(LC_ALL, 'ru_RU.utf8')) setlocale(LC_ALL, 'en_US.utf8');

    }


    public function action_index()
    {
        $content = View::factory($this->theme . '/admin/import/v_import_index');


        // Вывод в шаблон
        $this->template->page_title = 'Импорт/Экспорт';
        $this->template->page_caption = 'Импорт/экспорт';
        $this->template->center_block = array($content);
    }



public function action_import() {

$csv_delimiter = ';';

if(isset($_POST['files']))
{
//$filelog = fopen('tmplog.txt',"w");
$newfileim = substr($_POST['selectFile'],1);
$file = fopen('php://memory', 'w+');
fwrite($file, iconv('WINDOWS-1251', 'UTF-8//IGNORE', str_replace('^', ';', file_get_contents($newfileim))));
rewind($file);
$data = fgetcsv($file, 0, $csv_delimiter); /// закомментировать если не будет шапки
$count_inserted = 0;
$log = array();
$datadb = array();
while($data = fgetcsv($file, 0, $csv_delimiter)){
  $prodt = ORM::factory('Product')->where('code','=',$data[0])->find();
  if($prodt->loaded()) {
       $this->log[] = 'Код ' . $data[0] . 'Уже есть на сайте !' . PHP_EOL;
       $imagearr = explode('/',$data[3]);    //// Изображение
        $image = $imagearr[count($imagearr)-1];
        if(($prodt->image_id == 0 ) && ($image != "")) {
            $this->makesmallimage($image);
            // Запись в БД
            $im_db = ORM::factory('Image');
            $im_db->product_id = $prodt->prod_id;
            $im_db->name = $image;
            $im_db->save();
            $prodt->image_id = $im_db->pk();
            $prodt->save();
            $this->log[] = 'Код ' . $prodt->prod_id . 'Добавлено изображение товара !' . PHP_EOL;
            /*$p_db = ORM::factory('Product',$product->pk());
            $p_db->image_id = $im_db->pk();
            $p_db->save();   */
        }
      continue;
  }
$datadb['code'] = $data[0];  // Код товара
$datadb['title'] = htmlspecialchars($data[1]);  // Название
$datadb['price'] = str_replace(array(',', ' '), array('.', ''), trim($data[2]));  /// Цена
$datadb['price'] = ($datadb['price'] ? $datadb['price'] : 0);/// Цена
$datadb['preview'] = str_replace('"', "''", trim($data[7]));  // Краткое описание
$datadb['content'] = str_replace('"', "''", trim($data[8]));  /// Полное описание
$datadb['status'] = 1;
$datadb['present'] = 1;
$datadb['top']  = 0;
$datadb['path']  = $this->setpath($datadb['title']);
$imagearr = explode('/',$data[3]);    //// Изображение
$image = $imagearr[count($imagearr)-1];
if($image!="") {
    $this->makesmallimage($image);
}
//$datadb['image_id'] = 0;
$catArr = array();  ///////   Работаем с категорией товара
if(strpos($data[4],'/') !== FALSE )  $catArr = explode("/",$data[4]);
if(count($catArr)>0) { $cat = trim($catArr[count($catArr)-1]);  }
else $cat = trim($data[4]);
$catdb = ORM::factory('Category')->where('title', '=', $cat)->find();
if($catdb->loaded())
{
    $categories = array();
    $categories[] = $catdb->cat_id;
    $datadb['cat_id'] = $catdb->cat_id;
}
else {
    $this->log[] = 'Код ' . $data[0] . 'Не найдена категория ' . $data[4];
}
$brand = trim($data[5]);   ///  Бренд
if(!$brand) {
    $this->log[] = 'Код ' . $data[0] . 'Нет  названия производителя ';
    $datadb['brand_id'] = 0;
}
$serie = trim($data[6]);    /// Серия
if(!$serie) {
    $this->log[] = 'Код ' . $data[0] . 'Нет названия серии ';
    $datadb['series_id'] = 0;
}
if($datadb['code'] && $datadb['title']){
    if($brand)
    {
       $branddb = ORM::factory('Brand')->where('title', '=', $brand)->find();
        if(!$branddb->loaded())
        {
            $newbrand = ORM::factory('Brand');
            $newbrand->title = $brand;
            $newbrand->path = $this->setpath($brand);
            $newbrand->save();
            $datadb['brand_id'] = $newbrand->pk();
            $this->log[] = 'Создан производитель ' . $brand;
            $datadb['content'] .= "<br/>Производитель: $brand";
        }
        else
        {
            $datadb['brand_id'] = $branddb->brand_id;
            $datadb['content'] .= "<br/>Производитель: $brand";
        }
    }
    if($serie)
    {
        $seriedb =  ORM::factory('Serie')
            ->where('title', '=', $serie)
            ->and_where('brand_id','=',$datadb['brand_id'])->find();
        if(!$seriedb->loaded())
        {
            $newserie = ORM::factory('Serie');
            $newserie->title = $serie;
            $newserie->brand_id = $datadb['brand_id'];
            $newserie->path = $this->setpath($serie);
            $newserie->save();
            $datadb['series_id'] = $newserie->pk();
            $this->log[] = 'Создана серия ' . $serie;
            $datadb['content'] .= "<br/>Коллекция: $serie";
        }
        else
        {
            $datadb['series_id'] = $seriedb->serie_id;
            $datadb['content'] .= "<br/>Коллекция: $serie";
        }
    }
}  /// if code && title

               // $this->log[] =

            ////////////  Добавляем товар  //////////////////
            /***************/
            $product = ORM::factory('Product');
            $product->values($datadb);

            try {
                $product->save();
                if(count($categories)>0)
                $product->add('categories', $categories);

               /////////////////////////////// Работа с изображениями ///////////////////////////
				if($image != "")
				{
					// Запись в БД
					$im_db = ORM::factory('Image');
					$im_db->product_id = $product->pk();
					$im_db->name = $image;
					$im_db->save();

					$p_db = ORM::factory('Product',$product->pk());
					$p_db->image_id = $im_db->pk();
					$p_db->save();
				}
                $this->log[] = 'Код ' . $data[0] . 'Добавлен успешно !' . PHP_EOL;
                $count_inserted++;
                file_put_contents('tmplog.txt', $this->log);
                    //$this->redirect(Request::detect_uri());
            } catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('validation');
                }  /************/

            }   ///////////////////////  while fgetcsv


        }  //////////  if $_POST

       $content = View::factory($this->theme . '/admin/import/v_import_import')
           ->bind('errors',$errors)
           ->bind('log',$this->log)
           ->bind('countprods',$count_inserted);
       $this->breadcrumbs[] = array('name' => 'Импорт товаров', 'link' => '/admin/import/import');
       $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
       $this->template->page_title = 'Импортировать товары';
       $this->template->page_caption = 'Импортировать товары';
       $this->template->center_block = array($content);

    }

	public function action_export() {

    }





public function action_update() {
    $log = array();
    $brands = array ();
    $ormbrands = ORM::factory("Brand")->order_by('title','ASC')->find_all();
    foreach($ormbrands as $brand)
    {
        $brands[$brand->brand_id] = $brand->title;
    }
    if(isset($_POST['updateprodattributes']))
    {
        $start = microtime(true);
        $timelimit = 280;
        $query = "SELECT agr.code, agr.size FROM `agromat` as agr WHERE agr.code NOT IN (SELECT p.code FROM `jk_products` as p inner join `jk_prodattributes` as atr on p.prod_id = atr.prod_id)";
        $result = DB::query(Database::SELECT,$query)->execute()->as_array();
        $k=0;
        foreach ($result as $r) {
            $time = microtime(true) - $start;
            if($time > $timelimit) break;

            $code = $r['code'];
            $q = "SELECT `prod_id` FROM `jk_products` WHERE `code` = " . $code;
            $res = DB::query(Database::SELECT,$q)->execute()->as_array();
            if(count($res) && isset($res[0]['prod_id'])) { $prod_id = $res[0]['prod_id']; $log[] = $prod_id;}
            else {   continue; }
            if($prod_id = '13694') continue;
            $size =  explode('x',$r['size']);
            if(count($size) != 3)  continue;
            $width = str_replace(',','.',$size[0]);
            $length = str_replace(',','.',$size[1]);
            $thick = str_replace(',','.',$size[2]);
            $log[]  = "Длина $length  Ширина $width Толщина $thick <br/>";

            if($width && $length && $thick){
                $qins =  "INSERT INTO `jk_prodattributes` (`prod_id`,`length`,`width`, `thick`) VALUES ($prod_id,$length,$width,$thick)";
                $log[] = $query;
                $insresult = DB::query(Database::INSERT,$qins)->execute();
                $rows = $insresult[1];
                $log[] = $rows;
            }
        }

    }
    if(isset($_POST['filters']))
    {
        
    }
    $content = View::factory($this->theme . '/admin/import/v_import_update')
        ->bind('log',$log)
        ->bind('brands',$brands);
    $this->breadcrumbs[] = array('name' => 'Данные', 'link' => '/admin/import/update');
    $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
    $this->template->page_title = 'Обновление данных';
    $this->template->page_caption = 'Обновление данных';
    $this->template->center_block = array($content);
}
public function action_prices()
{
    /*
       Схема для изменения цен

        30% (111,7,133,126,45,64,35,65,9,47,56,57,80,73,29)
        26%  (8,27,51,12,14,82)
    */
    /* Фабрики на которых не должны стоять цены
        UPDATE `jk_products` SET `price` = 0 where `brand_id` in (31,63,29,9,64,83,41,127,74,47,112,89,108,184)
    */

     $log = array();
     $brands = array ();
     $ormbrands = ORM::factory("Brand")->order_by('title','ASC')->find_all();
     foreach($ormbrands as $brand)
     {
         $brands[$brand->brand_id] = $brand->title;
     }


     if(isset($_POST['dataagromat']))
     {
         $codestm = array();
        $qprods = ORM::factory('Dataagromat')->find_all();
        foreach ($qprods as $p)
        {

            $codestm[]= $p->code;
        }
         $i=0;
        $h = substr($_POST['selectFile'],1);
        $file = fopen('php://memory', 'w+');
        fwrite($file, file_get_contents($h));//iconv('WINDOWS-1251', 'UTF-8//IGNORE', file_get_contents($h))
        rewind($file);

        $query =  "INSERT INTO `jk_dataagromat` (`code`, `price`, `unit`, `size`, `pieces`, `meters` ) VALUES ";
        while(($dt = fgetcsv($file,1000,";")) !== FALSE)
        {
            if(in_array($dt[0], $codestm)) continue;

            $i++;
            if(!is_numeric($dt[0]) || !is_numeric($dt[3])) continue;
            $query .= "($dt[0],$dt[3],".'"'."$dt[4]".'"'.",".'"'."$dt[5]".'"'.",$dt[6],$dt[7])";

            if($i == 6000) {
                $query .= ";";
                $i=0;
                $insresult = DB::query(Database::INSERT,$query)->execute();
                $rows = $insresult[1];
                $log[] = $rows . '<br/>';
                $query =  "INSERT INTO `jk_dataagromat` (`code`, `price`, `unit`, `size`, `pieces`, `meters` ) VALUES ";
            }
            else $query .= ",";
        }
        $lastquery = substr_replace($query,';',-1);
        $insresult = DB::query(Database::INSERT,$lastquery)->execute();
        $rows = $insresult[1];
        $log[] = $rows . '<br/>';
     }
     if(isset($_POST['uploadtable']))
     {

        $i=0;
        $h = substr($_POST['selectFile'],1);
        $file = fopen('php://memory', 'w+');
        fwrite($file, file_get_contents($h));//iconv('WINDOWS-1251', 'UTF-8//IGNORE', file_get_contents($h))
        rewind($file);

        $query =  "INSERT INTO `tmpprice` (`code`, `price`, `unit`, `size`, `pieces`, `meters` ) VALUES ";
        while(($dt = fgetcsv($file,1000,";")) !== FALSE)
        {
            if(!is_numeric($dt[3])) continue;
            $i++;
            $query .= "($dt[0],$dt[3],".'"'."$dt[4]".'"'.",".'"'."$dt[5]".'"'.",$dt[6],$dt[7])";

            if($i == 6000) {
                $query .= ";";
                $i=0;
                $insresult = DB::query(Database::INSERT,$query)->execute();
                $rows = $insresult[1];
                $log[] = $rows . '<br/>';
                $query =  "INSERT INTO `tmpprice` (`code`, `price`, `unit`, `size`, `pieces`, `meters` ) VALUES ";
            }
            else $query .= ",";
        }
        $lastquery = substr_replace($query,';',-1);

        $insresult = DB::query(Database::INSERT,$lastquery)->execute();
        $rows = $insresult[1];
        $log[] = $rows . '<br/>';


      } /// uploadtable

    if(isset($_POST['agromatbase'])){
        $i=0;
        $h = substr($_POST['selectFile'],1);
        $file = fopen('php://memory', 'w+');
        fwrite($file, file_get_contents($h));//iconv('WINDOWS-1251', 'UTF-8//IGNORE', file_get_contents($h))
        rewind($file);

        $query =  "INSERT INTO `agromat` (`code`,`brand`,`title`,`price`, `unit`, `size`, `piece`, `meters`,`some`,`persp` ) VALUES ";
        while(($dt = fgetcsv($file,1000,";")) !== FALSE)
        {
            $dt[2] = str_replace("'", " ", $dt[2]);
            if(count($dt)!= 10) continue;
            if(!is_numeric($dt[3])) continue;
            $i++;
            $query .= "($dt[0],'$dt[1]','$dt[2]',$dt[3],'$dt[4]','$dt[5]','$dt[6]','$dt[7]','$dt[8]','$dt[9]')";
            //$query .= "($dt[0],".'"'."'$dt[1]".'"'.",".'"'."$dt[2]".'"'.",$dt[6],$dt[7])";

            if($i == 6000) {
                $query .= ";";
                $i=0;
                $insresult = DB::query(Database::INSERT,$query)->execute();
                $rows = $insresult[1];
                $log[] = $rows . '<br/>';
                $query =  "INSERT INTO `agromat` (`code`,`brand`,`title`, `price`, `unit`, `size`, `piece`, `meters`,`some`,`persp` ) VALUES ";
            }
            else $query .= ",";
        }
        $lastquery = substr_replace($query,';',-1);

        $insresult = DB::query(Database::INSERT,$lastquery)->execute();
        $rows = $insresult[1];
        $log[] = $rows . '<br/>';

    }
    if(isset($_POST['change']))
    {
        $str="";
         $brandsnull = '(31,63,29,9,64,83,41,127,74,47,112,89,108,184)';
        $koef = isset($_POST['koef']) ? trim($_POST['koef']) : 0;
        if(isset($_POST['schema']) && $_POST['schema']!='')
        {
            $brandsstr = '(' . $_POST['schema'] . ')';
            $log[] = $brandsstr;
        }
        elseif(isset($_POST['brands']))
        {
            $brandsstr = '(' . implode(',' , $_POST['brands']) . ')';
        }
        if(isset($_POST['decor'])) $str = " where pr.decor > 0";

        if($koef && $brandsstr)
        {
            $q = "UPDATE `jk_products` as pr inner join `tmpprice` as tbl on pr.code = tbl.code SET pr.price = tbl.price * $koef";
            if($str) {
                $q.= $str;
                $q.= " and pr.brand_id in $brandsstr";
            }
            else{
                $q.= " where pr.brand_id in $brandsstr";
            }
             $q.= " AND pr.brand_id not in $this->brandsnull";
            $res =DB::query(Database::UPDATE,$q)->execute();

            $log[] = $q . "<br/>";
            $log[] = "Поставлена скидка $koef на " . $res . "товаров<br/>";
        }
        elseif($koef == 0 && $brandsstr){
            $q = "UPDATE `jk_products` as pr SET pr.price = 0 where pr.brand_id in $brandsstr";

            $res =DB::query(Database::UPDATE,$q)->execute();

            $log[] = $q . "<br/>";
            $log[] = "Поставлена скидка $koef на " . $res . "товаров<br/> ";
        }
        else $log[] = "Ошибка входных данных формы!!!<br/>";


    }
    if(isset($_POST['changesantehnika']))
    {
        $brands = ORM::factory('Brand')->where('type','=','santehnika')->and_where('discount','<>','1')->find_all()->as_array();
         $brandsnullar = array (31,63,29,9,64,83,41,127,74,47,112,89,108,184);
        foreach ($brands as $b)
        {
            if(in_array($b->brand_id, $this->brandsnull)) continue;
            $log[]="<hr/>";
            $koef = round(floatval((100 - ($b->discount - 8))/100),2);
            $koef = str_replace(',', '.', $koef);
            $q = "UPDATE `jk_products` as pr inner join `tmpprice` as tbl on pr.code = tbl.code SET pr.price = tbl.price * " . $koef . " where pr.brand_id = " . $b->brand_id;
            $res =DB::query(Database::UPDATE,$q)->execute();
            $log[] = "<p>".$q ."</p>";
            $disc = (1-$koef)*100;
            $log[] = "Поставлена скидка ". $disc. " % на " . $res . " товаров у бренда " . $b->title;
        }
    }
    // по схеме 12 18 22
    if(isset($_POST['defaultprices'])) {
        $q = "UPDATE `jk_products` as pr SET pr.price = 0 where pr.brand_id IN  $this->brandsnull";
        $res =DB::query(Database::UPDATE,$q)->execute();
        $log[] = "<p>".$q ."</p>";
        $log[] = "Поставлена скидка  на " . $res . " товаров ";
        $q = "UPDATE `jk_products` as pr inner join `tmpprice` as tbl on pr.code = tbl.code SET pr.price = tbl.price * 0.82 where pr.brand_id NOT IN  " . $this->brandsnull . " AND pr.brand_id IN " . $this->max18;
        $res =DB::query(Database::UPDATE,$q)->execute();
        $log[] = "<p>".$q ."</p>";
        $log[] = "Поставлена скидка  на " . $res . " товаров ";
        $q = "UPDATE `jk_products` as pr inner join `tmpprice` as tbl on pr.code = tbl.code SET pr.price = tbl.price * 0.78 where pr.brand_id NOT IN  " . $this->brandsnull . " AND pr.brand_id IN " . $this->max22;
        $res =DB::query(Database::UPDATE,$q)->execute();
        $log[] = "<p>".$q ."</p>";
        $log[] = "Поставлена скидка  на " . $res . " товаров ";
    }
    if(isset($_POST['changelastschema'])) {
        $brandsnull = '(31,63,29,9,64,83,41,127,74,47,112,89,108,184)';
        $brandsvip = '(7,68,126,69,138,52,139,132,45,30,41,154,79,84,33,78,20,59,53,42,47,55,56,117,57,148,49,74,80,73,157,127,44,29,13)';
        // 18
        $q = "UPDATE `jk_products` as pr inner join `tmpprice` as tbl on pr.code = tbl.code SET pr.price = tbl.price * 0.82 where pr.brand_id NOT IN  " . $brandsvip . " AND pr.brand_id NOT IN " . $brandsnull;
        $res =DB::query(Database::UPDATE,$q)->execute();
        $log[] = "<p>".$q ."</p>";
        $log[] = "Поставлена скидка  на " . $res . " товаров ";
        // 22
        $q = "UPDATE `jk_products` as pr inner join `tmpprice` as tbl on pr.code = tbl.code SET pr.price = tbl.price * 0.78 where pr.brand_id IN  " . $brandsvip . " AND pr.brand_id NOT IN " . $brandsnull;
        $res =DB::query(Database::UPDATE,$q)->execute();
        $log[] = "<p>".$q ."</p>";
        // 12
        $log[] = "Поставлена скидка  на " . $res . " товаров ";
        $q = "UPDATE `jk_products` as pr inner join `tmpprice` as tbl on pr.code = tbl.code SET pr.price = tbl.price * 0.88 where pr.brand_id IN  (144,24,17)";
        $res =DB::query(Database::UPDATE,$q)->execute();
        $log[] = "<p>".$q ."</p>";
        $log[] = "Поставлена скидка  на " . $res . " товаров ";
        // 5
        $q = "UPDATE `jk_products` as pr inner join `tmpprice` as tbl on pr.code = tbl.code SET pr.price = tbl.price * 0.95 where pr.brand_id IN  (136)";
        $res =DB::query(Database::UPDATE,$q)->execute();
        $log[] = "<p>".$q ."</p>";
        $log[] = "Поставлена скидка  на " . $res . " товаров ";
        // 0
        $q = "UPDATE `jk_products` as pr SET pr.price = 0 where pr.brand_id IN  $this->brandsnull";
        $res =DB::query(Database::UPDATE,$q)->execute();
        $log[] = "<p>".$q ."</p>";
        $log[] = "Поставлена скидка  на " . $res . " товаров ";
    }
    if(isset($_POST['changemyschema'])) {
        $brandsnull = '(31,63,29,9,64,83,41,127,74,47,112,89,108,184)';
        $brandsvip = '(7,68,126,69,138,52,139,132,45,30,41,154,79,84,33,78,20,59,53,42,47,55,56,117,57,148,49,74,80,73,157,127,44,29,13)';

        $q = "UPDATE `jk_products` as pr inner join `tmpprice` as tbl on pr.code = tbl.code SET pr.price = tbl.price * 0.77 where pr.brand_id NOT IN  " . $brandsvip . " AND pr.brand_id NOT IN " . $brandsnull;
        $res =DB::query(Database::UPDATE,$q)->execute();
        $log[] = "<p>".$q ."</p>";
        $log[] = "Поставлена скидка  на " . $res . " товаров ";
        $q = "UPDATE `jk_products` as pr inner join `tmpprice` as tbl on pr.code = tbl.code SET pr.price = tbl.price * 0.76 where pr.brand_id IN  " . $brandsvip . " AND pr.brand_id NOT IN " . $brandsnull;
        $res =DB::query(Database::UPDATE,$q)->execute();
        $log[] = "<p>".$q ."</p>";
        $log[] = "Поставлена скидка  на " . $res . " товаров ";
        $q = "UPDATE `jk_products` as pr inner join `tmpprice` as tbl on pr.code = tbl.code SET pr.price = tbl.price * 0.80 where pr.brand_id IN  (144,24,17)";
        $res =DB::query(Database::UPDATE,$q)->execute();
        $log[] = "<p>".$q ."</p>";
        $log[] = "Поставлена скидка  на " . $res . " товаров ";
        $q = "UPDATE `jk_products` as pr inner join `tmpprice` as tbl on pr.code = tbl.code SET pr.price = tbl.price * 0.95 where pr.brand_id IN  (136)";
        $res =DB::query(Database::UPDATE,$q)->execute();
        $log[] = "<p>".$q ."</p>";
        $log[] = "Поставлена скидка  на " . $res . " товаров ";
        $q = "UPDATE `jk_products` as pr SET pr.price = 0 where pr.brand_id IN  $this->brandsnull";
        $res =DB::query(Database::UPDATE,$q)->execute();
        $log[] = "<p>".$q ."</p>";
        $log[] = "Поставлена скидка  на " . $res . " товаров ";
    }
    if(isset($_POST['changeasfullhouse']))
    {
        $cat_type = ($_POST['plsan']== 1) ? 'plitka' : 'santehnika';
        $diffkoef = isset($_POST['diffkoef']) ? $_POST['diffkoef'] : 10;
        $brands = ORM::factory('Brand')->where('type','=',$cat_type)->and_where('discount','<>','1')->find_all()->as_array();
        foreach ($brands as $b)
        {
            $log[]="<hr/>";
            $koef = round(floatval((100 - ($b->discount - $diffkoef))/100),2);
            $koef = str_replace(',', '.', $koef);
          $q = "UPDATE `jk_products` as pr inner join `tmpprice` as tbl on pr.code = tbl.code SET pr.price = tbl.price * " . $koef . " where pr.brand_id = " . $b->brand_id;
          $res =DB::query(Database::UPDATE,$q)->execute();
          $log[] = "<p>".$q ."</p>";
           $disc = (1-$koef)*100;
          $log[] = "Поставлена скидка ". $disc. " % на " . $res . " товаров у бренда " . $b->title;
        }
        $q = "UPDATE `jk_products` as pr  SET pr.price = 0 where pr.brand_id IN $this->brandsnull";
          $res =DB::query(Database::UPDATE,$q)->execute();
          $log[] = "<p>".$q ."</p>";
          $log[] = "Поставлена цена 0 на " . $res . "товаров<br/>";
    }
    if(isset($_POST['changebypreset']))
    {
      $data = array();
      $data[0]['koef']=(float) 0.95;
      $data[0]['brands']='(136)';
      $data[1]['koef']=0.78;
      $data[1]['brands']='(50,62,170)';
      $data[2]['koef']=0.77;
      $data[2]['brands']='(32,77,129,130,135,137,144,149,150,155,159,160)';
      $data[3]['koef']=0.76;
      $data[3]['brands']='(17,24,25,26,51,143,152)';
      $data[4]['koef']=0.75;
      $data[4]['brands']='(11,27,31,36,48,76,81,82,128,134,145)';
      $data[5]['koef']=0.74;
      $data[5]['brands']='(12,16,18,34,35,38,39,46,54,146,156,158)';
      $data[6]['koef']=0.73;
      $data[6]['brands']='(14,21,22)';
      $data[7]['koef']=0.72;
      $data[7]['brands']='(8)';
      $data[8]['koef']=0.7;
      $data[8]['brands']='(53,83,117)';
      $data[9]['koef']=0.69;
      $data[9]['brands']='(64,65,71)';
      $data[10]['koef']=0.68;
      $data[10]['brands']='(70,85,127,151)';
      $data[11]['koef']=0.67;
      $data[11]['brands']='(7,9,13,20,29,30,33,41,42,44,45,47,56,57,59,63,68,69,73,78,79,80,84,126,133,138,139,142,148,154,157)';
      $data[12]['koef']=0.66;
      $data[12]['brands']='(52,55,132)';


          for($discounts=0;$discounts<count($data);$discounts++)
          {
           $data[$discounts]['koef'] =  str_replace(',','.',$data[$discounts]['koef']);

          $log[]="<hr/>";
          $q = "UPDATE `jk_products` as pr inner join `tmpprice` as tbl on pr.code = tbl.code SET pr.price = tbl.price * " . $data[$discounts]['koef'] . " where pr.brand_id in " . $data[$discounts]['brands'];
          $res =DB::query(Database::UPDATE,$q)->execute();
          $log[] = "<p>".$q ."</p>";
          $disc = (1-$data[$discounts]['koef'])*100;
          $log[] = "Поставлена скидка ". $disc. " % на " . $res . "товаров<br/>";

          }

    }

    if(isset($_POST['stock'])) {

        //This input should be from somewhere else, hard-coded in this example
        $file_name = 'public/uploads/import/Rest.xml.gz';
        $out_file_name = str_replace('.gz', '', $file_name);

if(file_exists($file_name)){

        $buffer_size = 4096; // read 4kb at a time
        $file = gzopen($file_name, 'rb');
        $out_file = fopen($out_file_name, 'wb');
        while (!gzeof($file)) {
            // Read buffer-size bytes
            // Both fwrite and gzread and binary-safe
            fwrite($out_file, gzread($file, $buffer_size));
        }
        $log[]= 'File has extracted!';
        fclose($out_file);
        gzclose($file);
}
        $xml = simplexml_load_file('public/uploads/import/Rest.xml');
        $stockfile = '<?php $stock = array (';
        $str = array();
        foreach($xml->goods as $good)
        {

             $str[] = $good['code'][0];
//            echo $good->regions->region['count'][0];
        }

        $stockfile .= implode(',',$str);
        $stockfile .= ');';

            file_put_contents('public/uploads/import/stock.php',$stockfile);

        $log[]='Файл с остатками  - public/uploads/import/stock.php';
    }
    if(isset($_POST['createcsv']))
    {
        //print_r($_POST); exit;
		 $src = "http://wsn.agromat.ua/bilgibankasi/plst.zip";
			$newfile = file_get_contents($src);
			$saveto=$_SERVER['DOCUMENT_ROOT'].'/public/uploads/import/plst.zip';
			if($newfile)
			{
					$res=file_put_contents($saveto, $newfile);
					 if(!$res) { $log[] =  "Ошибка записи !!!"; }
					$zip = new ZipArchive;
					$res = $zip->open('public/uploads/import/plst.zip');
					if ($res === TRUE) {
					  $zip->extractTo('public/uploads/import/');
					  $zip->close();
					  rename("public/uploads/import/plst.txt", "public/uploads/import/plst.csv");
					} else {
					  $log[]= 'failed extract zip';
					}
			 }

		 $f_price = "public/uploads/import/plst.csv";

        if($_POST['plsan']== 1)
        {
            $filename = "agrom_tolkoplitka";
            $listp = DB::query(Database::SELECT,"SELECT * FROM `jkw_brandsagr`  WHERE `type` = 'plitka'")->execute();
            foreach ($listp as $l)
            {
                //$title = iconv('utf-8','windows-1251',$l["title"]);
                $list[]= $l["title"];//$title;
            }
        }
        if($_POST['plsan']== 2)
        {
            $filename = "agrom_santehnika";
            $listp = DB::query(Database::SELECT,"SELECT * FROM `jkw_brandsagr`  WHERE `type` = 'santehnika'")->execute();
            foreach ($listp as $l)
            {
                //$title = iconv('utf-8','windows-1251',$l["title"]);
                $list[]= $l["title"];//$title;
            }
        }

     if ($f_price)
    $h = file_get_contents($f_price);
    else {
      $log[] = 'Не найден файл с основным прайс-листом plst.csv';
      exit();
    }
    $h2 = fopen($_SERVER['DOCUMENT_ROOT'].'/public/uploads/import/'.$filename.'.csv',"w");
    $file = fopen('php://memory', 'w+');
    fwrite($file, iconv('WINDOWS-1251', 'UTF-8//IGNORE',$h));
    rewind($file);

    $pat_exit = '/(\\/)(Сорт.[2|3])(\\/)/';
    $pat_exit1 = '/\s(xbc)\s/';
    $pat_exit2 = '/(\\/)(Замов.)(\\/)/';
    $pat_exit3 = '/(\\/)(Некомп.)(\\/)/';
    $pat_exit11 = '/\\((xbc)\\)/';
     $i=0;
    // Записываем в файл agrom_tolkoplitka записи с фабриками по плитке
    while(($dt = fgetcsv($file,1000,chr(9))) !== FALSE)
    {
       // $d = iconv('WINDOWS-1251', 'UTF-8//IGNORE',$dt[1]);
       
      if( in_array($dt[1], $list) )
      {
          if(empty($_POST['checkxbc'])) {
              if (preg_match($pat_exit,$dt[2]) || preg_match($pat_exit1,$dt[2])
                  || preg_match($pat_exit2,$dt[2]) || preg_match($pat_exit3,$dt[2])
                  || preg_match($pat_exit11,$dt[2]) )
               {
                 continue;
               }
          }

			fputcsv($h2,$dt,";");
            $i++;
      }

    }
    $log[] = $i;
    fclose($h2);
}	 // createcsv

    if(isset($_POST['getxbc'])){
        $f_price = "public/uploads/import/plst.csv";
        $h2 = fopen($_SERVER['DOCUMENT_ROOT'].'/public/uploads/import/xbc.csv',"w");
        $start = microtime(true);
        if ($f_price)
            $h = file_get_contents($f_price);
        else {
            $log[] = 'Не найден файл с основным прайс-листом plst.csv';
            exit();
        }
        $file = fopen('php://memory', 'w+');
        fwrite($file, iconv('WINDOWS-1251', 'UTF-8//IGNORE',$h));
        rewind($file);

        $pat_exit = '/(\\/)(Сорт.[2|3])(\\/)/';
        $pat_exit1 = '/\s(xbc)\s/';

        $pat_exit3 = '/(\\/)(Некомп.)(\\/)/';
        $pat_exit11 = '/\\((xbc)\\)/';
        $i=0;
        $products = DB::query(DATABASE::SELECT,"SELECT `code` FROM `jk_products` where 1")->execute()->as_array();
       $prods = array();
        foreach ($products as $p)
        {
            $prods[]=$p['code'];
        }
        while(($dt = fgetcsv($file,1000,chr(9))) !== FALSE)
        {
            $time = microtime(true) - $start;
            if($time > 180) break;
                if (preg_match($pat_exit,$dt[2]) || preg_match($pat_exit1,$dt[2]) )
                {
                    if(in_array($dt[0],$prods)) {
                        fputcsv($h2,array($dt[0]),",");
                        $i++;
                        $log[]=$dt[0];
                    }
                }


        }
        fclose($h2);
        $log[] = $i;
    }
        if(isset($_POST['importattributes'])) {

    $csv_delimiter = ';';
    $options = false;
    if(isset($_POST['options'])) $options = true;

    $h = substr($_POST['selectFile'],1);
    $file = fopen('php://memory', 'w+');
    fwrite($file, file_get_contents($h));
    rewind($file);

    $count_inserted = 0;
    $log = array();
    $datadb = array();
    while($data = fgetcsv($file, 0, $csv_delimiter)){
      $prodt = ORM::factory('Product')->where('code','=',$data[0])->find();
      if(!$prodt->loaded()) {  $log[] = 'Код ' . $data[0] . 'не найден !'; continue; }


      $change = false;
      if(!$options)
      {
          $attrdata = ORM::factory('Prodattribut')->where('prod_id','=',$prodt->prod_id)->find();
              if(!$attrdata->loaded()) continue;
      }
      foreach($data as $dt)
      {

          if(strpos($dt, ':')=== FALSE) continue;
          $strar = explode(':',$dt);
          $str = trim($strar[1],'-');
          if($options)
          {
              $filtersDb = ORM::factory('Filters')->where('filter_title','=',$strar[0])->find();
             if(!$filtersDb->loaded())  { $log[] = 'нет фильтра '. $strar[0]; continue;}
             $filterOptions = ORM::factory('Filteroptions')->where('option_title','=',$str)->and_where('filter_id','=',$filtersDb->filter_id)->find();
             if(!$filterOptions->loaded())  { $log[] = 'нет опции '. $str; continue;}
             $filtervalues = ORM::factory('Froptionvalues');
             $filtervalues->prod_id = $prodt->prod_id;
             $filtervalues->option_id = $filterOptions->option_id;
             $filtervalues->option_value = 1;
             $filtervalues->save();
             $log[] = 'Код ' . $data[0] . 'добавлена опция ' . $str;
             $count_inserted++;
          }
          else {

          if($strar[0] == 'Страна производитель') { $attrdata->country = $str; $change = true; }
          if($strar[0] == 'Тип поверхности') { $attrdata->surface = $str; $change = true; }
          if($strar[0] == 'Назначение плитки') { $attrdata->destination = $str; $change = true; }
          if($strar[0] == 'Основание') { $attrdata->material = $str; $change = true; }
          if($strar[0] == 'Тип стиля') { $attrdata->imitation = $str; $change = true; }
          if($strar[0] == 'Форма плитки') { $attrdata->form = $str; $change = true; }
          if($strar[0] == 'Цвет') { $attrdata->color = $str; $change = true; }
          if($strar[0] == 'Вес упаковки, кг') { $attrdata->weight = $str; $change = true; }
          }
      }
      if($change)
      {
          $count_inserted++;
          $attrdata->save();
          $log[] = 'Код ' . $data[0] . 'изменен !' . $prodt->prod_id . '- id' .PHP_EOL;
      }
      }//while
      $log[] = 'Всего изменено ' . $count_inserted;



    }
    if(isset($_POST['filters'])) {
        $start = microtime(true);
       $col = isset($_POST['column'])?trim($_POST['column']):"";
       $filtername = isset($_POST['filtername'])?trim($_POST['filtername']):"";
       if(!$col || !$filtername) { $log[] = 'Не задана колонка или название фильтра';}
       else {
       $attrdata = ORM::factory('Prodattribut')->where($col,'<>','')->find_all();

       foreach ($attrdata as $at)
       {
           $prod = ORM::factory('Product')->where('prod_id','=',$at->prod_id)->find();
           if(!$prod->loaded()) continue;
           $cats = array (53,54,55,61,62,56);
           if(!in_array($prod->cat_id, $cats)) continue;
           $filter = ORM::factory('Filters')->where('cat_id','=',$prod->cat_id)->and_where('filter_title','=',$filtername)->find();
           if(!$filter->loaded()){
               $filter = ORM::factory('Filters');
               $filter->cat_id = $prod->cat_id;
               $filter->filter_title = $filtername;
                     $sql = DB::select(array(DB::expr('MAX(`filter_orderid`)'), 'max_order'))
                     ->from('filters')
                     ->where('cat_id', '=', $prod->cat_id);
                     $max_order = $sql->execute()->as_array();
                     if(!$max_order[0]['max_order']) $ord = 1;
                         else $ord = $max_order[0]['max_order'] + 1;
                $filter->filter_orderid = $ord;
                 try
                {
                    $filter->save();

                }
                catch (ORM_Validation_Exception $e)
                {
                    $errors = $e->errors('validation');
                    $log[] = implode(';',$errors);
                    continue;
                }

                $fid = $filter->pk();
                $log[] = 'Создан фильтр ' . $fid . ' ' . $filtername;
           }
           else $fid = $filter->filter_id;

           $option = ORM::factory('Filteroptions')->where('option_title','=',$at->{$col})->and_where('filter_id','=',$fid)->find();
           if(!$option->loaded()){
               $option = ORM::factory('Filteroptions');
               $option->filter_id = $fid;
               $option->option_title = $at->{$col};
               $option->option_path = $this->setpath($at->{$col});
               $sql = DB::select(array(DB::expr('MAX(`option_orderid`)'), 'max_order'))
                     ->from('filteroptions')
                     ->where('filter_id', '=', $fid);
                     $max_order = $sql->execute()->as_array();
                     if(!$max_order[0]['max_order']) $ord = 1;
                         else $ord = $max_order[0]['max_order'] + 1;
               $option->option_orderid = $ord;
                try
                {
                    $option->save();

                }
                catch (ORM_Validation_Exception $e)
                {
                    $errors = $e->errors('validation');
                    $log[] = implode(';',$errors);
                    continue;
                }

               $opid = $option->pk();
               $log[] = 'Добавлена опция ' . $opid . ' ' . $at->{$col};
           }
           else $opid = $option->option_id;
           $opvalue = ORM::factory('Froptionvalues')->where('prod_id','=',$at->prod_id)->and_where('option_id','=',$opid)->find();
           if($opvalue->loaded()) continue;
           else{
               $opvalue=ORM::factory('Froptionvalues');
               $opvalue->prod_id = $at->prod_id;
               $opvalue->option_id = $opid;
               $opvalue->option_value = 1;
                try
                {
                     $opvalue->save();
                 }
                catch (ORM_Validation_Exception $e)
                {
                    $errors = $e->errors('validation');
                    $log[] = implode(';',$errors);
                    continue;
                }

               $log[] = 'Записано в таблицу товары-опции ' . $opid . ' prod_id ' . $at->prod_id;
           }
            $time = microtime(true) - $start;
            if($time > 70) { $log[] = 'Время !!!  ' . $at->prod_id; break;}
       }
       } //else
    }
    if(isset($_POST['smallImages']))
        {
            $log = $this->makeSmallImages("tmp");
        }
    if(isset($_POST['runconsole'])) {

        $log = $this->console();
    }

    if(isset($_POST['setfilteroptions_by_request'])) {
        $log = $this->setfilteroptions_by_request();
    }
    if(isset($_POST['addoptionsbyarray'])) {
        $log = $this->addoptionsbyarray();
    }
    if(isset($_POST['importoptionsbycsv'])) {
        $log = $this->importoptionsbycsv();
    }
    if(isset($_POST['addimagesfromtmp'])) {
        $log = $this->addimagesfromtmp();
    }
    if(isset($_POST['tablagromat'])) {
        $log = $this->tablagromat();
    }
    if(isset($_POST['addimagesbycatid'])) {
        $cat_id = isset($_POST['catid'])?(int) $_POST['catid']: null;
        if($cat_id) $log = $this->addimages($cat_id);
        else $log[] = "Не задано ID категории";
    }
    $content = View::factory($this->theme . '/admin/import/v_import_prices')
    ->bind('log',$log)
    ->bind('brands',$brands);
  $this->breadcrumbs[] = array('name' => 'Цены', 'link' => '/admin/import/prices');
  $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
  $this->template->page_title = 'Цены';
  $this->template->page_caption = 'Цены';
  $this->template->center_block = array($content);
}


private function tablagromat()
{
    $i=0;
    $h = substr($_POST['selectFile'],1);
    $file = fopen('php://memory', 'w+');
    fwrite($file, file_get_contents($h));//iconv('WINDOWS-1251', 'UTF-8//IGNORE', file_get_contents($h))
    rewind($file);

    $query =  "INSERT INTO `agromat` (`code`,`brand`,`title`, `price`, `unit`, `size`, `piece`, `meters`,`some`,`persp`) VALUES ";
    while(($dt = fgetcsv($file,1000,";")) !== FALSE)
    {
        //if(!is_numeric($dt[3])) continue;
       if(count($dt)!= 10) continue;
        $i++;

        $query .= "($dt[0],'$dt[1]','$dt[2]',$dt[3],'$dt[4]','$dt[5]',$dt[6],$dt[7],$dt[8],$dt[9])";

        if($i == 6000) {
            $query .= ";";
            $i=0;
            $insresult = DB::query(Database::INSERT,$query)->execute();
            $rows = $insresult[1];
            $log[] = $rows . '<br/>';
            $query =  "INSERT INTO `agromat` (`code`,`brand`,`title`, `price`, `unit`, `size`, `piece`, `meters`,`some`,`persp`) VALUES ";
        }
        else $query .= ",";
    }
    $lastquery = substr_replace($query,';',-1);

    $insresult = DB::query(Database::INSERT,$lastquery)->execute();
    $rows = $insresult[1];
    $log[] = $rows . '<br/>';

}
   private function addimages($cat_id)
{
    $q = "SELECT * FROM `jk_products` WHERE `image_id`='' AND `cat_id`= ".$cat_id;
    $res = DB::query(DATABASE::SELECT, $q)->execute()->as_array();
    $count = 0;
    foreach($res as $c)
    {
         $image = 'tm_' . $c['code'];
         $dir = '';
         $prodid= $c['prod_id'];
         $log[]= "Обработка товара " . $c['code'];
        if(file_exists('public/uploads/tmp/'.$image.'.jpg')) {$dir = 'public/uploads/tmp/'; $image .= '.jpg';}
        elseif(file_exists('public/uploads/tmp/'.$image.'.png')) {$dir = 'public/uploads/tmp/'; $image .= '.png';}
        elseif(file_exists('public/uploads/tmp/'.$image.'.jpeg')) {$dir = 'public/uploads/tmp/';$image .= '.jpeg';}
        else {$image = ''; $log[]= "Не найдена картинка для товара " . $c['code']; continue;}
        
           if($image && $dir) {
                copy($dir.$image,'public/uploads/tmpres/'.$image);
                $log[] = 'Картинка из папки '. $dir;
                $im_db = ORM::factory('Image');
                                    $im_db->product_id = $prodid;
                                    $im_db->name = $image;
                                    $im_db->save();
                                    $p_db = ORM::factory('Product',$prodid);
                                    $p_db->image_id = $im_db->pk ();
                                    $p_db->save();   
                $log[]= "Добавлена картинка $image для товара " . $c['code'];
               $count++;
               
           }
       
    }
    $log[]= "Всего добавлено $count";
    return $log;
}


private function importoptionsbycsv() {
  $filelog=array();
  $logs_dir = "public/uploads/import/prodoptionsimport" . date('dmy').".php"; 
$csv_delimiter = ';';
$h = substr($_POST['selectFile'],1);
$file = fopen('php://memory', 'w+');
fwrite($file, file_get_contents($h));
rewind($file);

$count_inserted = 0;
$log = array();       
$datadb = array();
$change = false;
$start = microtime(true);
$maincat_id = isset($_POST['catid'])?(int) $_POST['catid']: null;
$typeGoods = isset($_POST['typeGoods'])?$_POST['typeGoods']: null;
switch($typeGoods) 
{	
case 'plitka':                  define( 'TABLE', 'agromat');
                                break;
case 'santehnika':              define( 'TABLE', 'agromatsan');
                                break;
}
$filterdata = isset($_POST['filternames']) ? trim($_POST['filternames']) : array();
if(!empty($filterdata)) {
    $filtercols = explode(',',$filterdata);
}

while($data = fgetcsv($file, 0, $csv_delimiter)){
  if(!$typeGoods) {$log[]='Не задано тип товаров плитка/сантехника'; break;}
  if(!$maincat_id) {$log[]='Не задано категории id'; break;}
    $serie_title = "";
    $size ="";
    $cat_id = $maincat_id;
    foreach($data as $sr) {
       if(strpos($sr, 'Коллекция:')!== FALSE) {$serar = explode(':',$sr); $serie_title = $serar[1];}
       if(strpos($sr, 'Размеры:')!== FALSE) {$serar = explode(':',$sr); $size.= 'Размеры: '.$serar[1].'<br/>';}
       if(strpos($sr, 'Ширина:')!== FALSE) {$serar = explode(':',$sr); if($serar[1]) $size .= 'Ширина: '.$serar[1] .'<br/>';}
       if(strpos($sr, 'Глубина:')!== FALSE) {$serar = explode(':',$sr); if($serar[1])$size .= 'Глубина: '.$serar[1].'<br/>';}
       if(strpos($sr, 'Высота:')!== FALSE) {$serar = explode(':',$sr); if($serar[1]) $size .= 'Высота: '.$serar[1].'<br/>';}
       if(strpos(mb_strtolower($sr), 'керамогранит')!== FALSE) { $cat_id = 62; }
       if(strpos(mb_strtolower($sr), 'мозаика')!== FALSE) { $cat_id = 56; }
       if(strpos(mb_strtolower($sr), 'форма кубика')!== FALSE) { $cat_id = 56; }
       if(strpos(mb_strtolower($sr), 'канистра')!== FALSE) { $cat_id = 83; }

    }
  $product = ORM::factory('Product')->where('code','=',$data[0])->find();
  if($product->loaded()) {
      $prodid = $product->prod_id;
      if(!$product->image_id){
          $imnames = array();
          $imnames[] = 'tm_' . $data[0];
//          $imnames[] = 'tm_' . $data[0].'-1';
//          $imnames[] = 'tm_' . $data[0].'-2';
//          $imnames[] = 'tm_' . $data[0].'-3';
          $dir = 'public/uploads/tmp/';
          foreach ($imnames as $imname) {
              $image = '';
              if (file_exists($dir . $imname . '.jpg')) {
                  $image = $imname . '.jpg';
              } elseif (file_exists($dir . $imname . '.png')) {
                  $image = $imname . '.png';
              } elseif (file_exists($dir . $imname . '.jpeg')) {
                  $image = $imname . '.jpeg';
              }

              if ($image) {
                  if (file_exists($dir . $image)) {
                      if (!file_exists('public/uploads/imgproducts/' . $image)) copy($dir . $image, 'public/uploads/imgproducts/' . $image);
                      $this->makesmallimage($image,'tmp');
                  }
                  $log[] = 'Картинка из папки ' . $dir;
                  $im_db = ORM::factory('Image');
                  $im_db->product_id = $prodid;
                  $im_db->name = $image;
                  $im_db->save();
                  if(strpos($image,'-')=== FALSE) {
                      $p_db = ORM::factory('Product', $prodid);
                      $p_db->image_id = $im_db->pk();
                      $p_db->save();
                  }
                  $log[] = "Добавлена картинка " . $image;
              }
          }
           
      }
      else { $log[]= "товар ".$data[0]." с картинкой"; }
      if(!$product->series_id && $serie_title)
      {
            $serie = ORM::factory('Serie')->where('title','=',$serie_title)->and_where('brand_id','=',$product->brand_id)->find();
            if($serie->loaded())
            {
                $serie_id = $serie->serie_id;
                 $log[]= 'Обновляем у товара серию '. $serie_title;
            }
            else{
                 $newserie =  ORM::factory('Serie');
                 $newserie->title = $serie_title;
                 $newserie->path=$this->setpath($serie_title);
                 $newserie->brand_id=$product->brand_id;
                try {
                    $newserie->save();
                    $serie_id = $newserie->pk();
                    $log[]= 'Добавили серию '. $serie_title;
                    
                }
                catch (ORM_Validation_Exception $e) {
                    $log[] = implode(';',$e->errors('validation'));
                    $log[]= $data[0] .' Ошибка добавления серии '. $serie_title;
                    continue;
                } 
            }
            $updprod = ORM::factory('Product',$product->prod_id);
            $updprod->series_id = $serie_id;
            $updprod->save();
      }
  $log = $log + $this->addfilters($data, $cat_id, $prodid);
  } // if product loaded
  else {
      
      $query = "SELECT * FROM `".TABLE."` WHERE `code` = '" .$data[0]."'";
      $res = DB::query(Database::SELECT,$query)->execute()->as_array();
      if(!count($res)) {$log[]='Нет товара в ' . TABLE .' '. $data[0]; continue;} 
      $res = $res[0];
      
      $path = $res['code']. "-" .$this->setpath($res['title']);
      $search_brand = ORM::factory('Brand')->where('nameagrom','=',$res['brand'])->find();
      if($search_brand->loaded()) $brand_id =  $search_brand->brand_id;        
      else {$log[]='Не найден бренд' . $res['brand']; continue;}
      if($serie_title){
      $serie = ORM::factory('Serie')->where('title','=',$serie_title)->and_where('brand_id','=',$brand_id)->find();
            if($serie->loaded())
            {
                $serie_id = $serie->serie_id;
            }
            else{
                 $newserie =  ORM::factory('Serie');
                 $newserie->title = $serie_title;
                 $newserie->path=$this->setpath($serie_title);
                 $newserie->brand_id=$brand_id;
                try {
                    $newserie->save();
                    $serie_id = $newserie->pk();
                    $log[]= 'Добавили серию '. $serie_title;
                }
                catch (ORM_Validation_Exception $e) {
                    $log[] = implode(';',$e->errors('validation'));
                    $log[]= $data[0] .' Ошибка добавления серии '. $serie_title;
                    continue;
                }

            }
        }  else  $serie_id = '';  
            $description = '<p>';
            if($res['unit']) $description .= 'Единица измерения: ' . $res['unit'] . '<br />';
            //if($size) $description .= 'Размер, мм: ' . $size . '<br />';
            if($size) $description .= '<br/>' . $size . '<br />';
            if($res['piece']) $description .= 'количество в упаковке, шт: ' . $res['piece'] . '<br />';
            //if($res['meters']) $description .= 'м2 в упаковке: ' . $res['meters'] . '<br />';
            //$description .= 'вес, кг: ' . $res['some'] . '<br />';
            $description .= 'Бренд: ' . $res['brand'] . '<br /></p>';
            $decor = 0;
            if(strpos($res['title'],'фриз')) $decor = 2;
            if(strpos($res['title'],'декор')) $decor = 1;
            //$price = round(floatval($res['price'] * 0.75),2);
            $pdata = array();
            $pdata['title'] = $res['title'];
            $pdata['path'] = $path;   
            $pdata['code'] = $res['code'];
            $pdata['present'] = 1;
            $pdata['status'] = 1;
            $pdata['top'] = 0;
            $pdata['decor'] = $decor;
            $pdata['price'] = 0;
//            if(in_array($brand_id,array(31,63,29,9,64,83,41,127,74,47,112,89,108,184))) $pdata['price'] = 0;
//            else     $pdata['price'] = 0;
            $pdata['brand_id'] = $brand_id;
            $pdata['series_id'] = $serie_id;
            $pdata['content'] = $description;
            $pdata['cat_id'] = $cat_id;
            $pdata['image_id'] = 0;
            $newprod=ORM::factory('Product');
            $newprod->values($pdata);

        try {
            $newprod->save();
            $prodid = $newprod->pk();
            $log[]= "Добавлен товар - " . $res['code']; 
            $count_inserted++;
            $insertcat  = DB::query(Database::INSERT,"INSERT INTO `jk_prod_cats`(`prod_id`, `cat_id`) VALUES (" . $prodid . "," . $cat_id . ")")->execute();
            $imnames = array();
            $imnames[] = 'tm_' . $data[0];
//            $imnames[] = 'tm_' . $data[0].'-1';
//            $imnames[] = 'tm_' . $data[0].'-2';
//            $imnames[] = 'tm_' . $data[0].'-3';
            $dir = 'public/uploads/tmp/';
            foreach ($imnames as $imname) {
                $image = '';
                if (file_exists($dir . $imname . '.jpg')) {
                    $image = $imname . '.jpg';
                } elseif (file_exists($dir . $imname . '.png')) {
                    $image = $imname . '.png';
                } elseif (file_exists($dir . $imname . '.jpeg')) {
                    $image = $imname . '.jpeg';
                }

                if ($image) {
                    if (file_exists($dir . $image)) {
                        copy($dir . $image, 'public/uploads/imgproducts/' . $image);
                        $this->makesmallimage($image,'tmp');
                    }
                    $log[] = 'Картинка из папки ' . $dir;
                    $im_db = ORM::factory('Image');
                    $im_db->product_id = $prodid;
                    $im_db->name = $image;
                    $im_db->save();
                    if(strpos($image,'-')=== FALSE) {
$savemainimg  = DB::query(Database::UPDATE,"UPDATE `jk_products` SET `image_id`= ".$im_db->pk()." WHERE `prod_id`=$prodid")->execute();
                    }
                    if($savemainimg) $log[] = "Добавлена картинка " . $image;
                    else $log[] = "Ошибка при установке главного изображения " . $image;
                }
            }
        }
        catch (ORM_Validation_Exception $e) {
            $log[] = implode(';',$e->errors('validation'));
            $log[]= "Невозможно добавить товар " .$res['code'];
            continue;
        }
    $log = $log + $this->addfilters($data, $cat_id, $prodid);
  }

     $time = microtime(true) - $start;
        if($time > 250) { $log[] = 'Время !!!  ' . $data[0]; break;}
        
  }//while 
  $log[] = 'Всего изменено ' . $count_inserted;
   file_put_contents($logs_dir, join(PHP_EOL, $log), FILE_APPEND);
  return $log;

}
/****************************************************************************************/
private function addfilters($data, $cat_id, $prodid, $filtercols= array())
{
    if(in_array($cat_id,array(54,55,61,62))) $cat_id = 53;
    $log = array();
    /* для категории мебель для ванной /
    $filtercols = array ('страна','категория мебели','комплектация','цвет','способ установки','поверхность',
        'фурнитура с доводчиком','количество дверец','дверца (тип открывания)','количество ящиков','материал','стиль','расположение петель');
    **/
    if(empty($filtercols)) {
             //$log[] = 'Не заданы фильтры';
        $sqldata =  DB::query(Database::SELECT,"SELECT LCASE( `filter_title` ) as title FROM `jk_filters`  WHERE  `cat_id` = " . $cat_id)->execute();
        foreach($sqldata as $r){
            $filtercols[] = $r['title'];
        }
    }

    
    foreach($data as $dt)
   {
      
      if(strpos($dt, ':')=== FALSE) continue;
      $strar = explode(':',$dt);
      $str = $strar[1];
      if(!$str) continue;
      $col = mb_strtolower($strar[0]);

      if(!in_array($col, $filtercols)) {
          $log[]='Нет фильтра '.$strar[0].' в перечисленных названиях :'. implode(',',$filtercols);
          continue;
      }
      $adddata = array();
      $filter = ORM::factory('Filters')->where('filter_title','=',$col)->and_where('cat_id', '=', $cat_id)->find();
      if($filter->loaded()) $filter_id = $filter->filter_id;
      else {
            $adddata['cat_id'] = $cat_id;
            $sql = DB::select(array(DB::expr('MAX(`filter_orderid`)'), 'max_order'))
             ->from('filters')
             ->where('cat_id', '=', $cat_id);
             $max_order = $sql->execute()->as_array();
             if(!$max_order[0]['max_order']) $ord = 1;
                 else $ord = $max_order[0]['max_order'] + 1;
            $adddata['filter_orderid'] = $ord;
            $adddata['filter_title'] = $strar[0];
            $newfilter = ORM::factory('Filters') ;
            $newfilter->values($adddata);
            try
            {
                $newfilter->save();
                $filter_id = $newfilter->pk();
            }
            catch (ORM_Validation_Exception $e)
            {
                $log[] = implode(',',$e->errors('validation'));
                continue;
            }
            if(!$filter_id) {$log[]='Не получилось добавить фильтр '. $col; continue;}
       }
      
        $names = explode(',',$str);
        foreach ($names as $optitle)
        {
            $optitle = trim($optitle);
            $adddata=array();
            $option = ORM::factory('Filteroptions')->where('option_title','=',$optitle)->and_where('filter_id','=',$filter_id)->find();
            if($option->loaded()) { $option_id = $option->option_id; }
            else {
                $log[] = 'Не найдена опция ' . $optitle . 'у фильтра ' . $filter_id;
                $adddata['filter_id'] = $filter_id;
                $sql = DB::select(array(DB::expr('MAX(`option_orderid`)'), 'max_order'))
                 ->from('filteroptions')
                 ->where('filter_id', '=', $filter_id);
                 $max_order = $sql->execute()->as_array();
                 if(!$max_order[0]['max_order']) $ord = 1;
                     else $ord = $max_order[0]['max_order'] + 1;
                $adddata['option_orderid'] = $ord;
                $adddata['option_title'] = $optitle;
                $adddata['option_path'] = $this->setpath($optitle);
                $adddata['notshowinprod'] = 0;
            
                $newoption = ORM::factory('Filteroptions') ;
                $newoption->values($adddata);
                try
                {
                    $newoption->save();
                    $option_id = $newoption->pk();
                }
                catch (ORM_Validation_Exception $e)
                {
                    $log[] = implode(',',$e->errors('validation'));
                    continue;
                }
                if(!$option_id) {$log[]='Не получилось добавить опцию '. $optitle; continue;}
                else  {$log[]='Добавлена опция '. $optitle; continue;}
            }
            
            $searchfr = ORM::factory('Froptionvalues')->where('prod_id','=',$prodid)->and_where('option_id','=',$option_id)->find();
            if($searchfr->loaded()) { $log[]='Товару уже назначена опция '. $option_id; continue;}
            $newfr = ORM::factory('Froptionvalues');
            $newfr->prod_id = $prodid;
            $newfr->option_id = $option_id;
            $newfr->option_value = 1;
            try {
             $newfr->save();
             }
             catch (ORM_Validation_Exception $e) {
                  $log[] = implode(',',$e->errors('validation'));
                  continue;
             }
            $log[]= 'option ' . $option_id . ' product ' . $prodid . ' - '.$data[0];
            
        }   // foreach names for options
                
      
   } // foreach data
   return $log;
}
    private function setpath($str){
       $find = array('І','А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',' ','.',',','0','1','2','3','4','5','6','7','8','9','!','@','#','$','%','^','&','*','(',')','-','+','=',':',';','\'','"','<','>','?','і','ї','є','~','№','`','|','/','\\','{','}','[',']','™','.');
       $replace = array('i','a','b','v','g','d','e','yo','zh','z','i','i','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','','i','','e','yu','ya','a','b','v','g','d','e','yo','zh','z','i','i','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','','i','','e','yu','ya','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','_','_','_','0','1','2','3','4','5','6','7','8','9','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','i','ji','je','_','_','_','_','_','_','_','_','_','_','_','_');
       
       return str_replace($find, $replace, $str);
    }
    private function rus2translit($string) {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '',  'ы' => 'y',   'ъ' => '',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '',  'Ы' => 'Y',   'Ъ' => '',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }
    private function str2url($str) {
        // переводим в транслит
        $str = $this->rus2translit($str);
        // в нижний регистр
        $str = strtolower($str);
        // заменям все ненужное нам на "-"
        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
        // удаляем начальные и конечные '_'
        $str = trim($str, "_");
        return $str;
    }
    private function makesmallimage($img,$catname='imgproducts')
    {
            $imgsize = Kohana::$config->load("config.cat_imagesize");
            $imgwidth = $imgsize['width'];
            $imgheight = $imgsize['height'];
            //$catname = 'imgproducts';
            // Все загруженные файлы помещаются в эту папку
            $uploaddir = 'public/uploads/' . $catname . '/';
            $uploaddir_small = 'public/uploads/'. $catname .'_small/';

            // Вытаскиваем необходимые данные
            
            $name = $img;
            $filepath = $uploaddir.$name;
            if(file_exists($filepath))
            {
                if(!file_exists($uploaddir_small . "small_$name"))
                {
                    $im = Image::factory($filepath);
                    $im->resize($imgwidth,$imgheight);
                    $im->save($uploaddir_small . "small_$name");
                }
                return true;
            }
            else return false;
    }
    /*
 * Читает каталог с файлами картинок в виде tm_100001.jpg
 * и возвращает массив list с кодами агромата в примере array(100001 , ...)
 * 
 */
private function readDir($dir)
{
    // $dir = "public/uploads/tmp";
    $codes = array();
		
		If ($handle = opendir($dir))
		{
			While (False !== ($file = readdir($handle)))
			{
				If ($file == '.' || $file == '..')
				{
						Continue;
				}
                                
				$codes[substr(strstr($file, '.', true),3)] = $file;
			}
		}	
		closedir($handle);
    return $codes;            
}
    
    function startup()
{
	// Настройки подключения к БД.
	$hostname = 'localhost'; 
	$username = 'root'; 
	$password = 'jkalex1979';
	$dbName = 'shop';
	
	// Подключение к БД.
	mysql_connect($hostname, $username, $password) or die('No connect with data base'); 
	mysql_query('SET NAMES utf8');
	mysql_select_db($dbName) or die('No data base');
}

private function getcodes()
{
return array(

    
);
}
private function makeSmallImages($catname)
{
    $log = array();
    $dirwhere = "public/uploads/" . $catname;
		If ($handle = opendir($dirwhere))
		{
			While (False !== ($file = readdir($handle)))
			{
				If ($file == '.' || $file == '..')
				{
						Continue;
				}
                            if($this->makesmallimage($file,$catname)) $log[] = "Создан маленький $file <br/>";    
			    
			}
		}	
		closedir($handle);
                return $log;
}
private function console()
{
    $log = array();
    $sqldata =  DB::query(Database::SELECT,"SELECT LCASE( `filter_title` ) as title FROM `jk_filters`  WHERE  `cat_id` = 53")->execute();
    foreach($sqldata as $r){
        $log[] = $r['title'];
    }
   $data = implode(',',$log);
    return $data;
    
}
/*Функции которые пока не используются*/
    private function addimagesfromtmp()
    {   $count = 0;
        $log = array();
        $setmain = false;
        if(isset($_POST['setmainimg'])) $setmain = true;
        $dir = "public/uploads/tmp";
        $codes = $this->readDir($dir);
        foreach ($codes as $code => $image)
        {
            $code = preg_replace('/-(.+)/', '', $code);
            $prod = ORM::factory('Product')->where('code','=',$code)->find();
            if(!$prod->loaded()) { $log[]='Не найден ' . $code; continue; }
            // Запись в БД
            $im_db = ORM::factory('Image');
            $im_db->product_id = $prod->prod_id;
            $im_db->name = $image;
            $im_db->save();
            $image_id = $im_db->pk();
            if($setmain)
            {
                $updprod = ORM::factory('Product',$prod->prod_id);
                $updprod->image_id = $image_id;
                $updprod->save();
            }
            $log[] = $code . ' - ' . $image;
            $count++;
        }
        $log[] = 'Всего вставлено ' . $count;
        return $log;
    }
    private function addoptionsbyarray()
    {
        $start = microtime(true);
        $log = array();
        $filelog=array();
        $logs_dir = "public/uploads/import/importnewprods" . date('dmy').".php";
        $codes = $this->getcodes();
        $query = "SELECT * FROM `jk_prodoptions` as op join `agromat` as a on op.`code` = a.`code` WHERE op.`code` in (".implode(',',$codes).")";
        //$query = "SELECT * FROM  `agromat`  WHERE`code` in (".implode(',',$codes).")";

        $result = DB::query(Database::SELECT,$query)->execute()->as_array();
        foreach($result as $r)
        {
            $prod=ORM::factory('Product')->where('code','=',$r['code'])->find();
            if($prod->loaded()) {$log[]='На сайте уже есть товар ' . $r['code']; continue;}
            $path = $r['code']. "-" .$this->setpath($r['title']);
            $cat_id = 54;
            if($r['material'] == 'керамогранит') $cat_id = 62;
            $search_brand = ORM::factory('Brand')->where('nameagrom','=',$r['brand'])->find();
            if($search_brand->loaded()) $brand_id =  $search_brand->brand_id;
            else {$log[]='Не найден бренд' . $r['brand']; continue;}
            if($brand_id == 83) $cat_id = 56;
            if($r['serie']){
                $serie = ORM::factory('Serie')->where('title','=',$r['serie'])->and_where('brand_id','=',$brand_id)->find();
                if($serie->loaded())
                {
                    $serie_id = $serie->serie_id;
                }
                else{
                    $newserie =  ORM::factory('Serie');
                    $newserie->title = $r['serie'];
                    $newserie->path=$this->setpath($r['serie']);
                    $newserie->brand_id=$brand_id;
                    try {
                        $newserie->save();
                        $serie_id = $newserie->pk();
                        $log[]= 'Добавили серию '. $r['serie'];
                    }
                    catch (ORM_Validation_Exception $e) {
                        $log[] = implode(';',$e->errors('validation'));
                        $log[]= $r['code'] .' Ошибка добавления серии '. $r['serie'];
                        continue;
                    }

                }
            }  else  $serie_id = '';
            $description = '<p>Единица измерения: ' . $r['unit'] . '<br />';
            $description .= 'Размер, мм: ' . $r['size'] . '<br />';
            $description .= 'количество в упаковке, шт: ' . $r['piece'] . '<br />';
            $description .= 'м2 в упаковке: ' . $r['meters'] . '<br />';
            //$description .= 'вес, кг: ' . $r['some'] . '<br />';
            $description .= 'Бренд: ' . $r['brand'] . '<br /></p>';
            $decor = 0;
            if(strpos($r['title'],'фриз')) $decor = 2;
            if(strpos($r['title'],'декор')) $decor = 1;
            $price = round(floatval($r['price'] * 0.75),2);
            $data = array();
            $data['title'] = $r['title'];
            $data['path'] = $path;
            $data['code'] = $r['code'];
            $data['present'] = 1;
            $data['status'] = 1;
            $data['top'] = 0;
            $data['decor'] = $decor;
            $data['price'] = $price;
            $data['brand_id'] = $brand_id;
            $data['series_id'] = $serie_id;
            $data['content'] = $description;
            $data['cat_id'] = $cat_id;
            $data['image_id'] = 0;
            $newprod=ORM::factory('Product');
            $newprod->values($data);

            try {
                $newprod->save();
                $prodid = $newprod->pk();
                $log[]= "Добавлен товар - " . $r['code'];
                $insertcat  = DB::query(Database::INSERT,"INSERT INTO `jk_prod_cats`(`prod_id`, `cat_id`) VALUES (" . $prodid . "," . $cat_id . ")")->execute();
                $image = 'tm_' . $r['code'].'.jpg';
                if(file_exists('public/uploads/imgproducts/'.$image)){
                    $im_db = ORM::factory('Image');
                    $im_db->product_id = $prodid;
                    $im_db->name = $image;
                    $im_db->save();

                    $p_db = ORM::factory('Product',$prodid);
                    $p_db->image_id = $im_db->pk ();
                    $p_db->save();
                    $log[]= "Добавлена картинка ";
                }
                $filelog[]=$r['code'];
            }
            catch (ORM_Validation_Exception $e) {
                $log[] = implode(';',$e->errors('validation'));
                $log[]= "Невозможно добавить товар " .$r['code'];
                continue;
            }

            foreach ($r as $k => $name)
            {
                if(!$name) continue;
                switch($k) {
                    case 'color': if($cat_id == 56) $fid=54; else $fid=40; break;
                    case 'imitation': if($cat_id == 56) $fid=58; else $fid=45; break;
                    case 'material': if($cat_id == 56) $fid=55; else $fid=46; break;
                    case 'form': if($cat_id == 56) $fid=56; else $fid=43; break;
                    case 'surface': if($cat_id == 56) $fid=57; else $fid=47; break;
                    case 'imagedecor': $fid=48; break;
                    case 'moroz': $fid=49; break;
                    case 'country': if($cat_id == 56) $fid=20; else $fid=41; break;
                    case 'antislip': $fid=53; break;
                    case 'variativnost': $fid=50; break;
                    case 'klasstir': $fid=51; break;
                    case 'destination': if($cat_id == 56) $fid=26; else $fid=42; break;
                    default: $fid = false; continue; break;
                }
                if(!$fid) continue;

                $names = explode(',',$name);
                foreach ($names as $optitle)
                {
                    $searchop = ORM::factory('Filteroptions')->where('option_title','=',$optitle)->and_where('filter_id','=',$fid)->find();
                    if(!$searchop->loaded()) continue;
                    $option_id = $searchop->option_id;
                    if($option_id)
                    {

                        $opt = ORM::factory('Froptionvalues');
                        $opt->prod_id = $prodid;
                        $opt->option_id = $option_id;
                        $opt->option_value = 1;
                        $opt->save();
                        $log[]='Опция добавлена!';
                    }
                }
            }
            $time = microtime(true) - $start;
            if($time > 90) { $log[] = 'Время !!!  ' . $prodid; break;}
        }
        file_put_contents($logs_dir, join(','.PHP_EOL, $filelog), FILE_APPEND);
        file_put_contents($logs_dir, join(','.PHP_EOL, $log), FILE_APPEND);
        return $log;
    }

    private function setfilteroptions_by_request()
    {
        $log = array();
        $filelog=array();
        $logs_dir = "public/uploads/import/codes" . date('dmy').".php";
        $start = microtime(true);
        $query = "SELECT * FROM `jk_products` as p join `jk_prodoptions` as op on p.code = op.code where p.prod_id not in (select `prod_id` from `jk_froptionvalues`)  order by p.`prod_id` desc limit 350";
        $result = DB::query(Database::SELECT,$query)->execute()->as_array();
        foreach ($result as $r)
        {
            $cat_id = $r['cat_id'];
            foreach ($r as $k => $name)
            {
                switch($k) {
                    case 'color': if($cat_id == 56) $fid=54; else $fid=40; break;
                    case 'imitation': if($cat_id == 56) $fid=58; else $fid=45; break;
                    case 'material': if($cat_id == 56) $fid=55; else $fid=46; break;
                    case 'form': if($cat_id == 56) $fid=56; else $fid=43; break;
                    case 'surface': if($cat_id == 56) $fid=57; else $fid=47; break;
                    case 'imagedecor': $fid=48; break;
                    case 'moroz': $fid=49; break;
                    case 'country': if($cat_id == 56) $fid=20; else $fid=41; break;
                    case 'antislip': $fid=53; break;
                    case 'variativnost': $fid=50; break;
                    case 'klasstir': $fid=51; break;
                    case 'rect': $fid=52; break;
                    default: $fid = false; continue; break;
                }
                if(!$fid) continue;
                $names = explode(',',$name);
                foreach ($names as $optitle)
                {
                    $option = ORM::factory('Filteroptions')->where('option_title','=',$optitle)->and_where('filter_id','=',$fid)->find();
                    if(!$option->loaded()) continue;
                    $newopt = ORM::factory('Froptionvalues');
                    $newopt->prod_id = $r['prod_id'];
                    $newopt->option_id = $option->option_id;
                    $newopt->option_value = 1;
                    try {
                        $newopt->save();
                    }
                    catch (ORM_Validation_Exception $e) {
                        $log[] = 'Код ' . $r['code'] .' option ' . $option->option_id . ' ' . $option->option_title . ' product ' . $r['prod_id']. ' ошибка !';
                        continue;
                    }
                    $log[]= 'option ' . $option->option_id . ' ' . $option->option_title . ' product ' . $r['prod_id'];

                }
            }
            $filelog[]=$r['code'];
            $time = microtime(true) - $start;
            if($time > 70) { $log[] = 'Время !!!  ' . $op['code']; break;}
        }
        file_put_contents($logs_dir, join(',', $filelog), FILE_APPEND);
        return $log;
    }




}