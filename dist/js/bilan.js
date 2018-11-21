function liste_dep() { // Afficher les départements de l'emprise
    "use strict";
    $.ajax({
        url: "modeles/ajax/bilan/listedep.php",
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

function carte(e, i='%' ) {
    // "use strict";
    var t = $("#utm").val(), a = $("#emp").val(), o = $("#contour2").val();
    $.ajax({
        url: "modeles/ajax/bilan/cartebilan.php",
        type: "POST",
        dataType: "json",
        data: {choixcarte: e, utm: t, emp: a, cont: o, iddep: i},
        success: function (e) {
            "Oui" == e.statut && ("non" == e.maille && cartecommune(e.data, e.carto, e.maxnb, e.dep), "oui" == e.maille && (e.maille5 ? cartemaille5(e.data, e.carto, e.maxnb) : cartemaille(e.data, e.carto, e.maxnb)))
        }
    })
}

function cartecommune(e, t, a, o) {
    "use strict";
    var r = "oui" == o ? "dep" : "com", i = Highcharts.geojson(t, "map");
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
            dataClasses: [{to: .1 * a}, {from: .1 * a, to: .26 * a}, {from: .26 * a, to: .42 * a}, {
                from: .42 * a,
                to: .58 * a
            }, {from: .58 * a, to: .74 * a}, {from: .74 * a, to: .9 * a}, {from: .9 * a}]
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
                            nbespece(r, this.id, this.nom)
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
            name: "nom",
            borderColor: "black",
            borderWidth: 1,
            cursor: "pointer",
            states: {hover: {borderWidth: 1.5}}
        }, {data: dep, type: "mapline", lineWidth: 2.5, color: "black", enableMouseTracking: !1}]
    })
}

function cartemaille(e, t, a) {
    "use strict";
    var i = Highcharts.geojson(t, "map");

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
                dataClasses: [{to: .1 * a}, {from: .1 * a, to: .26 * a}, {
                    from: .26 * a,
                    to: .42 * a
                }, {from: .42 * a, to: .58 * a}, {from: .58 * a, to: .74 * a}, {
                    from: .74 * a,
                    to: .9 * a
                }, {from: .9 * a}], dataClassColor: "category"
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
                                nbespece("maille", this.id, this.nom)
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
            },  {data: dep, type: "mapline", lineWidth: 2, color: "black", enableMouseTracking: !1}]
        })
}

