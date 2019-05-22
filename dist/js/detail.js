function cartept(a, e) {
    "use strict";
    map = new L.map("mapdetail");
    var t = new L.TileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
    });
    if (map.addLayer(t), e) {
        e = JSON.parse(e);
        var i = L.geoJson(e, {style: {color: "#03F", fillOpacity: .1, weight: 3}}).addTo(map);
        map.fitBounds(i.getBounds())
    } else {
        var o = a.split(",", 2), s = parseFloat(o[0]), r = parseFloat(o[1]);
        L.marker([s, r]).addTo(map);
        map.setView(new L.LatLng(s, r), 14)
    }
}

function cartecom(a) {
    "use strict";
    var e = $("#color").val(), t = $("#weight").val(), i = $("#opacity").val();
    proj4.defs("EPSG:2154", "+proj=lcc +lat_1=49 +lat_2=44 +lat_0=46.5 +lon_0=3 +x_0=700000 +y_0=6600000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs"), map = new L.map("mapdetail");
    var o = new L.TileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
    });
    map.addLayer(o);
    var s = {color: e, weight: t, fillOpacity: i}, r = L.Proj.geoJson(a, {style: s}).addTo(map);
    map.fitBounds(r.getBounds())
}

function adson(a) {
    "use strict";
    $.ajax({
        url: "modeles/ajax/saisie/attribution.php",
        type: "POST",
        dataType: "json",
        data: {idobs: a},
        success: function (a) {
            "Oui" == a.statut ? document.location.href = "index.php?module=membre&action=ajoutson" : alert("Erreur ! ")
        }
    })
}

function modfiche(a) {
    "use strict";
    $.ajax({
        url: "modeles/ajax/saisie/attribution.php",
        type: "POST",
        dataType: "json",
        data: {idfiche: a},
        success: function (a) {
            "Oui" == a.statut ? document.location.href = "index.php?module=saisie&action=saisie" : alert("Erreur ! ")
        }
    })
}

function adphoto(a) {
    "use strict";
    $.ajax({
        url: "modeles/ajax/saisie/attribution.php",
        type: "POST",
        dataType: "json",
        data: {idobs: a},
        success: function (a) {
            "Oui" == a.statut ? document.location.href = "index.php?module=membre&action=ajoutphoto" : alert("Erreur ! ")
        }
    })
}

function supobs(a) {
    "use strict";
    $("#dia1").modal("show")
}

function supfiche(a) {
    "use strict";
    $("#dia2").modal("show")
}

function suppressiont(a) {
    "use strict";
    $.ajax({
        url: "modeles/ajax/saisie/supobs.php",
        type: "POST",
        dataType: "json",
        data: {idligne: "tous", idobs: a},
        success: function (a) {
            "Oui" == a.statut ? location.reload() : alert("Erreur : problème lors de la suppression")
        }
    })
}

function suppressionfiche(a) {
    "use strict";
    $.ajax({
        url: "modeles/ajax/saisie/supfiche.php",
        type: "POST",
        dataType: "json",
        data: {idfiche: a},
        success: function (a) {
            "Oui" == a.statut ? location.reload() : alert("Erreur : problème lors de la suppression")
        }
    })
}

function supph(a) {
    "use strict";
    $("#idphoto").val(a), $("#dia3").modal("show")
}

function supson(a) {
    "use strict";
    $("#idson").val(a), $("#dia6").modal("show")
}

function suppressionphoto(a) {
    "use strict";
    $.ajax({
        url: "modeles/ajax/photo/supphoto.php",
        type: "POST",
        dataType: "json",
        data: {idphoto: a},
        success: function (a) {
            "Oui" == a.statut ? location.reload() : alert("Erreur : problème lors de la suppression")
        }
    })
}

function suppressionson(a) {
    "use strict";
    $.ajax({
        url: "modeles/ajax/son/supson.php",
        type: "POST",
        dataType: "json",
        data: {idson: a},
        success: function (a) {
            "Oui" == a.statut ? location.reload() : alert("Erreur : problème lors de la suppression")
        }
    })
}

function valid(a) {
    "use strict";
    var e = $("#idobs").val();
    $.ajax({
        url: "modeles/ajax/validation/infovali.php",
        type: "POST",
        dataType: "json",
        data: {idobs: e, rvali: a},
        success: function (e) {
            "Oui" == e.statut ? ("non" == a && $("#histo").html(e.histo), $("#com").html(e.com), $("#dia4").modal("show")) : alert("Erreur : problème lors dans les requêtes")
        }
    })
}

