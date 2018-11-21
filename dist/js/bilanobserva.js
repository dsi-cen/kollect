function liste_dep() { // Afficher les départements de l'emprise
    "use strict";
    $.ajax({
        url: "../modeles/ajax/bilan/listedep.php",
        type: "POST",
        dataType: "json",
        success: function(res) {
            $('#iddep').empty();
            $('#iddep').append('<option value="%" selected>Tous les départements</option>');
            var JSONObject = res;
            for (var key in JSONObject) {
                if (JSONObject.hasOwnProperty(key)) {
                    $('#iddep').append('<option value="' + JSONObject[key]["id"]+ '">' + JSONObject[key]["emp"]  + '</option>');
                }
            }
        },
        error: function(res) {
            console.log("Erreur");
        }
    });
}

function carte(e, i='%') {
    // "use strict";
    var t = $("#utm").val(), o = $("#nomvar").val(), a = $("#emp").val();
    $.ajax({
        url: "modeles/ajax/bilan/cartebilan.php",
        type: "POST",
        dataType: "json",
        data: {choixcarte: e, utm: t, nomvar: o, emp: a, iddep: i},
        success: function (e) {
            "Oui" == e.statut && ("non" == e.maille && cartecommune(e.data, e.carto, e.nbsp, e.dep), "oui" == e.maille && (e.maille5 ? cartemaille5(e.data, e.carto, e.nbsp) : cartemaille(e.data, e.carto, e.nbsp)))
        }
    })
}

function cartecommune(e, t, o, a) {
    "use strict";
    var r = "oui" == a ? "dep" : "com", i = Highcharts.geojson(t, "map");
    $("#container").highcharts("Map", {
        chart: {
            events: {
                load: function (e) {
                    $(".highcharts-legend").appendTo("#legendContainer"), $(".highcharts-legend").removeAttr("transform")
                }, redraw: function () {
                    $(".highcharts-legend").removeAttr("transform")
                }
            }, backgroundColor: "rgba(255, 255, 255, 0)"
        },
        title: {text: ""},
        credits: {enabled: !1},
        legend: {floating: !0, layout: "vertical", valueDecimals: 0, symbolRadius: 0, symbolHeight: 14, x: -10, y: 0},
        colors: ["#d73027", "#fc8d59", "#fee08b", "#ffffbf", "#d9ef8b", "#91cf60", "#1a9850"],
        colorAxis: {
            dataClassColor: "category",
            dataClasses: [{to: 1}, {from: 1, to: .01 * o}, {from: .01 * o, to: .1 * o}, {
                from: .1 * o,
                to: .25 * o
            }, {from: .25 * o, to: .5 * o}, {from: .5 * o, to: .75 * o}, {from: .75 * o}]
        },
        navigation: {
            buttonOptions: {
                verticalAlign: "top",
                align: "right",
                width: 28,
                height: 28,
                symbolX: 14,
                symbolY: 14,
                symbolStroke: "white",
                theme: {
                    fill: couleur1,
                    "stroke-width": 0,
                    r: 0,
                    states: {hover: {fill: "#ccc"}, select: {stroke: "#039", fill: "#ccc"}}
                }
            }
        },
        mapNavigation: {
            enableMouseWheelZoom: !1,
            enabled: !0,
            buttonOptions: {verticalAlign: "bottom", align: "right"},
            buttons: {
                zoomIn: {style: {color: "white"}, theme: {fill: couleur1, "stroke-width": 0, r: 0}},
                zoomOut: {style: {color: "white"}, theme: {fill: couleur2, "stroke-width": 0, r: 0}}
            }
        },
        plotOptions: {
            series: {
                point: {
                    events: {
                        click: function () {
                            nbobs(r, this.id, this.nom)
                        }
                    }
                }
            }
        },
        tooltip: {
            backgroundColor: null, borderWidth: 0, shadow: !1, useHTML: !0, formatter: function () {
                return '<div class="popupcarte" style="background-color:' + this.point.color + '"><b>' + this.point.nom + "</b><br>" + this.point.info + "</div>"
            }
        },
        series: [{
            mapData: i,
            data: e,
            joinBy: ["id", "id"],
            name: "Commune",
            borderColor: "black",
            borderWidth: 1,
            cursor: "pointer",
            states: {hover: {borderWidth: 1.5}}
        }, {data: dep, type: "mapline", lineWidth: 2.5, color: "black", enableMouseTracking: !1}]
    })
}