function cartemaille5(e, t, a) {
    $.getJSON("emprise/contour2.geojson", function (o) {
        var r = Highcharts.geojson(o, "mapline"), i = Highcharts.geojson(t, "mapline"),
            l = Highcharts.geojson(t, "map");
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
                dataClasses: [{to: .1 * a}, {from: .1 * a, to: .26 * a}, {
                    from: .26 * a,
                    to: .42 * a
                }, {from: .42 * a, to: .58 * a}, {from: .58 * a, to: .74 * a}, {
                    from: .74 * a,
                    to: .9 * a
                }, {from: .9 * a}], dataClassColor: "category"
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
                                nbespece("maille5", this.id, this.nom)
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
                mapData: l,
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

function nbespece(e, t, a) {
    "use strict";
    if (cartoleaflet && (map.removeLayer(cartoleaflet), cartoleaflet = null), "aucun" != e) {
        if ($("#graphespece").html('<div class="m-t-xl"><p class="text-warning text-xs-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement du graph...</p></div>'), "com" == e) if ($("#titregraph").html("Commune de " + a), "oui" == $("#contour2").val()) {
            var o = t.toString(), r = o.substring(0, 2);
            $("#lienid").html('<a href="index.php?module=commune&amp;action=commune&amp;codecom=' + t + '">Liste des espèces de ' + a + '</a> - <a href="index.php?module=depart&amp;action=depart&amp;iddep=' + r + '">Liste des espèces du ' + r + "</a>")
        } else $("#lienid").html('<a href="index.php?module=commune&amp;action=commune&amp;codecom=' + t + '">Liste des espèces de ' + a + "</a>");
        if ("dep" == e && ($("#titregraph").html("Département de " + a), $("#lienid").html('<a href="index.php?module=depart&amp;action=depart&amp;iddep=' + t + '">Liste des espèces de ' + a + "</a>"), $("#cachemap").hide()), "maille" == e) {
            var i = $("#utm").val();
            e = "non" == i ? "l93" : "utm", $("#cachemap").show(), $("#titregraph").html("Maille 10 " + a), $("#lienid").html('<a href="index.php?module=maille&amp;action=maille&amp;maille=' + t + '">Liste des espèces de la la maille ' + a + "</a>")
        }
        "maille5" == e && ($("#titregraph").html("Maille 5 " + a), $("#lienid").html('<a href="index.php?module=maille&amp;action=maille5&amp;maille=' + t + '">Liste des espèces de la la maille ' + a + "</a>")), regraph = "oui"
    }
    $.ajax({
        url: "modeles/ajax/bilan/graphbilan.php",
        type: "POST",
        dataType: "json",
        data: {choix: e, id: t},
        success: function (t) {
            "Oui" == t.statut && (t.graph ? ($("#graphespece").hasClass("cartefiche") ? graphespece(t.data) : ($("#graphespece").addClass("minigraph cartefiche"), graphespece(t.data)), $("#grapheobs").hasClass("cartefiche") ? grapheobs(t.datao) : ($("#grapheobs").addClass("minigraph cartefiche"), grapheobs(t.datao))) : ($("#graphespece").removeClass("minigraph cartefiche"), $("#graphespece").html(t.data), $("#grapheobs").removeClass("minigraph cartefiche"), $("#grapheobs").html(""), t.lien && $("#lienid").html("")), "com" != e && "l93" != e && "maille5" != e && "utm" != e || carteleaflet(t.carto))
        }
    })
}

function graphespece(e) {
    $("#graphespece").highcharts({
        chart: {type: "pie"},
        credits: {enabled: !1},
        title: {text: ""},
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
        tooltip: {pointFormat: "<b>{point.y}</b>", valueSuffix: " espèces <br>({point.percentage:.1f}%)"},
        plotOptions: {pie: {allowPointSelect: !0, cursor: "pointer", showInLegend: !1}},
        series: [{data: e}]
    })
}

function grapheobs(e) {
    $("#grapheobs").highcharts({
        chart: {type: "pie"},
        credits: {enabled: !1},
        title: {text: ""},
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
        tooltip: {pointFormat: "<b>{point.y}</b>", valueSuffix: " observations <br>({point.percentage:.1f}%)"},
        plotOptions: {pie: {allowPointSelect: !0, cursor: "pointer", showInLegend: !1}},
        series: [{data: e}]
    })
}

function carteleaflet(e) {
    "use strict";
    var t = {color: "#ff7800", weight: 5, opacity: .65};
    if (proj4.defs("EPSG:2154", "+proj=lcc +lat_1=49 +lat_2=44 +lat_0=46.5 +lon_0=3 +x_0=700000 +y_0=6600000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs"), "oui" == nbmap) {
        nbmap = "non", map = new L.map("mapdetail");
        var a = new L.TileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
        });
        map.addLayer(a), cartoleaflet = L.Proj.geoJson(e, {style: t}).addTo(map), map.fitBounds(cartoleaflet.getBounds())
    } else cartoleaflet = L.Proj.geoJson(e, {style: t}).addTo(map), map.fitBounds(cartoleaflet.getBounds())
}

var dep, regraph, couleur1, couleur2;
$(document).ready(function () {
    "use strict";
    liste_dep();
    couleur1 = $("#couleur1").css("backgroundColor"), couleur2 = $("#menu").css("backgroundColor");
    var e = $("#choixcarte").val();
    "dep" == e && $("#cachemap").hide(), "maille" == e && $("#maille").attr("checked", "checked"), carte(e), dep = "oui" == $("#contour2").val() ? $.getJSON("emprise/contour2.geojson", function (e) {
        dep = Highcharts.geojson(e, "mapline")
    }) : "", nbespece("aucun", "aucun", "aucun")
}), $("input[name=choixcarte]").change(function () {
    "use strict";
    var e = $("input[name=choixcarte]:checked").val();
    var i = $("#iddep").val();
    if ("commune" == e && ($("#titrecarte").html("Nombre d'espèces par commune"), $("#container").html('<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la carte...</p></div>'), carte(e, i)), "dep" == e && ($("#titrecarte").html("Nombre d'espèces par département"), $("#container").html('<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la carte...</p></div>'), carte(e, i)), "maille" == e) {
        $("#container").html('<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la carte...</p></div>');
        var t = $("#utm").val();
        var i = $("#iddep").val();
        "oui" == t ? $("#titrecarte").html("Nombre d'espèces par maille UTM") : $("#titrecarte").html("Nombre d'espèces par maille 10 x 10"), carte(e, i)
    }
    "maille5" == e && ($("#container").html('<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la carte...</p></div>'), $("#titrecarte").html("Nombre d'espèces par maille 5 x 5"), carte(e, i)), "oui" == regraph && (nbespece("aucun", "aucun", "aucun"), $("#titregraph").html("Nombre d'espèces et d'observations par observatoire"), $("#lienid").html(""), regraph = "non")
}), Highcharts.setOptions({
    exporting: {
        chartOptions: {
            chart: {
                backgroundColor: '#FFFFFF'
            }
        }
    },
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
    }, title: {text: ""}, credits: {enabled: !1}
});
var map, nbmap = "oui", cartoleaflet;

$("#iddep").change(function () { // Chargement de la carte départementale au choix de l'utilisateur
    $("#container").html('<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la carte...</p></div>');
    i = $("#iddep").val();
    var e = $("input[name=choixcarte]:checked").val();
    carte(e, i);
});