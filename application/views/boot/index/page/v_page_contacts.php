<div class="row">
    <div class="col-md-5 col-sm-12 col-xs-12">
       <?=$contacts->content?>

    </div>
    <div class="col-md-7 col-sm-12 col-xs-12">
        <div class="cont-h1 form-title">Свяжитесь с нами:</div>
       <div class="form"> 
                <?php if(isset($errors)): ?>
                <div class="error"> 
                <?php foreach($errors as $e => $error):?>
                <b>Поле&nbsp;<?=$error?></b>
                <?php endforeach; ?>
                </div>  
                <?php endif; ?>   
                <?=Form::open('contacts.html')?>
           
                <label for="name">Имя:</label>
                <input type="text" id="name" name="name" class="" required/>
                 <label for="E-mail">E-mail:</label>
                 <input type="email" id="email" name="email" class=""/>
                 <label for="text">Текст сообщения:</label>      
                 <?=Form::textarea('text', '')?>
                   <div class="form-item">
                       <label>Введите код указанный на картинке:</label>
                       <?=Form::input('captcha')?>
                       <?=$captcha_image?>
                       <img alt="Обновить код" src="/public/img/refresh.png" class="refresh" title="Обновить код" />
                   </div>
                 <?=Form::submit('send', 'Отправить',array('class'=>'button'))?>
                 <?=Form::close()?>
        </div>
    </div>
</div>
<div id="map" style="height: 500px;position: relative;"></div>
<script>

    function initMap() {
        mapCenter = new google.maps.LatLng(50.471346, 30.342431);

        var mapProp = {
            center : mapCenter,
            zoom:14,
            mapTypeId:google.maps.MapTypeId.ROADMAP,
            disableDefaultUI:true,
            zoomControl:true,
            scaleControl:true,
            panControl:true,
            scrollwheel:false
        };
        var map;
        map = new google.maps.Map(document.getElementById('map'),mapProp);
        var marker = new google.maps.Marker({
            position:mapCenter,
            icon:'public/img/Map-marker-02.svg'
        });
        marker.setMap(map);

    }



</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBKgmSw9zyPAn5lYlGzztAjJQXkx-VHUnQ&callback=initMap"
        async defer></script>