 <?php defined('SYSPATH') or die('No direct script access.');

class Controller_Index_Catalog extends Controller_Index 
{
    
public $needDesc = 'yes';
public $pageOne = true;
public $log = array();
private $price_from = 0;
private $price_to = 1;
private $sort_type;
private $sort_type_s;
private $direction;
private $filters="";
public static $prodsFilter = array();
public static $cat_ids;
public static $category;
public static $serie_id;
public static $serie_path;

public function before() {
    parent::before();
    $this->template->scripts[] = 'public/js/double-slider.js';
    $this->template->scripts[] = 'public/js/owl_min.js';
    if($this->request->param('filters')) { $this->filters = $this->request->param('filters'); $this->needDesc  = 'no'; }
    if($this->request->param('page')) $this->pageOne = false;
    if(isset($_GET['sort']) || isset($_GET['price_from']) || isset($_GET['price_to']))
        {
            $this->needDesc  = 'no';
            $this->pageOne = false;
        } 
    $cat =  $this->request->param('cat');
   

   self::$cat_ids = $this->get_where_cats($cat);
   

$this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/');
$this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
 
}

public function action_index()
{
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
    $this->template->center_block = array($content);


} 

public function action_cat()
{
    $cat =  $this->request->param('cat');
    $filters = $this->request->param('filters');

    if($cat== 'root'){
        $this->redirect(URL::base());
    }
    if($this->request->param('page')) $this->needDesc  = 'no';
    $category = ORM::factory('Category')->where('path', '=', $cat)->find();
    if(!$category->loaded()){
        $this->redirect('/404');
        
    }
    self::$category = $category; 
    $showproducts = false;
    if(!$category->has_children() || $category->cat_id == 53) {
    $showproducts = true;
    $this->get_params();
    $this->get_sort();
    $countprs=0;
    $prodfs = array();
   
    $flbrands = Widget::load('Flbrands');
    if($filters)
    {
        if(count(self::$prodsFilter)) $prodfs = self::$prodsFilter;

        
    }   
    if($filters && !count($prodfs) ) $products = array();
    else {
        $products = ORM::factory('Product')
            ->select('dataagromat.unit','dataagromat.meters','dataagromat.pieces')
                    ->join('dataagromat','LEFT')
                    ->on('product.code','=','dataagromat.code')
                    ->where('status', '<>', 0)
                    ->and_where('product.cat_id','IN',self::$cat_ids)
                    ->and_where('product.price','>=',$this->price_from)
                    ->and_where('product.price','<=',$this->price_to);

        if(count($prodfs))  $products->and_where('product.prod_id','IN',$prodfs);
        $countprs = $products->reset(FALSE)->count_all();
        if($countprs) {
        $pagination = Pagination::factory(array('total_items'=>$countprs,'items_per_page'=>21,'view'=> 'pagination/floating'));
        $products = $products->limit($pagination->items_per_page)
                            ->offset($pagination->offset)
                            ->order_by($this->sort_type,$this->direction)
                            ->find_all()->as_array();
        }

    }
    
    $shortDesc = array();  
    $vowels = array("<br>","<br/>","<br />","<p>","</p>");
    if($countprs) {              
    foreach($products as $p)
    {
        $tempStr = str_replace($vowels, " ", $p->content);
        $shortDesc[$p->prod_id] = Text::limit_words(strip_tags($tempStr), 25);

    }
    }
    $filters = Widget::load('Filterwd');
    $this->template->left_block[] = $filters;
    $this->template->left_block[] = $flbrands;
    $this->template->thisuri = 'cat/' . $cat;
    } // category->has_children
    elseif(!$showproducts) {
         $allbrs = Widget::load('Allbrands');
         //array_splice($this->template->left_block, 0);
         $this->template->left_block[] = $allbrs;
         $this->template->right_block = null;
      
    }
    
    $articles = Widget::load('Articles');
    $content = View::factory('/' . $this->theme . '/index/catalog/v_catalog_cat')
            ->bind('products', $products)
            ->bind('shortDesc', $shortDesc)
            ->bind('cat' , $cat)
            ->bind('countprs' , $countprs)
            ->bind('category' , $category)
            ->bind('sort_type' , $this->sort_type_s)
            ->bind('pagination',$pagination)
            ->bind('needDesc', $this->needDesc)
            ->bind('articles', $articles)
            ->bind('showproducts', $showproducts);

    
    
    // Выводим в шаблон
     $this->breadcrumbs[] = array('name' => $category->title, 'link' => '/catalog/cat/' . $category->path);
    $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
    
    if($category->meta_title)
        $this->template->page_title = $category->meta_title;
    else $this->template->page_title = $category->title . " " . $category->meta_title;
    if($category->meta_description)
        $this->template->description = $category->meta_description;
    else $this->template->description = $category->title .  ' по самой доступной цене в интернет-магазине “Трейдмаг”. Тел. (044) 331-38-59';
    if($category->meta_keywords)
        $this->template->keywords = $category->meta_keywords;
    else $this->template->keywords = $category->title . ', купить плитку и сантехнику Киев,интернет магазин сантехники и плитки';
    $this->template->page_caption = $category->title;
    $this->template->center_block = array($content);
    
    
    
}


public function action_brand()
{
$cat =  $this->request->param('cat');
$brand_p =  $this->request->param('brand');
 $filters = $this->request->param('filters');

if((!$cat) || (!$brand_p) )
    $this->redirect('/404');

if($this->request->param('page')) $this->needDesc  = 'no';
$category = ORM::factory('Category')->where('path', '=', $cat)->find();
self::$category = $category;
if($category->parent_id != 1)
{
    $this->needDesc  = 'no';
}

$this->get_params();
$this->get_sort();

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
if(!$brs)  $this->redirect('/404');
$countprs=0;
$prodfs = array();
$flbrands = Widget::load('Flbrands');
if($filters)
    {
       if(count(self::$prodsFilter)) $prodfs = self::$prodsFilter;
    }   
    if($filters && !count($prodfs) ) $products = null;
    else {
        
        $products = ORM::factory('Product')
                    ->select('dataagromat.unit','dataagromat.meters','dataagromat.pieces')
                    ->join('dataagromat','LEFT')
                    ->on('product.code','=','dataagromat.code')
                    ->where('product.status', '<>', 0)
                    ->and_where('product.cat_id','IN',self::$cat_ids)
                    ->and_where('product.brand_id','IN',$brs)
                    ->and_where('product.price','>=',$this->price_from)
                    ->and_where('product.price','<=',$this->price_to);

        if(count($prodfs))  $products->and_where('product.prod_id','IN',$prodfs);
        $countprs = $products->reset(FALSE)->count_all();
        if($countprs) {
        $pagination = Pagination::factory(array('total_items'=>$countprs,'items_per_page'=>21,'view'=> 'pagination/floating'));
        $products = $products->limit($pagination->items_per_page)
                            ->offset($pagination->offset)
                            ->order_by($this->sort_type,$this->direction)
                            ->find_all();
        }  
    }          


    $brand = ORM::factory('Brand')->where('path', '=', $brand_p)->find();
    $cb = count($brands);

    $this->template->page_caption = $category->title . " ";
    if($brand->loaded())
    {
        $series = $brand->series
                ->join('products')
                ->on('serie.serie_id', '=', 'products.series_id')
                ->and_where('cat_id','IN',self::$cat_ids)
                ->order_by('serie.title','ASC')
                ->distinct('serie.serie_id')
                ->find_all();
         $brand_desc = $brand->description;
         if($category->parent_id != 1 && $this->pageOne && !$this->filters)
         {
            $descbrandcat = ORM::factory('Descbrandcat')->where('brand_id', '=', $brand->brand_id)->and_where('cat_id','=',$category->cat_id)->find();
            if($descbrandcat->description)
            {
                $this->needDesc  = 'yes';
                $brand_desc = $descbrandcat->description;
            }
         }
        if($category->parent_id == 53) { $cat = 'plitka';}
        $this->template->thisuri = 'cat/' . $cat . '-' . $brand->path;

        if($brand->meta_title)  $this->template->page_title = $brand->meta_title;
        else {
            $this->template->page_title = $category->title . " " . $brand->title . " купить";
            $this->template->page_caption .= $brand->title;
        }
        if($brand->meta_description) $this->template->description = $brand->meta_description;
        else $this->template->description = $this->template->page_title .  ' по лучшей цене в интернет-магазине “Трейдмаг”. Тел. (044) 331-38-59';
        if($brand->meta_keywords)
            $this->template->keywords = $brand->meta_keywords;
        else $this->template->keywords = $brand->title . ', купить киев, интернет магазин сантехники и плитки';

    }
    elseif($cb>1)
    {

        $this->template->page_title = $category->title . " ";
        $this->template->thisuri = 'cat/' . $cat;
        $koma="";
        foreach($brands as $i=>$b)
        {
            if(($i+1)<$cb) $koma = " - ";
            else $koma = " ";
            $this->template->page_title .= $b->title.$koma;
            $this->template->page_caption .= $b->title . $koma;
            $this->template->keywords .= $b->title . ',';
        }

        $this->template->description = $this->template->page_title .  ' по лучшей цене в интернет-магазине “Трейдмаг”. Тел. (044) 331-38-59';
        $this->template->keywords .= 'купить киев, интернет магазин сантехники и плитки';
    }
    
    $shortDesc = array(); 
    $vowels = array("<br>","<br/>","<br />","<p>","</p>");
    if($countprs) {              
    foreach($products as $p)
    {
        $tempStr = str_replace($vowels, " ", $p->content);
        $shortDesc[$p->prod_id] = Text::limit_words(strip_tags($tempStr), 25);

    }
    }
    $content = View::factory('/' . $this->theme . '/index/catalog/v_catalog_brand')
            ->bind('series', $series)
            ->bind('shortDesc', $shortDesc)
            ->bind('products', $products)
            ->bind('cat' , $cat)
            ->bind('sort_type' , $this->sort_type_s)
            ->bind('pagination',$pagination)
            ->bind('brand',$brand_p)
            ->bind('brand_desc',$brand_desc)
            ->bind('countprs' , $countprs)
            ->bind('needDesc', $this->needDesc);
     $this->breadcrumbs[] = array('name' => $category->title, 'link' => '/cat/' . $category->path);
     $this->breadcrumbs[] = array('name' => $brand->title, 'link' => '/cat/' . $category->path . '-' . $brand->path);
     $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
     $this->template->keywords = mb_strtolower($this->template->keywords);    
   
    
   
    // Выводим в шаблон
    $filters = Widget::load('Filterwd');
    $this->template->left_block[] = $filters;  
    $this->template->left_block[] = $flbrands; 
    $this->template->title = $category->title;
    $this->template->center_block = array($content);
    
}

public function action_serie()
{
    $cat =  $this->request->param('cat');
    $brand_p =  $this->request->param('brand');
    $filters = $this->request->param('filters');
$serie_p =  $this->request->param('serie');
if((!$cat) || (!$brand_p) || (!$serie_p) )
    $this->redirect('/404');

  
 if($this->request->param('page')) $this->needDesc  = 'no';
    
$category = ORM::factory('Category')->where('path', '=', $cat)->find();
if(!$category->loaded()){
        $this->redirect();
    }
self::$category = $category;
$this->get_params();
$this->get_sort();
$brand = ORM::factory('Brand')->where('path', '=', $brand_p)->find();
$serie = ORM::factory('Serie')
        ->where('path', '=', $serie_p)
        ->and_where('brand_id','=',$brand->brand_id)
        ->find();
 
if(!$serie->loaded()) { $this->redirect('/404'); }
self::$serie_id = $serie->serie_id;
self::$serie_path = $serie->path;
$countprs=0;
$prodfs = array();
$flbrands = Widget::load('Flbrands');
if($filters)
{
    if(count(self::$prodsFilter)) $prodfs = self::$prodsFilter;
}   
if($filters && !count($prodfs) ) $products = null;
else {
    
    $products = ORM::factory('Product')
        ->select('dataagromat.unit','dataagromat.meters','dataagromat.pieces')
        ->join('dataagromat','LEFT')
        ->on('product.code','=','dataagromat.code')
                ->where('product.status', '<>', 0)
                ->and_where('product.cat_id','IN',self::$cat_ids)
                ->and_where('product.series_id','=',$serie->serie_id)
                ->and_where('product.price','>=',$this->price_from)
                ->and_where('product.price','<=',$this->price_to);

    
    if(count($prodfs))  $products->and_where('product.prod_id','IN',$prodfs);
    $countprs = $products->reset(FALSE)->count_all();
    if($countprs) {
    $pagination = Pagination::factory(array('total_items'=>$countprs,'items_per_page'=>21,'view'=> 'pagination/floating'));
    $this->log['products'] = $prodfs; 
    $products = $products->limit($pagination->items_per_page)
                        ->offset($pagination->offset)
                        ->order_by($this->sort_type,$this->direction)
                        ->find_all();
    }  
}    



    $this->breadcrumbs[] = array('name' => $category->title, 'link' => '/cat/' . $category->path);
    $this->breadcrumbs[] = array('name' => $brand->title, 'link' => '/cat/' . $category->path . '-' . $brand->path);
    if($category->parent_id == 53) { $catserie = 'plitka';} else $catserie = $category->path;
    $this->breadcrumbs[] = array('name' => $serie->title, 'link' => '/cat/' . $catserie . '-' . $brand->path . '/series-' . $serie->path);
    $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
    $shortDesc = array(); 
    $vowels = array("<br>","<br/>","<br />","<p>","</p>");
    if($countprs) {            
    foreach($products as $p)
    {
        $tempStr = str_replace($vowels, " ", $p->content);
        $shortDesc[$p->prod_id] = Text::limit_words(strip_tags($tempStr), 25);

    }
    }
    $content = View::factory('/' . $this->theme . '/index/catalog/v_catalog_serie')
            ->bind('count' , $count)
            ->bind('shortDesc', $shortDesc)
            ->bind('products', $products)
            ->bind('sort_type' , $this->sort_type_s)
            ->bind('pagination',$pagination)
            ->bind('countprs' , $countprs)
            ->bind('needDesc', $this->needDesc);

    $this->template->page_title = $category->title . " " . $brand->title ." коллекция ". $serie->title. " купить";
    $this->template->description =$this->template->page_title .  ' по лучшей цене в интернет-магазине “Трейдмаг”. Тел. (044) 331-38-59';
    $this->template->keywords = $serie->title . ', купить плитку и сантехнику Киев, интернет магазин сантехники и плитки';

    // Выводим в шаблон
    
    $filters = Widget::load('Filterwd');
    $this->template->left_block[] = $filters;  
    $this->template->left_block[] = $flbrands; 
    $this->template->title = $serie->title;
    $this->template->page_caption = $category->title . " от компании " . $brand->title . ", коллекция " . $serie->title;
    $this->template->center_block = array($content);
    if($category->parent_id > 1) { $parcat = ORM::factory('Category')->where('cat_id','=',$category->parent_id)->find(); $cat = $parcat->path;}
    $this->template->thisuri = 'cat/' . $cat. '-'. $brand->path . '/series-' . $serie->path;
}

// Просмотр товара
public function action_view() {


         $cat = $this->request->param('cat');
         $id =  $this->request->param('id');
         //$id = mysql_real_escape_string($id);



    //$product = ORM::factory('Product')->where('prod_id', '=', $id)->and_where('status', '!=', 0)->find();
    $product = ORM::factory('Product')
        ->select('dataagromat.unit','dataagromat.meters','dataagromat.pieces')
        ->join('dataagromat','LEFT')
        ->on('product.code','=','dataagromat.code')
        ->where('path', '=', $id)
        ->and_where('status', '!=', 0)
        ->find();
    $prodBrand = ORM::factory('Brand')->where('brand_id', '=', $product->brand_id)->find();
    $prodSerie = ORM::factory('Serie')->where('serie_id', '=', $product->series_id)->find();
    
    if(!$cat)
    {
        $category = $product->categories->find();

    }
    else  $category = ORM::factory('Category')->where('path', '=', $cat)->find();
    self::$category = $category;
    if (!$product->loaded()){
         $this->redirect('/404');
    }
    $characks = ORM::factory('Filters')
            ->select('filteroptions.option_title','option_title')
            ->join('filteroptions')->on('filters.filter_id','=','filteroptions.filter_id')
            ->join('froptionvalues')->on('froptionvalues.option_id','=','filteroptions.option_id')
            ->where('froptionvalues.prod_id','=',$product->prod_id)
            ->and_where('filteroptions.notshowinprod','<>',1);
    //$countcharacks = $characks->reset(FALSE)->count_all();
    //if($countcharacks>0) 
    $characks = $characks->find_all();
    $charar = array();
    foreach ($characks as $c)
    {
        if(isset($charar[$c->filter_title])) $charar[$c->filter_title] .= ', '.$c->option_title;
        else $charar[$c->filter_title] = $c->option_title;
    }
          
    //$attributes = ORM::factory('Prodattribut')->where('prod_id','=',$product->prod_id)->find();
    //$dataAgrom = ORM::factory('Dataagromat')->where('code','=',$product->code)->find();
    $anotherProducts = null;
    if($prodSerie->serie_id)
    $anotherProducts = ORM::factory('Product')
        ->select('dataagromat.unit','dataagromat.meters','dataagromat.pieces')
        ->join('dataagromat','LEFT')
        ->on('product.code','=','dataagromat.code')
        ->where('product.status', '<>', 0)
        ->and_where('product.series_id','=',$prodSerie->serie_id)
        ->and_where('product.prod_id','<>',$product->prod_id)
        ->and_where('product.brand_id','=',$product->brand_id)
        ->and_where('product.cat_id','=',$category->cat_id)
        ->limit(30)
        ->find_all();
    
    $shortDesc = array();  
    $dataAgr=array();
    $vowels = array("<br>","<br/>","<br />","<p>","</p>");
    if($anotherProducts) {              
    foreach($anotherProducts as $p)
    {
        $tempStr = str_replace($vowels, " ", $p->content);
        $shortDesc[$p->prod_id] = Text::limit_words(strip_tags($tempStr), 25);

    }
    
    }
     $images =  $product->images->find_all();
     $mainimage = ORM::factory('Image')->where('id', '=', $product->image_id)->find();
        $comproduct = Widget::load('prodcomments', array('param'=>$product->prod_id));
     $comments = $product->comments->find_all();
     $stockbool = 0;
     
    if(file_exists('public/uploads/import/stock.php')) {
        include_once(URL::base() .'public/uploads/import/stock.php');
        $stockbool = in_array($product->code,$stock)? 1 : 0;
    }
     $content = View::factory('/' . $this->theme . '/index/catalog/v_catalog_view', array(
        'product' => $product->as_array(),
        'comments' => $comments,
        'category' => $category,
        'images' => $images,
        'mainimage' => $mainimage,
        'anotherProducts' => $anotherProducts,
        'prodSerie' => $prodSerie,
        'prodBrand' => $prodBrand,
         'stock'=>$stockbool
        
     ))
            // ->bind('dataAgrom',$dataAgrom)
             ->bind('shortDesc', $shortDesc)
             //->bind('countcharacks',$countcharacks)
             ->bind('characks',$charar);
     
     
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
    if($category->title == 'Ламинат') $word = 'Ламинат ';
    if($category->title == 'Мозаика') $word = 'Мозаика ';
    $brandTitlear = explode('(', $prodBrand->title);
    $brandTitle = $brandTitlear[0];
    if(!stripos($product->title, $brandTitle)) $this->template->page_caption = $word ." ". $brandTitle ." ". $product->title;
    else $this->template->page_caption = $word ." ". $product->title;
    $this->template->page_title = $category->title . " "  . $product->title ." "/*. ' (Код товара: ТМ-' . $product->code . ") "*/ . $prodBrand->title;
    //if($product->price > 0) $this->template->page_title .= " Цена " . $product->price . " грн.";
    $this->template->page_title .=" купить Киев недорого"; 
    $this->template->description = $this->template->page_title .  ' по лучшей цене в интернет-магазине “Трейдмаг”. Тел. (044) 331-38-59';
    $this->template->keywords = $product->title .',купить киев,интернет магазин сантехники и плитки';
    $this->template->keywords = mb_strtolower($this->template->keywords);     
    // Breadcrubs
    $this->breadcrumbs[] = array('name' => $category->title, 'link' => '/catalog/cat/' . $category->path);
    $this->breadcrumbs[] = array('name' => $prodBrand->title, 'link' => '/cat/' . $category->path . '-' . $prodBrand->path);
    if($category->parent_id == 53) { $catserie = 'plitka';} else $catserie = $category->path;
    if($prodSerie->serie_id) $this->breadcrumbs[] = array('name' => $prodSerie->title, 'link' => '/cat/' . $catserie . '-' . $prodBrand->path . '/series-' . $prodSerie->path);
    $this->breadcrumbs[] = array('name' => $product->title, 'link' => '/catalog/view/' . $product->path);
    $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
          
}

public function action_checkcount() {
 if ( ! $this->request->is_ajax())
     {
        $this->redirect(URL::base());
        die;
     }
    
$id = ($this->request->param ('id'))?$this->request->param ('id'):null;
     $count = ($this->request->param ('count'))?$this->request->param ('count'):null;
    
   $prod = ORM::factory('Product')->where('prod_id', '=', $id)->find();
   if(!$prod->loaded()) die;
  
  $dataAgrom = ORM::factory('Dataagromat')->where('code','=',$prod->code)->find();
  if(!$dataAgrom->loaded()) 
  {
	    //$data['count'] = ceil($count);
       // $data['count_p'] = $data['count'];
       // echo json_encode($data);
	  die;
  }
  //$data = array();
  if($dataAgrom->unit == 'м2' && $dataAgrom->meters >0 && $dataAgrom->pieces > 0)
  {    
      
    $square = $dataAgrom->meters/$dataAgrom->pieces;

    if($dataAgrom->meters === 1) 
    {
        $data['count_m'] = $dataAgrom->meters;
        $data['count_p'] = $count*$dataAgrom->pieces;
    }    
    else {
        $count = ceil($count/$square)*$square;
        
        $data['count_m'] = round($count,3);
        $data['count_p'] = ceil($count/$square);
        $data['count'] = $data['count_m'];
    }
    
    echo json_encode($data);
    die;
  }
   if($dataAgrom->unit == 'шт' || $dataAgrom->unit == 'компл')
  { 
       
        $data['count'] = ceil($count);
        $data['count_p'] = $data['count'];
        echo json_encode($data);
        die;
  }   
   
   die;
     
}
public function action_checkpiece() {
 if ( ! $this->request->is_ajax())
     {
        $this->redirect(URL::base());
        die;
     }
    
$id = ($this->request->param ('id'))?$this->request->param ('id'):null;
     $count = ($this->request->param ('count'))?$this->request->param ('count'):null;
    
   $prod = ORM::factory('Product')->where('prod_id', '=', $id)->find();
   if(!$prod->loaded()) die;
  
  $dataAgrom = ORM::factory('Dataagromat')->where('code','=',$prod->code)->find();
  if(!$dataAgrom->loaded()) die;
  
  //$data = array();
  if($dataAgrom->unit == 'м2' && $dataAgrom->meters >0 && $dataAgrom->pieces > 0)
  {    
    $count = ceil($count);  
    $square = $dataAgrom->meters/$dataAgrom->pieces;
            
    $data['count_m'] = round($count*$square,3);
    $data['count_p'] = $count;
    if($dataAgrom->unit == 'м2') $data['count'] = $data['count_m'];
    if($dataAgrom->unit == 'шт') $data['count'] = $data['count_p'];
   
    
    echo json_encode($data);
    die;
  }
  
 
   die;
     
}
    
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
                if($cat->cat_id = 53 && $c->cat_id == 83 ) continue;
                $catids[] = $c->cat_id; 
            }
            $this->catids = $catids;
            return $catids;
        }
    }
    else{
        $catids[] = $cat->cat_id;
        return $catids;
    }
    $this->catids = null;
    return null;
}
 
