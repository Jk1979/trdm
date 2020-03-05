 <?php defined('SYSPATH') or die('No direct script access.');

class Controller_Index_Catalog extends Controller_Index 
{
public $needDesc = 'yes';
public function before() {
    parent::before();
    $this->template->scripts[] = 'public/js/double-slider.js';
    
    
   // $ispage = isset($this-request->param('page')) ? true : false;
    if(isset($_GET['sort']) || isset($_GET['price_from']) || isset($_GET['price_to']))
        {
            $this->needDesc  = 'no';
        } 
    //$this->template->scripts[] = 'public/js/lightbox.min.js';
    //$this->template->scripts[] = 'public/js/jquery-tabs-ui.min.js';
    //$this->template->styles[] = 'css/double-slider';

$this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/');
//$this->breadcrumbs[] = array('name' => 'Каталог', 'link' => '/catalog');
$this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
 
}

public function action_index()
{
   // $brands = Widget::load('Allbrands');
    
    $categories = ORM::factory('Category')->fulltree()->as_array();
    foreach($categories as $cat)
    {
        if($cat->parent_id !=1)
        $cat->title = str_repeat('&nbsp;', 1 * $cat->lvl) . $cat->title;
    }
    $content = View::factory('/' . $this->theme . '/index/catalog/v_catalog_index')
            ->bind('categories', $categories);



    $this->template->page_title='Каталог керамической плитки и сантехники (Киев)';
    $this->template->page_caption='Каталог';
//$this->template->left_block[] = $brands;
    $this->template->center_block = array($content);


} 

public function action_cat()
{


   // $cat = (int) $this->request->param('cat');
    $cat =  $this->request->param('cat');
        $cat = mysql_escape_string ($cat);
    $filters = $this->request->param('filters');
        $filters = mysql_escape_string ($filters);
    if($cat== 'root'){
        $this->redirect(URL::base());
        
    }
    
    if($this->request->param('page')) $this->needDesc  = 'no';

    // Получаем список 
    $category = ORM::factory('Category')->where('path', '=', $cat)->find();
    if(!$category->loaded()){
        $this->redirect('/404');
        
    }
    $countprs=0;
    $sort = 1;
    $direction = "ASC";
    if(isset($_GET['sort']))
    {
        $sort = (int) $_GET['sort'];
    }
    $sort_type = "product.prod_id";
    switch($sort) 
    {
      case 1: $sort_type = "product.title"; $sort_type_s = "по названию"; break;
      case 2: $sort_type = "product.price"; $direction = "ASC"; $sort_type_s = "от дешевых к дорогим"; break;
      case 3: $sort_type = "product.price"; $direction = "DESC"; $sort_type_s = "от дорогих к дешевым"; break;
      case 4: $sort_type = "product.prod_id"; $direction = "DESC"; $sort_type_s = "последние добавленные"; break;

      default:  $sort_type = "product.prod_id"; break;
    }
    ////////***************************/////////
    $price_from = 0; 
    $maxpricedb = $category->products->select(array(DB::expr('MAX(price)'), 'maxprice'))->find()->as_array();
        if($maxpricedb['maxprice']) $maxprice = $maxpricedb['maxprice'];
            else $maxprice = 1;
		
    $price_to = $maxprice;
    if((isset($_GET['price_from'])) && (isset($_GET['price_to'])))
    {    
        $price_from = isset($_GET['price_from'])?$_GET['price_from']:0; 
        $price_to = isset($_GET['price_to'])?$_GET['price_to']:$maxprice;
    }
    //////////*************************////////    
    $prs=array();
    $prods = array();
    if($filters)
    {
        $prodfs = array();
        $filtersArr = array();
        if(strpos($filters,'+') !== FALSE )  $filtersArr = explode("+",$filters);
        else $filtersArr[] = $filters;
        $prodoptids = ORM::factory('Froptionvalues')
            ->where("option_id","IN", $filtersArr)
            ->and_where("option_value","=",1)
            ->find_all();
        foreach ($prodoptids as $p)
        {
            $prodfs[] = $p->prod_id;
        }
        if(count($prodfs))
        {
            $prs = ORM::factory('Product')
                ->where('cat_id','=',$category->cat_id)
                ->and_where('status', '<>', 0)
                ->and_where('price','>=',$price_from)
                ->and_where('price','<=',$price_to)
                ->and_where('prod_id','IN',$prodfs)
                ->find_all();
            foreach ($prs as $p)
            {
                $prods[] = $p->prod_id;
            }
            $countprs = count($prs);
        }
    }
    else
    {  
        $countprs = ORM::factory('Product')
            ->where('cat_id','=',$category->cat_id)
            ->and_where('price','>=',$price_from)
            ->and_where('price','<=',$price_to)
            ->and_where('status', '<>', 0)
            ->count_all();
        
    }
    $pagination = Pagination::factory(array('total_items'=>$countprs,'items_per_page'=>21,'view'=> 'pagination/floating'));
    if(count($prods))
    {
        $products = ORM::factory('Product')
                ->where('status', '<>', 0)
                ->and_where('cat_id','=',$category->cat_id)
                ->and_where('prod_id','IN',$prods)
                ->limit($pagination->items_per_page)
                ->offset($pagination->offset)
                ->order_by($sort_type,$direction)
                ->find_all();
    }
    elseif($countprs) $products = ORM::factory('Product')
                ->where('status', '<>', 0)
                ->and_where('cat_id','=',$category->cat_id)
                ->and_where('price','>=',$price_from)
                ->and_where('price','<=',$price_to)
                ->limit($pagination->items_per_page)
                ->offset($pagination->offset)
                ->order_by($sort_type,$direction)
                ->find_all();
    
    /*$brands = ORM::factory('Brand')
            ->join('products')
            ->on('brand.brand_id', '=', 'products.brand_id')
            ->where('products.cat_id','=',$category->cat_id)
			->and_where('brand.status','=',1)
            ->group_by('brand.title')
            ->order_by('brand.title', 'ASC')
            ->find_all();*/
    
    $this->breadcrumbs[] = array('name' => $category->title, 'link' => '/catalog/cat/' . $category->path);
    $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);

