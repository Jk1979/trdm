$(function(){
    $('.icon').click(function() {
        var src = $(this).attr('src');
        src = src.replace(/_small/g, '');
        src = src.replace(/small_/g, '');
        $('#main_icon img').attr('src', src);
        $('#main_icon a').attr('href', src);
    });
});