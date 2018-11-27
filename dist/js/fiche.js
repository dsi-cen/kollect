function general(e) {
    "use strict";
    var t = $("#emprise").val(), a = $("#cdnom").val(), o = $("#utm").val(), i = $("#rangsp").val(),
        n = $("#nomvar").val(), r = $("#sensible").val();
    $.ajax({
        url: "modeles/ajax/fiche/general.php",
        type: "POST",
        dataType: "json",
        data: {emprise: t, choixcarte: e, cdnom: a, utm: o, rang: i, nomvar: n, sensible: r},
        success: function (e) {
            "Oui" == e.statut && ("non" == e.sensible ? e.ras ? ($("#categen").html(e.ras), $("#categen").removeClass("curseurlien")) : ("non" == e.maille && carteminicommune(e.data, e.carto), "oui" == e.maille && carteminimaille(e.data, e.carto)) : ($("#categen").html(""), $("#categen").removeClass("curseurlien")), e.tab ? minigraphpheno(e.tab) : ($("#minigraphpheno").html(""), $("#minigraphpheno").removeClass("minigraph")))
        }
    })
}

function carte(e) {
    "use strict";
    var t = $("#emprise").val(), a = $("#cdnom").val(), o = $("#utm").val(), i = $("#rangsp").val(),
        n = $("#nomvar").val(), r = $("#nom").val();
    $.ajax({
        url: "modeles/ajax/fiche/cartefiche.php",
        type: "POST",
        dataType: "json",
        data: {emprise: t, choixcarte: e, cdnom: a, utm: o, rang: i, nomvar: n},
        success: function (e) {
            if ("Oui" == e.statut) {
                var t = e.data, a = e.carto;
                e.nbnouv ? $("#nouvemp").html('<i class="fa fa-square fa-lg" style="color:' + e.cnouv + '"></i> ' + e.nbnouv + "<br>") : $("#nouvemp").html(""), "non" == e.maille && cartecommune(t, a, r), "oui" == e.maille && (e.maille5 ? cartemaille5(t, a, r, e.maille10) : cartemaille(t, a, r))
            }
        }
    })
}

function carteminicommune(e, t) {
    var a = Highcharts.geojson(t, "map");
    $("#categen").highcharts("Map", {
        legend: {enabled: !1},
        exporting: {enabled: !1},
        series: [{
            mapData: a,
            data: e,
            joinBy: ["id", "id"],
            name: "Commune",
            borderColor: "black",
            borderWidth: 1,
            enableMouseTracking: !1
        }, {data: dep, type: "mapline", lineWidth: 2, color: "black", enableMouseTracking: !1}]
    })
}

function carteminimaille(e, t) {
    $.getJSON("../emprise/contour2.geojson", function (a) {
        var o = Highcharts.geojson(a, "mapline"), i = Highcharts.geojson(t, "map");
        $("#categen").highcharts("Map", {
            legend: {enabled: !1},
            exporting: {enabled: !1},
            series: [{
                mapData: i,
                type: "map",
                data: e,
                joinBy: ["id", "id"],
                name: "Observation(s)",
                borderColor: "black",
                borderWidth: .5,
                enableMouseTracking: !1
            }, {
                data: o,
                type: "mapline",
                name: "Commune",
                lineWidth: .3,
                color: "black",
                enableMouseTracking: !1
            }, {data: dep, type: "mapline", lineWidth: 1.5, color: "black", enableMouseTracking: !1}]
        })
    })
}

