<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Products extends Controller_Admin
{
    public function before() {
        parent::before();
        
        
        $this->template->scripts[] = 'public/js/jquery.MultiFile.pack.js';
        $this->template->scripts[] = 'public/js/upload.js';
//        $this->template->scripts[] = 'public/js/genpath.js';
        $this->template->scripts[] = 'public/js/getsubbrands.js';
        //$this->template->scripts[] = 'public/js/getfoptions.js';
        $this->template->scripts[] = 'public/js/uploadFromPage.js';
	$this->template->scripts[] = 'public/js/magn_popup_inline.js';	

        // Вывод в шаблон
        $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/admin');
        $this->breadcrumbs[] = array('name' => 'Товары', 'link' => '/admin/products');
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        $this->template->submenu = Widget::load('menuproducts');
    }
    public function action_index()
    {
       
         if (isset($_POST['query']))
        {
            $search = Arr::get($_POST, 'query');
            $search = substr($search, 0, 128);
            $search = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $search);
            $good = htmlspecialchars($search);

           
            $good = '%'.$good.'%';
			
            $search_result = ORM::factory('Product')
                    ->where('prod_id', 'LIKE', $good)
                    ->or_where('title', 'LIKE', $good)
                    ->or_where('code', 'LIKE', $good)
                    ->order_by('prod_id','DESC')
                    ->find_all()->as_array();
            $paginaton = "";
           $content = View::factory($this->theme . '/admin/products/v_products_index')
                ->bind('products',$search_result)
                ->bind('pagination',$pagination); 
         
        }
        else 
        {
            $count = ORM::factory('Product')->count_all();
            $pagination = Pagination::factory(array('total_items'=>$count,
                                              'items_per_page'=>50 ))
                     ->route_params(array(
                      'controller' => Request::current()->controller(),
                       'action' => Request::current()->action(),
                                          ));

            $products = ORM::factory('Product')
                    ->limit($pagination->items_per_page)
                    ->offset($pagination->offset)
                    ->order_by('prod_id','DESC')
                    ->find_all(); 


            $content = View::factory($this->theme . '/admin/products/v_products_index')
                    ->bind('products',$products)
                    ->bind('pagination',$pagination);
        }
        // Вывод в шаблон
        $this->template->page_title = 'Товары';
        $this->template->page_caption = 'Товары';
        $this->template->center_block = array($content);
        
    }  

