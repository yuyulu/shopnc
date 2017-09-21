$(function() {
    $("#area_info").on("click",
    function() {
        $.areaSelected({
            success: function(a) {
                $("#area_info").val(a.area_info).attr({
                    "data-areaid": a.area_id,
                    "data-areaid2": a.area_id_2 == 0 ? a.area_id_1: a.area_id_2
                })
            }
        })
    })
});