<?php if(count($search)==0 && count($auth)==0):?>
<div>По Вашему запросу ничего не найдено</div>
<?php else:?>
		<h1>Результаты поиска:</h1>
		<table width="100%" border="0" class="tblsearch"  cellspacing="0">
			<thead>
				<tr height="30">
					<th>Код товара</th><th>Название</th><th>Цена</th>
				</tr>
			</thead>
		<?php foreach ($search as $prod):?>
			<?php $cat = $prod->categories->find();?>
		<tr>
			<td style="width:100px;" ><?=$prod->code;?></td>
		<td ><?=HTML::anchor('product/' . $prod->path, $prod->title)?></td>
		<td width="50" align="center"><?=$prod->price?></td>
		</tr>
		<?php endforeach?>
			<?php if(count($auth)>0):?>
				<?php foreach ($auth as $prod):?>
					<?php $cat = $prod->categories->find();?>
				<tr>
					<td style="width:100px;" ><?=$prod->code;?></td>
				<td ><?=HTML::anchor('product/' . $prod->path, $prod->title)?></td>
				<td width="50" align="center"><?=$prod->price?></td>
				</tr>
				<?php endforeach?>
			<?php endif;?>	
		</table>
<?php endif;?>