var valchoix;
$(document).ready(function () {
    "use strict";
    $("#verif").hide()
}), $("#choix").change(function () {
    "use strict";
    var e = $("#choix option:selected").val();
    "NR" != e ? ($("#disc").val(e), $.ajax({
        url: "modeles/ajax/validation/choixobser.php",
        type: "POST",
        dataType: "json",
        data: {sel: e},
        success: function (e) {
            if ("Oui" == e.statut) {
                var t;
                $.each(e.stade, function (e, a) {
                    t += "<option value=" + a + ">" + e + "</option>"
                }), $("#stade").html(t), $("#verif").show()
            } else $("#verif").hide()
        }
    })) : $("#disc").val("")
}), $("#BttV").click(function () {
    "use strict";
    var e = $("#choix option:selected").val(), t = $("#dec").val(), a = $("#stade").val();
    "" != t && "" != a ? t < 36 && ($("#liste").html("<progress></progress>"), $.ajax({
        url: "modeles/ajax/validation/verif.php",
        type: "POST",
        dataType: "json",
        data: {sel: e, stade: a, dec: t},
        success: function (e) {
            "Oui" == e.statut ? $("#liste").html(e.liste) : $("#liste").html("")
        }
    })) : alert("Choisir un stade et un nombre de décade")
});