protected function get_sort()
{
     $sort = 4;
    $this->direction = "ASC";
    if(isset($_GET['sort']))
    {
        $sort = (int) HTML::chars(Arr::get($_GET, 'sort',1));
        
    }
    $this->sort_type = "product.prod_id";
    switch($sort) 
    {
      case 1: $this->sort_type = "product.title"; $this->sort_type_s = "по названию"; break;
      case 2: $this->sort_type = "product.price"; $this->direction = "ASC"; $this->sort_type_s = "от дешевых к дорогим"; break;
      case 3: $this->sort_type = "product.price"; $this->direction = "DESC"; $this->sort_type_s = "от дорогих к дешевым"; break;
      case 4: $this->sort_type = "product.prod_id"; $this->direction = "DESC"; $this->sort_type_s = "последние добавленные"; break;

      default:  $this->sort_type = "product.prod_id"; break;
    }
}

protected function get_params()
{
    ////////***************************/////////
    $price_from =0; 
    $maxpricedb = ORM::factory('Product')->select(array(DB::expr('MAX(price)'), 'maxprice'));
    if(!empty(self::$cat_ids)) $maxpricedb = $maxpricedb->where('cat_id','IN',self::$cat_ids); 
    $maxpricedb = $maxpricedb->find()->as_array();
    
        if($maxpricedb['maxprice']) $maxprice = $maxpricedb['maxprice'];
            else $maxprice = 1;
    $this->price_to = $maxprice;
    if((isset($_GET['price_from'])) && (isset($_GET['price_to'])))
    {    
        
         $this->price_from = (int) HTML::chars(Arr::get($_GET, 'price_from',0));
         $this->price_to = (int) HTML::chars(Arr::get($_GET, 'price_to',$maxprice));
       
    }
}



