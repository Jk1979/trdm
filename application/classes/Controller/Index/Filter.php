<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Index_Filter extends Controller_Index 
{
    public function before() {
        parent::before();

        
 
        
        
    $this->breadcrumbs[] = array('name' => 'Главная', 'link' => '/');
    $this->breadcrumbs[] = array('name' => 'Каталог', 'link' => '/catalog');
    $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        
    }

    public function action_index()
    {
        $filter =  $this->request->param('filter');
            $filter = mysql_real_escape_string ($filter);
            
        if(strlen($filter)>0) // Если картинок больше одной
			{
				$options = explode('-',$filter);
			}
                        
        $products = ORM::factory('Product')
                ->join('froptionvalues')
                ->on('product.prod_id', '=', 'froptionvalues.prod_id')
                ->where('froptionvalues.option_id','in',$options)
                ->and_where('froptionvalues.option_value', '=', 1)
                ->group_by('product.title')
                ->order_by('product.title', 'ASC')
                ->find_all();
        
        $categories = ORM::factory('category')->fulltree()->as_array();
        
        $content = View::factory('/' . $this->theme . 'index/catalog/v_filter_index')
                ->bind('categories', $categories)
                ->bind('products', $products);
        
        
       
        $topproducts = Widget::load('topproducts');
        $this->template->page_title='Каталог';
        $this->template->page_caption='Каталог';
        $this->template->center_block = array($content, $topproducts);
        
        //$this->template->right_block = null;
    } 
    
    public function action_cat()
    {
        
        
       // $cat = (int) $this->request->param('cat');
        $cat =  $this->request->param('cat');
            $cat = mysql_real_escape_string ($cat);
       
        // Получаем список продукций
       // $category = ORM::factory('category')->where('cat_id', '=', $cat)->find();
        $category = ORM::factory('category')->where('path', '=', $cat)->find();

        if(!$category->loaded()){
            $this->redirect();
        }
        
        $count = $category->products->where('status', '<>', 0)->count_all();
        $pagination = Pagination::factory(array('total_items'=>$count,'items_per_page'=>2));
        $prods = array();
        $products = $category->products
                ->where('status', '<>', 0)
                ->limit($pagination->items_per_page)
                ->offset($pagination->offset)
                ->find_all();
        $prs = $category->products
                ->select('prod_cats.prod_id')
                ->where('status', '<>', 0)
                ->find_all();
        foreach ($prs as $p)
        {
            $prods[] = $p->prod_id;
        }
        if(count($prods))
        $brands = ORM::factory('brand')
                ->join('products')
                ->on('brand.brand_id', '=', 'products.brand_id')
                ->where('products.prod_id','in',$prods)
                ->group_by('brand.title')
                ->order_by('brand.title', 'ASC')
                ->find_all();
            
         
        
        //$products = $category->products->where('status', '!=', 0)->find_all();
       // $this->breadcrumbs[] = array('name' => $category->title, 'link' => '/catalog/cat/c' . $category->cat_id);
        $this->breadcrumbs[] = array('name' => $category->title, 'link' => '/catalog/cat/' . $category->path);
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        
        $content = View::factory('/' . $this->theme . 'index/catalog/v_catalog_cat', array(
            'products' => $products,
            'cat' => $cat,
            'pagination' =>$pagination,
            'brands' =>$brands,
            
        ));
        
        // Выводим в шаблон
       
        $this->template->title = $category->title;
        $this->template->page_title = $category->title;
        $this->template->page_caption = $category->title;
        $this->template->center_block = array($content);
        $this->template->block_right = null;  
        $filter = Filter::factory();
        $filter->loadFiltersOptions($category->cat_id);
        $this->template->filter = $filter->render();
    }


    public function action_brand()
    {
    $cat =  $this->request->param('cat');
        $cat = mysql_real_escape_string ($cat);
    $brand_p =  $this->request->param('brand');
        $brand_p = mysql_real_escape_string ($brand_p);
    if((!$cat) || (!$brand_p) )
        $this->redirect();
    
    $category = ORM::factory('category')->where('path', '=', $cat)->find();
    if(!$category->loaded()){
            $this->redirect();
        }
    $prs = $category->products
                ->select('prod_cats.prod_id')
                ->where('status', '<>', 0)
                ->find_all();
        foreach ($prs as $p)
        {
            $prods[] = $p->prod_id;
        }    
    $brand = ORM::factory('brand')->where('path', '=', $brand_p)->find();
    $series = $brand->series
            ->join('products')
            ->on('serie.serie_id', '=', 'products.series_id')
            ->where('products.brand_id', '=', $brand->brand_id)
            ->and_where('products.prod_id','IN', $prods)
            ->distinct('serie.serie_id')
            ->find_all();
    
        $count = $category->products->where('brand_id','=',$brand->brand_id)->and_where('status', '<>', 0)->count_all();
        $pagination = Pagination::factory(array('total_items'=>$count,'items_per_page'=>2));
        $products = $category->products
                ->where('status', '<>', 0)
                ->and_where('brand_id','=',$brand->brand_id)
                ->limit($pagination->items_per_page)
                ->offset($pagination->offset)
                ->find_all();
        
        //$products = $category->products->where('status', '!=', 0)->find_all();
       // $this->breadcrumbs[] = array('name' => $category->title, 'link' => '/catalog/cat/c' . $category->cat_id);
        $this->breadcrumbs[] = array('name' => $category->title, 'link' => '/cat/' . $category->path);
        $this->breadcrumbs[] = array('name' => $brand->title, 'link' => '/cat/' . $category->path . '-' . $brand->path);
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        
        $content = View::factory('/' . $this->theme . 'index/catalog/v_catalog_brand', array(
            'products' => $products,
            'cat' => $cat,
            'series' => $series,
            'brand' => $brand_p,
            'category' => $category,
            'pagination' =>$pagination,));
        
        // Выводим в шаблон
       
        $this->template->title = $category->title;
        $this->template->page_title = $category->title;
        $this->template->page_caption = $category->title;
        $this->template->center_block = array($content);
        $this->template->block_right = null;  
    }

    public function action_serie()
    {
    $cat =  $this->request->param('cat');
        $cat = mysql_real_escape_string ($cat);
    $brand_p =  $this->request->param('brand');
        $brand_p = mysql_real_escape_string ($brand_p);
    $serie_p =  $this->request->param('serie');
        $serie_p= mysql_real_escape_string ($serie_p);
    if((!$cat) || (!$brand_p) || (!$serie_p) )
        $this->redirect();
    
    $category = ORM::factory('category')->where('path', '=', $cat)->find();
    if(!$category->loaded()){
            $this->redirect();
        }
    $prs = $category->products->where('status', '<>', 0)->find_all();
     foreach ($prs as $p)
        {
            $prods[] = $p->prod_id;
        }
    $brand = ORM::factory('brand')->where('path', '=', $brand_p)->find();
    $serie = ORM::factory('serie')
            ->where('path', '=', $serie_p)
            ->and_where('brand_id','=',$brand->brand_id)
            ->find();
    $count = $serie->products->where('status', '<>', 0)->and_where('prod_id','IN',$prods)->count_all();
    $pagination = Pagination::factory(array('total_items'=>$count,'items_per_page'=>2));
    $products = $serie->products
            ->where('status', '<>', 0)
            ->and_where('prod_id','IN',$prods)
            ->limit($pagination->items_per_page)
            ->offset($pagination->offset)
            ->find_all();
        $this->breadcrumbs[] = array('name' => $category->title, 'link' => '/cat/' . $category->path);
        $this->breadcrumbs[] = array('name' => $brand->title, 'link' => '/cat/' . $category->path . '-' . $brand->path);
        $this->breadcrumbs[] = array('name' => $serie->title, 'link' => '/cat/' . $category->path . '-' . $brand->path . '/' . $serie->path);
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        
        $content = View::factory('/' . $this->theme . 'index/catalog/v_catalog_serie', array(
            'products' => $products,
            'count' => $count,
            'pagination' =>$pagination,));
        
        // Выводим в шаблон
       
        $this->template->title = $serie->title;
        $this->template->page_title = $serie->title;
        $this->template->page_caption = $serie->title;
        $this->template->center_block = array($content);
        $this->template->block_right = null;  
    }

// Просмотр товара
    public function action_view() {
        
        //$cat = (int) $this->request->param('cat');
             $cat = $this->request->param('cat');
        $id =  $this->request->param('id');
             $id = mysql_real_escape_string ($id);

        
        
        //$product = ORM::factory('Product')->where('prod_id', '=', $id)->and_where('status', '!=', 0)->find();
        $product = ORM::factory('Product')->where('path', '=', $id)->and_where('status', '!=', 0)->find();
        $prodBrand = ORM::factory('brand')->where('brand_id', '=', $product->brand_id)->find();
        $prodSerie = ORM::factory('serie')->where('serie_id', '=', $product->series_id)->find();
        if(!$cat)
        {
            $category = $product->categories->find();
            
        }
        else  $category = ORM::factory('category')->where('path', '=', $cat)->find();
        
        if (!$product->loaded()){
            $this->redirect();
        }
         $images =  $product->images->find_all();
         $mainimage = ORM::factory('Image')->where('id', '=', $product->image_id)->find();
         $content = View::factory('/' . $this->theme . 'index/catalog/v_catalog_view', array(
            'product' => $product->as_array(),
            'comments' => $product->comments->find_all()->as_array(),
            'category' => $category,
            'images' => $images,
            'mainimage' => $mainimage,
        ));
        
        $this->template->center_block = array($content);

        $comproduct = Widget::load('prodcomments', array('param'=>$product->prod_id));
        switch($comproduct->status()) 
        {
          case 302: $this->redirect(Request::detect_uri()); break;
          default:  $this->template->center_block[] = $comproduct; break;
        }
        // Выводим в шаблон
        $this->template->title = $product->title;
        
        
        $this->template->page_caption = $product->title;
        
        $this->template->page_title = $product->title;
        $this->template->block_right = null;
        // Breadcrubs
        $this->breadcrumbs[] = array('name' => $category->title, 'link' => '/catalog/cat/' . $category->path);
        $this->breadcrumbs[] = array('name' => $prodBrand->title, 'link' => '/cat/' . $category->path . '-' . $prodBrand->path);
        $this->breadcrumbs[] = array('name' => $prodSerie->title, 'link' => '/cat/' . $category->path . '-' . $prodBrand->path . '/' . $prodSerie->path);
        $this->breadcrumbs[] = array('name' => $product->title, 'link' => '/catalog/view/' . $product->path);
        $this->template->breadcrumbs = Breadcrumb::generate($this->breadcrumbs);
        
    }

   
}
