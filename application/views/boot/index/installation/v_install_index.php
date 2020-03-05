<div class="row">
    <div class="col-md-12  col-sm-12">
        <h1 class="page_title"><?=$data['title']?></h1>
        <p>
            <?=$data['text']?>
        </p>
    </div>
    </div>
<div class="row">
    <div class="col-sm-12">
    <h2>Цены на сантехнические работы </h2>
    <table width="100%" border="0" cellspacing="0" class="tblcharacks">
        <thead>
        <tr>
            <th class="col-sm-8">Наименование работы</th>
            <th class="col-sm-2">Цена, грн</th>
            <th class="col-sm-2">Еденица измерения</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($prices as $price):?>
            <tr class="tr-prods">
                <td class="col-sm-8"><?=$price->title;?></td>
                <td class="col-sm-2 text-center"><?=$price->price;?></td>
                <td class="col-sm-2 text-center"><?=$price->unit;?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    </div>

</div>
