function carte() {
    "use strict";
    var e = $("#nomvar").val(), a = $("#emp").val(), t = $("#choixcarte").val();
    $.ajax({
        url: "modeles/ajax/bilan/carteprospection.php",
        type: "POST",
        dataType: "json",
        data: {choixcarte: t, nomvar: e, emp: a},
        success: function (e) {
            "Oui" == e.statut && ($("#nbmax").html("&nbsp;&nbsp;&nbsp;&nbsp;" + e.nbmax), nbvalue = Math.round(.1 * e.nbsp), $("#nbaffiche").text(nbvalue + " (10 %) "), generateSlider(e.nbmax), datanew = affichenbmax(e.data, nbvalue), cartemaille(datanew, e.data, e.carto, e.nbmax, nbvalue))
        }
    })
}

function affichenbmax(e, a) {
    "use strict";
    return datanew = [], e.forEach(function (e) {
        e.value <= a ? datanew.push({
            nom: e.id,
            id: e.id,
            value: e.value,
            info: "Nombre d'espèces :" + e.value
        }) : datanew.push({nom: e.id, id: e.id, value: -1, info: "Maille avec plus de " + a + " espèces"})
    }), datanew
}

function cartemaille(e, a, t, o, n) {
    "use strict";
    $.getJSON("../emprise/contour2.geojson", function (n) {
        var r = Highcharts.geojson(n, "mapline"), i = Highcharts.geojson(t, "map");
        var l = Highcharts.Map("container", {
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
                enabled: true,
                floating: !0,
                layout: "vertical",
                valueDecimals: 0,
                symbolRadius: 0,
                symbolHeight: 14,
                x: -10,
                y: 0
            },
            title: {text: ""},
            credits: {enabled: !1},
            colors: ["#ffffff", "#d73027", "#fc8d59", "#fee08b", "#ffffbf", "#d9ef8b", "#91cf60", "#1a9850"],
            colorAxis: {
                dataClassColor: "category",
                dataClasses: [{to: -1}, {from: 0, to: 1}, {from: 1, to: .01 * o}, {
                    from: .01 * o,
                    to: .1 * o
                }, {from: .1 * o, to: .25 * o}, {from: .25 * o, to: .5 * o}, {
                    from: .5 * o,
                    to: .75 * o
                }, {from: .75 * o}]
            },
            plotOptions: {
                series: {
                    point: {
                        events: {
                            click: function () {
                                this.value >= 0 && affichemaille(this.id)
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
            }, {data: dep, type: "mapline", lineWidth: 1.5, color: "black", enableMouseTracking: !1}]
        });
        mySlider.on("change", function () {
            var e = Math.round(mySlider.getValue());
            $("#nbaffiche").text(e), datanew = [], a.forEach(function (a) {
                a.value <= e ? datanew.push({
                    nom: a.id,
                    id: a.id,
                    value: a.value,
                    info: "Nombre d'espèces :" + a.value
                }) : datanew.push({nom: a.id, id: a.id, value: -1, info: "Maille avec plus de " + e + " espèces"})
            }), l.series[0].setData(datanew)
        })
    })
}

function generateSlider(e) {
    "use strict";
    $("#slider").css({width: "300px", "text-align": "center"}), mySlider = new Slider("#sliderControl", {
        value: nbvalue,
        min: 0,
        max: e,
        step: 1
    }), $("#slider .slider-track-high").css({background: maxcolor}), $("#slider .slider-handle").css({background: maxcolor})
}

function affichemaille(e) {
    "use strict";
    cartoleaflet && (map.removeLayer(cartoleaflet), cartoleaflet = null);
    var a = $("#choixcarte").val();
    $.ajax({
        url: "modeles/ajax/bilan/leafletprospection.php",
        type: "POST",
        dataType: "json",
        data: {choix: a, id: e},
        success: function (e) {
            "Oui" == e.statut && carteleaflet(e.carto)
        }
    })
}

function carteleaflet(e) {
    "use strict";
    var a = {color: "#ff7800", weight: 5, opacity: .65, fillOpacity: 0};
    if (proj4.defs("EPSG:2154", "+proj=lcc +lat_1=49 +lat_2=44 +lat_0=46.5 +lon_0=3 +x_0=700000 +y_0=6600000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs"), "oui" == nbmap) {
        nbmap = "non", map = new L.map("carteleaflet");
        var t = new L.TileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
        });
        map.addLayer(t), cartoleaflet = L.Proj.geoJson(e, {style: a}).addTo(map), map.fitBounds(cartoleaflet.getBounds()), generateprint()
    } else cartoleaflet = L.Proj.geoJson(e, {style: a}).addTo(map), map.fitBounds(cartoleaflet.getBounds())
}

function generateprint() {
    "use strict";
    var e = L.Control.extend({
        options: {title: "Imprimer la carte", position: "topleft"}, onAdd: function (e) {
            var a = L.DomUtil.create("div", "leaflet-control-easyPrint leaflet-bar leaflet-control");
            return this.link = L.DomUtil.create("a", "leaflet-bar-part curseurlien", a), this.link.id = "leafletEasyPrint", this.link.title = this.options.title, L.DomEvent.addListener(this.link, "click", printPage, this.options), a
        }
    });
    map.addControl(new e)
}

function printPage() {
    $("#carteleaflet").print()
}

var dep, mySlider, nbvalue, maxcolor, datanew, carte;
$(document).ready(function () {
    "use strict";
    maxcolor = $("#maxcolor").val(), carte(choixcarte), dep = "oui" == $("#contour2").val() ? $.getJSON("../emprise/contour2.geojson", function (e) {
        dep = Highcharts.geojson(e, "mapline")
    }) : ""
});
var map, nbmap = "oui", cartoleaflet;