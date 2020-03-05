    <!-- Default panel contents -->
    <a class="btn btn-success margin-bottom margin-top" href="saninstall/add">Добавить услугу</a>
    <a class="btn btn-success margin-bottom margin-top" href="saninstall/pageinfo">Информация</a>
    <!-- Table -->
    <table class="table table-striped">
        <thead>
        <tr>
            <th>№ id</th>
            <th>Наименование</th>
            <th>Цена</th>
            <th>Ед.изм.</th>
            <th>Удалить</th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($items) && !empty($items)):?>
        <?php foreach ($items as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td><?=HTML::anchor('admin/saninstall/edit/'. $item->id, $item->title)?></td>
            <td><?=$item->price?></td>
            <td><?=$item->unit?></td>
            <td><?=HTML::anchor('admin/saninstall/delete/'.$item->id,'<i class="fa fa-trash"></i>',array("onclick"=>"return clickHandler();"))?></td>
        </tr>
        <?php endforeach;?>
        <?php else:?>
            <tr>
        <td>
            <h3>Данные отсутствуют</h3>
        </td>
            </tr>
        <?php endif;?>
        </tbody>
    </table>