    $content = View::factory('/' . $this->theme . '/index/catalog/v_catalog_cat')
            ->bind('products', $products)
            ->bind('cat' , $cat)
            ->bind('category' , $category)
            ->bind('sort_type' , $sort_type_s)
            ->bind('pagination',$pagination)
            ->bind('needDesc', $this->needDesc);
           // ->bind('brands',$brands);


    $flbrands = Widget::load('Flbrands');
    $filters = Widget::load('Filterwd');
    // Выводим в шаблон
    if($category->meta_title)
        $this->template->page_title = $category->meta_title;
    else $this->template->page_title = $category->title . " " . $category->meta_title;
    if($category->meta_description)
        $this->template->description = $category->meta_description;
    else $this->template->description = $category->title .  ' по самой доступной цене в интернет-магазине “Трейдмаг”. Тел. (044) 331-38-59';
    if($category->meta_keywords)
        $this->template->keywords = $category->meta_keywords;
    else $this->template->keywords = $category->title . ' Купить плитку и сантехнику Киев. Интернет магазин сантехники и плитки';
    $this->template->page_caption = $category->title;
    $this->template->center_block = array($content);
    $this->template->left_block[] = $filters;  
    $this->template->left_block[] = $flbrands; 
    if($category->has_children()) {
         $allbrs = Widget::load('Allbrands');
         array_splice($this->template->left_block, 0);
         $this->template->left_block[] = $allbrs;
        $this->template->right_block = null;
        //$this->template->right_block = null;
    }

}


