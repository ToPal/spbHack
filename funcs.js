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
            
            points = msg;
        },
        error: function(jqXHR, textStatus, errorThrown ) {
            setStatus("Возникла ошибка. Обратитесь, пожалуйста, к разработчику.");
        }
    });

}