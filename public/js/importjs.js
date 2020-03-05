$(document).ready(function() {


    $('#uploadsrv').click(function (e){
        e.preventDefault();

        var dir = $('#importdir').val() ? $('#importdir').val() : 'import';
       console.log(dir);
        wnd = window.open('/admin/importfile/index/' + dir, 'wnd', "left=50,top=100, width=" + (450) + ",height=" + (screen.height-350) + ",resizable=yes,scrollbars=yes,status=yes");
        wnd.focus();
        });
        $('#files').click( function (e){ 
            $('#importfrm').submit(); 
        });
        $('#cleantable').click( function (e){ 
            $.ajax({
                url: '/ajaximport/cleantable',
                dataType: 'text',
                type: 'GET',
                error: function (jqXHR, textStatus, errorThrown) {alert('clean table error');},
                success:
                function (data)
                {
                  
                  alert(data)

                }
        });
        });
});