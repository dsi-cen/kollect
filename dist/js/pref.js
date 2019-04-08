$(document).ready(function () {
    "use strict";
    $("#valajax").hide();
    var a = $("#flou").val(), e = $("#couche").val(), t = $("#tdon").val();
    $('#floutage option[value="' + a + '"]').prop("selected", !0), $('#choixcouche option[value="' + e + '"]').prop("selected", !0), $('#typedon option[value="' + t + '"]').prop("selected", !0)
}), $("#floutage").change(function () {
    "use strict";
    var a = $("#floutage option:selected").val();
    $("#flou").val(a)
}), $("#choixcouche").change(function () {
    "use strict";
    var a = $("#choixcouche option:selected").val();
    $("#couche").val(a)
}), $("#typedon").change(function () {
    "use strict";
    var a = $("#typedon option:selected").val();
    $("#tdon").val(a)
}), $(".idvar").click(function () {
    "use strict";
    var a = $(this).attr("id");
    $(".list-inline").find("li").removeClass("color1"), $(this).addClass("color1"), $("#sel").val(a)
}), $("#BttV").click(function () {
    "use strict";
    var a = $("input[name=radionom]:checked").val(), e = $("input[name=radiocontact]:checked").val(),
        t = $("#idm").val(), s = $("#sel").val(), l = $("#flou").val(), c = $("#couche").val(), o = $("#tdon").val(),
        i = $("#org").val();
    $("#valajax").show(), $("#mes").html(""), $.ajax({
        url: "modeles/ajax/membre/prefmembre.php",
        type: "POST",
        dataType: "json",
        data: {latin: a, idm: t, sel: s, flou: l, contact: e, couche: c, typedon: o, org: i},
        success: function (a) {
            "Oui" == a.statut ? $("#mes").html('<div class="alert alert-success mt-1">Modification enregistrée.</div>') : alert("Erreur !"), $("#valajax").hide()
        }
    })
}), $("#favatar").on("submit", function (a) {
    "use strict";
    a.preventDefault(), $("#mesa").html("");
    var e = $(this), t = window.FormData ? new FormData(e[0]) : null, s = null !== t ? t : e.serialize();
    $.ajax({
        url: "modeles/ajax/membre/avatar.php",
        type: "POST",
        dataType: "json",
        data: s,
        contentType: !1,
        processData: !1,
        success: function (a) {
            if ("Oui" == a.statut) {
                var e = $("#idm").val(), t = $("#prenom").val();
                $("#avatar").html('<img src="photo/avatar/' + t + e + '.jpg" width=36 height=36 alt=""/>'), $("#mesa").html(a.mes)
            } else $("#mesa").html(a.mes)
        }
    })
}),



    $("#BttMail").click(function () { // Mofification du mail du membre
    var a = $("#mail").val();
    $("#mes1").html(""), $.ajax({
        url: "modeles/ajax/membre/membremail.php",
        type: "POST",
        dataType: "json",
        data: {mail: a},
        success: function (a) {
            a.statut == 'ok' ? $("#mes1").html('<div class="alert alert-success mt-1">' + a.mess + '</div>') : $("#mes1").html('<div class="alert alert-danger mt-1">' + a.mess + '</div>');
        }
    })
}),



    $("#Bttmdp").click(function () {
    var a = $("#mdp").val(), e = $("#mdp1").val();
    $("#mes1").html(""), $.ajax({
        url: "modeles/ajax/membre/membremdp.php",
        type: "POST",
        dataType: "json",
        data: {mdp: a, mdpn: e},
        success: function (a) {
            "Oui" == a.statut ? $("#mes1").html('<div class="alert alert-success mt-1">Modification enregistrée.</div>') : alert("Erreur !")
        }
    })
}), $("#avatar").on("click", ".supavatar", function () {
    "use strict";
    $.ajax({
        url: "modeles/ajax/membre/supavatar.php", type: "POST", dataType: "json", success: function (a) {
            "Oui" == a.statut ? ($("#avatar").html('<img src="photo/avatar/usera.jpg" width=36 height=36 alt=""/>'), $("#mesa").html(a.mes)) : $("#mesa").html(a.mes)
        }
    })
});