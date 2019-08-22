function carte1(e) {
    "use strict";
    proj4.defs("EPSG:2154", "+proj=lcc +lat_1=49 +lat_2=44 +lat_0=46.5 +lon_0=3 +x_0=700000 +y_0=6600000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs");
    var t = new L.TileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
    });
    $.getJSON("emprise/contour2.geojson", {}, function (t) {
        var a = {color: e.stylecontour2.color, weight: e.stylecontour2.weight},
            i = L.Proj.geoJson(t, {style: a}).addTo(map);
        map.fitBounds(i.getBounds())
    }), map = L.map("carte", {layers: [t]}), drawnItems = new L.FeatureGroup, map.addLayer(drawnItems), map.addControl(new L.Control.Draw({
        draw: {
            polygon: {
                allowIntersection: !1,
                showArea: !0,
                shapeOptions: {color: "#03F"}
            }, rectangle: !1, circle: !1, marker: !1, polyline: !1
        }
    })), $(".leaflet-draw-draw-polygon").click(function () {
        $("#rayon").val(0), drawnItems.getLayers().length > 0 && drawnItems.clearLayers()
    }), map.on("draw:created", function (e) {
        var t = (e.layerType, e.layer);
        drawnItems.addLayer(t);
        for (var a = "((", i = t.getLatLngs(), o = 0; o < i[0].length; o++) 0 != o && (a += "),("), a += i[0][o].lng + "," + i[0][o].lat;
        a += "))", $("#poly").val(a), $("#choixloca").val("poly"), $("#lloca").html(""), $("#BttS").show()
    }), map.on("click", function (e) {
        var t = 1e3 * $("#rayon").val();
        if (0 != t) {
            drawnItems.getLayers().length > 0 && drawnItems.clearLayers();
            var a = L.circle(e.latlng, {fillOpacity: .1, radius: t});
            drawnItems.addLayer(a), map.fitBounds(a.getBounds());
            var i = Math.round(1e4 * e.latlng.lat) / 1e4, o = Math.round(1e4 * e.latlng.lng) / 1e4;
            $("#choixloca").val("cercle"), $("#latc").val(i), $("#lngc").val(o), $("#lloca").html(""), $("#BttS").show()
        }
    })
    $(".leaflet-draw-draw-circlemarker").hide(); // Cacher l'outil 'cercle' non utilisable
}

function observation(e) {
    "use strict";
    $("#choixconsult").hide(), $("#rchoix").show(), $("#listeobs").html('<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement des données...</p></div>'), $("#listeobs").show(), $.ajax({
        url: "modeles/ajax/consultation/observation_mv.php",
        type: "POST",
        dataType: "json",
        data: e.serialize(),
        success: function (e) {
            if ("Oui" == e.statut) {
                var t = e.nbobs;
                if ($("#listeobs").html(e.listeobs) /* , e.pagination && ($("#afpage").show(), $("#pagination").html(e.pagination)), t > 0 && 1 == $("#page").val() */ ) {
                    var a = "",
                        i = '<i class="fa fa-info-circle text-info"></i> : Aperçu de l\'observation <br>- <i class="fa fa-eye text-info"></i> : Ouvre un nouvel onglet avec détail de l\'observation <br><br><p style="color: darkred;">ATTENTION, au maximum 10 000 lignes sont affichées.</p>';
                    a = "" != $("#idobser").val() ? $("#perso").is(":checked") ? 1 == t ? "Votre observation " : "Vos observations (" + t + ")" : 1 == t ? "Observation de <b>" + $("#obser").val() + "</b>" : "Observations (" + t + ") de <b>" + $("#obser").val() + "</b>" : 1 == t ? "Une observation " : t + " observations", $("#nb").html(' : ' + a) , $("#lchoix").html("<br>- " + i)
                }
                // Cellules de recherche
                $('#querytable thead tr').clone(true).appendTo( '#querytable thead' );
                $('#querytable thead tr:eq(1) th').each( function (i) {
                    if(i === $('#querytable thead tr:eq(1) th').length-1) {
                        return;
                    }
                    var title = $(this).text();
                    $(this).html( '<input type="text" placeholder="Rechercher" />' );
                    $( 'input', this ).on( 'keyup change', function () {
                        if ( t.column(i).search() !== this.value ) {
                            t
                                .column(i)
                                .search( this.value )
                                .draw();
                        }
                    });
                });
                var t = $("#querytable").DataTable({
                    language: {
                        url: "dist/js/datatables/france.json"
                    },
                    orderCellsTop: true,
                    fixedHeader: true,
                    pageLength: 25
                });



            } else alert("Erreur ! ")
        }
    })
}

