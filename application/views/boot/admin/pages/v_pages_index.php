<?php if($pages->count() > 0):?>
<br/>
<table width="100%" border="0" class="tbl"  cellspacing="0">
    <thead>
        <tr height="30">
            <th>Алиас</th>
            <th>Название</th>
            <th>Функции</th>
        </tr>
    </thead>
<?php foreach ($pages as $page):?>
<tr>
    <td width="200" align="center" ><?=$page->alias?></td>
    <td ><?=HTML::anchor('admin/pages/edit/'. $page->page_id, $page->title)?></td>
    <td width="100" align="center">

    <?=HTML::anchor('page/'. $page->alias, HTML::image('public/img/see.png'), array('target' => '_blank'))?>
    
    <?=HTML::anchor('admin/pages/edit/'. $page->page_id, HTML::image('public/img/edit.png'))?>

    <?=HTML::anchor('admin/pages/delete/'. $page->page_id, HTML::image('public/img/delete.png'),
            array("onclick"=>"return clickHandler();"))?>

</td>
</tr>
<?php endforeach;?>

</table>

<?php else:?>
<p align="center">Нет страниц</p>
<?php endif?>

<br/>
<p align="right">
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>

<?=HTML::anchor('admin/pages/add', 'Добавить')?>
</p>
<?=$pagination?>