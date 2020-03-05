<br/>
<table width="100%" border="0" class="tbl"  cellspacing="0">
    <thead>
        <tr height="30">
            <th>Дата</th><th>Категория</th><th>Название</th><th>Функции</th>
        </tr>
    </thead>
<?php foreach ($articles as $article):?>
<tr>
<td align="center" width="100"><?=$article->date?></td>
<td align="left" ><?=$article->cat_title?></td>
<td ><?=HTML::anchor('admin/articles/edit/'. $article->id, $article->title)?></td>
<td width="100" align="center">
<?=HTML::anchor('admin/articles/edit/'. $article->id, HTML::image('public/img/edit.png'))?>

<?=HTML::anchor('admin/articles/delete/'. $article->id, HTML::image('public/img/delete.png'),
            array("onclick"=>"return clickHandler();"))?>

</td>
</tr>
<?php endforeach?>

</table>

<br/>
<p align="right">
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
    
<?=HTML::anchor('admin/articles/add', 'Добавить')?>
</p>
<?=$pagination?>