function calcexport(e) {
    "use strict";
    $("#mes").html(""), $.ajax({
        url: "modeles/ajax/consultation/calcexport_mv.php",
        type: "POST",
        dataType: "json",
        data: e.serialize(),
        success: function (e) {
            if ("Oui" == e.statut) {
                var t = e.nbobs;
                if (t > 0) {
                    if (t > 1e4) {
                        $("#rdia1").html("Votre demande comporte " + t + " lignes. Le fichier sera donc volumineux."), $("#bttdia1").prop("disabled", !0)
                    } else $("#rdia1").html("Votre demande comporte " + t + " lignes."), $("#bttdia1").prop("disabled", !1);
                    $("#dia1").modal("show")
                } else $("#mes").html('<div class="alert alert-danger">Aucune observation pour ces critères &#128577;</div>')
            } else alert("Erreur ! ")
        }
    })
}

function exportobs(e) {
    "use strict";
    $("#choixconsult").hide(), $("#rchoix").show(), $("#listeobs").show(), $.ajax({
        url: "modeles/ajax/consultation/export.php",
        type: "POST",
        dataType: "json",
        data: e.serialize(),
        success: function (e) {
            "Oui" == e.statut ? ($("#listeobs").html(e.tbl), e.tblok && remplirtableexport(e.data)) : alert("Erreur ! ")
        }
    })
}

function remplirtableexport(e) {
    var t = $("#nomfichier").val();
    if ("" != t) var a = t + '-'; else var a = "Export-du-";
        var i = new Date;
        o = i.getMonth().length + 1 === 1 ? i.getMonth() + 1 : "0" + (i.getMonth() + 1);
        a = a + i.getDate() + "-" + o + "-" + i.getFullYear();
    var r = $("#tblexport").DataTable({
        language: {
            url: "dist/js/datatables/france.json",
            buttons: {colvis: "Choix des champs à exporter"}
        },
        data: e,
        deferRender: !0,
        scrollY: 600,
        scrollX: true,
        scrollCollapse: !0,
        scroller: !0,
        columnDefs: [{
            targets: [5],
            data: 5,
            render: {_: "date", sort: "tri"}
        }, {
            targets: [6, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 32], // Indiquer les champs à ne pas afficher
            visible: !1
        }],
        dom: "Bfrtip",
        buttons: [
            {extend: "csvHtml5",
                exportOptions: {columns: ":visible"},
                title: a},
            {extend: "excelHtml5",
                exportOptions: {columns: ":visible"},
                title: a },
            {extend: "colvis",
                collectionLayout: 'fixed two-column'} ],
        initComplete: function () {
            setTimeout(function () {
                r.buttons().container().appendTo("#tblexport_wrapper .col-md-6:eq(0)")
            }, 10)
        }
    })
}

function traitementid() {
    "use strict";
    var e = [];
    $("#ltaxon > li[id]").each(function () {
        e.push("'" + $(this).attr("id") + "'")
    }), $("#rchoixtax").val(e), e = [], $("#lloca > li[id]").each(function () {
        e.push("'" + $(this).attr("id") + "'")
    }), $("#rchoixloca").val(e), e = [], $("#lindice > li[id]").each(function () {
        e.push("'" + $(this).attr("id") + "'")
    }), $("#rindice").val(e), e = [], $("#lstatut > li[id]").each(function () {
        e.push("'" + $(this).attr("id") + "'")
    }), $("#rstatut").val(e), e = [], $("#lLRR > li[data-id]").each(function () {
        e.push("'" + $(this).attr("data-id") + "'")
    }), $("#rlrr").val(e), e = [], $("#lLRE > li[data-id]").each(function () {
        e.push("'" + $(this).attr("data-id") + "'")
    }), $("#rlre").val(e), e = [], $("#lLRF > li[data-id]").each(function () {
        e.push("'" + $(this).attr("data-id") + "'")
    }), $("#rlrf").val(e)
}

function exportsinp(e) {
    "use strict";
    var t = e.serialize(), a = "modeles/ajax/consultation/exportsinp.php&" + t;
    window.open(a)
}

function modfiche(e) {
    "use strict";
    $.ajax({
        url: "modeles/ajax/saisie/attribution.php",
        type: "POST",
        dataType: "json",
        data: {idfiche: e},
        success: function (e) {
            "Oui" == e.statut ? document.location.href = "index.php?module=saisie&action=saisie" : alert("Erreur ! ")
        }
    })
}

function carte(e, t, a) {
    "use strict";
    var i = t.split(",", 2), o = parseFloat(i[0]), r = parseFloat(i[1]);
    if ("oui" == nbmap) {
        nbmap = "non", map = new L.map("mapobser");
        var s = new L.TileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
        });
        map.setView(new L.LatLng(o, r), a), map.addLayer(s), 1 == e && (marker = L.marker([o, r]).addTo(map))
    } else map.setView(new L.LatLng(o, r), a), 1 == e && (marker = L.marker([o, r]).addTo(map))
}

