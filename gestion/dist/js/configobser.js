function aide() {
    "use strict";
    $(this).hasClass("btn-info") ? ($(this).removeClass("btn-info").addClass("btn-success"), $("#btn-aide-txt", this).text("Cacher"), $("#infoaide").show()) : ($(this).removeClass("btn-success").addClass("btn-info"), $("#btn-aide-txt", this).text("Aide"), $("#infoaide").hide())
}

function tri(t) {
    var e = [], o = [];
    $("#" + t + " option:selected").each(function () {
        e.push([$(this).val(), $(this).data("order")]), o.push([$(this).text(), $(this).data("order")])
    }), e.sort(function (t, e) {
        return t[1] - e[1]
    }), o.sort(function (t, e) {
        return t[1] - e[1]
    });
    var i = [], a = [];
    i[0] = "", a[0] = "";
    for (var s = 0; s < e.length; s++) i[s] = e[s][0];
    for (var s = 0; s < o.length; s++) a[s] = o[s][0];
    var c = [i, a];
    return c
}

$(document).ready(function () {
    "use strict";
    var t = 0;
    $(".multit").multiselect({
        buttonClass: "btn btn-outline-secondary",
        buttonContainer: '<div class="dropdown" />',
        maxHeight: 400,
        templates: {li: '<li><a class="dropdown-item" tabindex="0"><label style="padding-left: 10px;width: 100%"></label></a></li>'},
        onChange: function (e, o) {
            o ? (t++, $(e).data("order", t)) : $(e).data("order", "")
        },
        buttonText: function (t) {
            if (0 === t.length) return "Aucune sélection";
            if (t.length > 8) return t.length + " sélectionnés";
            var e = [];
            t.each(function () {
                e.push([$(this).text(), $(this).data("order")])
            }), e.sort(function (t, e) {
                return t[1] - e[1]
            });
            for (var o = "", i = 0; i < e.length; i++) o += e[i][0] + ", ";
            return o.substr(0, o.length - 2)
        }
    }), $("#valajax").hide(), $("#aide").on("click", aide), $("#infoaide").hide(), $("#insecta").hide(), $("#aves").hide(), $("#BttV").hide(), $("#ajout").hide(), $("#affiche").hide()
}), $(".info").click(function () {
    "use strict";
    var t;
    "info1" == this.id && (t = "stade"), "info2" == this.id && (t = "methode"), "info3" == this.id && (t = "prospection"), "info4" == this.id && (t = "occstatutbio"), "infocomp" == this.id && (t = "comportement"), "infocite" == this.id && (t = "infocite"), "info5" == this.id && (t = "occmort"), $.ajax({
        url: "modeles/ajax/observatoire/inforef.php",
        type: "POST",
        dataType: "json",
        data: {table: t},
        success: function (t) {
            "Oui" == t.statut && $("#inforef").html(t.ref)
        }
    })
}), $("#choix").change(function () {
    "use strict";
    $("#mes").html("");
    var t = $("#choix option:selected").val();
    "NR" != t ? $.ajax({
        url: "modeles/ajax/observatoire/observatoire.php",
        type: "POST",
        dataType: "json",
        data: {sel: t},
        success: function (t) {
            "Oui" == t.statut ? ($("#affiche").show(), $("#BttV").show(), $("#insecta").show(), $("#locale").prop("checked", "oui" == t.locale), $("#mf").prop("checked", "oui" == t.mf), $("#plteh").prop("checked", "oui" == t.plteh), $("#inicheur").prop("checked", "oui" == t.aves), $("#collection").prop("checked", "oui" == t.collection), $("#stade").multiselect("dataprovider", t.stade), $("#meth").multiselect("dataprovider", t.methode), $("#col").multiselect("dataprovider", t.collecte), $("#bio").multiselect("dataprovider", t.statutbio), $("#comport").multiselect("dataprovider", t.comportement), $("#proto").multiselect("dataprovider", t.protocole), $("#denom").multiselect("dataprovider", t.denom), $("#cmort").multiselect("dataprovider", t.mort), "mort" == t.stbio ? ($("#vivant").attr("checked", !1), $("#mort").attr("checked", !0)) : ($("#mort").attr("checked", !1), $("#vivant").attr("checked", !0)), "Equisetopsida" == t.classe ? $("#clbota").val("oui") : $("#clbota").val("non"), "Aves" == t.classe ? $("#aves").show() : $("#aves").hide(), "oui" == t.plteh ? ($("#obsbota").show(), t.listebota && $("#idbota").val(t.listebota)) : $("#obsbota").hide()) : ($("#mes").html(t.mes), $("#insecta").hide())
        }
    }) : ($("#stade option:selected").remove(), $("#meth option:selected").remove(), $("#col option:selected").remove(), $("#insecta").hide(), $("#BttV").hide())
}), $("#plteh").change(function () {
    "use strict";
    $(this).is(":checked") ? $("#obsbota").show() : $("#obsbota").hide()
}), $("#BttV").click(function () {
    "use strict";
    var t = $("input[name=stbio]:checked").val(), e = $("#choix option:selected").val(),
        o = $("#locale").is(":checked") ? "oui" : "non", i = $("#mf").is(":checked") ? "oui" : "non",
        a = $("#plteh").is(":checked") ? "oui" : "non", s = $("#inicheur").is(":checked") ? "oui" : "non",
        c = $("#collection").is(":checked") ? "oui" : "non", n = $("#idbota").val(), l = $("#clbota").val();
    if ("NR" != e) {
        $("#valajax").show();
        var d, r, h = [], u = [], v = [], m = [], p = [], f = [], b = [], x = [], ci = [], cv = [], k = [], j = [], g = [], w = [],
            y = [], C = [];
        d = "stade", r = tri(d), u = r[0], h = r[1], d = "meth", r = tri(d), m = r[0], v = r[1], d = "col", r = tri(d), f = r[0], p = r[1], d = "bio", r = tri(d), x = r[0], b = r[1], d = "comport", r = tri(d), ci = r[0], cv = r[1], d = "cmort", r = tri(d), y = r[0], C = r[1], d = "proto", r = tri(d), k = r[0], j = r[1], d = "denom", r = tri(d), g = r[0], w = r[1], $("#proto option:selected").each(function (t) {
            k[t] = $(this).val(), j[t] = $(this).text()
        }), $.ajax({
            url: "modeles/ajax/observatoire/valobservatoire.php",
            type: "POST",
            dataType: "json",
            data: {
                sel: e,
                stadeval: h,
                stadeid: u,
                locale: o,
                plteh: a,
                idbota: n,
                aves: s,
                methval: v,
                methid: m,
                colval: p,
                colid: f,
                bioval: b,
                bioid: x,
                compval: cv,
                compid: ci,
                mortval: C,
                mortid: y,
                protoval: j,
                protoid: k,
                denomid: g,
                denomval: w,
                collect: c,
                clbota: l,
                stbio: t,
                mf: i
            },
            success: function (t) {
                var e = t.statut;
                "Oui" == e ? $("#mes").html(t.mes) : $("#mes").html(t.mes), $("html, body").animate({scrollTop: 0}, "slow")
            }
        }), $("#valajax").hide()
    }
});