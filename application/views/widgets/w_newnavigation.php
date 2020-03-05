
<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 hidden-sm hidden-xs">
    <div class="parent__menublock">
    <button class="cat_button catmainbut">Каталог товаров<i class="fa fa-bars float_rt"></i></button>
    <div class="side">
      <ul class="menu">
        <?php foreach($categories as $cat): ?>
               <?php if($cat->parent_id==0):  continue; endif;?>
            <?php if($cat->has_children()): ?>
              <li class="menu__list"><?=HTML::anchor('cat/' . $cat->path,  $cat->title)?>
                <?php if(count($cat->children)<=5) $wd = count($cat->children)*170; else $wd = 694;?>
                <div class="menu__drop plitka" <?php if(isset($wd)) echo 'style="width:'. $wd. 'px"'?>>
                  <?php foreach($cat->children as $l): ?>
                    <a href="<?=URL::base() . 'cat/' . $l->path?>" title="<?=$l->title?>">
                      <div class="menu__subitem">
                        <div class="menu__imgbox"><img src="<?php echo URL::base(); ?>public/img/nav/<?=$l->image?>" alt="<?$l->title?>" /></div>
                        <p class="newmenu_title"><?=$l->title?></p>
                      </div>
                    </a>
                  <?php endforeach; ?>
                </div>
              </li>
<?php elseif($cat->parent_id==1):?>
    <li>
        <?=HTML::anchor('cat/' . $cat->path,  $cat->title)?>
    </li>
    <?php endif;?>
        <?php endforeach; ?>
            </ul>
            </div>    
    </div>
    <?php if(Request::initial()->controller() == 'Main'):?>
    <div >
    <a href="/cat/santehnika-devit__chehiya_">
    <img src="/public/img/freedevit.jpg" alt="бесплатная доставка">
    </a>
    </div>
    <?php endif;?>
</div>
<div class="col-sm-12 col-xs-12 hidden-lg hidden-md">
<div class="parent__menublock">
   <button class="cat_button catmainbutmob">Каталог товаров<i class="fa fa-bars float_rt"></i></button>
    <div class="side">
      <ul class="menu">
        <?php foreach($categories as $cat): ?>
               <?php if($cat->parent_id==0):  continue; endif;?>
                <?php if($cat->has_children()): ?>
                  <li class="menu_small_li"><i class="fa fa-bars float_rt smallmenu_icon"></i><?=HTML::anchor('cat/' . $cat->path,  $cat->title)?>
                  <ul class="menu__dropsmall"> 
                    <?php foreach($cat->children as $l): ?>
                       <li><a href="<?=URL::base() . 'cat/' . $l->path?>" title="<?=$l->title?>"><?=$l->title?></a></li>
                    <?php endforeach; ?>
                 </ul> 
                 </li>
                 <?php elseif($cat->parent_id==1):?> 
                  <li><?=HTML::anchor('cat/' . $cat->path,  $cat->title)?></li>
                <?php endif;?>   
        <?php endforeach; ?>
          <li><?=HTML::anchor('saninstall', 'Установка сантехники')?></li>
     </ul>
    </div>
    </div>
</div>