function adson(e) {
    "use strict";
    $.ajax({
        url: "modeles/ajax/saisie/attribution.php",
        type: "POST",
        dataType: "json",
        data: {idobs: e},
        success: function (e) {
            "Oui" == e.statut ? document.location.href = "index.php?module=membre&action=ajoutson" : alert("Erreur ! ")
        }
    })
}

function adphoto(e) {
    "use strict";
    $.ajax({
        url: "modeles/ajax/saisie/attribution.php",
        type: "POST",
        dataType: "json",
        data: {idobs: e},
        success: function (e) {
            "Oui" == e.statut ? document.location.href = "index.php?module=membre&action=ajoutphoto" : alert("Erreur ! ")
        }
    })
}

var dep = "non", map, marker, nbmap = "oui", drawnItems;
$(document).ready(function () {
    "use strict";
    // var t = {};
    $("#parobservatoire").hide();
    $("#avance").hide();
    $("#dlsrc1").hide(), $("#dlsrc2").hide();
    $("#dl").hide();
    $("#dlxls").hide();
    $("#dlgeo").hide();
    $('#bttdia1perso').hide();
    $("#listeobs").hide(), $("#rchoix").hide(), $("#afpage").hide(), $("#infoaide").hide(), $("#dlink").hide(), $("#collr").hide(), $("#BttS").hide();
    $("#perso").prop("checked", !0);
    $("#perso").is(":checked") ? ($("#idobser").val($("#idobseror").val()) && $("#obser").val($("#observateur").val())) : "oui" == $("#cperso").val() && ($("#obser").val($("#observateur").val()), $("#perso").prop("checked", !0));
    var e = $("#droit").val();
    "oui" != $("#cperso").val() || e ? ($("#BttE").hide(), $("#BttG").hide()) : ($("#BttE").show(), $("#BttG").show()), "oui" == e && ($("#BttE").show(), $("#BttG").show());
    var t = {};
    $.ajax({
        url: "emprise/emprise.json", dataType: "json", success: function (e) {
            t = e, carte1(t)
        }
    }), $("#carte").css("cursor", "crosshair")
}), $("#BttA").click(function () {
    "use strict";
    $(this).hasClass("btn-info") ? ($(this).removeClass("btn-info").addClass("btn-success"), $("#btn-aide-txt", this).text("Cacher"), $("#infoaide").show()) : ($(this).removeClass("btn-success").addClass("btn-info"), $("#btn-aide-txt", this).text("Aide"), $("#infoaide").hide())
}), $("#date").datepicker({
    changeMonth: !0, changeYear: !0, onClose: function (e) {
        $("#date2").datepicker("option", "minDate", e), $("#date2").val($(this).val()), $("#dates").val(""), $("#dates2").val(""), $("#decade").val("NR")
    }
}), $("#date2").datepicker({changeMonth: !0, changeYear: !0}), $("#dates").datepicker({
    changeMonth: !0,
    changeYear: !0,
    onClose: function (e) {
        $("#dates2").datepicker("option", "minDate", e), $("#dates2").val($(this).val()), $("#date").val(""), $("#date2").val(""), $("#decade").val("NR")
    }
}), $("#dates2").datepicker({changeMonth: !0, changeYear: !0}), $("#decade").change(function () {
    "use strict";
    $("#date").val(""), $("#date2").val(""), $("#dates").val(""), $("#dates2").val("")
}), $("#obser").autocomplete({
    source: function (e, t) {
        $.getJSON("modeles/ajax/saisie/listeobservateur.php", {term: e.term}, function (e) {
            t($.map(e, function (e) {
                return {label: e.observateur, value: e.idobser}
            }))
        })
    }, select: function (e, t) {
        this.value = t.item.label, $("#idobser").val(t.item.value);
        var a = $("#idobseror").val(), i = $("#droit").val();
        return i ? a == t.item.value ? $("#perso").prop("checked", !0) : $("#perso").prop("checked", !1) : a == t.item.value ? ($("#perso").prop("checked", !0), $("#BttE").show(), $("#BttG").show()) : ($("#perso").prop("checked", !1), $("#BttE").hide(), $("#BttG").hide()), !1
    }
}), $("#perso").change(function () {
    "use strict";
    var e = $("#droit").val(), t = $("#voir").val();
    "oui" == t ? e ? $("#perso").is(":checked") ? ($("#obser").val($("#observateur").val()), $("#idobser").val($("#idobseror").val())) : ($("#obser").val(""), $("#idobser").val("")) : $("#perso").is(":checked") ? ($("#obser").val($("#observateur").val()), $("#BttE").show(), $("#BttG").show(), $("#d").val("oui"), $("#idobser").val($("#idobseror").val())) : ($("#obser").val(""), $("#BttE").hide(), $("#BttG").hide(), $("#d").val("non"), $("#idobser").val("")) : $(this).prop("checked", !0)
}), $("#obser").change(function () {
    "use strict";
    "" == $(this).val() && $("#idobser").val("")
});


    // Get les param d'un observatoire