public function action_add()
{
   $mainImage  = "";
   $categories = ORM::factory('Category');
   $cats = array();
   $categories = $categories->fulltree()->as_array();
       foreach($categories as $cat) {
           $cats[$cat->cat_id]= str_repeat('&nbsp;', 2 * $cat->lvl) . $cat->title;
       }
   $all_brands = ORM::factory('Brand')->order_by('title','ASC')->find_all();
   $brands = array(
        null => 'Без производителя',
    ); 
       foreach($all_brands as $brand) {
           $brands[$brand->brand_id]= $brand->title;
       }
    $series = array(
        null => 'Без серии',

    );  
   if(isset($_POST['add'])) {

        $data = Arr::extract($_POST, array('title','path','code','present','status','top','decor','price',
            'brand_id','series_id','preview','content', 'meta_title',
            'meta_keywords', 'meta_description','categories','foptions','attributes'));

        $products = ORM::factory('Product');

                    ////// обработка данных о картинках /////////////////////
                    $oneImage = Arr::get($_POST, 'selectImage');
                    $manyImages = Arr::get($_POST, 'anotherImages');
                    $image = array();
                    if(strlen($manyImages)>0) // Если картинок больше одной
                    {
                            $image = explode('/',$manyImages);
                    }
                    // Если картинка одна 
                    elseif(strlen($oneImage)>0)
                    {
                            $image = explode('/',$oneImage);
                            $mainImage = $image[count($image)-1];
                    }
                    $foptions = array();
                    if(isset($_POST['foptions']) && count($_POST['foptions']) > 0)
                    {
                            $foptions = $_POST['foptions'];
                            if(isset($data['foptions'])) unset($data['foptions']);
                    }
                    $attributes = array();
                    if(isset($_POST['attributes']) && count($_POST['attributes']) > 0)
                    {
                            $attributes['length'] = $_POST['attributes'][0];
                            $attributes['width'] = $_POST['attributes'][1];
                            $attributes['thick'] = $_POST['attributes'][2];
                            $attributes['color'] = $_POST['attributes'][3];
                            $attributes['imitation'] = $_POST['attributes'][4];
                            $attributes['material'] = $_POST['attributes'][5];
                            $attributes['form'] = $_POST['attributes'][6];
                            $attributes['surface'] = $_POST['attributes'][7];
                            $attributes['destination'] = $_POST['attributes'][8];
                            $attributes['weight'] = $_POST['attributes'][9];
                            //if(isset($data['attributes'])) unset($data['attributes']);
                            $data['attributes'] = $attributes;
                    }
                    $data['cat_id'] = $data['categories'][0] ? $data['categories'][0] : 0;
        $products->values($data);

        try {
            $products->save();
            if($data['categories'])
            $products->add('categories', $data['categories']);

/////////////////////////////// Работа с изображениями ///////////////////////////
            if($mainImage != "")
            {
                    // Запись в БД
                    $im_db = ORM::factory('Image');
                    $im_db->product_id = $products->pk();
                    $im_db->name = $mainImage;
                    $im_db->save();

                    $p_db = ORM::factory('Product',$products->pk());
                    $p_db->image_id = $im_db->pk();
                    $p_db->save();   
            }
            elseif (count($image)>1 && $mainImage=="")
            {
                    foreach($image as $im => $imname)
                    {
                            if(strlen($imname)>0)
                            {
                                    // Запись в БД
                                    $im_db = ORM::factory('Image');
                                    $im_db->product_id = $products->pk();
                                    $im_db->name = $imname;
                                    $im_db->save();

                                    $p_db = ORM::factory('Product',$products->pk());
                                    if($p_db->image_id == 0) 
                                    {
                                            $p_db->image_id = $im_db->pk ();
                                            $p_db->save();   
                                    }
                            }
                    }
            }
                                //// Конец блока Работа с изображениями /////////////////////////// 
                            /// Работа с опциями фильтров  /////////////////////////////////////////////////
                            if(count($foptions)>0) {
                                    foreach ($foptions as $opn_id) {
                                            $options = ORM::factory('Froptionvalues');
                                           /* $option = $options->where('prod_id', '=', $products->pk())->and_where('option_id', '=',$opn_id)->find();
                                            if($option->loaded())    continue;*/
                                            $options->prod_id = $products->pk();
                                            $options->option_id = $opn_id;
                                            $options->option_value = 1;
                                            $options->save();
                                    }
                            }

                            //////// Атрибуты товара  ////////////
                            if(count($attributes)>0)
                            {
                                if(array_filter($attributes) ) {
                                    $newattr = ORM::factory('Prodattribut');
                                    $newattr->values($attributes);
                                    $newattr->prod_id = $products->pk();
                                    $newattr->save();
                                }
                            }        

            $this->redirect('admin/products');
        }
        catch (ORM_Validation_Exception $e) {
            $errors = $e->errors('validation');
        }
   }



   $content = View::factory($this->theme . '/admin/products/v_products_add')
        ->bind('cats', $cats)
        ->bind('brands', $brands)
        ->bind('series', $series)
        ->bind('errors',$errors)
        ->bind('data',$data);
    $this->breadcrumbs[] = array('name' => 'Новый товар', 'link' => '/admin/products/add');
    $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
   $this->template->page_title = 'Добаивть новый товар';
   $this->template->page_caption = 'Добавить новый товар';
   $this->template->center_block = array($content);
}
    