public function action_brand()
{
$cat =  $this->request->param('cat');
    $cat = mysql_escape_string ($cat);
$brand_p =  $this->request->param('brand');
    $brand_p = mysql_escape_string ($brand_p);
 $filters = $this->request->param('filters');
        $filters = mysql_escape_string ($filters);


if((!$cat) || (!$brand_p) )
    $this->redirect('/404');

if($this->request->param('page')) $this->needDesc  = 'no';

$category = ORM::factory('Category')->where('path', '=', $cat)->find();
$catids = $this->get_where_cats($cat);

if($category->parent_id != 1)
{
    $this->needDesc  = 'no';
}
    
    /*if(!$category->loaded()){
        $this->redirect('/404');
    }*/
$sort = 1;
    $direction = "ASC";
    if(isset($_GET['sort']))
    {
        $sort = (int) $_GET['sort'];
    }
    $sort_type = "product.prod_id";
    switch($sort) 
    {
      case 1: $sort_type = "product.title"; $sort_type_s = "по названию"; break;
      case 2: $sort_type = "product.price"; $direction = "ASC"; $sort_type_s = "от дешевых к дорогим"; break;
      case 3: $sort_type = "product.price"; $direction = "DESC"; $sort_type_s = "от дорогих к дешевым"; break;
      case 4: $sort_type = "product.prod_id"; $direction = "DESC"; $sort_type_s = "последние добавленные"; break;

      default:  $sort_type = "product.prod_id"; break;
    }
////////***************************/////////
    $price_from =0; 
    $maxpricedb = ORM::factory('Product')->select(array(DB::expr('MAX(price)'), 'maxprice'))
        ->where('cat_id','IN',$catids)
        ->find()->as_array();
    
        if($maxpricedb['maxprice']) $maxprice = $maxpricedb['maxprice'];
            else $maxprice = 1;
    $price_to = $maxprice;
    if((isset($_GET['price_from'])) && (isset($_GET['price_from'])))
    {    
        $price_from = isset($_GET['price_from'])?$_GET['price_from']:0; 
        $price_to = isset($_GET['price_to'])?$_GET['price_to']:$maxprice;
    }
     
     
    //////////*************************//////// 
$brands_arr = array();
if(strpos($brand_p,'+') !== FALSE )  
    $brands_arr = explode("+",$brand_p);
else $brands_arr[] = $brand_p;
    
    $brands = ORM::factory('Brand')->where('path', 'IN', $brands_arr)->find_all();
    $brs=array();
    foreach ($brands as $b)
        {
            $brs[] = $b->brand_id;
        }
    if(count($brs)==0)  $this->redirect('/404');
    $prods = array();
    $prs = array();
    $countprs=0;
    if($filters)
    {
        $prodfs = array();
        $filtersArr = array();
        if(strpos($filters,'+') !== FALSE )  $filtersArr = explode("+",$filters);
        else $filtersArr[] = $filters;
        $prodoptids = ORM::factory('Froptionvalues')
            ->where("option_id","IN", $filtersArr)
            ->and_where("option_value","=",1)
            ->find_all();
        foreach ($prodoptids as $p)
        {
            $prodfs[] = $p->prod_id;
        }
        if(count($prodfs))
        {
            $prs = ORM::factory('Product')
                    ->where('cat_id','IN',$catids)
                    ->and_where('status', '<>', 0)
                    ->and_where('price','>=',$price_from)
                    ->and_where('price','<=',$price_to)
                    ->and_where('brand_id','IN',$brs)
                    ->and_where('prod_id','IN',$prodfs)
                    ->find_all();
            
            foreach ($prs as $p)
            {
                $prods[] = $p->prod_id;
            }
            
           $countprs = count($prs); 
        }
    }
    else
    { 
        $countprs = ORM::factory('Product')
                ->where('cat_id','IN',$catids)
                ->and_where('status', '<>', 0)
                ->and_where('price','>=',$price_from)
                ->and_where('price','<=',$price_to)
                ->and_where('brand_id','IN',$brs)
                ->count_all();
          
    }
     
    
    $pagination = Pagination::factory(array('total_items'=>$countprs,'items_per_page'=>21,'view'=> 'pagination/floating'));
    if($countprs)
    {   
        if($filters) 
        {
           $products = ORM::factory('Product')
                    ->where('status', '<>', 0)
                    ->and_where('cat_id','IN',$catids)
                    ->and_where('prod_id','IN',$prods)
                    ->limit($pagination->items_per_page)
                    ->offset($pagination->offset)
                    ->order_by($sort_type,$direction)
                    ->find_all(); 
        }
        elseif($countprs) { 
            $products = ORM::factory('Product')
                    ->where('status', '<>', 0)
                    ->and_where('price','>=',$price_from)
                    ->and_where('price','<=',$price_to)
                    ->and_where('cat_id','IN',$catids)
                    ->and_where('brand_id','IN',$brs)
                    ->limit($pagination->items_per_page)
                    ->offset($pagination->offset)
                    ->order_by($sort_type,$direction)
                    ->find_all();
        }
    }
    $brand = ORM::factory('Brand')->where('path', '=', $brand_p)->find();
    
    if($brand->loaded())
    {
    $series = $brand->series
            ->join('products')
            ->on('serie.serie_id', '=', 'products.series_id')
            ->and_where('cat_id','IN',$catids)
            ->distinct('serie.serie_id')
            ->find_all();  
    $brand_desc = $brand->description;
    }
    //else  $this->redirect('/404');
    $content = View::factory('/' . $this->theme . '/index/catalog/v_catalog_brand')
            ->bind('series', $series)
            ->bind('products', $products)
            ->bind('cat' , $cat)
            ->bind('sort_type' , $sort_type_s)
            ->bind('pagination',$pagination)
            ->bind('brand',$brand_p)
            ->bind('brand_desc',$brand_desc)
            ->bind('needDesc', $this->needDesc);
     $this->breadcrumbs[] = array('name' => $category->title, 'link' => '/cat/' . $category->path);
     $this->breadcrumbs[] = array('name' => $brand->title, 'link' => '/cat/' . $category->path . '-' . $brand->path);
     $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
    
    $this->template->page_title = $category->title . " - ";
    
    $this->template->page_caption = "";
    
    $cb = count($brands);$koma="";
    foreach($brands as $i=>$b)
    {
        if(($i+1)<$cb) $koma = " - ";
        else $koma = " ";
        $this->template->page_title .= $b->title . " купить Киев " . $koma;
        $this->template->page_caption .= $b->title . $koma;
    }
    if($brand->meta_title)
        $this->template->page_title = $brand->meta_title;
    if($brand->meta_description)
        $this->template->description = $brand->meta_description;
    else $this->template->description = $this->template->page_title .  ' по лучшей цене в интернет-магазине “Трейдмаг”. Тел. (044) 331-38-59';
    if($brand->meta_keywords)
        $this->template->keywords = $brand->meta_keywords;
    else $this->template->keywords = $this->template->page_title . ' Купить плитку и сантехнику Киев. Интернет магазин сантехники и плитки';

    // Выводим в шаблон
    $flbrands = Widget::load('Flbrands');
    $filters = Widget::load('Filterwd');
    $this->template->left_block[] = $filters;  
    $this->template->left_block[] = $flbrands; 
    $this->template->title = $category->title;
    
    $this->template->center_block = array($content);

}