function cartemaille(e, t, o) {
    "use strict";
    $.getJSON("../emprise/contour2.geojson", function (a) {
        var r = Highcharts.geojson(a, "mapline"), i = Highcharts.geojson(t, "map");
        $("#container").highcharts("Map", {
            chart: {
                events: {
                    load: function (e) {
                        $(".highcharts-legend").appendTo("#legendContainer"), $(".highcharts-legend").removeAttr("transform")
                    }, redraw: function () {
                        $(".highcharts-legend").removeAttr("transform")
                    }
                }, backgroundColor: "rgba(255, 255, 255, 0)"
            },
            title: {text: ""},
            credits: {enabled: !1},
            legend: {
                floating: !0,
                layout: "vertical",
                valueDecimals: 0,
                symbolRadius: 0,
                symbolHeight: 14,
                x: -10,
                y: 0,
                labelFormatter: function () {
                    return void 0 === this.from ? "< " + this.to + " (0 %)" : void 0 === this.to ? "> " + this.from.toFixed(0) + " (75 %)" : this.from.toFixed(0) + " - " + this.to.toFixed(0)
                }
            },
            colors: ["#d73027", "#fc8d59", "#fee08b", "#ffffbf", "#d9ef8b", "#91cf60", "#1a9850"],
            colorAxis: {
                dataClassColor: "category",
                dataClasses: [{to: 1}, {from: 1, to: .01 * o}, {from: .01 * o, to: .1 * o}, {
                    from: .1 * o,
                    to: .25 * o
                }, {from: .25 * o, to: .5 * o}, {from: .5 * o, to: .75 * o}, {from: .75 * o}]
            },
            navigation: {
                buttonOptions: {
                    verticalAlign: "top",
                    align: "right",
                    width: 28,
                    height: 28,
                    symbolX: 14,
                    symbolY: 14,
                    symbolStroke: "white",
                    theme: {
                        fill: couleur1,
                        "stroke-width": 0,
                        r: 0,
                        states: {hover: {fill: "#ccc"}, select: {stroke: "#039", fill: "#ccc"}}
                    }
                }
            },
            mapNavigation: {
                enableMouseWheelZoom: !1,
                enabled: !0,
                buttonOptions: {verticalAlign: "bottom", align: "right"},
                buttons: {
                    zoomIn: {style: {color: "white"}, theme: {fill: couleur1, "stroke-width": 0, r: 0}},
                    zoomOut: {style: {color: "white"}, theme: {fill: couleur2, "stroke-width": 0, r: 0}}
                }
            },
            plotOptions: {
                series: {
                    point: {
                        events: {
                            click: function () {
                                nbobs("maille", this.id, this.nom)
                            }
                        }
                    }
                }
            },
            tooltip: {
                backgroundColor: null, borderWidth: 0, shadow: !1, useHTML: !0, formatter: function () {
                    return '<div class="popupcarte" style="background-color:' + this.point.color + '"><b>' + this.point.nom + "</b><br>" + this.point.info + "</div>"
                }
            },
            series: [{
                mapData: i,
                type: "map",
                data: e,
                joinBy: ["id", "id"],
                name: "Observation(s)",
                borderColor: "black",
                borderWidth: .5,
                cursor: "pointer",
                states: {hover: {borderWidth: 1.5}}
            }, {
                data: r,
                type: "mapline",
                name: "Commune",
                lineWidth: .3,
                color: "black",
                enableMouseTracking: !1
            }, {data: dep, type: "mapline", lineWidth: 2, color: "black", enableMouseTracking: !1}]
        })
    })
}

