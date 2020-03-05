jQuery(document).ready(function($){
    $('.commentlist li').each(function (i) {
        $(this).find('div.commentNumber').text('#'+ (i+1));
    })
    $('#commentform').on('click','#submit',function (e) {
        e.preventDefault();
        var comParent = $(this);
        $('.wrap_result').css('color','green').text('Сохранение комментария...')
            .fadeIn(500, function () {
                var data = $('#commentform').serializeArray();
                $.ajax({
                    url : $('#commentform').attr('action'),
                    data : data,
                    type : 'POST',
                    datatype : 'JSON',
                    headers : {'Cache-Control': 'no-cache, no-store, must-revalidate',
                        'Pragma': 'no-cache',
                        'Expires': '0'},
                    success : function(html){
                        var html = JSON.parse(html);
                        if(html.error){

                            $('.wrap_result').css('color','red').append('<br/><strong>Ошибка:</strong> ' + JSON.stringify(html.error));
                            $('.wrap_result').delay(4000).fadeOut(500);
                        }
                        else if(html.success){
                            console.log(html);
                            $('.wrap_result').append('<br/><strong>Сохранено!</strong>')
                                .delay(1000).fadeOut(200,function () {
                                if(html.data.parent_id > 0){
                                    comParent.parents('div#respond').prev().after('<ul class="children">' + html.comment + '</ul>');
                                }
                                else {
                                    if($('#comments').find('ol.commentlist').length != 0){

                                        $('ol.commentlist').append(html.comment);
                                    }
                                    else {
                                        $('#respond').before('<ol class="commentlist group">' + html.comment + '</ol>');
                                    }
                                }
                                $('#cancel-comment-reply-link').click();
                            });
                        }
                    },
                    error: function() {
                        $('.wrap_result').css('color','red').append('<br/><strong>Ошибка</strong> ')
                            .delay(1500).fadeOut(500,function () {
                            $('#cancel-comment-reply-link').click();
                        });
                    }
                });
            });
    });
});


/*
*    $('#commentform').on('click','#submit',function (e) {
 e.preventDefault();
 var comParent = $(this);
 $('.wrap_result').css('color','green').text('Сохранение комментария...')
 .fadeIn(500, function () {
 var data = $('#commentform').serializeArray();
 $.ajax({
 url : $('#commentform').attr('action'),
 data : data,
 type : 'POST',
 datatype : 'JSON',
 headers : {'Cache-Control': 'no-cache, no-store, must-revalidate',
 'Pragma': 'no-cache',
 'Expires': '0'},
 success : function(html){
 if(html.error){
 $('.wrap_result').css('color','red').append('<br/><strong>Ошибка:</strong> ' + html.error.join('<br/>'));
 $('.wrap_result').delay(2000).fadeOut(500);
 }
 else if(html.success){
 console.log(html);
 $('.wrap_result').append('<br/><strong>Сохранено!</strong>')
 .delay(1000).fadeOut(200,function () {
 if(html.data.parent_id > 0){
 comParent.parents('div#respond').prev().after('<ul class="children">' + html.comment + '</ul>');
 }
 else {
 if($('#comments').find('ol.commentlist').length != 0){

 $('ol.commentlist').append(html.comment);
 }
 else {
 $('#respond').before('<ol class="commentlist group">' + html.comment + '</ol>');
 }
 }
 $('#cancel-comment-reply-link').click();
 });
 }
 },
 error: function() {
 $('.wrap_result').css('color','red').append('<br/><strong>Ошибка</strong> ')
 .delay(1500).fadeOut(500,function () {
 $('#cancel-comment-reply-link').click();
 });
 }
 });
 });
 });
* */