public function action_serie()
{
$cat =  $this->request->param('cat');
    $cat = mysql_escape_string ($cat);
$brand_p =  $this->request->param('brand');
    $brand_p = mysql_escape_string ($brand_p);
$serie_p =  $this->request->param('serie');
    $serie_p= mysql_escape_string ($serie_p);
if((!$cat) || (!$brand_p) || (!$serie_p) )
    $this->redirect();
$filters = $this->request->param('filters');
        $filters = mysql_escape_string ($filters);
  
 if($this->request->param('page')) $this->needDesc  = 'no';
    
$category = ORM::factory('Category')->where('path', '=', $cat)->find();
if(!$category->loaded()){
        $this->redirect();
    }
$catids = $this->get_where_cats($cat);
$brand = ORM::factory('Brand')->where('path', '=', $brand_p)->find();
$serie = ORM::factory('Serie')
        ->where('path', '=', $serie_p)
        ->and_where('brand_id','=',$brand->brand_id)
        ->find();
$sort = 1;
    $direction = "ASC";
    if(isset($_GET['sort']))
    {
        $sort = (int) $_GET['sort'];
    }
    $sort_type = "product.prod_id";
    switch($sort) 
    {
      case 1: $sort_type = "product.title"; $sort_type_s = "по названию"; break;
      case 2: $sort_type = "product.price"; $direction = "ASC"; $sort_type_s = "от дешевых к дорогим"; break;
      case 3: $sort_type = "product.price"; $direction = "DESC"; $sort_type_s = "от дорогих к дешевым"; break;
      case 4: $sort_type = "product.prod_id"; $direction = "DESC"; $sort_type_s = "последние добавленные"; break;

      default:  $sort_type = "product.prod_id"; break;
    }
    ////////***************************/////////
    $price_from = 0; 
    $maxpricedb = ORM::factory('Product')->select(array(DB::expr('MAX(price)'), 'maxprice'))
        ->where('cat_id','IN',$catids)
        ->find()->as_array();
        if($maxpricedb['maxprice']) $maxprice = $maxpricedb['maxprice'];
            else $maxprice = 1;
    $price_to = $maxprice;
    if((isset($_GET['price_from'])) && (isset($_GET['price_from'])))
    {    
        $price_from = isset($_GET['price_from'])?$_GET['price_from']:0; 
        $price_to = isset($_GET['price_to'])?$_GET['price_to']:$maxprice;
    }
    //////////*************************//////// 
    $countprs=0;
    if($filters)
    {
        $prodfs = array();
        $filtersArr = array();
        if(strpos($filters,'+') !== FALSE )  $filtersArr = explode("+",$filters);
        else $filtersArr[] = $filters;
        $prodoptids = ORM::factory('Froptionvalues')
            ->where("option_id","IN", $filtersArr)
            ->and_where("option_value","=",1)
            ->find_all();
        foreach ($prodoptids as $p)
        {
            $prodfs[] = $p->prod_id;
        }
        if(count($prodfs))
        {
            $prs = ORM::factory('Product')
                    ->where('cat_id','IN',$catids)
                    ->and_where('series_id','=',$serie->serie_id)
                    ->and_where('status', '<>', 0)
                    ->and_where('price','>=',$price_from)
                    ->and_where('price','<=',$price_to)
                    ->and_where('prod_id','IN',$prodfs)
                    ->find_all();
            $countprs = count($prs);
        }
    }
else 
{    
 $countprs = ORM::factory('Product')
     ->where('cat_id','IN',$catids)
     ->and_where('series_id','=',$serie->serie_id)
     ->and_where('status', '<>', 0)
     ->and_where('price','>=',$price_from)
     ->and_where('price','<=',$price_to)
     ->count_all();
 
}

$pagination = Pagination::factory(array('total_items'=>$countprs,'items_per_page'=>21,'view'=> 'pagination/floating'));
if($countprs) 
{
$products = ORM::factory('Product')
        ->where('status', '<>', 0)
        ->and_where('cat_id','IN',$catids)
        ->and_where('series_id','=',$serie->serie_id)
        ->and_where('price','>=',$price_from)
        ->and_where('price','<=',$price_to)
        ->limit($pagination->items_per_page)
        ->offset($pagination->offset)
        ->order_by($sort_type,$direction)
        ->find_all();
}
    $this->breadcrumbs[] = array('name' => $category->title, 'link' => '/cat/' . $category->path);
    $this->breadcrumbs[] = array('name' => $brand->title, 'link' => '/cat/' . $category->path . '-' . $brand->path);
    $this->breadcrumbs[] = array('name' => $serie->title, 'link' => '/cat/' . $category->path . '-' . $brand->path . '/series-' . $serie->path);
    $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);

    $content = View::factory('/' . $this->theme . '/index/catalog/v_catalog_serie')
            ->bind('count' , $count)
            ->bind('products', $products)
            ->bind('sort_type' , $sort_type_s)
            ->bind('pagination',$pagination)
            ->bind('needDesc', $this->needDesc);

    $this->template->page_title = $category->title . " - " . $brand->title . " - ";
    $this->template->description =$this->template->page_title .  ' по лучшей цене в интернет-магазине “Трейдмаг”. Тел. (044) 331-38-59';
    $this->template->keywords = $serie->title . ' Купить плитку и сантехнику Киев. Интернет магазин сантехники и плитки';

    // Выводим в шаблон
    $flbrands = Widget::load('Flbrands');
    $filters = Widget::load('Filterwd');
    $this->template->left_block[] = $filters;  
    $this->template->left_block[] = $flbrands; 
    $this->template->title = $serie->title;
    $this->template->page_title .= $serie->title;
    $this->template->page_caption = $serie->title;
    $this->template->center_block = array($content);
}

