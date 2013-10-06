function getRaitings() {
    addr = $("#address").val();
    setStatus("Подождите..");
    clearRaitings();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "action.php",
        data: {address: addr},
        async: true,
        success: function(msg){
            if (msg.result != "success") {
                setStatus(msg.errorMessage);
                return;
            }
            localRaitings = msg.localRaitings;
            setStatus("");
            $("#mainRaiting").html("Рейтинг: " + msg.raiting);
            $("#socialRaiting").html("Социальный рейтинг: " + localRaitings.socialRaiting);
            $("#infrastructureRaiting").html("Рекреационный рейтинг: " + localRaitings.recreationRaiting);
            $("#recreationRaiting").html("Инфраструктурный рейтинг: " + localRaitings.infrastructureRaiting);
            $("#map").html("<a href='" + msg.map + "'>Карта</a>");
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