function chercher_param_observa(o){

    $.getJSON("modeles/ajax/consultation/cherche_param_observa.php", {observa: o}, function (data) {
        console.log(data)
        // Stade
        var items = ["<option value='0'>Stade</option>"];
        $.each( data.saisie.stade, function( key, val ) {
            items.push( "<option value='" + key + "'>" + key + "</option>" );
        });
        console.log(items)
        $("[name='stade']").html(items);
        // Contact
        var items = ["<option value='0'>Contact</option>"];
        $.each( data.saisie.methode, function( key, val ) {
            items.push( "<option value='" + key + "'>" + key + "</option>" );
        });
        $("[name='methode']").html(items);
        // Prospection
        var items = ["<option value='0'>Prospection</option>"];
        $.each( data.saisie.collecte, function( key, val ) {
            items.push( "<option value='" + key + "'>" + key + "</option>" );
        });
        $("[name='prospect']").html(items);
        // StatusBio
        var items = ["<option value='0'>Statut bio</option>"];
        $.each( data.saisie.statutbio, function( key, val ) {
            items.push( "<option value='" + key + "'>" + key + "</option>" );
        });
        $("[name='statbio']").html(items);
        // Acquisition
        var items = ["<option value='0'>Type d'acquisition</option>"];
        $.each( data.saisie.protocole, function( key, val ) {
            items.push( "<option value='" + key + "'>" + key + "</option>" );
        });
        $("[name='acquisition']").html(items);
    })
}

    // Si un observatoire est sélectionné
    $("#observa").change(function () {
    "use strict";
    var e = $("#observa").val(), t = $("#choixtax").val();
    if ("NR" != e) {
        $("#parobservatoire").show();
        chercher_param_observa( e );
        var a = $("#observa option:selected").text();
        "observa" != t && ($("#ltaxon").html(""), $("#choixtax").val("observa")), $("#ltaxon").prepend('<li id="' + e + '"><i class="fa fa-trash curseurlien text-danger"></i> ' + a + "</li>")
    } else
    {
        $("#parobservatoire").hide(), $("#choixtax").val(""), $("#ltaxon").html("");
    }
    var t = $("#ltaxon").contents().length;
    if (0 == t || t > 1) {
        $("#parobservatoire").hide();
    }
});

    // Choix d'un taxon général
    $("#taxon").autocomplete({
    source: function (e, t) {
        $.getJSON("modeles/ajax/liste.php", {term: e.term}, function (e) {
            t($.map(e, function (e) {
                return "" == e.nomvern ? {label: e.nom, value: e} : {label: e.nom + " (" + e.nomvern + ")", value: e}
            }))
        })
    }, select: function (e, t) {
        $("#taxon").val(""), $("#observa").val("NR"), $("#parobservatoire").hide()
        var a = $("#choixtax").val();
        return "espece" != a && ($("#ltaxon").html(""), $("#choixtax").val("espece")), $("#ltaxon").prepend('<li id="' + t.item.value.cdnom + '"><i class="fa fa-trash curseurlien text-danger"></i> <i>' + t.item.value.nom + "</i> " + t.item.value.nomvern + "</li>"), $("#BttS").hide(), !1
    }
});

    // Choix d'une espèce si un seul observatoire sélectionné
    $("#espece").autocomplete({
        source: function (request, response) {
            var observa = $("#observa").val();
            $.getJSON("modeles/ajax/consultation/espece.php", {term: request.term, observa: observa}, function (data) {
                response($.map(data, function (data) {
                    return "" == data.nomvern ? {label: data.nom, value: data} : {label: data.nom + " (" + data.nomvern + ")", value: data}
                }))
            })
        }, select: function (e, t) {
            $("#espece").val("");
            var a = $("#choixtax").val();
            return "espece" != a && ($("#ltaxon").html(""), $("#choixtax").val("espece")), $("#ltaxon").prepend('<li id="' + t.item.value.cdnom + '"><i class="fa fa-trash curseurlien text-danger"></i> <i>' + t.item.value.nom + "</i> " + t.item.value.nomvern + "</li>"), !1
        }
    });
    // Choix d'un genre si un seul observatoire sélectionné
    $("#genre").autocomplete({
        source: function (request, response) {
            var observa = $("#observa").val();
            $.getJSON("modeles/ajax/consultation/genre.php", {term: request.term, observa: observa}, function (data) {
                response($.map(data, function (data) {
                    return {label: data.genre, value: data}
                }))
            })
        }, select: function (e, t) {
            $("#genre").val("");
            var a = $("#choixtax").val();
            return "genre" != a && ($("#ltaxon").html(""), $("#choixtax").val("genre")), $("#ltaxon").prepend('<li id="' + t.item.value.genre + '"><i class="fa fa-trash curseurlien text-danger"></i> <i>' + t.item.value.genre + "</i></li>"), !1
        }
    });
    // Choix d'une famille si un seul observatoire sélectionné
    $("#famille").autocomplete({
        source: function (e, t) {
            var observa = $("#observa").val();
            $.getJSON("modeles/ajax/consultation/famille.php", {term: e.term, observa: observa}, function (e) {
                t($.map(e, function (e) {
                    return {label: e.famille, value: e}
                }))
            })
        }, select: function (e, t) {
            $("#famille").val("");
            var a = $("#choixtax").val();
            return "famille" != a && ($("#ltaxon").html(""), $("#choixtax").val("famille")), $("#ltaxon").prepend('<li id="' + t.item.value.famille + '"><i class="fa fa-trash curseurlien text-danger"></i> ' + t.item.value.famille + "</li>"), !1
        }
    });

    // A la suppression d'un taxon
    $("#ltaxon").on("click", ".fa-trash", function () {
    "use strict";
    var e = $(this).parent().attr("id");
    $("#" + e).remove();
    var t = $("#ltaxon").contents().length;
    0 == t && ($("#choixtax").val(""), $("#observa").val("NR"))
    0 == t || t > 1 ? $("#parobservatoire").hide() : null;
    1 == t ? $("#parobservatoire").show() : null;
});


    $("#commune").autocomplete({
    source: function (e, t) {
        $.getJSON("modeles/ajax/listecommune.php", {term: e.term, dep: dep}, function (e) {
            t($.map(e, function (e) {
                return "non" == dep ? {label: e.commune, value: e} : {
                    label: e.commune + " (" + e.departement + ")",
                    value: e
                }
            }))
        })
    }, select: function (e, t) {
        $("#commune").val("");
        var a = $("#choixloca").val();
        return "commune" != a && ($("#lloca").html(""), $("#choixloca").val("commune")), $("#lloca").prepend('<li id="' + t.item.value.codecom + '"><i class="fa fa-trash curseurlien text-danger"></i> ' + t.item.label + "</li>"), $("#BttS").show(), drawnItems.getLayers().length > 0 && (drawnItems.clearLayers(), $("#poly").val("")), !1
    }
}), $("#site").autocomplete({
    minLength: 2, source: function (e, t) {
        $.getJSON("modeles/ajax/listesite.php", {term: e.term}, function (e) {
            t($.map(e, function (e) {
                return {label: e.site + " (" + e.commune + ")", value: e}
            }))
        })
    }, select: function (e, t) {
        $("#site").val("");
        var a = $("#choixloca").val();
        return "site" != a && ($("#lloca").html(""), $("#choixloca").val("site")), $("#lloca").prepend('<li id="' + t.item.value.idsite + '"><i class="fa fa-trash curseurlien text-danger"></i> ' + t.item.label + "</li>"), $("#BttS").show(), drawnItems.getLayers().length > 0 && (drawnItems.clearLayers(), $("#poly").val("")), !1
    }
}), $("#sitee").change(function () {
    "use strict";
    $("#lloca").html(""), "" != $(this).val() ? ($("#choixloca").val("sitee"), $("#BttS").show()) : ($("#choixloca").val(""), $("#BttS").hide())
}), $("#lloca").on("click", ".fa-trash", function () {
    "use strict";
    var e = $(this).parent().attr("id");
    $("#" + e).remove();
    var t = $("#lloca").contents().length;
    0 == t && $("#choixloca").val("")
}), $("#etude").change(function () {
    "use strict";
    0 != $(this).val() ? $("#BttS").show() : $("#BttS").hide()
});
var lrr = "non", lre = "non", lrf = "non";
$("#statut").change(function () {
    "use strict";
    var e = $(this).val(), t = $("option:selected", this).text();
    "NR" != e ? $("#lstatut").prepend('<li id="' + e + '"><i class="fa fa-trash curseurlien text-danger"></i> ' + t + "</li>") : ($("#lstatut").html(""), lrr = "non", lre = "non", lrf = "non"), "LRR" == $(this).val() || "LRF" == $(this).val() || "LRE" == $(this).val() ? $("#collr").show() : $("#collr").hide()
}), $("#lstatut").on("click", ".fa-trash", function () {
    "use strict";
    var e = $(this).parent().attr("id");
    $("#" + e).remove();
    var t = $("#lstatut").contents().length;
    0 == t && ($("#statut").val("NR"), lrr = "non", lre = "non", lrf = "non")
}), $("#lr").change(function () {
    "use strict";
    var e = $(this).val(), t = $("option:selected", this).text(), a = $("#statut").val();
    "NR" != e && ("LRR" == a && ("non" == lrr ? ($("#" + a).append('<ul id="l' + a + '"><li data-id="' + e + '"><i class="fa fa-trash curseurlien text-danger"></i> ' + t + "</li></ul>"), lrr = "oui") : $("#l" + a).prepend('<li data-id="' + e + '"><i class="fa fa-trash curseurlien text-danger"></i> ' + t + "</li>")), "LRE" == a && ("non" == lre ? ($("#" + a).append('<ul id="l' + a + '"><li data-id="' + e + '"><i class="fa fa-trash curseurlien text-danger"></i> ' + t + "</li></ul>"), lre = "oui") : $("#l" + a).prepend('<li data-id="' + e + '"><i class="fa fa-trash curseurlien text-danger"></i> ' + t + "</li>")), "LRF" == a && ("non" == lrf ? ($("#" + a).append('<ul id="l' + a + '"><li data-id="' + e + '"><i class="fa fa-trash curseurlien text-danger"></i> ' + t + "</li></ul>"), lrf = "oui") : $("#l" + a).prepend('<li data-id="' + e + '"><i class="fa fa-trash curseurlien text-danger"></i> ' + t + "</li>")), $(this).val("NR"))
}), $("#lstatut").on("click", "#lLRR .fa-trash", function () {
    "use strict";
    $(this).parent().remove();
    var e = $("#lLRR").contents().length;
    0 == e && (lrr = "non")
}), $("#lstatut").on("click", "#lLRE .fa-trash", function () {
    "use strict";
    $(this).parent().remove();
    var e = $("#lLRE").contents().length;
    0 == e && (lre = "non")
}), $("#lstatut").on("click", "#lLRF .fa-trash", function () {
    "use strict";
    $(this).parent().remove();
    var e = $("#lLRF").contents().length;
    0 == e && (lrf = "non")
}), $("#indice").change(function () {
    "use strict";
    var e = $("#indice").val();
    if ("NR" != e) {
        var t = $("#indice option:selected").text();
        $("#lindice").prepend('<li id="' + e + '"><i class="fa fa-trash curseurlien text-danger"></i> ' + t + "</li>")
    } else $("#lindice").html("")
}), $("#lindice").on("click", ".fa-trash", function () {
    "use strict";
    var e = $(this).parent().attr("id");
    $("#" + e).remove();
    var t = $("#lindice").contents().length;
    0 == t && $("#indice").val("NR")
}), $("#rchoix").click(function () {
    "use strict";
    $("#rchoix").hide(), $("#listeobs").hide(), $("#afpage").hide(), $("#choixconsult").show(), $("#lchoix").html(""), $("#page").val(1)
}), $("#BttV").click(function () {
    "use strict";
    $("#Bt").val("BttV")
}), $("#BttE").click(function () {
    "use strict";
    $("#Bt").val("BttE");
    $("#dl").hide();
}), $("#BttSINP").click(function () {
    "use strict";
    $("#Bt").val("BttSINP")
});
var $exp;
$("#form").on("submit", function (e) {
    "use strict";
    e.preventDefault(), traitementid();
    var t, a = $(this), i = $("#Bt").val(), o = $("#choixtax").val(), r = $("#choixloca").val(), s = $("#date").val(),
        l = $("#dates").val(), n = $("#idobser").val(), c = $("#etude").val(), d = $("#orga").val(),
        u = $("#rstatut").val();
    "" == o && "" == r && "" == s && "" == l && 0 == c && "NR" == d && "" == u ? $("#perso").is(":checked") || "" != n ? (t = "oui", $("#mes").html("")) : $("#mes").html('<div class="alert alert-danger">Vous devez au minimum sélectionner soit : un organisme, un observateur, une localisation, une espèce / groupe, une date, une etude, un statut</div>') : (t = "oui", $("#mes").html("")), "oui" == t && ("BttV" == i && observation(a), "BttE" == i && ($exp = a, calcexport($exp)))
}), $("#bttdia1").click(function () {
    exportobs($exp)
}), $("#Butavance").click(function () {
    $("#avance").show();
    $("#bttdia1").hide();
    $("#Butavance").hide();
    $('#bttdia1perso').show();
    $("#fields").multiSelect();
});

    $("#cancel").click(function () { // Bouton 'Annuler'
    $("#avance").hide();
    $("#bttdia1").show();
    $('#bttdia1perso').hide();
    $("#Butavance").show();
    $("#dlsrc1").hide(), $("#dlsrc2").hide();
    $("#dlxls").hide();
    $("#dlgeo").hide();
    $("#nomfichier").val("");
    $("#custom_fields").val("");


});

