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
            $("#Raiting").html("<b>Рейтинг: " + msg.raiting+",</b>");
            $("#socialRaiting").html("Социальный рейтинг: " + localRaitings.socialRaiting);
            $("#infrastructureRaiting").html("Рекреационный рейтинг: " + localRaitings.recreationRaiting);
            $("#recreationRaiting").html("Инфраструктурный рейтинг: " + localRaitings.infrastructureRaiting);

            $("#x").html("x: " + msg.coords.longitude);
            $("#y").html("y: " + msg.coords.latitude);

            $("#nearest").html("<h3>По близости:</h3>");
            for(key in msg.nearest){
                dst = (Math.round(100*msg.nearest[key]))+0.5;
                dst = (dst-0.5)/100;
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
var placemark;
var pols;
function initMap(x,y){
    $('#map').html('');

    mmap = new ymaps.Map ("map", {
            center: [x, y], 
            zoom: 16
        });
    placemark = new ymaps.Placemark([x, y], {}, {
        preset: 'twirl#redIcon' 
    });
    mmap.geoObjects.add(placemark); 

    reloadWarmMap();
    mmap.behaviors.events.add('dragend', function(){ setTimeout ( function(){reloadWarmMap()}, 500 ) } );
    mmap.behaviors.events.add('zoomchange', function(){ setTimeout ( function(){reloadWarmMap()}, 500 ) } );
    //mmap.behaviors.events.add('mouseup', function(){ reloadWarmMap() } );

}
function loadAreas(){
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "action.php",
        data: {
            func   : 'getPoints'
        },
        async: true,
        success: function(msg){
            if (msg.result != "success") {
                alert(msg.errorMessage);
                return;
            }
            points = msg.points;
            reloadWarmMap();
        },
        error: function(jqXHR, textStatus, errorThrown ) {
            setStatus("Возникла ошибка. Обратитесь, пожалуйста, к разработчику.");
        }
    });
}
function reloadWarmMap(){
    if ( !points ){
        loadAreas();
        return;
    }


    bounds = mmap.getBounds();

    k=1;
    while(points[k].X == points[0].X) k++;
    dx = Math.abs( (points[k].X - points[0].X) / 2 );
    k=1;
    while(points[k].Y == points[0].Y) k++;
    dy = Math.abs( (points[k].Y - points[0].Y) / 2 );
    
    if ( pols ){
        for (k=0; k< pols.length; k++){
            mmap.geoObjects.remove( pols[k] );
        }
    }
    i = 1;
    pols= [];
    Xes = [];
    Yes = [];
    while(points[i]){
        x   = parseFloat(points[i].X);
        y   = parseFloat(points[i].Y);
        if ( x < bounds [0][0]-dx || x > bounds[1][0]+dx || y < bounds[0][1]-dy || y > bounds [1][1]+dy ){
            i++;
            continue;
        }
        val = points[i][2];
            
        pol = new ymaps.Polygon([[
            [x-dx,y+dy],
            [x+dx,y+dy],
            [x+dx,y-dy],
            [x-dx,y-dy]
            ]],
            {
                hintContent:"Рейтинг: " + val
            },
            {
                strokeWidth: 0.1
            });
        pols[pols.length] = pol;
        Xes[i] = x;
        Yes[i] = y;
        mmap.geoObjects.add(pol);
        i++;
    }
}