function cartemaille5(e, t, o) {
    $.getJSON("../emprise/contour2.geojson", function (a) {
        var r = Highcharts.geojson(a, "mapline"), i = Highcharts.geojson(t, "mapline"),
            n = Highcharts.geojson(t, "map");
        $("#container").highcharts("Map", {
            chart: {
                events: {
                    load: function (e) {
                        $(".highcharts-legend").appendTo("#legendContainer"), $(".highcharts-legend").removeAttr("transform")
                    }, redraw: function () {
                        $(".highcharts-legend").removeAttr("transform")
                    }
                }, backgroundColor: "rgba(255, 255, 255, 0)"
            },
            title: {text: ""},
            credits: {enabled: !1},
            legend: {
                floating: !0,
                layout: "vertical",
                valueDecimals: 0,
                symbolRadius: 0,
                symbolHeight: 14,
                x: -10,
                y: 0
            },
            colors: ["#d73027", "#fc8d59", "#fee08b", "#ffffbf", "#d9ef8b", "#91cf60", "#1a9850"],
            colorAxis: {
                dataClassColor: "category",
                dataClasses: [{to: 1}, {from: 1, to: .01 * o}, {from: .01 * o, to: .1 * o}, {
                    from: .1 * o,
                    to: .25 * o
                }, {from: .25 * o, to: .5 * o}, {from: .5 * o, to: .75 * o}, {from: .75 * o}]
            },
            mapNavigation: {
                enableMouseWheelZoom: !1,
                enabled: !0,
                buttonOptions: {verticalAlign: "bottom", align: "right"}
            },
            plotOptions: {
                series: {
                    point: {
                        events: {
                            click: function () {
                                nbobs("maille5", this.id, this.nom)
                            }
                        }
                    }
                }
            },
            tooltip: {
                backgroundColor: null, borderWidth: 0, shadow: !1, useHTML: !0, formatter: function () {
                    return '<div class="popupcarte" style="background-color:' + this.point.color + '"><b>' + this.point.nom + "</b><br>" + this.point.info + "</div>"
                }
            },
            series: [{
                mapData: n,
                type: "map",
                data: e,
                joinBy: ["id", "id"],
                name: "Observation(s)",
                borderColor: "black",
                borderWidth: .5,
                cursor: "pointer",
                states: {hover: {borderWidth: 1.5}}
            }, {
                data: i,
                type: "mapline",
                name: "Maille",
                lineWidth: .5,
                color: "black",
                dashStyle: "LongDash",
                enableMouseTracking: !1
            }, {
                data: r,
                type: "mapline",
                name: "Commune",
                lineWidth: .3,
                color: "black",
                enableMouseTracking: !1
            }, {data: dep, type: "mapline", lineWidth: 2, color: "black", enableMouseTracking: !1}]
        })
    })
}

function cartenicheur() {
    "use strict";
    var e = $("#utm").val(), t = $("#nomvar").val();
    $.ajax({
        url: "modeles/ajax/bilan/cartenicheur.php",
        type: "POST",
        dataType: "json",
        data: {utm: e, nomvar: t},
        success: function (e) {
            "Oui" == e.statut && ("oui" == e.nicheur ? ($("#nbsup").html(e.nbsp), cartemaillenicheur(e.data, e.carto, e.nbsp)) : $("#cartesup").html("Aucun nicheur"))
        }
    })
}

