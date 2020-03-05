<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title><?=$sitename?> | <?=$page_title?></title>
    <meta content="text/html; charset=utf8" http-equiv="content-type">
   <?php foreach($styles as $style): ?>
    <link href="<?php echo URL::base(); ?>public/css/<?php echo $style; ?>.css" 
    rel="stylesheet" type="text/css" />
    <?php endforeach; ?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">


    <?php if(isset($scripts)):?>
        <?php foreach ($scripts as $script):?>
        <?=html::script($script)?>
    <?php endforeach;?>
    <?php endif;?>
    <script>
        function clickHandler()
        {
            if(confirm('Действительно удалить!'))
            {
                return true;
            }
            return false;
        }
    </script>
    <?php if(Request::current()->controller()!='Import'):?>
    <script type="text/javascript" src="<?php echo URL::base(); ?>tiny_mce/tiny_mce.js"></script> 
    <script type="text/javascript"><!--
    tinyMCE.init({
    language : "ru",
    mode : "textareas",
    theme : "advanced",
    plugins : "safari, spellchecker, style, layer, table, save, advhr, advimage, advlink, emotions, iespell, inlinepopups, insertdatetime, preview, media, searchreplace, print, contextmenu,paste, directionality, fullscreen, noneditable, visualchars, nonbreaking, xhtmlxtras, template, pagebreak",
    theme_advanced_buttons1_add_before : "save,newdocument,separator",
    theme_advanced_buttons1_add : "fontselect, fontsizeselect",
    theme_advanced_buttons2_add : "separator, insertdate, inserttime, preview, separator, forecolor, backcolor",
    theme_advanced_buttons2_add_before: "cut, copy, paste, pastetext, pasteword, separator, search, replace, separator",
    theme_advanced_buttons3_add_before : "tablecontrols, separator",
    theme_advanced_buttons3_add : "emotions, iespell, media, advhr, separator, print, separator, ltr, rtl, separator, fullscreen",
    theme_advanced_buttons4 : "insertlayer, moveforward, movebackward, absolute, |, styleprops,|, spellchecker, cite, abbr, acronym, del, ins, attribs,|,visualchars, nonbreaking, template, blockquote, pagebreak,|, insertfile, insertimage",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    plugin_insertdate_dateFormat : "%Y-%m-%d",
    plugin_insertdate_timeFormat : "%H:%M:%S",
    theme_advanced_resize_horizontal : true,
    theme_advanced_resizing : true,
    apply_source_formatting : false,
    valid_elements : "*[*]", // разрешены все элемиенты html и их атрибуты
    remove_linebreaks : false,
    forced_root_block : '',
    spellchecker_languages : "+English=en,Danish=da, Dutch=nl, Finnish=fi, French=fr, German=de, Italian=it, Polish=pl, Portuguese=pt, Spanish=es, Swedish=sv"
    });
    // --></script> 
   <?php endif;?>
</head>

<body>

<div class="container-fluid">
    <div class="menu_admin"><?=$menu_admin?></div>
    <div id="main_content">

        <!-- Центральный блок-->
            <?php if(isset($center_block)):?>
            <div class="content">
                <?php if(isset($breadcrumbs)) echo $breadcrumbs;?>
                <?php if(isset($page_caption)):?>
                    <h2 class="page_title"><?=$page_caption?></h2>
                 <?php endif; ?>  
                    <?php if(isset($submenu)):?>
                    <div class="sub_menu"><?=$submenu?></div>
                    <?php endif; ?>
                    <?php foreach ($center_block as $block):?>    
                        <div class="c_block"><?=$block?></div>
                    <?php endforeach; ?>    
            </div>
        <?php endif; ?>
            <!-- /Центральный блок-->
    </div>
</div>
   
</body>