$("#all").change(function() {
    if(this.checked) {
        $('#fields').multiSelect('select_all');
    } else {
        $('#fields').multiSelect('deselect_all');
    }
});

    $("#bttdia1perso").click(function () {
    // $("#formdia1").submit();
        console.log('ok');
        var e = $("#form").serializeArray();
        var f = JSON.stringify($("#fields").val());
        var g = $("#custom_fields").val();
        var h = $("#user_fields").val();
        e.push({ name: "fields", value: f});
        e.push({ name: "custom_fields", value: g});
        e.push({ name: "user_fields", value: h});
        var i = $("#get_status").is(":checked") ? 'oui' : 'non';
        e.push({ name: "status", value: i});
        exportavance(e);
});

function exportavance(e) {
    "use strict";
    // e.preventDefault();
    $.ajax({
        url: "modeles/ajax/consultation/exportperso_mv.php",
        type: "POST",
        dataType: "json",
        data: e,
        success: function (e) {
            $("#dl").attr('onClick', 'window.location.href="modeles/ajax/consultation/getfile.php?f=' + e + '&t=tsv&n=' + $("#nomfichier").val() + '"');
            $("#dlxls").attr('onClick', 'window.location.href="modeles/ajax/consultation/getfile.php?f=' + e + '&t=xls&n=' + $("#nomfichier").val() + '"');
            $("#dlsrc1").attr('onClick', 'window.location.href="modeles/ajax/consultation/getfile.php?f=' + e + '&t=txt&n=' + $("#nomfichier").val() + '"');
            $("#dlgeo").attr('onClick', 'window.location.href="modeles/ajax/consultation/getfile.php?f=' + e + '&t=geojson&n=' + $("#nomfichier").val() + '"');
            $("#dl").show(), $("#dlxls").show(), $("#dlgeo").show(), $("#dlsrc1").show();
            console.log("ok-exp");
        }
    })
};

    $("#tousfiche").change(function () {
    "use strict";
    var e = $("#ultousfiche").find(":checkbox");
    this.checked ? e.prop("checked", !0) : e.prop("checked", !1)
}), $("#tousobs").change(function () {
    "use strict";
    var e = $("#ultousobs").find(":checkbox");
    this.checked ? e.prop("checked", !0) : e.prop("checked", !1)
}), $("#dlink").click(function () {
    "use strict";
    $("#dlink").hide()
}), $("#bttrhaut").click(function () {
    "use strict";
    $("html, body").animate({scrollTop: 0}, "slow")
}), $("#pagination").on("click", ".page-item", function () {
    "use strict";
    var e = $(this).attr("id"), t = e.substring(0, 2);
    if ("pp" == t) var a = e.substring(2); else var a = e.substring(1);
    $("#page").val(a), $("html, body").animate({scrollTop: 0}, "slow"), $("#form").submit()
}), $("#fiche").on("show.bs.modal", function (e) {
    "use strict";
    var t = $(e.relatedTarget), a = t.data("idfiche"), i = $(this);
    $.ajax({
        url: "modeles/ajax/observation/infofiche.php",
        type: "POST",
        dataType: "json",
        data: {idfiche: a},
        success: function (e) {
            "Oui" == e.statut ? (i.find(".modal-title").html("Information sur le relevé n° " + a), e.mod ? i.find(".lienidobs").html('<a class="color1" href="' + e.lien + '" target="_blank">Plus de détail</a><i class="fa fa-pencil curseurlien ml-3" onclick="modfiche(' + a + ')" title="Modifier votre relevé"></i>') : i.find(".lienidobs").html('<a class="color1" href="' + e.lien + '" target="_blank">Plus de détail</a>'), i.find("#listefiche").html(e.liste)) : alert("erreur")
        }
    })
}), $("#obs").on("show.bs.modal", function (e) {
    "use strict";
    var t = $(e.relatedTarget), a = t.data("nomlat"), i = t.data("idobs"), o = t.data("latin"), r = t.data("nomfr"),
        s = t.data("photo"), l = t.data("idmor"), n = $(this);
    $.ajax({
        url: "modeles/ajax/observation/infoobs.php",
        type: "POST",
        dataType: "json",
        data: {idobs: i, photo: s},
        success: function (e) {
            if ("Oui" == e.statut) if (e.diffcdref ? n.find(".diffcdref").html("Saisie sous le nom de : <i>" + e.diffcdref + "</i>") : n.find(".diffcdref").html(""), n.find(".obsdatefr").html(e.date), n.find(".obsidobs").html(i), n.find(".obsfloutage").html(e.lieu), n.find(".obsobservateur").html(e.observateur), n.find(".obsdeterminateur").html("Déterminateur : " + e.determinateur), n.find(".obsligne").html(e.ligne), n.find("#idobscom").val(i), n.find("#idmor").val(l), n.find(".lienidobs").html('<a class="color1" href="' + e.lien + '"  target="_blank">Plus de détail</a>'), e.mod ? n.find(".modobs").html('<i class="fa fa-pencil curseurlien ml-3" onclick="modfiche(' + e.idfiche + ')" title="Modifier votre observation"></i><i class="fa fa-camera curseurlien ml-2" onclick="adphoto(' + i + ')" title="Ajouter une photo"></i><i class="fa fa-volume-off curseurlien ml-2" onclick="adson(' + i + ')" title="Ajouter un son"></i>') : e.adphoto ? n.find(".modobs").html('<i class="fa fa-camera curseurlien ml-2" onclick="adphoto(' + i + ')" title="Ajouter une photo"></i><i class="fa fa-volume-off curseurlien ml-2" onclick="adson(' + i + ')" title="Ajouter un son"></i>') : n.find(".modobs").html(""), e.commentaire ? n.find(".obscommentaire").html(e.commentaire) : n.find(".obscommentaire").html(""), e.photo ? n.find(".obsphoto").html(e.photo) : n.find(".obsphoto").html(""), e.coord) {
                var t = e.pre, a = e.coord, o = 1 == t ? 14 : 12;
                carte(t, a, o), setTimeout(function () {
                    map.invalidateSize()
                }, 400)
            } else {
                var t = 0, o = 8, r = $("#lat").val(), s = $("#lng").val(), a = r + "," + s;
                carte(t, a, o), setTimeout(function () {
                    map.invalidateSize()
                }, 400)
            } else alert("erreur")
        }
    }), "oui" == o ? n.find(".modal-title").html("<i>" + a + "</i>") : n.find(".modal-title").html(r + " <i>" + a + "</i>")
}), $("#obs").on("hidden.bs.modal", function () {
    "use strict";
    marker && (map.removeLayer(marker), marker = null)
}), $("#BttVcom").click(function () {
    "use strict";
    var e = $("#idobscom").val(), t = $("#idmcom").val(), a = $("#idmor").val(), i = $("#commentaire").val();
    "" != i ? $.ajax({
        url: "modeles/ajax/observation/commentaire.php",
        type: "POST",
        dataType: "json",
        data: {idm: t, idobs: e, com: i, idmor: a},
        success: function (e) {
            "Oui" != e.statut && alert("Erreur ! lors insertion table"), $("#commentaire").val("")
        }
    }) : alert("Aucun commentaire de saisie !")
}), $(".popup-gallery").magnificPopup({
    delegate: "a",
    type: "image",
    tLoading: "Loading image #%curr%...",
    mainClass: "mfp-img-mobile",
    gallery: {enabled: !0, navigateByImgClick: !0, preload: [0, 1]},
    image: {
        tError: '<a href="%url%">The image #%curr%</a> est absente..', titleSrc: function (e) {
            return e.el.attr("title")
        }
    }
});