function validation() {
    var a = $("#idobs").val(), e = $("#vali").val(), t = $("#rq").val(), i = $("#new").val(), o = $("#observa").val();
    $.ajax({
        url: "gestion/modeles/ajax/validation/validation.php",
        type: "POST",
        dataType: "json",
        data: {idobs: a, choix: e, rq: t, nouv: i, observa: o},
        success: function (a) {
            if ("Oui" == a.statut) switch (e) {
                case"1":
                    $("#retourvali").html('<i class="fa fa-check-circle val1"></i> Donnée considérée certaine, très probable');
                    break;
                case"2":
                    $("#retourvali").html('<i class="fa fa-check-circle val2"></i> Donnée considérée comme probable');
                    break;
                case"3":
                    $("#retourvali").html('<i class="fa fa-check-circle val3"></i> Donnée considérée comme peu vraisemblable');
                    break;
                case"4":
                    $("#retourvali").html('<i class="fa fa-check-circle val4"></i> Donnée invalide');
                    break;
                case"5":
                    $("#retourvali").html('<i class="fa fa-check-circle val5"></i> Donnée en attente de validation');
                    break;
                case"6":
                    $("#retourvali").html('<i class="fa fa-check-circle"></i> Donnée en attente de validation')
            } else $("#mes").html(a.mes), $("#dia5").modal("show")
        }
    })
}

var map;
$(document).ready(function () {
    "use strict";
    var a = $("#pre").val(), e = $("#sel").val(), t = $("#flou").val(), i = $("#rvali").val();
    "aucun" != t && 3 != a ? $.ajax({
        url: "modeles/ajax/observation/detail.php",
        type: "POST",
        dataType: "json",
        data: {flou: t, sel: e},
        success: function (a) {
            "Oui" == a.statut ? ("point" == t && cartept(a.point, a.contour), "commune" != t && "maille" != t && "dep" != t || cartecom(a.carto)) : alert("erreur")
        }
    }) : $("#mapdetail").html("<p>Pas de carte pour cette observation.</p>"), "oui" == i && valid(i)
}), $("#BttVcom").click(function () {
    "use strict";
    var a = $("#idobscom").val(), e = $("#idmcom").val(), t = $("#idmor").val(), i = $("#commentaire").val();
    "" != i ? $.ajax({
        url: "modeles/ajax/observation/commentaire.php",
        type: "POST",
        dataType: "json",
        data: {idm: e, idobs: a, com: i, idmor: t},
        success: function (a) {
            "Oui" == a.statut ? location.reload() : alert("Erreur ! lors insertion table")
        }
    }) : ($("#mes").html("Aucun commentaire de saisie !"), $("#dia5").modal("show"))
}), $(document).ready(function () {
    $(".popup-gallery").magnificPopup({
        delegate: "a",
        type: "image",
        tLoading: "Loading image #%curr%...",
        mainClass: "mfp-img-mobile",
        gallery: {enabled: !0, navigateByImgClick: !0, preload: [0, 1]},
        image: {
            tError: '<a href="%url%">The image #%curr%</a> est absente..', titleSrc: function (a) {
                return a.el.attr("title")
            }
        }
    })
}), $("#bttdia1").click(function () {
    "use strict";
    var a = $("#idobs").val();
    suppressiont(a)
}), $("#bttdia2").click(function () {
    "use strict";
    var a = $("#idfiche").val();
    suppressionfiche(a)
}), $("#bttdia3").click(function () {
    "use strict";
    var a = $("#idphoto").val();
    suppressionphoto(a)
}), $("#bttdia6").click(function () {
    "use strict";
    var a = $("#idson").val();
    suppressionson(a)
}), $("#vali").change(function () {
    "use strict";
    var a = $("#vali").val();
    1 != a && 2 != a || ($("#dia4").modal("hide"), validation())
}), $("#BttV").click(function () {
    "use strict";
    $("#dia4").modal("hide"), validation()
}), $("#BttVr").click(function () {
    "use strict";
    $("#dia4").modal("hide");
    var a = $("#idobs").val(), e = $("#rq").val();
    "" != e ? $.ajax({
        url: "modeles/ajax/validation/commentaire.php",
        type: "POST",
        dataType: "json",
        data: {idobs: a, rq: e},
        success: function (a) {
            "Oui" == a.statut ? $("#dia4").modal("hide") : alert("Erreur ! lors insertion commentaire")
        }
    }) : ($("#mes").html("Aucun commentaire de saisie !"), $("#dia5").modal("show"))
});