function cartemaillenicheur(e, t, o) {
    $.getJSON("../emprise/contour.geojson", function (a) {
        var r = Highcharts.geojson(a, "mapline"), i = Highcharts.geojson(t, "map");
        $("#cartesup").highcharts("Map", {
            title: {text: ""},
            credits: {enabled: !1},
            legend: {enabled: !1},
            colorAxis: {min: 0, max: o, maxColor: "#FF0000"},
            navigation: {
                buttonOptions: {
                    verticalAlign: "top",
                    align: "right",
                    width: 28,
                    height: 28,
                    symbolX: 14,
                    symbolY: 14,
                    symbolStroke: "white",
                    theme: {
                        fill: couleur1,
                        "stroke-width": 0,
                        r: 0,
                        states: {hover: {fill: "#ccc"}, select: {stroke: "#039", fill: "#ccc"}}
                    }
                }
            },
            mapNavigation: {
                enableMouseWheelZoom: !1,
                enabled: !0,
                buttonOptions: {verticalAlign: "bottom", align: "right"},
                buttons: {
                    zoomIn: {style: {color: "white"}, theme: {fill: couleur1, "stroke-width": 0, r: 0}},
                    zoomOut: {style: {color: "white"}, theme: {fill: couleur2, "stroke-width": 0, r: 0}}
                }
            },
            tooltip: {
                backgroundColor: null, borderWidth: 0, shadow: !1, useHTML: !0, formatter: function () {
                    return '<div class="popupcarte" style="background-color:' + this.point.color + '"><b>' + this.point.nom + "</b><br>" + this.point.info + "</div>"
                }
            },
            series: [{
                mapData: i,
                type: "map",
                data: e,
                joinBy: ["id", "id"],
                name: "Observation(s)",
                borderColor: "black",
                borderWidth: .5,
                cursor: "pointer",
                states: {hover: {borderWidth: 1.5}}
            }, {
                data: r,
                type: "mapline",
                name: "Commune",
                lineWidth: .3,
                color: "black",
                enableMouseTracking: !1
            }, {data: dep, type: "mapline", lineWidth: 2, color: "black", enableMouseTracking: !1}]
        })
    })
}

function nbobs(e, t, o) {
    "use strict";
    cartoleaflet && (map.removeLayer(cartoleaflet), cartoleaflet = null);
    var a = $("#nomvar").val(), r = $("#nbobstotal").val();
    if ("aucun" != e) {
        if ("com" == e) if ("oui" == $("#contour2").val()) {
            var i = t.toString(), n = i.substring(0, 2);
            $("#lienid").html('<a href="index.php?module=commune&amp;action=commune&amp;d=' + a + "&amp;codecom=" + t + '">Liste des espèces de ' + o + '</a> - <a href="index.php?module=depart&amp;d=' + a + "&amp;action=depart&amp;iddep=" + n + '">Liste des espèces du ' + n + "</a>")
        } else $("#lienid").html('<a href="index.php?module=commune&amp;action=commune&amp;d=' + a + "&amp;codecom=" + t + '">Liste des espèces de ' + o + "</a>");
        if ("maille" == e) {
            var l = $("#utm").val();
            e = "non" == l ? "l93" : "utm", $("#lienid").html('<a href="index.php?module=maille&amp;action=maille&amp;d=' + a + "&amp;maille=" + t + '">Liste des espèces de la la maille ' + o + "</a>")
        }
        "maille5" == e && $("#lienid").html('<a href="index.php?module=maille&amp;action=maille5&amp;d=' + a + "&amp;maille=" + t + '">Liste des espèces de la la maille ' + o + "</a>"), reinfo = "oui"
    }
    $.ajax({
        url: "modeles/ajax/bilan/infobilan.php",
        type: "POST",
        dataType: "json",
        data: {choix: e, id: t, nomvar: a, nbtotal: r},
        success: function (t) {
            if ("Oui" == t.statut) {
                if ("aucun" == e) $("#nbobstotal").val(t.nb), $("#infonbobs").html(t.nb); else {
                    if ("com" == e) var a = "Commune " + o + " : <b>" + t.nb + "</b> (" + t.pourcent + " % du nombre total)";
                    if ("dep" == e) var a = "Département " + o + " : <b>" + t.nb + "</b> (" + t.pourcent + " % du nombre total)";
                    if ("l93" == e || "utm" == e) var a = "Maille 10 " + o + " : <b>" + t.nb + "</b> (" + t.pourcent + " % du nombre total)";
                    if ("maille5" == e) var a = "Maille 5 " + o + " : <b>" + t.nb + "</b> (" + t.pourcent + " % du nombre total)";
                    $("#infonbobs").html(a)
                }
                "com" != e && "l93" != e && "maille5" != e && "utm" != e || ($("#mapdetail").show(), carteleaflet(t.carto))
            }
        }
    })
}

