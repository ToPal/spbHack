<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script type="text/javascript" src="js/jquery-2.0.3.js"></script>
    <!-- 1. Подключим библиотеку jQuery (без нее jQuery UI не будет работать) -->
    <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>-->
    <!-- 2. Подключим jQuery UI -->
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/redmond/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

    <script src="js/bootstrap/dist/js/bootstrap.min.js"></script>
    <link href="js/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <script type="text/javascript" src="js/funcs.js"></script>
    <script type="text/javascript" src="js/fabric.all.min.js"></script>

    <script src="http://api-maps.yandex.ru/2.0-stable/?load=package.full&lang=ru-RU" type="text/javascript"></script>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=visualization&sensor=false">
    </script>

    <title>Городские зоны комфорта</title>

    <style>
        body{
            font-size:25px;
            color:gray;
        }
        input{
            border-radius: 3px;
        }
        .container{
            margin-top:20px;
        }
        .slider{
            font-size:15px;
        }
        #search_field{
            padding:20px;
        }

        #params li{
            margin-top: 15px;
        }
        #map{
            margin:10px;
            margin-left:20px;
            border:3px solid #ddd;
            border-radius:5px;
        }  
        #desc{
            margin-top:30px;
            font-size:20px;
        }

    </style>
</head>

<body>
    <div class="container">

            <div id="search_field" style="clear:both; text-align:center;">
                <input id="address" type="text" value="Фрунзенская, 23"/> 
                <input id="btnCalculate" type="button" class="btn btn-primary btn-large" value="Смотреть" onclick="getRaitings()"><br>
            </div>

            <div id="sidebar" style="float:left; width: 300px;">

                <div id="params"> 
                    <ul class="nav nav-tabs nav-stacked">
                        <li>
                            Школы (<span id="scools_percent"></span> %)
                            <div id="schools_slider" class="slider"></div>
                        </li>
                        <li>
                            Метро (<span id="metro_percent"></span> %)
                            <div id="metro_slider" class="slider"></div>
                        </li><!--
                        <li>
                            АЗС (<span id="fuel_percent"></span> %)
                            <div id="fuel_slider" class="slider"></div>
                        </li>-->
                        <li>
                            Безопасность  (<span id="zog_percent"></span> %)
                            <div id="zog_slider" class="slider"></div>
                        </li>
                    </ul>
                </div>

                <div id="desc">
                    <div id = "Raiting"></div>
                    <div id = "socialRaiting"></div>
                    <div id = "infrastructureRaiting"></div>
                    <div id = "recreationRaiting"></div>
                    <div id = "x"></div>
                    <div id = "y"></div>
                    <div id = "nearest"></div>
                </div>

            </div>

            <div     id="map"        style="float:left; width:800px; height: 600px;"></div>
    </div>
</body>
<script type="text/javascript">
    $('document').ready(function(){ymaps.ready(function(){getRaitings();})});

    $("#schools_slider").slider({
        slide  : function(){ set_sl_val($(this),"scools_percent"); },
        change : function(){ set_sl_val($(this),"scools_percent"); },
        create : function(){ set_sl_val($(this),"scools_percent"); },
        min : 0,
        max : 100,
        value: 50
    });

    $("#metro_slider").slider({
        slide  : function(){ set_sl_val($(this),"metro_percent"); },
        change : function(){ set_sl_val($(this),"metro_percent"); },
        create : function(){ set_sl_val($(this),"metro_percent"); },
        min : 0,
        max : 100,
        value: 50
    });/*
    $("#fuel_slider").slider({
        slide  : function(){ set_sl_val($(this),"fuel_percent"); },
        change : function(){ set_sl_val($(this),"fuel_percent"); },
        create : function(){ set_sl_val($(this),"fuel_percent"); },
        min : 0,
        max : 100,
        value: 50
    });*/
    $("#zog_slider").slider({
        slide  : function(){ set_sl_val($(this),"zog_percent"); },
        change : function(){ set_sl_val($(this),"zog_percent"); },
        create : function(){ set_sl_val($(this),"zog_percent"); },
        min : 0,
        max : 100,
        value: 50
    });

    function set_sl_val(slider,input_id){
        $('#'+input_id).text(slider.slider('option','value'));
    }
</script>

<!-- Yandex.Metrika counter --
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter22531141 = new Ya.Metrika({id:22531141,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/22531141" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
!-- /Yandex.Metrika counter -->

</html>