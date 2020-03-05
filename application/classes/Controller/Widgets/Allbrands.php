<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Widgets_Allbrands extends Controller_Widgets 
{
	 // Главная страница
    
public function action_index()
{
$cat = Request::initial()->param('cat');
if(!$cat)
{
     $this->redirect();
}
$category = ORM::factory('Category')->where('path', '=', $cat)->find();
     if(!$category->loaded()){
        $this->redirect();
    }
$cats = $category->children();
if(count($cats) == 0) {
        $this->redirect();
    }
$catsids =array();    
foreach($cats as $c)
{
    $catsids[] = $c->cat_id; 
}
$brands = array();

$brands = ORM::factory('Brand')
            ->join('products')
            ->on('brand.brand_id', '=', 'products.brand_id')
            ->where('products.cat_id','IN',$catsids)
            ->and_where('brand.status','=',1)
            ->group_by('brand.title')
            ->order_by('brand.title', 'ASC')
            ->find_all();
    $brs=array();
    $i = 0;

    foreach ($brands as $b)
        {
            $brs[$i]["title"] = $b->title;
            $brs[$i]["path"] = "cat/" . $cat . "-" . $b->path;
            $brs[$i]["check"] = false;
            $i++;
        }

   
  $this->template->content = View::factory('widgets/w_allbrands')
            ->set('flbrands', $brs);
    } 
}//class   