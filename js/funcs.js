var glob_msg;
function getRaitings() {
    var addr = $("#address").val();
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
var gmap;
var points;
var placemark;
var pols = [];
var maxVal;
var minVal;
var vLen
var dVal;
var dColor;
var cLen;
var polColor;
function initMap(x,y){
    $('#map').html('');
  //Загружаем яндокарты
    mmap = new ymaps.Map ("map", {
            center: [x, y], 
            zoom: 16
        });
    placemark = new ymaps.Placemark([x, y], {}, {
        preset: 'twirl#redIcon' 
    });
    mmap.geoObjects.add(placemark);

  /*подгружаем и рисуем теплокубики*/
    reloadWarmMap();
  /*следим чтоб кубики не убегали*/
    //mmap.behaviors.events.add('dragend', function(){ setTimeout ( function(){reloadWarmMap()}, 500 ) } );
    //mmap.behaviors.events.add('zoomchange', function(){ setTimeout ( function(){reloadWarmMap()}, 500 ) } );
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

var canvas;
var local_points;
var pix_delta;
function reloadWarmMap(){
    if ( !points ){
        loadAreas();
        return;
    }
    var bounds = mmap.getBounds();
    var k=1;
    while(points[k].X == points[0].X) k++;
    var dx = Math.abs( (points[k].X - points[0].X) / 2 );
    while(points[k].Y == points[0].Y) k++;
    var dy = Math.abs( (points[k].Y - points[0].Y) / 2 );
    //console.log("dx = " + dx + "  dy = " + dy);
    
    if ( pols ){
        for (k=0; k< pols.length; k++){
            mmap.geoObjects.remove( pols[k] );
        }
    }

    var RGBPoints = [];
    RGBPoints[0] = [252, 0,   0];
    RGBPoints[1] = [252, 247, 0];
    RGBPoints[2] = [0,   218, 26];
    maxVal = parseFloat(points[0][2]);
    minVal = parseFloat(points[0][2]);
    
    i = 1;
    while(points[i]){
        var val = parseFloat(points[i][2]);
        if ( val > maxVal ) maxVal = val;
        if ( val < minVal ) minVal = val;
        i++;
    }
    var nSteps   = 20;
    vLen     = (maxVal - minVal);
    dVal     = vLen / nSteps; 
    cLen     = getLineLength(RGBPoints);
    dColor   = cLen / nSteps;

    var i = 1;
    while(points[i]){
        var x   = parseFloat(points[i].X);
        var y   = parseFloat(points[i].Y);

        if ( x < bounds [0][0]-dx || x > bounds[1][0]+dx || y < bounds[0][1]-dy || y > bounds [1][1]+dy ){
            //i++;
            //continue;
        }

        val      = parseFloat(points[i][2]);
        var cValStep = Math.round((val - minVal) / dVal);
        var ColorX   = dColor * cValStep;
        var cColor   = lineToFunction (RGBPoints,ColorX);
        //console.log("val="+val + "  cValStep="+cValStep + "  ColorX="+ColorX + "  cColor="+cColor);
        polColor = "rgb(" + Math.round(cColor[0]) + "," + Math.round(cColor[1]) + "," + Math.round(cColor[2]) + ")";

        var pol = new ymaps.Polygon([[
            [x-dx,y+dy],
            [x+dx,y+dy],
            [x+dx,y-dy],
            [x-dx,y-dy]
            ]],
            {
                hintContent:"Рейтинг: "+val + "  cValStep="+cValStep + "  ColorX="+ColorX + "  cColor="+cColor
            },
            {
                strokeWidth: 0.1,
                strokeColor: polColor,
                fillColor: polColor,
                opacity:0.7
            });
        pols[pols.length] = pol;
        mmap.geoObjects.add(pol);

        i++;
    }
}

function globalToLocal(point){
    var projection = mmap.options.get('projection');
    var global_in_pixels = projection.toGlobalPixels(point, mmap.getZoom());
    //console.log("global_in_pixels = " + global_in_pixels);
    var screen_in_pixels = mmap.converter.globalToPage(global_in_pixels);
    //console.log("screen_in_pixels = " + screen_in_pixels);
    var local_in_pixels = [];
    local_in_pixels[0] = screen_in_pixels[0] - $("#map").position().left;
    local_in_pixels[1] = screen_in_pixels[1] - $("#map").position().top;
    return local_in_pixels;
}

function getLineLength(line){
    var lenFull = 0;
    var lenSeg,i,j;
    for (i = 1; i < line.length; i++){
        lenSeg = 0;
        for (j= 0; j< line[i].length; j++){
            lenSeg += (line[i][j] - line[i-1][j]) * (line[i][j] - line[i-1][j]);
        }
        lenSeg = Math.sqrt(lenSeg);
        lenFull += lenSeg;
    }
    return lenFull;
}

function lineToFunction(line,X){
    var lenFull = 0;
    var coords = [];
    var lenSeg,i,j;
    if (X <= 0)
        return line[0];
    if (X >= getLineLength(line))
        return line[line.length-1];
    for (i= 1; i< line.length; i++){
        lenSeg = 0;
        for (j= 0; j< line[i].length; j++){
            lenSeg += (line[i][j] - line[i-1][j]) * (line[i][j] - line[i-1][j]);
        }
        lenSeg = Math.sqrt(lenSeg);
        lenFull += lenSeg;
        if ( X < lenFull ){
            for (j= 0; j< line[i].length; j++){
              coords[j] = line[i-1][j]  +  (line[i][j] - line[i-1][j]) / lenSeg   *   (X - lenFull + lenSeg );
            }
            return coords;
        }
    }
    if (coords == [])
        alert("X="+X);
}