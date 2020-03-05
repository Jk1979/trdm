$(function(){
        $('#multi').MultiFile({
            accept:'jpg|jpeg|gif|bmp|png', max:10, STRING: {
            remove:'<img src="/public/img/delete.png"> ',
            selected:'Выбраны: $file',
            denied:'Неверный тип файла: $ext!',
            duplicate:'Этот файл уже выбран:\n$file!'
        }});
    });
    