public function action_edit()
{
   $id = (int) $this->request->param('id');

   if(!$id) {
       $this->redirect('admin/products');
   }
           $mainImage = "";
   //Получение категорий
  $categories = ORM::factory('Category');
  $categories = $categories->fulltree()->as_array();
      foreach($categories as $cat) {
          $cats[$cat->cat_id]= str_repeat('&nbsp;', 2 * $cat->lvl) . $cat->title;
      }
  $all_brands = ORM::factory('Brand')->order_by('title','ASC')->find_all();
  $brands = array(
       null => 'Без производителя',
   ); 
      foreach($all_brands as $brand) {
          $brands[$brand->brand_id]= $brand->title;
   }

   $products = ORM::factory('Product', $id);

   $data = $products->as_array();
   $series_obj = ORM::factory('Serie')->where('brand_id','=',$products->brand_id)->order_by('title','ASC')->find_all();
   $series = array(
       null => 'Без серии',
   ); 
   foreach($series_obj as  $s) {
          $series[$s->serie_id]= $s->title;
   }

   $data['categories'] = $products->categories->find_all()->as_array();
   $data['images'] = $products->images->find_all()->as_array();
   $data['main_img'] = $products->image_id;
   
   $attributes = ORM::factory('Prodattribut')->where('prod_id','=',$id)->find()->as_array();
   if($attributes)
   {
       $data['attributes']=$attributes;
   }
   // Редактирование
   if (isset($_POST['save'])) {
       $data = Arr::extract($_POST, array('title','path','code','present','status','top','decor','price',
           'brand_id','series_id','preview','images','content', 'meta_title',
           'meta_keywords', 'meta_description','categories','attributes'));

                           ////// обработка данных о картинках /////////////////////
                   $oneImage = Arr::get($_POST, 'selectImage');
                   $manyImages = Arr::get($_POST, 'anotherImages');
                   $image = array();

                   if(strlen($manyImages)>0) // Если картинок больше одной
                   {
                           $image = explode('/',$manyImages);
                   }
                   // Если картинка одна 
                   elseif(strlen($oneImage)>0)
                   {
                           $image = explode('/',$oneImage);
                           $mainImage = $image[count($image)-1];
                   }
                   $foptions = array();
                   if(isset($_POST['foptions']) && count($_POST['foptions']) > 0)
                   {
                           $foptions = $_POST['foptions'];
                           if(isset($data['foptions'])) unset($data['foptions']);
                   }
                    
                    if(isset($_POST['attributes']) && count($_POST['attributes']) > 0)
                    {
                            $attributes = array();
                            $attributes['length'] = $_POST['attributes'][0];
                            $attributes['width'] = $_POST['attributes'][1];
                            $attributes['thick'] = $_POST['attributes'][2];
                            $attributes['color'] = $_POST['attributes'][3];
                            $attributes['imitation'] = $_POST['attributes'][4];
                            $attributes['material'] = $_POST['attributes'][5];
                            $attributes['form'] = $_POST['attributes'][6];
                            $attributes['surface'] = $_POST['attributes'][7];
                            $attributes['destination'] = $_POST['attributes'][8];
                            $attributes['weight'] = $_POST['attributes'][9];
                            //if(isset($data['attributes'])) unset($data['attributes']);
                            if(count($attributes)) $data['attributes'] = $attributes;
                    }
                  
                   
                       
                   
                  
       $data['cat_id'] = $data['categories'][0];
       $products->values($data);

       try {
           $products->save();
           $products->remove('categories');
           $products->add('categories', $data['categories']);

           /////////////////////////////// Работа с изображениями ///////////////////////////
                           if($mainImage != "")
                           {
                                   // Запись в БД
                                   $im_db = ORM::factory('Image');
                                   $im_db->product_id = $products->pk();
                                   $im_db->name = $mainImage;
                                   $im_db->save();

                                   $p_db = ORM::factory('Product',$products->pk());
                                   $p_db->image_id = $im_db->pk();
                                   $p_db->save();   
                           }
           elseif (count($image)>1 && $mainImage=="")
           {
                                           foreach($image as $im => $imname)
                                           {
                                                   if(strlen($imname)>0)
                                                   {
                                                           // Запись в БД
                                                           $im_db = ORM::factory('Image');
                                                           $im_db->product_id = $products->pk();
                                                           $im_db->name = $imname;
                                                           $im_db->save();

                                                           $p_db = ORM::factory('Product',$products->pk());
                                                           if($p_db->image_id == 0) 
                                                           {
                                                                   $p_db->image_id = $im_db->pk ();
                                                                   $p_db->save();   
                                                           }
                                                   }
                   }
           }
/////////////////////////////// Конец блока Работа с изображениями /////////////////////////// 
   ///////////////////////////// Работа с опциями фильтров  /////////////////////////////////////////////////
   if(!$foptions) {
           $oldOptions = ORM::factory('Froptionvalues')->where('prod_id','=',$id)->find_all();
           if(count($oldOptions)>0)
           {
                   foreach($oldOptions as $op){
                   $op->delete();
                   }
           }
   }
           if(count($foptions)>0) {
                   $oldOptions = ORM::factory('Froptionvalues')->where('prod_id','=',$id)->find_all();
                   if(count($oldOptions)>0)
                   {
                           foreach($oldOptions as $op){
                           $op->delete();
                           }
                   }
                   foreach ($foptions as $opn_id) {
                           $options = ORM::factory('Froptionvalues');
                           $options->prod_id = $id;
                           $options->option_id = $opn_id;
                           $options->option_value = 1;
                           $options->save();
                   }
           }

    ///////////////////////// Конец работы с опциями фильтров  //////////////////
           //////// Атрибуты товара  ////////////
                        if(count($attributes)>0)
                        {
                            if(array_filter($attributes) ) {
                                $newattr = ORM::factory('Prodattribut',$id);
                                if($newattr->loaded())
                                {
                                    $newattr->prod_id = $id;
                                    $newattr->values($attributes);
                                    $newattr->save();
                                }
                                else{
                                    $newattr = ORM::factory('Prodattribut');
                                    $newattr->values($attributes);
                                    $newattr->prod_id = $id;
                                    $newattr->save();
                                }
                               
                            }
                            else
                            {
                                $newattr = ORM::factory('Prodattribut',$id);
                                if($newattr->loaded())
                                    $newattr->delete();
                            }
                        }        
           
           $this->redirect('admin/products');

       }
       catch (ORM_Validation_Exception $e) {
           $errors = $e->errors('validation');
       }
   }

   $content = View::factory($this->theme . '/admin/products/v_products_edit')
           ->bind('id', $id)
           ->bind('errors', $errors)
           ->bind('cats', $cats)
           ->bind('brands', $brands)
           ->bind('series', $series)
           ->bind('data', $data)
           ->bind('filters', $filters);
   // Вывод в шаблон
   $this->breadcrumbs[] = array('name' => $products->title, 'link' => '/admin/products/edit/' . $id);
   $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
   $this->template->page_title = 'Редактировать товар';
   $this->template->page_caption = 'Редактировать товар';
   $this->template->center_block = array($content);
}
    public function action_copy()
    {
        $id = (int) $this->request->param('id');

        if(!$id) {
            $this->redirect('admin/products');
        }
        $products = ORM::factory('Product', $id);
        
        $data = $products->as_array();
        $data['title'] .= '_copy_' . date('Ymd-His');      
        $data['path'] .= '_copy_' . date('Ymd-His');      
        $data['code'] .= '_copy_' . date('His');      
        $data['categories'] = $products->categories->find_all()->as_array();
        $data['main_img'] = $products->image_id;
        $mainImage = ORM::factory('Image',$products->image_id);
        $newproduct = ORM::factory('Product');
        $newproduct->values($data);
          
        $newproduct->save();
        $newproduct->add('categories', $data['categories']);

        if($data['main_img'])
        {
            // Запись в БД
            $im_db = ORM::factory('Image');
            $im_db->product_id = $newproduct->pk();
            $im_db->name = $mainImage->name;
            $im_db->save();
        
        }
        $this->redirect('admin/products');  

    }    
    public function action_delete() {
        $id = (int) $this->request->param('id');

        if(!$id) {
            $this->redirect('admin/products');
            
        }
        
        $prod = ORM::factory('Product', $id);
        $prod->remove('categories');
        $im_db = ORM::factory('Image')
		->where('product_id','=',$id)->find_all();
		if(count($im_db)>0)
		{
			foreach($im_db as $im){
			$im->delete();
			}
		}
		$foptions = ORM::factory('Froptionvalues')->where('prod_id','=',$id)->find_all();
		if(count($foptions)>0)
		{
			foreach($foptions as $fop){
			$fop->delete();
			}
		}
                $attr = ORM::factory('Prodattribut',$id);
                if($attr->loaded())
                {
                    $attr->delete();
                }
		
        $prod->delete();
        $this->redirect('admin/products');
    }
    
    
	public function action_delimage()
	{
            $id = (int) $this->request->param('id');
            $image = ORM::factory('Image', $id);
            $product_id = $image->product->prod_id;
            
            if(!$image->loaded()) {
                $this->redirect('admin/products');
            }
        
        $image->delete();
        $prod = ORM::factory('Product', $product_id);    
        if($prod->image_id == $id) 
        {    
            $prod->image_id = $prod->images->find()->pk(); 
            $prod->save();
        }
        $this->redirect('admin/products/edit/' . $product_id . '#image_str');
	}
        
    public function action_setmain()
    {
        $id = (int) $this->request->param('id');
        $image = ORM::factory('Image', $id);
        $product_id = $image->product_id;

        if(!$image->loaded()) {
            $this->redirect('admin/products');
        }

    $prod = ORM::factory('Product', $product_id);    
    if($prod->image_id != $id) 
    {    
        $prod->image_id = $id;
        $prod->save();
    }
    $this->redirect('admin/products/edit/' . $product_id . '#image_str');
    }

        
    public function _upload_img($file,$name = NULL, $ext = NULL, $directory = NULL)
    {

        if($directory == NULL)
        {
            $directory = 'public/uploads';
        }

        if($ext== NULL)
        {
            $ext= 'jpg';
        }

        // Изменение размера и загрузка изображения
        $im = Image::factory($file);

        if($im->width > 150)
        {
            $im->resize(150);
        }
        
        if($name== NULL)
        {
            // Генерируем случайное название
            $symbols = '0123456789abcdefghijklmnopqrstuvwxyz';
            $name = substr(str_shuffle($symbols), 0, 16);
            $im->save("$directory/small_$name.$ext");
            $im = Image::factory($file);
            $im->save("$directory/$name.$ext");
            return $name;
        }
        
        $im->save("$directory/small_$name");

        $im = Image::factory($file);
        $im->save("$directory/$name");
        
        return "$name";
    }
