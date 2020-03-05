<h2>Статистика</h2>

<p><a href="?interval=1">За сегодня</a></p>
<p><a href="?interval=7">За последнюю неделю</a></p>

<table style="border: 1px solid silver;">

<tr>
    <td style="border: 1px solid silver;">Дата</td>
    <td style="border: 1px solid silver;">Уникальных посетителей</td>
    <td style="border: 1px solid silver;">Просмотров</td>
</tr>

<?php foreach($result as $row):?>
    <tr>
			     <td style="border: 1px solid silver;"><?=$row['date']?></td>
			     <td style="border: 1px solid silver;"><?=$row['hosts']?></td>
			     <td style="border: 1px solid silver;"><?=$row['views']?></td>
    </tr>
    <?php endforeach;?>