function cartecommune(e, t, a) {
    var o = "fr" != $("#emprise").val() ? "com" : "dep", i = Highcharts.geojson(t, "map");
    var taxon = $('h2.tleg i').text(), date = $('h2.tleg small').text();
    $("#container").highcharts("Map", {
            title: {
                text: '<i>' + taxon + '</i> - <small>' + date + '</small>',
                useHTML: true
            },
        legend: {enabled: !1},
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
        exporting: {enabled: 1},
        plotOptions: {
            series: {
                point: {
                    events: {
                        click: function () {
                            ouvremodal(this.id, o, this.nom)
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
        }, {data: dep, type: "mapline", lineWidth: 2, color: "black", enableMouseTracking: !1}]
    })
}

function cartemaille(e, t, a) {
    var o = "oui" == $("#utm").val() && "fr" == $("#emprise").val() ? $.getJSON("../emprise/mgrs100.geojson", function (e) {
        o = Highcharts.geojson(e, "mapline")
    }) : "";
    $.getJSON("../emprise/contour2.geojson", function (a) {
        var i = Highcharts.geojson(a, "mapline"), n = Highcharts.geojson(t, "map");
        var taxon = $('h2.tleg i').text(), date = $('h2.tleg small').text();
        $("#container").highcharts("Map", {
            legend: {enabled: !1},
            title: {
                text: '<i>' + taxon + '</i> - <small>' + date + '</small>',
                useHTML: true
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
            exporting: {enabled: 1},
            plotOptions: {
                series: {
                    point: {
                        events: {
                            click: function () {
                                ouvremodal(this.id, "maille", this.nom)
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
                name: "Commune",
                lineWidth: .3,
                color: "black",
                enableMouseTracking: !1
            }, {data: dep, type: "mapline", lineWidth: 1.5, color: "black", enableMouseTracking: !1}, {
                data: o,
                type: "mapline",
                lineWidth: .3,
                color: "black",
                enableMouseTracking: !1
            }]
        })
    })
}

function cartemaille5(e, t, a, o) {
    $.getJSON("../emprise/contour2.geojson", function (a) {
        var i = Highcharts.geojson(a, "mapline"), n = Highcharts.geojson(o, "map"), r = Highcharts.geojson(t, "map");
        var taxon = $('h2.tleg i').text(), date = $('h2.tleg small').text();
        $("#container").highcharts("Map", {
            legend: {enabled: !1},
            title: {
                text: '<i>' + taxon + '</i> - <small>' + date + '</small>',
                useHTML: true
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
            exporting: {enabled: 1},
            plotOptions: {
                series: {
                    point: {
                        events: {
                            click: function () {
                                ouvremodal(this.id, "maille5", this.nom)
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
                mapData: r,
                type: "map",
                data: e,
                joinBy: ["id", "id"],
                name: "Observation(s)",
                borderColor: "black",
                borderWidth: .5,
                cursor: "pointer",
                states: {hover: {borderWidth: 1.5}}
            }, {
                data: n,
                type: "mapline",
                name: "Maille",
                lineWidth: .5,
                color: "black",
                dashStyle: "LongDash",
                enableMouseTracking: !1
            }, {
                data: i,
                type: "mapline",
                name: "Commune",
                lineWidth: .3,
                color: "black",
                enableMouseTracking: !1
            }, {data: dep, type: "mapline", lineWidth: 1.5, color: "black", enableMouseTracking: !1}]
        })
    })
}

function graph() {
    var e = $("#cdnom").val(), t = $("#rangsp").val(), a = $("#nomvar").val();
    $.ajax({
        url: "modeles/ajax/fiche/graphfiche.php",
        type: "POST",
        dataType: "json",
        data: {cdnom: e, rang: t, nomvar: a},
        success: function (e) {
            if ("Oui" == e.statut) {
                var t = e.decade, a = e.serie2, o = e.serie3, i = e.serie6, n = e.serie13, r = e.serie1, s = e.tab2,
                    l = e.tab3, c = e.tab6, p = e.tab13, m = e.tab1;
                graphpheno(t, a, o, i, n, r, s, l, c, p, m)
            }
        }
    })
}

function minigraphpheno(e) {
    $("#minigraphpheno").highcharts({
        chart: {type: "column"},
        exporting: {enabled: !1},
        xAxis: {categories: ["Ja", "F", "Ma", "Av", "M", "Ju", "Jl", "A", "S", "O", "N", "D"]},
        yAxis: {min: 0, allowDecimals: !1, title: {text: ""}},
        tooltip: {
            formatter: function () {
                var e, t = this.y > 1 ? "observations" : "observation";
                return "Ja" == this.x && (e = "Janvier"), "F" == this.x && (e = "Février"), "Ma" == this.x && (e = "Mars"), "Av" == this.x && (e = "Avril"), "M" == this.x && (e = "Mai"), "Ju" == this.x && (e = "Juin"), "Jl" == this.x && (e = "Juillet"), "A" == this.x && (e = "Août"), "S" == this.x && (e = "Septembre"), "O" == this.x && (e = "Octobre"), "N" == this.x && (e = "Novembre"), "D" == this.x && (e = "Décembre"), "<b>" + e + " :</b> " + this.y + " " + t + "<br/>Cliquer pour plus de détail"
            }
        },
        plotOptions: {column: {stacking: "normal", pointPadding: 0, borderWidth: 0}},
        series: [{name: "Observations par mois (observé vivant)", data: e}]
    })
}

function graphpheno(e, t, a, o, i, n, r, s, l, c, p) {
    var m = $(".tlegpheno").text(), h = $("#nom").val(), u = e, d = !0, b = !0, g = !0, f = !0, v = !0,
        y = "myPlotLine", x = {color: "#FF0000", id: y, width: 2, value: u};
    "non" == t && (d = !1), "non" == a && (b = !1), "non" == o && (g = !1), "non" == i && (f = !1), "non" == n && (v = !1), $("#graphpheno").highcharts({
        chart: {type: "column"},
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
        exporting: {chartOptions: {title: {text: m}}, filename: "Phéno-" + h},
        xAxis: {
            categories: ["Ja1", "Ja2", "Ja3", "Fe1", "Fe2", "Fe3", "Ma1", "Ma2", "Ma3", "Av1", "Av2", "Av3", "M1", "M2", "M3", "Ju1", "Ju2", "Ju3", "Jl1", "Jl2", "Jl3", "A1", "A2", "A3", "S1", "S2", "S3", "O1", "O2", "O3", "N1", "N2", "N3", "D1", "D2", "D3"],
            plotLines: [x],
            labels: {staggerLines: 2}
        },
        yAxis: {min: 0, allowDecimals: !1, title: {text: "Nombre d'observations", style: {fontWeight: "bold"}}},
        tooltip: {
            formatter: function () {
                return "<b>" + this.x + "</b><br/>" + this.series.name + ": " + this.y
            }
        },
        plotOptions: {column: {stacking: "normal", pointPadding: 0, borderWidth: 0}},
        series: [{showInLegend: d, name: t, data: r}, {showInLegend: b, name: a, data: s}, {
            showInLegend: g,
            name: o,
            data: l
        }, {showInLegend: f, name: i, data: c}, {showInLegend: v, name: n, data: p}, {
            color: "#FF0000",
            name: "Décade actuelle",
            marker: {enabled: !1},
            events: {
                legendItemClick: function (e) {
                    this.visible ? this.chart.xAxis[0].removePlotLine(y) : this.chart.xAxis[0].addPlotLine(x)
                }
            }
        }]
    })
}

function observateur() {
    "use strict";
    var e = $("#cdnom").val(), t = $("#rangsp").val(), a = $("#nomvar").val();
    $.ajax({
        url: "modeles/ajax/fiche/obserfiche.php",
        type: "POST",
        dataType: "json",
        data: {cdnom: e, rang: t, nomvar: a},
        success: function (e) {
            "Oui" == e.statut && ($("#nbobser").html("(" + e.nb + ")"), $("#listeobser").html(e.listeobser))
        }
    })
}

function info() {
    "use strict";
    var e = $("#cdnom").val(), t = $("#rangsp").val(), a = $("#nomvar").val();
    $.ajax({
        url: "modeles/ajax/fiche/info.php",
        type: "POST",
        dataType: "json",
        data: {cdnom: e, rang: t, nomvar: a},
        success: function (e) {
            "Oui" == e.statut && ($("#infomax").html(e.obsmax), $("#extrememin").html(e.extrememin), $("#extrememax").html(e.extrememax), $("#derniere").html(e.derniere), e.altimin && $("#afalt").html('<h3 class="h5">Altitude</h3><p>' + e.altimin + "<br />" + e.altimax + "</p>"), graphenbobs(e.tabclass, e.tabnb), e.graphetat ? ($("#grapheetatbio").addClass("miniminigraph"), grapheetatbio(e.dataetat)) : $("#grapheetatbio").html("<p>Pour 100 % des observations : " + e.dataetat), grapheprospect(e.dataprospect), e.graphmethode ? ($("#graphemethode").addClass("miniminigraph"), graphemethode(e.datamethode)) : $("#graphemethode").html("<p>Pour 100 % des observations : " + e.datamethode))
        }
    })
}

function graphenbobs(e, t) {
    "use strict";
    $("#graphenbobs").highcharts({
        chart: {type: "line"},
        title: {text: ""},
        credits: {enabled: !1},
        exporting: {enabled: !1},
        xAxis: {categories: e, title: {text: "Années"}},
        yAxis: {title: {text: ""}},
        tooltip: {shared: !0},
        series: [{name: "Nombre d'observations", data: t}]
    })
}

function grapheetatbio(e) {
    "use strict";
    $("#grapheetatbio").highcharts({
        chart: {type: "pie"},
        title: {text: ""},
        credits: {enabled: !1},
        exporting: {enabled: !1},
        tooltip: {pointFormat: "<b>{point.y}</b>", valueSuffix: " observations <br>({point.percentage:.1f}%)"},
        plotOptions: {pie: {allowPointSelect: !0, cursor: "pointer", showInLegend: !1}},
        series: [{data: e}]
    })
}

function grapheprospect(e) {
    "use strict";
    $("#grapheprospect").highcharts({
        chart: {type: "pie"},
        title: {text: ""},
        credits: {enabled: !1},
        exporting: {enabled: !1},
        tooltip: {pointFormat: "<b>{point.y}</b>", valueSuffix: " observations <br>({point.percentage:.1f}%)"},
        plotOptions: {pie: {allowPointSelect: !0, cursor: "pointer", showInLegend: !1}},
        series: [{data: e}]
    })
}

function graphemethode(e) {
    "use strict";
    $("#graphemethode").highcharts({
        chart: {type: "pie"},
        title: {text: ""},
        credits: {enabled: !1},
        exporting: {enabled: !1},
        tooltip: {pointFormat: "<b>{point.y}</b>", valueSuffix: " observations <br>({point.percentage:.1f}%)"},
        plotOptions: {pie: {allowPointSelect: !0, cursor: "pointer", showInLegend: !1}},
        series: [{data: e}]
    })
}

function indice() {
    "use strict";
    var e = $("#cdnom").val(), t = $("#nomvar").val();
    $.ajax({
        url: "modeles/ajax/fiche/indice.php",
        type: "POST",
        dataType: "json",
        data: {cdnom: e, nomvar: t},
        success: function (e) {
            "Oui" == e.statut && $("#listeindice").html(e.liste)
        }
    })
}

function nicheur() {
    function e(e, t, o) {
        $.getJSON("../emprise/contour.geojson", function (s) {
            var l = Highcharts.geojson(s, "mapline"), c = Highcharts.geojson(e, "map"),
                p = t.certain ? Highcharts.geojson(t.certain, "mappoint") : "",
                m = t.possible ? Highcharts.geojson(t.possible, "mappoint") : "",
                h = t.probable ? Highcharts.geojson(t.probable, "mappoint") : "", u = Highcharts.Map("cartenicheur", {
                    legend: {enabled: !1},
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
                    exporting: {chartOptions: {title: {text: nom}}, filename: nom},
                    plotOptions: {
                        series: {
                            tooltip: {
                                headerFormat: "",
                                pointFormat: "{point.properties.id} : {point.properties.info}"
                            }
                        }
                    },
                    series: [{
                        data: p,
                        type: "mappoint",
                        name: "certain",
                        marker: {symbol: "circle", radius: 10},
                        color: "#FF0000"
                    }, {
                        data: h,
                        type: "mappoint",
                        name: "probable",
                        marker: {symbol: "circle", radius: 10},
                        color: "#FFAA00"
                    }, {
                        data: m,
                        type: "mappoint",
                        name: "possible",
                        marker: {symbol: "circle", radius: 10},
                        color: "#FFFF00"
                    }, {
                        data: c,
                        type: "mapline",
                        name: "Maille",
                        lineWidth: .5,
                        color: "black",
                        enableMouseTracking: !1
                    }, {
                        data: l,
                        type: "mapline",
                        name: "Commune",
                        lineWidth: .3,
                        color: "black",
                        enableMouseTracking: !1
                    }, {data: dep, type: "mapline", lineWidth: 1.5, color: "black", enableMouseTracking: !1}]
                });
            r.on("change", function () {
                var e = r.getValue();
                i = e[0], n = e[1];
                var t = a(o), s = t.certain ? Highcharts.geojson(t.certain, "mappoint") : "",
                    l = t.possible ? Highcharts.geojson(t.possible, "mappoint") : "",
                    c = t.probable ? Highcharts.geojson(t.probable, "mappoint") : "";
                u.series[0].setData(s), u.series[1].setData(c), u.series[2].setData(l), $("#annicheur").text(i + " - " + n)
            })
        })
    }

    function t() {
        "use strict";
        $("#slideraves").css({
            "text-align": "center",
            width: "100%"
        }), r = new Slider("#sliderControlaves", {
            value: [i, n],
            min: i,
            max: n,
            step: 1
        }), $("#slideraves .slider-horizontal").css({width: "60%"}), $("#slideraves .slider-selection").css({background: couleur1}), $("#slideraves .slider-track-low").css({background: couleur2}), $("#slideraves .slider-track-high").css({background: couleur2}), $("#slideraves .slider-handle").css({background: couleur1}), $("#anminaves").html(i + "&nbsp;&nbsp;&nbsp;&nbsp"), $("#anmaxaves").html("&nbsp;&nbsp;&nbsp;&nbsp;" + n)
    }

    function a(e) {
        "use strict";
        var t = {};
        e.forEach(function (e) {
            e.annee >= i && e.annee <= n && (t[e.codel93] = {code: e.code, id: e.codel93, point: e.point})
        });
        var e = o(t);
        return e
    }

    function o(e) {
        "use strict";
        var t = {}, a = {type: "FeatureCollection", features: []}, o = {type: "FeatureCollection", features: []},
            i = {type: "FeatureCollection", features: []}, n = 0, r = 0, s = 0;
        return $.each(e, function (e, l) {
            if (l.code <= 3) {
                var c = l.point, p = {id: l.id, info: "Nidification possible"};
                a.features.push({type: "Feature", properties: p, geometry: c}), t.possible = a, s += 1
            } else if (l.code > 3 && l.code <= 10) {
                var c = l.point, p = {id: l.id, info: "Nidification probable"};
                o.features.push({type: "Feature", properties: p, geometry: c}), t.probable = o, r += 1
            } else if (l.code > 10) {
                var c = l.point, p = {id: l.id, info: "Nidification certaine"};
                i.features.push({type: "Feature", properties: p, geometry: c}), t.certain = i, n += 1
            }
        }), $("#nc").html(n), $("#npr").html(r), $("#np").html(s), t
    }

    var i, n, r, s = $("#cdnom").val();
    $.ajax({
        url: "modeles/ajax/fiche/aves.php",
        type: "POST",
        dataType: "json",
        data: {cdnom: s},
        success: function (o) {
            if ("Oui" == o.statut) if ("oui" == o.nicheur) {
                i = o.min, n = o.max;
                var r = a(o.data);
                e(o.carto, r, o.data), t()
            } else $("#cartenicheur").html("Aucune nidfication pour cette espèce."), $("#slideraves").html("")
        }
    })
}

function biogeo() {
    "use strict";
    var e = $.getJSON("../emprise/refgeos.geojson", function (t) {
        e = Highcharts.geojson(t, "map")
    }), t = $("#cdnom").val(), a = $("#utm").val(), o = $("#rangsp").val(), i = $("#nomvar").val();
    $.ajax({
        url: "modeles/ajax/fiche/cartebio.php",
        type: "POST",
        dataType: "json",
        data: {cdnom: t, utm: a, rang: o, nomvar: i},
        success: function (t) {
            "Oui" == t.statut && (t.data ? (graphebiogeo(t.data, t.color), cartebiogeo(t.carto, t.bio, e)) : ($("#cartebiogeo").html(""), $("#graphebiogeo").html("")))
        }
    })
}

function cartebiogeo(e, t, a) {
    "use strict";
    var o = $("#nom").val(), i = $("#emprise").val(), n = "fr" == i ? "map" : "mapline";
    $.getJSON("../emprise/contour.geojson", function (i) {
        var r = Highcharts.geojson(i, "mapline"), s = Highcharts.geojson(e, "map");
        $("#cartebiogeo").highcharts("Map", {
            legend: {enabled: !1},
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
                buttonOptions: {verticalAlign: "top", align: "right"},
                buttons: {
                    zoomIn: {style: {color: "white"}, theme: {fill: couleur1, "stroke-width": 0, r: 0}},
                    zoomOut: {style: {color: "white"}, theme: {fill: couleur2, "stroke-width": 0, r: 0}}
                }
            },
            exporting: {chartOptions: {title: {text: o}}, filename: o},
            plotOptions: {
                series: {
                    tooltip: {headerFormat: "", pointFormat: "{point.nom}"},
                    point: {
                        events: {
                            click: function () {
                                phenobio(this.id, this.nom)
                            }
                        }
                    }
                }
            },
            series: [{
                mapData: a,
                type: "map",
                data: t,
                joinBy: ["id", "id"],
                name: "bio",
                showInLegend: !1,
                cursor: "pointer"
            }, {
                data: s,
                type: n,
                name: "Mailles",
                borderColor: "red",
                color: "red",
                borderWidth: .5,
                states: {hover: {borderWidth: 1.5}},
                enableMouseTracking: !1
            }, {data: r, type: "mapline", lineWidth: .3, color: "black", enableMouseTracking: !1}]
        })
    })
}

function graphebiogeo(e, t) {
    "use strict";
    Highcharts.getOptions().plotOptions.pie.colors = t, $("#graphebiogeo").highcharts({
        chart: {type: "pie"},
        credits: {enabled: !1},
        exporting: {enabled: !1},
        title: {text: ""},
        tooltip: {pointFormat: "<b>{point.y}</b>", valueSuffix: " observation(s)"},
        plotOptions: {pie: {allowPointSelect: !1, dataLabels: {enabled: !1}, showInLegend: !0}},
        series: [{data: e}]
    })
}

function phenobio(e, t) {
    "use strict";
    var a = $("#cdnom").val(), o = $("#rangsp").val(), i = $("#nomvar").val();
    $.ajax({
        url: "modeles/ajax/fiche/phenobio.php",
        type: "POST",
        dataType: "json",
        data: {cdnom: a, rang: o, nomvar: i, id: e},
        success: function (e) {
            if ("Oui" == e.statut) {
                $("#titregraphbio").html("Phénologie pour la zone " + t), $("#graphephenobio").html('<p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement du graph...</p>');
                var a = e.decade, o = e.serie2, i = e.serie6, n = e.serie13, r = e.serie1, s = e.tab2, l = e.tab6,
                    c = e.tab13, p = e.tab1;
                graphphenobio(a, o, i, n, r, s, l, c, p)
            }
        }
    })
}

function graphphenobio(e, t, a, o, i, n, r, s, l) {
    "use strict";
    var c = e, p = e + 1;
    $("#graphephenobio").highcharts({
        chart: {type: "column"},
        exporting: {enabled: !1},
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
        xAxis: {
            categories: ["Ja1", "Ja2", "Ja3", "Fe1", "Fe2", "Fe3", "Ma1", "Ma2", "Ma3", "Av1", "Av2", "Av3", "M1", "M2", "M3", "Ju1", "Ju2", "Ju3", "Jl1", "Jl2", "Jl3", "A1", "A2", "A3", "S1", "S2", "S3", "O1", "O2", "O3", "N1", "N2", "N3", "D1", "D2", "D3"],
            plotBands: [{color: "#FCFFC5", from: c, to: p}],
            labels: {enabled: !1}
        },
        yAxis: {min: 0, allowDecimals: !1, title: {text: ""}},
        tooltip: {
            formatter: function () {
                return "<b>" + this.x + "</b><br/>" + this.series.name + ": " + this.y
            }
        },
        plotOptions: {column: {stacking: "normal", pointPadding: 0, borderWidth: 0}},
        series: [{name: t, data: n}, {name: a, data: r}, {name: o, data: s}, {name: i, data: l}]
    })
}

function ouvremodal(e, t, a) {
    $("#idc").val(e), $("#typec").val(t), $("#nomc").val(a), $("#infos").modal()
}

function carteleaflet(e) {
    var t = {color: "#ff7800", weight: 5, opacity: .65};
    if (proj4.defs("EPSG:2154", "+proj=lcc +lat_1=49 +lat_2=44 +lat_0=46.5 +lon_0=3 +x_0=700000 +y_0=6600000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs"), "oui" == nbmap) {
        nbmap = "non", map = new L.map("mapdetail");
        var a = new L.TileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
        });
        map.addLayer(a), com = L.Proj.geoJson(e, {style: t}).addTo(map), map.fitBounds(com.getBounds())
    } else com = L.Proj.geoJson(e, {style: t}).addTo(map), map.fitBounds(com.getBounds())
}

function photo() {
    "use strict";
    var e = document.createElement("script");
    e.src = "../dist/js/isotope.js", document.body.appendChild(e);
    var t = $("#cdnom").val(), a = $("#nomvar").val(), o = $("#nom").val();
    $.ajax({
        url: "modeles/ajax/fiche/photo.php",
        type: "POST",
        dataType: "json",
        data: {cdnom: t, nomvar: a, nom: o},
        success: function (e) {
            "Oui" == e.statut && ($("#listebut").html(e.but), $("#listephoto").html(e.liste), isophoto(), $(".photo-grid").magnificPopup({
                delegate: "a",
                type: "image",
                tLoading: "Chargement image #%curr%...",
                mainClass: "mfp-img-mobile",
                gallery: {enabled: !0, navigateByImgClick: !0, preload: [0, 1]},
                image: {
                    tError: '<a href="%url%">Cette image #%curr%</a> est absente..', titleSrc: function (e) {
                        return e.el.attr("title")
                    }
                }
            }))
        }
    })
}

function isophoto() {
    "use strict";
    var e = {}, t = $(".photo-grid").isotope({
        itemSelector: ".grid-item",
        filter: "*",
        percentPosition: !0,
        masonry: {columnWidth: ".grid-sizer"},
        getSortData: {auteur: "[data-auteur]", datep: "[data-datep]"}
    });
    t.imagesLoaded().progress(function () {
        t.isotope("layout")
    }), $(".listefiltre").on("click", "button", function () {
        var a = $(this).attr("data-filter-group");
        e[a] = $(this).attr("data-filter"), $("*[data-filter-group=" + a + "].btn-success").removeClass("btn-success"), $(this).addClass("btn-success");
        var o = concatValues(e);
        t.isotope({filter: o})
    }), $(".listetri").on("click", "button", function () {
        var e = $(this).attr("data-sort-by");
        t.isotope({sortBy: e})
    })
}

function concatValues(e) {
    var t = "";
    for (var a in e) t += e[a];
    return t
}

function statut() {
    "use strict";
    var e = $("#cdnom").val(), t = $("#nomvar").val();
    $.ajax({
        url: "modeles/ajax/fiche/statut.php",
        type: "POST",
        dataType: "json",
        data: {cdnom: e, nomvar: t},
        success: function (e) {
            "Oui" == e.statut && $("#listestatut").html(e.liste)
        }
    })
}

function habitat() {
    "use strict";
    var e = $("#cdnom").val();
    $.ajax({
        url: "modeles/ajax/fiche/habitat.php",
        type: "POST",
        dataType: "json",
        data: {cdnom: e},
        success: function (e) {
            if ("Oui" == e.statut) {
                var t = e.total > 1 ? e.total + " observations ont été renseignées avec un habitat" : "Une observation renseignée avec habitat";
                $("#nbhab").html(t), $("#tblhabitat").html(e.table)
            }
        }
    })
}

function biblio() {
    "use strict";
    var e = $("#cdnom").val();
    $.ajax({
        url: "modeles/ajax/fiche/biblio.php",
        type: "POST",
        dataType: "json",
        data: {cdnom: e},
        success: function (e) {
            "Oui" == e.statut && $("#rbiblio").html(e.biblio)
        }
    })
}

function blocinfo() {
    var e = $("#cdnom").val();
    $('[data-toggle="tooltip"]').tooltip(), $.ajax({
        url: "modeles/ajax/fiche/blocinfo.php",
        type: "POST",
        dataType: "json",
        data: {cdnom: e},
        success: function (e) {
            "Oui" == e.statut && ($("#repartition").html(e.repartition), $("#blocinfo").glossarizer({
                sourceURL: "../json/glossaire.json",
                callback: function () {
                    $(".glossarizer_replaced").tooltip({html: !0})
                }
            }))
        }
    })
}

function cartoleaflet() {
    "use strict";
    var e = $("#cdnom").val(), t = $("#rangsp").val(), a = $("#nomvar").val();
    $.ajax({
        url: "modeles/ajax/fiche/carteleaflet.php",
        type: "POST",
        dataType: "json",
        data: {cdnom: e, rang: t, nomvar: a},
        success: function (e) {
            "Oui" == e.statut && (e.point ? (nb_obs = e.nbobs, anneemin = e.min, anneemax = e.max, affichecarto(e.color, e.weight, e.lat, e.lng), affichemarker(e.point, anneemin, anneemax, e.dr), generateSlider(), mySlider.on("change", function () {
                var t = mySlider.getValue();
                anneemin = t[0], anneemax = t[1], map.removeLayer(currentLayer), affichemarker(e.point, anneemin, anneemax, e.dr);
                var a = 0;
                geojsonespece.features.forEach(function (e) {
                    a += e.properties.nb_observations
                }), $("#nbObs").html("Nombre d'observation(s): " + a + " (" + anneemin + "-" + anneemax + ")")
            })) : $("#mapleaflet").html("<p>Aucune donnée ou information non accessible</p>"))
        }
    })
}

function affichecarto(e, t, a, o) {
    "use strict";
    var i = $("#ign").val(), n = $("#emprise").val();
    proj4.defs("EPSG:2154", "+proj=lcc +lat_1=49 +lat_2=44 +lat_0=46.5 +lon_0=3 +x_0=700000 +y_0=6600000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs"), "fr" != n ? (map = L.map("mapleaflet", {fullscreenControl: !0}), $.getJSON("../emprise/contour2.geojson", {}, function (a) {
        var o = {color: e, weight: t}, i = L.Proj.geoJson(a, {style: o}).addTo(map);
        map.fitBounds(i.getBounds())
    })) : map = L.map("mapleaflet", {center: [a, o], zoom: 6, fullscreenControl: !0});
    var r = L.tileLayer("https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}", {attribution: "Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community"}),
        s = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
        }).addTo(map), l = L.tileLayer("https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: '&copy; Openstreetmap France | &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }), c = L.tileLayer("https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png", {
            maxZoom: 16,
            attribution: 'Map data: &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="http://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)'
        });
    if ("non" != i) var p = L.tileLayer("https://wxs.ign.fr/" + i + "/geoportail/wmts?LAYER=GEOGRAPHICALGRIDSYSTEMS.MAPS&EXCEPTIONS=text/xml&FORMAT=image/jpeg&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}", {attribution: '&copy; <a href=http://www.ign.fr/">IGN</a>'}),
        m = L.tileLayer("https://wxs.ign.fr/" + i + "/geoportail/wmts?LAYER=ORTHOIMAGERY.ORTHOPHOTOS&EXCEPTIONS=text/xml&FORMAT=image/jpeg&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}", {attribution: '&copy; <a href="http://www.ign.fr/">IGN</a>'}),
        h = L.tileLayer("https://wxs.ign.fr/" + i + "/geoportail/wmts?LAYER=CADASTRALPARCELS.PARCELS&EXCEPTIONS=text/xml&FORMAT=image/png&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}", {
            opacity: "0.5",
            attribution: '&copy; <a href="http://www.ign.fr/">IGN</a>'
        }), u = {
            "Carte Open Street": s,
            "Carte Open Street FR": l,
            "Carte Open Topo": c,
            "Carte IGN": p,
            "Photo aériennes IGN": m,
            "Photo aériennes ESRI": r
        }, d = {Cadastre: h}; else var u = {
        "Carte Open Street": s,
        "Carte Open Street FR": l,
        "Carte Open Topo": c,
        "Photo aériennes ESRI": r
    }, d = {};
    var b = L.control.layers(u, d);
    b.addTo(map), L.control.scale({
        position: "bottomright",
        metric: !0,
        imperial: !1
    }).addTo(map), map.attributionControl.addAttribution('Données &copy; <a href="' + adresse + '">' + site + "</a>")
}

function onEachFeature(e, t) {
    "use strict";
    var a = "<b>Date: </b>" + e.properties.dateobs + "<br><b>Observateurs: </b>" + e.properties.observateurs + '<br /><a href="../index.php?module=observation&amp;action=detail&amp;idobs=' + e.properties.idobs + "\">Voir l'observation</a>";
    t.bindPopup(a)
}

function generateGeojsonPoint(e, t, a) {
    "use strict";
    return geojsonespece = {type: "FeatureCollection", features: []}, e.forEach(function (e) {
        if (e.annee >= t && e.annee <= a) {
            var o = e.geojson_point,
                i = {idobs: e.idobs, dateobs: e.dateobs, observateurs: e.obser, annee: e.annee, nb_observations: 1};
            geojsonespece.features.push({type: "Feature", properties: i, geometry: o})
        }
    }), geojsonespece
}

function affichemarker(e, t, a, o) {
    "use strict";
    var i = {radius: 5, fillColor: couleur1, color: couleur1, fillOpacity: 1};
    if (geojsonespece = generateGeojsonPoint(e, t, a), currentLayer = L.geoJson(geojsonespece, {
        onEachFeature: onEachFeature,
        pointToLayer: function (e, t) {
            return L.circleMarker(t, i)
        }
    }), geojsonespece.features.length > 100) {
        var n = currentLayer;
        currentLayer = L.markerClusterGroup(), currentLayer.addLayer(n), map.addLayer(currentLayer)
    } else currentLayer.addTo(map)
}

function generateSlider() {
    var e = L.Control.extend({
        options: {position: "bottomleft"}, onAdd: function (e) {
            var t = L.DomUtil.create("div", "leaflet-bar leaflet-control leaflet-slider-control");
            return t.style.backgroundColor = "white", t.style.width = "300px", t.style.height = "50px", t.style.border = "solid white 1px", t.style.cursor = "pointer", $(t).css("text-align", "center"), $(t).append('<p><span id="anmin"></span><input id="sliderControl" type="text"/><span id="anmax"></span></p><p id="nbObs" class="font-weight-bold"> Nombre d\'observation(s) : ' + nb_obs + "</p>"), L.DomEvent.disableClickPropagation(t), t
        }
    });
    map.addControl(new e), mySlider = new Slider("#sliderControl", {
        value: [anneemin, anneemax],
        min: anneemin,
        max: anneemax,
        step: 1
    }), $(".slider-handle").css({background: couleur1}), $("#anmax").html("&nbsp;&nbsp;&nbsp;&nbsp;" + anneemax), $("#anmin").html(anneemin + "&nbsp;&nbsp;&nbsp;&nbsp")
}

var dep, ongletcarto, pheno, obser, couleur1, couleur2, varbiogeo, varinfo, varleaflet, varphoto, varnicheur, varbiblio,
    site = $("#nomsite").val(), adresse = $("#adresse").val();
$(document).ready(function () {
    "use strict";
    $('[data-toggle="tooltip"]').tooltip(), couleur1 = $("#couleur1").css("backgroundColor"), couleur2 = $("#menu").css("backgroundColor"), $(".image-popup-no-margins").magnificPopup({
        type: "image",
        closeOnContentClick: !0,
        closeBtnInside: !1,
        fixedContentPos: !0,
        mainClass: "mfp-no-margins mfp-with-zoom",
        image: {verticalFit: !0},
        zoom: {enabled: !0, duration: 300}
    }), $(".popup-gallery").magnificPopup({
        delegate: "a",
        type: "image",
        tLoading: "Chargement image #%curr%...",
        mainClass: "mfp-img-mobile",
        gallery: {enabled: !0, navigateByImgClick: !0, preload: [0, 1]},
        image: {
            tError: '<a href="%url%">Cette image #%curr%</a> est absente..', titleSrc: function (e) {
                return e.el.attr("title")
            }
        }
    });
    var e = $("#choixcarte").val();
    general(e), dep = "oui" == $("#contour2").val() ? $.getJSON("../emprise/contour2.geojson", function (e) {
        dep = Highcharts.geojson(e, "mapline")
    }) : ""
}), $("input[name=choixcarte]").change(function () {
    var e = $("input[name=choixcarte]:checked").val();
    if ("commune" == e && ("fr" != $("#emprise").val() ? $("#titrecarte").html("Répartition communale - ") : $("#titrecarte").html("Répartition départementale - "), $("#container").html('<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la carte...</p></div>'), carte(e)), "maille" == e) {
        $("#container").html('<div class="mt-2"><p class="text-warning text-center"><span class="fa fa-spin fa-spinner fa-2x"></span> Chargement de la carte...</p></div>');
        var t = $("#utm").val();
        "oui" == t ? $("#titrecarte").html("Répartition par maille UTM - ") : $("#titrecarte").html("Répartition par maille 10 x 10 - "), carte(e)
    }
    "maille5" == e && ($("#titrecarte").html("Répartition par maille 5 x 5 - "), carte(e))
}), $("#categen").click(function () {
    $('#onglet a[href="#carto"]').tab("show")
}), $("#statutgen").click(function () {
    $('#onglet a[href="#statuts"]').tab("show")
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
    },
    title: {text: ""},
    credits: {text: site, href: adresse},
    colors: ["#4d5b73", "#6f85a1", "#a89297", "#a7bac9", "#d6bcbd"]
}), $('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
    "use strict";
    var t = $(e.target).attr("data-id");
    if ("carto" == t && "oui" != ongletcarto) {
        var a = $("#choixcarte").val();
        "maille" == a && $("#maille").attr("checked", "checked"), carte(a), ongletcarto = "oui"
    }
    "cartoleaflet" == t && "oui" != varleaflet && (varleaflet = "oui", cartoleaflet()), "phenologie" == t && "oui" != pheno && (pheno = "oui", graph()), "observateur" == t && "oui" != obser && (obser = "oui", observateur()), "nicheur" == t && "oui" != varnicheur && (varnicheur = "oui", nicheur()), "infosp" == t && "oui" != varinfo && (varinfo = "oui", info()), "habitat" == t && habitat(), "biogeo" == t && "oui" != varbiogeo && (varbiogeo = "oui", biogeo()), "blocinfo" == t && blocinfo(), "statuts" == t && statut(), "photo" == t && "oui" != varphoto && (varphoto = "oui", photo()), "biblio" == t && "oui" != varbiblio && (varbiblio = "oui", biblio()), "indice" == t && indice()
}), $("#categen").click(function () {
    $('#onglet a[href="#carto"]').tab("show")
}), $("#minigraphpheno").click(function () {
    $('#onglet a[href="#phenologie"]').tab("show")
});
var map, nbmap = "oui", com;
$("#infos").on("shown.bs.modal", function () {
    var e = $("#utm").val(), t = $("#idc").val(), a = $("#typec").val(), o = $("#nomc").val(), i = $("#cdnom").val(),
        n = $("#rangsp").val(), r = $("#nomvar").val();
    $.ajax({
        url: "modeles/ajax/fiche/modalfiche.php",
        type: "POST",
        dataType: "json",
        data: {id: t, type: a, utm: e, cdnom: i, rang: n, nomvar: r},
        success: function (i) {
            if ("Oui" == i.statut) {
                if (carteleaflet(i.carto), setTimeout(function () {
                    map.invalidateSize()
                }, 400), "maille" == a) if ("oui" == e) var n = "Maille UTM n° " + t; else var n = "Maille 10 x 10 km n° " + t;
                if ("com" == a) var n = "Commune de " + o;
                if ("dep" == a) var n = "Département de " + o;
                if ("maille5" == a) var n = "Maille 5 x 5 km n° " + t;
                $("#titleinfos").html(n), $("#listenbobs").html(i.listenbobs)
            } else alert("erreur")
        }
    })
}), $("#infos").on("hidden.bs.modal", function () {
    com && (map.removeLayer(com), com = null)
}), $("#tblhabitat").on("click", ".infohab", function () {
    "use strict";
    var e = $(this).attr("id");
    $("#cdhab").val(e), $("#infohab").modal()
}), $("#infohab").on("shown.bs.modal", function () {
    var e = $("#cdhab").val();
    $.ajax({
        url: "modeles/ajax/fiche/modalhabitat.php",
        type: "POST",
        dataType: "json",
        data: {cdhab: e},
        success: function (e) {
            "Oui" == e.statut ? $("#descrihab").html(e.descri) : alert("erreur")
        }
    })
});
var nb_obs, mySlider, anneemin, observationsPoint = [], anneemax, currentLayer, geojsonespece;