public function action_changeprice()
{
    $id = (int) $this->request->param('id');

        if(!$id) {
            die();
        } 
    $newprice = isset($_GET['newprice'])? $_GET['newprice']:null;
    $newprice = preg_replace("/[^.0-9]/", '', $newprice);
    if(!$newprice) {
        die();
    }
    else{
       $product = ORM::factory('Product',$id); 
       $product->price = $newprice;
       $product->save();
       echo $newprice;
       die();
    }
}
public function action_saveprodimg()
{
    $id = (int) $this->request->param('id');

    if(!$id) {
        die();
    } 
    $mainImage = isset($_GET['image'])? $_GET['image']:null;
    //$image = explode('/',$oneImage);
    //$mainImage = $image[count($image)-1];
    if(!$mainImage) {
        die();
    }
    
    $product = ORM::factory('Product',$id); 
    if($mainImage != "")
    {
            // Запись в БД
            $im_db = ORM::factory('Image');
            $im_db->product_id = $product->pk();
            $im_db->name = $mainImage;
            $im_db->save();

            $p_db = ORM::factory('Product',$product->pk());
            $p_db->image_id = $im_db->pk();
            $p_db->save();   
    }
    
    echo "ok";
    die();
    
}
 public function action_getlistimgs()
    {
         
        $title = $this->request->param('id');
        $images = ORM::factory('Image')
            ->where('name',"LIKE", "%$title%")
            ->limit(5)
            ->find_all();
        $data = array();$i=0;
        foreach($images as $im)
        {    
              $data[$i]['title']= $im->name;
              $i++;
        }
        echo json_encode($data);
        die;
    }



}