/*****************protected function filter($filtersGet)
{
    $prods=array();
    $filtersId = array();
    
     $newFilterAr = explode("&",$filtersGet);

    if($filtersGet && count($newFilterAr))
    {   
        
        $prod_attrids = ORM::factory('Prodattribut');
        foreach ($newFilterAr as $k => $part)
        {
            if($part)
            {    
                if(strpos($part,'options') !== FALSE )
                {
                    $tmpar = explode(':',$part);
                    if(strpos($tmpar[1],'+') !== FALSE )
                        $filtersId = explode("+",$tmpar[1]);
                    else $filtersId[] = $tmpar[1];
                    if(count($filtersId)) $prod_opt_ids = ORM::factory('froptionvalues')->where('option_id','IN',$filtersId)->find_all(); 
                    if($prod_opt_ids)
                    {
                        foreach ($prod_opt_ids as $p)
                        {
                            $prods[] = $p->prod_id;
                        }
                    }   
                }
                else {
                    $tmpar = explode(':',$part);
                    if(strpos($tmpar[1],'-') !== FALSE )
                    {
                        $diapazon = explode('-', $tmpar[1]);
                        $prod_attrids->and_where($tmpar[0], '>=', $diapazon[0]);
                        $prod_attrids->and_where($tmpar[0], '<=', $diapazon[1]);
                    }
                    else {
                        $prod_attrids->and_where($tmpar[0], '=', $tmpar[1]);
                    }
                }
            }
        }
        
        $prod_attrids= $prod_attrids->find_all();
       
        if($prod_attrids)
                {
                    foreach ($prod_attrids as $p)
                    {
                        $prods[] = $p->prod_id;
                    }
                }   
    }
    return $prods;
}*********************************/

}
