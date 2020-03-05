Директория  - <?php echo Request::current()->directory(); ?><br /><br />
Контроллер - <?php echo Request::current()->controller(); ?><br /><br />
Метод - <?php echo Request::current()->action(); ?>

<br/><br/>
<table width="100%">
    <tr>
        <td width="600" valign="top"><h3>Новые заказы:</h3> </td>
        <td valign="top"><?=$adminstat?></td>
    </tr>
</table>
<?php if(isset($stat)) echo $stat; ?>
