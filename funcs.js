var glob_msg;
function getRaitings() {
    addr = $("#address").val();
    scools_percent  = $('#scools_percent').text();
    metro_percent   = $('#metro_percent').text();
    fuel_percent    = $('#fuel_percent').text();
    zog_percent     = $('#zog_percent').text();
    setStatus("Подождите..");
    clearRaitings();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "action.php",
        data: {
            address: addr,
            scools_percent : scools_percent,
            metro_percent  : metro_percent,
            fuel_percent   : fuel_percent,
            zog_percent    : zog_percent
        },
        async: true,
        success: function(msg){
            glob_msg = msg;
            if (msg.result != "success") {
                setRaiting(msg.errorMessage);
                return;
            }
            localRaitings = msg.localRaitings;
            setStatus("");
            $("#mainRaiting").html("Рейтинг: " + msg.raiting);
            $("#socialRaiting").html("Социальный рейтинг: " + localRaitings.socialRaiting);
            $("#infrastructureRaiting").html("Рекреационный рейтинг: " + localRaitings.recreationRaiting);
            $("#recreationRaiting").html("Инфраструктурный рейтинг: " + localRaitings.infrastructureRaiting);

            $("#x").html("x: " + msg.coords.longitude);
            $("#y").html("y: " + msg.coords.latitude);

            $("#nearest").html("<h4>По близости:</h4>");
            for(key in msg.nearest){
                dst = Math.round(100*parseInt(msg.nearest[key]))/100;
                $("#nearest").html($("#nearest").html() + key + ': ' + dst + ' км<br>');
            }


            //$("#map").html("<a href='" + msg.map + "'>Карта</a>");


            initMap(msg.coords.longitude,msg.coords.latitude);
        },
        error: function(jqXHR, textStatus, errorThrown ) {
            setStatus("Возникла ошибка. Обратитесь, пожалуйста, к разработчику.");
        }
    });
}

function clearRaitings() {
    $("#mainRaiting").html("");
    $("#socialRaiting").html("");
    $("#infrastructureRaiting").html("");
    $("#recreationRaiting").html("");
}

function setStatus(status) {
    if (status == "") {
        status = "&nbsp;";
    }
    $("#status").html(status);
}

var mmap;
var points;
function initMap(x,y){
    $('#map').html('');

    mmap = new ymaps.Map ("map", {
            center: [x, y], 
            zoom: 16
        });
    var placemark = new ymaps.Placemark([x, y], {}, {
        preset: 'twirl#redIcon' 
    });
    mmap.geoObjects.add(placemark); 

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "action.php",
        data: {
            func   : 'getPoints'
        },
        async: true,
        success: function(msg){
            points = msg;
            if (msg.result != "success") {
                setRaiting(msg.errorMessage);
                return;
            }
            
            pts = msg.points;
            point = [];
            rts = [];
            dx = 0.03;
            dy = 0.03;
            for(i=0; i< pts.length; i++){
                point = pts[i];
                myPolygon = new ymaps.Polygon([
                            // Координаты вершин внешней границы многоугольника.
                    [
                    [point.x-dx,point.y-dy],
                    [point.x+dx,point.y-dy],
                    [point.x+dx,point.y+dy],
                    [point.x-dx,point.y+dy]
                    ]
                        ], {
                            //Свойства
                            hintContent: "Многоугольник"
                        }, {
                            // Опции.
                            // Цвет заливки (красный)
                            fillColor: '#FF0000',
                            // Цвет границ (синий)
                            strokeColor: '#0000FF',
                            // Прозрачность (полупрозрачная заливка)
                            opacity: 0.6,
                            // Ширина линии
                            strokeWidth: 5,
                            // Стиль линии
                            strokeStyle: 'shortdash'
                        });
                    mmap.geoObjects.add(myPolygon);


            }
            point = msg.points[0];
            point.x = parseFloat(point.x);
            point.y = parseFloat(point.y);
            rts[0] = new ymaps.Polygon([
                    [point.x-dx,point.y-dy],
                    [point.x-dx,point.y+dy],
                    [point.x+dx,point.y+dy],
                    [point.x+dx,point.y-dy]
                ]);
            mmap.geoObjects.add(rts[0]);

        },
        error: function(jqXHR, textStatus, errorThrown ) {
            setStatus("Возникла ошибка. Обратитесь, пожалуйста, к разработчику.");
        }
    });

}