// Просмотр товара
public function action_view() {

    //$cat = (int) $this->request->param('cat');
         $cat = $this->request->param('cat');
         $id =  $this->request->param('id');
         $id = mysql_escape_string($id);



    //$product = ORM::factory('Product')->where('prod_id', '=', $id)->and_where('status', '!=', 0)->find();
    $product = ORM::factory('Product')->where('path', '=', $id)->and_where('status', '!=', 0)->find();
    $prodBrand = ORM::factory('Brand')->where('brand_id', '=', $product->brand_id)->find();
    $prodSerie = ORM::factory('Serie')->where('serie_id', '=', $product->series_id)->find();
    if(!$cat)
    {
        $category = $product->categories->find();

    }
    else  $category = ORM::factory('Category')->where('path', '=', $cat)->find();

    if (!$product->loaded()){
         $this->redirect('/404');
    }
    $anotherProducts = ORM::factory('Product')
        ->where('status', '<>', 0)
        ->and_where('series_id','=',$prodSerie->serie_id)
        ->and_where('prod_id','<>',$product->prod_id)
        ->find_all();
    if(!$anotherProducts) $anotherProducts = null;
    
     $images =  $product->images->find_all();
     $mainimage = ORM::factory('Image')->where('id', '=', $product->image_id)->find();
        $comproduct = Widget::load('prodcomments', array('param'=>$product->prod_id));
     $comments = $product->comments->find_all();
     $content = View::factory('/' . $this->theme . '/index/catalog/v_catalog_view', array(
        'product' => $product->as_array(),
        'comments' => $comments,
        'category' => $category,
        'images' => $images,
        'mainimage' => $mainimage,
        'anotherProducts' => $anotherProducts,
        'prodSerie' => $prodSerie,
        'prodBrand' => $prodBrand,
    ));
    switch($comproduct->status()) 
        {
          case 302: $this->redirect(Request::detect_uri()); break;
          default:  //$this->template->center_block[] = $comproduct; break;
                    $content->set('comproduct', $comproduct); break;
        }
    $this->template->center_block = array($content);
    $this->template->right_block = null;

    // Выводим в шаблон
    $this->template->title = $product->title;
    $word = "";
    if($category->title == 'Плитка' || $category->title == 'Плитка для ванной' ||
      $category->title == 'Плитка для кухни' || $category->title == 'Напольная плитка'
      || $category->title == 'Керамогранит')
    {    
        if($product->decor == 1) $word = '';
        if($product->decor == 2) $word = '';
        if($product->decor == 0) $word = 'Плитка ';
    }
    if($category->title == 'Мозаика') $word = 'Мозаика ';
    
    $this->template->page_caption = $word . $product->title;
    $this->template->page_title = $category->title . " - "  . $prodBrand->title . ' (Код товара: ТМ-' . $product->code . ") " . $product->title;
    if($product->price > 0) $this->template->page_title .= " Цена " . $product->price . " грн.";
    $this->template->page_title .=" купить Киев"; 
    $this->template->description = $this->template->page_title .  ' по лучшей цене в интернет-магазине “Трейдмаг”. Тел. (044) 331-38-59';
    $this->template->keywords = $product->title .' Купить плитку и сантехнику Киев. Интернет магазин сантехники и плитки';

    // Breadcrubs
    $this->breadcrumbs[] = array('name' => $category->title, 'link' => '/catalog/cat/' . $category->path);
    $this->breadcrumbs[] = array('name' => $prodBrand->title, 'link' => '/cat/' . $category->path . '-' . $prodBrand->path);
    $this->breadcrumbs[] = array('name' => $prodSerie->title, 'link' => '/cat/' . $category->path . '-' . $prodBrand->path . '/series-' . $prodSerie->path);
    $this->breadcrumbs[] = array('name' => $product->title, 'link' => '/catalog/view/' . $product->path);
    $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);

}

