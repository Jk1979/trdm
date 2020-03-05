<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Widgets_Topline extends Controller_Widgets 
{
      public function  before() {
        parent::before();
       
       
    }
	 // Главная страница
    
public function action_index()
{
      $pbrands = null;
      $sbrands = null;
      
        $cat = Request::initial()->param('cat');
    /*switch($cat) 
    {
            case 'santehnika': 
            $pbrands = $this->getBrandsByCatpath('plitka');
            break;
            case 'plitka': 
            $sbrands = $this->getBrandsByCatpath('santehnika');
            break;
            default:
            $pbrands = $this->getBrandsByCatpath('plitka');
            $sbrands = $this->getBrandsByCatpath('santehnika');
    }*/
            $pbrands = $this->getBrandsByCatpath('plitka');
            $sbrands = $this->getBrandsByCatpath('santehnika');
    $this->template->content = View::factory('widgets/w_topline')
            ->bind('pbrands', $pbrands)
            ->bind('sbrands', $sbrands);
} 
    
function getBrandsByCatpath($catpath)
{
   $category = ORM::factory('Category')->where('path', '=', $catpath)->find();
 if(!$category->loaded()){
    return false;
    }
$cats = $category->children();
if(count($cats) == 0) {
        return false;
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
if($brands->count()) return $brands;
    else return false;
}
}//class   