// Collapse rows by categories
$("#form").on("click", ".releve", function () {
    "use strict";
    $("#collapsereleve").hasClass("show") ? $("#arrowreleve").removeClass("fa-expand").addClass("fa-compress") : $("#arrowreleve").removeClass("fa-compress").addClass("fa-expand")
}), $("#form").on("click", ".espece", function () {
    "use strict";
    $("#collapseespece").hasClass("show") ? $("#arrowespece").removeClass("fa-expand").addClass("fa-compress") : $("#arrowespece").removeClass("fa-compress").addClass("fa-expand")
}), $("#form").on("click", ".localisation", function () {
    "use strict";
    $("#collapselocalisation").hasClass("show") ? $("#arrowlocalisation").removeClass("fa-expand").addClass("fa-compress") : $("#arrowlocalisation").removeClass("fa-compress").addClass("fa-expand")
}), $("#form").on("click", ".date", function () {
    "use strict";
    $("#collapsedate").hasClass("show") ? $("#arrowdate").removeClass("fa-expand").addClass("fa-compress") : $("#arrowdate").removeClass("fa-compress").addClass("fa-expand")
}), $("#form").on("click", ".hab", function () {
    "use strict";
    $("#collapsehab").hasClass("show") ? $("#arrowhab").removeClass("fa-expand").addClass("fa-compress") : $("#arrowhab").removeClass("fa-compress").addClass("fa-expand")
});

// Refresh MV
$("#update").on("click", function () {
    $("#update").hide();
    $( "#mv" ).append( '<span class="ml-2 text-danger"><span class="fa fa-spin fa-spinner fa-2x"></span> Rafraichissement de la table</span>' );
    $.ajax({
        url: "modeles/ajax/consultation/refresh_mv.php",
        type: "POST",
        dataType: "json",
        success: function (e) {
            console.log(e.status)
            "mvok" === e.status ? $( "#mv" ).html('<button type="button" class="btn btn-sm btn-success ml-2">Actualisation réussie</button>') && $( "#actualisation" ).html( e.newdate ) : $( "#mv" ).html('<button type="button" class="btn btn-sm btn-danger ml-2">Problème à l\'actualisation des données.</button>')
        }
    });
});