/*public function action_onlybrand()
{

$brand_p =  $this->request->param('brand');
    $brand_p = mysql_escape_string ($brand_p);
 


if(!$brand_p)
    $this->redirect('/404');


$sort = 1;
    $direction = "ASC";
    if(isset($_GET['sort']))
    {
        $sort = (int) $_GET['sort'];
    }
    $sort_type = "product.prod_id";
    switch($sort) 
    {
      case 1: $sort_type = "product.title"; $sort_type_s = "по названию"; break;
      case 2: $sort_type = "product.price"; $direction = "ASC"; $sort_type_s = "от дешевых к дорогим"; break;
      case 3: $sort_type = "product.price"; $direction = "DESC"; $sort_type_s = "от дорогих к дешевым"; break;
      case 4: $sort_type = "product.prod_id"; $direction = "DESC"; $sort_type_s = "последние добавленные"; break;

      default:  $sort_type = "product.prod_id"; break;
    }
    $brand = ORM::factory('Brand')->where('path', '=', $brand_p)->find(); 
    if(!$brand->loaded())
    {
      $this->redirect('/404');  
    }

 
}*/
    
protected function get_where_cats($catpath)
{
    $catids = array();
    $cats_chs = array();
    $cat = ORM::factory('Category')->where('path', '=', $catpath)->find();
    if(!$cat->loaded()){
        return null;
    }
    if($cat->has_children()) 
    {
        $cats_chs = $cat->children();
    
        if(count($cats_chs))
        {    
            $catids[] = $cat->cat_id;
            foreach($cats_chs as $c)
            {
                $catids[] = $c->cat_id; 
            }
            return $catids;
        }
    }
    else{
        $catids[] = $cat->cat_id;
        return $catids;
    }
    return null;
}
   
}