function carteleaflet(e) {
    "use strict";
    var t = {color: "#ff7800", weight: 5, opacity: .65};
    if (proj4.defs("EPSG:2154", "+proj=lcc +lat_1=49 +lat_2=44 +lat_0=46.5 +lon_0=3 +x_0=700000 +y_0=6600000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs"), "oui" == nbmap) {
        nbmap = "non", map = new L.map("mapdetail");
        var o = new L.TileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
        });
        map.addLayer(o), cartoleaflet = L.Proj.geoJson(e, {style: t}).addTo(map), map.fitBounds(cartoleaflet.getBounds())
    } else cartoleaflet = L.Proj.geoJson(e, {style: t}).addTo(map), map.fitBounds(cartoleaflet.getBounds())
}

var dep, reinfo, couleur1, couleur2;
$(document).ready(function () {
    "use strict";
    liste_dep();
    couleur1 = $("#couleur1").css("backgroundColor"), couleur2 = $("#menu").css("backgroundColor"), $("#mapdetail").hide();
    var e = $("#choixcarte").val(), t = $("#aves").val(), o = $("#emp").val(), i = $("#iddep").val();
    "maille" == e && $("#maille").attr("checked", "checked"), carte(e), dep = "oui" == $("#contour2").val() ? $.getJSON("../emprise/contour2.geojson", function (e) {
        dep = Highcharts.geojson(e, "mapline")
    }) : "", "fr" != o && "oui" == t && cartenicheur(), nbobs("aucun", "aucun", "aucun")
}), $("input[name=choixcarte]").change(function () {
    "use strict";
    var e = $("input[name=choixcarte]:checked").val();
    if ("commune" == e && ($("#selectiondepartement").show(), $("#titrecarte").html("Nombre d'espèces par commune"), $("#container").html('<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la carte...</p></div>'), carte(e, i)), "dep" == e && ($("#titrecarte").html("Nombre d'espèces par département"), $("#container").html('<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la carte...</p></div>'), carte(e, i)), "maille" == e) {
        $("#container").html('<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la carte...</p></div>');
        var t = $("#utm").val();
        "oui" == t ? ($("#titrecarte").html("Nombre d'espèces par maille UTM"), $("#selectiondepartement").hide()) : $("#titrecarte").html("Nombre d'espèces par maille 10 x 10"), $("#selectiondepartement").hide(), carte(e)
    }
    "maille5" == e && ($("#selectiondepartement").hide(), $("#titrecarte").html("Nombre d'espèces par maille 5 x 5"), carte(e, i)), "oui" == reinfo && ($("#infonbobs").html($("#nbobstotal").val()), $("#lienid").html(""), reinfo = "non")
}), Highcharts.setOptions({
    lang: {
        contextButtonTitle: "Menu exportation",
        downloadPNG: "Télécharger au format PNG",
        downloadJPEG: "Télécharger au format JPG",
        downloadPDF: "Télécharger au format PDF",
        downloadSVG: "Télécharger au format SVG",
        exportButtonTitle: "Exporter image ou document",
        printChart: "Imprimer",
        zoomIn: "Zoom +",
        zoomOut: "Zoom -",
        loading: "Chargement..."
    }
});
var map, nbmap = "oui", cartoleaflet;

$("#iddep").change(function () { // Chargement de la carte départementale au choix de l'utilisateur
    $("#container").html('<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la carte...</p></div>');
    i = $("#iddep").val();
    carte("commune", i);
});