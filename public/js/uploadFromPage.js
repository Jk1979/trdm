$(document).ready(function() { 

var  output = [];
// Максимальное количество загружаемых изображений за одни раз
var maxFiles = $('#maxFiles').val()?$('#maxFiles').val():1;

// Кнопка выбора файлов
var defaultUploadBtn = $('#files');
// Область информер о загруженных изображениях - скрыта
$('#uploaded-files').hide();
$('#upload-button').hide();
// При нажатии на кнопку выбора файлов
   defaultUploadBtn.on('change', function() {
         // Заполняем массив выбранными изображениями
         var files = $(this)[0].files;
         // Проверяем на максимальное количество файлов
      if (files.length <= maxFiles) {
         // Передаем массив с файлами в функцию загрузки на предпросмотр
         loadInView(files);
              // Очищаем инпут файл путем сброса формы
              // Или вот так $("#uploadbtn").replaceWith( $("#uploadbtn").val('').clone( true ) );
             $("#files").replaceWith( $("#files").val('').clone( true ) );

      } else {
         alert('Вы не можете загружать больше '+maxFiles+' изображений!'); 
         files.length = 0;
      }
   });
   
function loadInView(files) {
    // Для каждого файла
    $.each(files, function(index, file) {

    // Несколько оповещений при попытке загрузить не изображение
    if (!files[index].type.match('image.*')) {

      alert('Только картинки !!!');

    } else {

// Проверяем количество загружаемых элементов
   if((output.length+files.length) <= maxFiles) {
      // показываем область с кнопками
      $('#upload-button').css({'display' : 'block'});
   } 
   else { alert('Вы не можете загружать больше '+maxFiles+' изображений!'); return; }

   // Создаем новый экземпляра FileReader
   var fileReader = new FileReader();
          // Инициируем функцию FileReader
    fileReader.onload = (function(file) {
               
    return function(e) {
//                    var img  = resizeImg(fileReader.result, 200, 150);
//                    output.push({name : file.name, value : img});
//                    $('[id^=img]').remove();
//                    addImage(-1);
                    output.push({name : file.name, value : fileReader.result});
                    addImage((output.length-1));

        }; // function (e)
                  
    })(files[index]);
         // Производим чтение картинки по URI
         fileReader.readAsDataURL(file);
                     }
      }); // each
      return false;
   }
   function resizeImg(Img, Width, Height)
   {
       var tempImg = new Image();
                    tempImg.src = Img;						
                    var MAX_WIDTH = Width; // желаемые ширина
                    var MAX_HEIGHT = Height; // и высота
                    var tempW = tempImg.width;
                    var tempH = tempImg.height;
                    if (tempW > tempH) {
                            if (tempW > MAX_WIDTH) {
                                    tempH *= MAX_WIDTH / tempW;
                                    tempW = MAX_WIDTH;
                            }
                    } else {
                            if (tempH > MAX_HEIGHT) {
                                    tempW *= MAX_HEIGHT / tempH;
                                    tempH = MAX_HEIGHT;
                            }
                    } // получаем размеры с соблюдением пропорций
                
                    var canvas = document.createElement('canvas'); 
                    canvas.width = tempW;
                    canvas.height = tempH;
                    var ctx = canvas.getContext("2d");
                    ctx.drawImage(tempImg, 0, 0, tempW, tempH); // меняем размеры
                    var datan = canvas.toDataURL("image/jpeg",0.7);	// 70% качество картинки
                    return(datan);
   }
// Процедура добавления эскизов на страницу
   function addImage(ind) {
     //  Если индекс отрицательный значит выводим весь массив изображений
      if (ind < 0 ) { 
      start = 0; end = output.length; 
      } else {
      // иначе только определенное изображение 
      start = ind; end = ind+1; } 
      // Оповещения о загруженных файлах
     if(output.length == 0) {
            // Если пустой массив скрываем кнопки и всю область
            $('#upload-button').hide();
         } else if (output.length == 1) {
            $('#upload-button span').html("Был выбран 1 файл");
         } else {
            $('#upload-button span').html(output.length+" файлов были выбраны");
         }
      // Цикл для каждого элемента массива
      for (i = start; i < end; i++) {
         // размещаем загруженные изображения
        if($('#dropped-files > .thumb').length <= maxFiles) { 
            var span = document.createElement('span');
            span.innerHTML = ['<img class="thumb" src="', output[i].value,
                              '" title="', escape(output[i].name), '"/>'].join('');
            $('#list').append('<div id="img-' + i + '"></div>');              
            document.getElementById('img-'+ i).insertBefore(span, null);
            $('#img-' + i).append('<br/><span class="upload-img-del" id="del-' + i + '">Удалить</span>');
        
        }
      }
    
      return false;
   }   
// Удаление только выбранного изображения 
   $("#dropped-files").on("click","span[id^='del']", function(e) {
      
      
      var elid = $(this).attr('id');
      // создаем массив для разделенных строк
      var temp = new Array();
      // делим строку id на 2 части
      temp = elid.split('-');
      // получаем значение после тире тоесть индекс изображения в массиве
      output.splice(temp[1],1);
      
      var ind = temp[1];
      
      // Удаляем старые эскизы
    
      $('[id^=img]').remove();
     
     // Обновляем эскизи в соответсвии с обновленным массивом
      addImage(-1);  
     
      if(output.length == 0) {
            // Если пустой массив скрываем кнопки и всю область
            $('#upload-button').hide();
         } else if (output.length == 1) {
            $('#upload-button span').html("Был выбран 1 файл");
         } else {
            $('#upload-button span').html(output.length+" файлов были выбраны");
         }
             
             
   });
       
       
 // Функция удаления всех изображений
   function restartFiles() {
    // Удаляем все изображения на странице и скрываем кнопки
    $('#upload-button').hide();
    $('[id^=img]').remove();
   
      // Очищаем массив
    output.length = 0;

    return false;
   }      

   $('#dropped-files #upload-button .delete').click(restartFiles);
   //document.getElementById('files').addEventListener('change', handleFileSelect, false);
   
   // Загрузка изображений на сервер
   $('#upload-button .upload').click(function() {
      
      var dir = $('#imagedir').val()?$('#imagedir').val():'images';
      // Для каждого файла
      $.each(output, function(index, file) { 
         // загружаем страницу и передаем значения, используя HTTP POST запрос 
         $.post('/admin/choiceimg/uploadimage/' + dir, output[index], function(data) {
         
            var fileName = output[index].name;
           
            
            // Формируем в виде списка все загруженные изображения
            // data формируется в upload.php
            var dataSplit = data.split(':');
            if(dataSplit[1] == 'загружен успешно') {
               $('#uploaded-files').append('<li><a href="images/'+ dir +'/'+dataSplit[0]+'">'+fileName+'</a> загружен успешно</li>');
            
				if(output.length > 1 && maxFiles >1)
				{
					var images = $("#anotherImages");
					var newval = images.val();
					newval += fileName + '/';
					images.val(newval);	
				}
				else {	
						// записываем имя файла картинки в hidden input с id - selectedFile 
						$('#selectedFile').val("public/uploads/"+ dir +"/" + fileName);         
				}
            } else {
               $('#uploaded-files').append('<li>'+data+'. Имя файла: '+output[index].name+'</li>');
            }
            
         });
      });
      // Показываем список загруженных файлов
      $('#uploaded-files').show();
      //setTimeout(3000,restartFiles());
       return false;
   });
   // Delete the selected image from page and database 
   $("#selectedImageView").on("click","span[id='deleteImageView']", function(e) {
       $('#selectedImageView').empty();
       $('#selectedFile').val("");
       $("#files").attr('disabled', false);
   });

   
    $('#uploadServer').click(function (e){
        var dir = $('#imagedir').val()?$('#imagedir').val():'images';
        wnd = window.open('/admin/choiceimg/index/' + dir, 'wnd', "left=50,top=100, width=" + (screen.width/2) + ",height=" + (screen.height-250) + ",resizable=yes,scrollbars=yes,status=yes");
        wnd.focus();
    });
   }); // end