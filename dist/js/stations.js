function carte(e) {
    "use strict";
    $("#map").css("cursor", "crosshair"), utm = e.utm;
    var a = e.ne, t = e.sw, l = e.cleign, o = a.split(",", 2), i = t.split(",", 2), s = parseFloat(o[0]),
        n = parseFloat(o[1]), r = parseFloat(i[0]), c = parseFloat(i[1]);
    map = L.map("map").fitBounds([[r, c], [s, n]]);
    var d = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
        }), u = L.tileLayer("https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: '&copy; Openstreetmap France | &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }), p = L.tileLayer("https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png", {
            maxZoom: 16,
            attribution: 'Map data: &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="http://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)'
        }),
        m = L.tileLayer("https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}", {attribution: "Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community"});
    if (l) var v = L.tileLayer("https://wxs.ign.fr/" + l + "/geoportail/wmts?LAYER=GEOGRAPHICALGRIDSYSTEMS.MAPS&EXCEPTIONS=text/xml&FORMAT=image/jpeg&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}", {attribution: '&copy; <a href="http://www.ign.fr/">IGN</a>'}),
        h = L.tileLayer("https://wxs.ign.fr/" + l + "/geoportail/wmts?LAYER=ORTHOIMAGERY.ORTHOPHOTOS&EXCEPTIONS=text/xml&FORMAT=image/jpeg&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}", {attribution: '&copy; <a href="http://www.ign.fr/">IGN</a>'}),
        f = L.tileLayer("https://wxs.ign.fr/" + l + "/geoportail/wmts?LAYER=CADASTRALPARCELS.PARCELS&EXCEPTIONS=text/xml&FORMAT=image/png&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}", {
            opacity: "0.5",
            attribution: '&copy; <a href="http://www.ign.fr/">IGN</a>'
        }), b = {
            "Carte Open Street": d,
            "Carte Open Street FR": u,
            "Carte Open Topo": p,
            "Carte IGN": v,
            "Photo aériennes IGN": h,
            "Photo aériennes ESRI": m
        }, g = {Cadastre: f}; else var b = {
        "Carte Open Street": d,
        "Carte Open Street FR": u,
        "Carte Open Topo": p,
        "Photo aériennes ESRI": m
    }, g = {};
    var x = $("#couche").val(), w = "osm" == x ? d : "osmfr" == x ? u : "topo" == x ? p : "ign" == x ? v : h;
    w.addTo(map);
    var y = L.control.layers(b, g);
    L.control.scale({
        position: "bottomleft",
        metric: !0,
        imperial: !1
    }).addTo(map), y.addTo(map), stylecontour = {
        color: e.stylecontour.color,
        weight: e.stylecontour.weight
    }, "fr" != e.emprise && $.getJSON("emprise/contour2.geojson", {}, function (a) {
        proj4.defs("EPSG:2154", "+proj=lcc +lat_1=49 +lat_2=44 +lat_0=46.5 +lon_0=3 +x_0=700000 +y_0=6600000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs");
        var t = {color: e.stylecontour2.color, weight: e.stylecontour2.weight};
        L.Proj.geoJson(a, {style: t}).addTo(map)
    });
    var k, C = new L.Icon({
        iconUrl: "dist/css/images/marker-icon.png",
        shadowUrl: "dist/css/images/marker-shadow.png",
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });
    drawnItems = new L.FeatureGroup, map.addLayer(drawnItems), map.addControl(new L.Control.Draw({
        edit: {
            featureGroup: drawnItems,
            poly: {allowIntersection: !1}
        },
        draw: {
            polyline: {allowIntersection: !1, shapeOptions: {color: "#03F", weight: 3}},
            polygon: {allowIntersection: !1, showArea: !0, shapeOptions: {color: "#03F"}},
            rectangle: !1,
            circle: !1,
            marker: !1
        }
    })), $(".leaflet-draw-draw-polygon").click(function () {
        k = "oui"
    }), $(".leaflet-draw-draw-polyline").click(function () {
        k = "oui"
    }), $(".leaflet-draw-edit-edit").click(function () {
        k = "oui"
    }), $(".leaflet-draw-edit-remove").click(function () {
        k = "oui"
    }), map.on("draw:created", function (e) {
        var a = (e.layerType, e.layer);
        drawnItems.getLayers().length > 0 && drawnItems.clearLayers(), drawnItems.addLayer(a);
        var t = (1e4 * a.getCenter().lat) / 1e4, o = (1e4 * a.getCenter().lng) / 1e4;
        "oui" == mod && ($("#spandia13").html($("#lieub").val()), $("#dia13").modal("show")), recupcoord(t, o, l), marker ? marker.setLatLng([t, o]) : marker = L.marker([t, o]).addTo(map), recupgeojson(a)
    }), map.on("draw:edited", function (e) {
        var a = e.layers;
        a.eachLayer(function (e) {
            var a = (1e4 * e.getCenter().lat) / 1e4, t = (1e4 * e.getCenter().lng) / 1e4;
            marker.setLatLng([a, t]), recupcoord(a, t, l), recupgeojson(e)
        })
    }), map.on("draw:drawstop", function (e) {
        k = "non"
    }), map.on("draw:editstop", function (e) {
        k = "non"
    }), map.on("draw:deletestop", function (e) {
        k = "non"
    }), map.on("draw:deleted", function (e) {
        $("#typepoly").val("")
    });

        map.on("click", function (e) { // Au clic sur la carte
            if ("oui" != k && $("#btn_create_station").prop("checked")) { // Si en mode 'création'
                drawnItems.getLayers().length > 0 && (drawnItems.clearLayers(), $("#typepoly").val("")), marker ? (marker.setLatLng(e.latlng), map.setView(marker.getLatLng(), map.getZoom())) : marker = L.marker(e.latlng, {icon: C}).addTo(map);
                var a = (1e4 * e.latlng.lat) / 1e4, t = (1e4 * e.latlng.lng) / 1e4;
                mod = "non", recupcoord(a, t, l);
                var o = $("#proche").val();
                "non" != o && proche(a, t, o)
            }
        });

    dep = "oui" == e.contour2 || "fr" == e.emprise ? "oui" : "non"
    $(".leaflet-draw").hide(); // Cacher les controles par défaut
}

function recupcoord(e, a, t) {
    "use strict";
    // "non" == mod && ($("#codesite").val("Nouv"), nonsite());
    $("#lat").val(e);
    $("#lng").val(a);
    $("#idcoord").val("Nouv");
    $("#pr").val(1);
    transform93(e, a);
    "oui" == utm && chercheutm(e, a);
    altitude(e, a, t)
}

function recupgeojson(e) {
    var a = e.toGeoJSON(), t = JSON.stringify(a);
    $("#typepoly").val(t)
}

function recupcode(e, a) { // Récupérer le code commune
    "use strict";
    $.ajax({
        url: "modeles/ajax/saisie/recupcom.php",
        type: "POST",
        dataType: "json",
        data: {x: e, y: a, dep: dep},
        success: function (e) {
            "Oui" == e.statut ? "Oui" == e.emp ? ($("#communeb").val(e.com.commune), $("#codecom").val(e.com.codecom), $("#codedep").val(e.com.iddep), "oui" == dep && $("#dep").val(e.com.departement)) : ($("#communeb").val(""), $("#codecom").val(""), $("#codedep").val(""), $("#xlambert").val(""), $("#ylambert").val(""), $("#dia3").modal("show")) : alert("problème ! pour récupérer les informations")
        }
    })
}

function altitude(e, a, t) { // Récupérer l'altitude (ne fonctionne qu'avec clé IGN)
    "use strict";
    $.ajax({
        url: "https://wxs.ign.fr/" + t + "/alti/rest/elevation.json",
        dataType: "json",
        data: {lon: a, lat: e, zonly: !0},
        success: function (e) {
            var a = parseInt(e.elevations[0]);
            "" != a ? $("#altitude").val(a) : $("#altitude").val("")
        }
    })
}

function transform93(e, a) {
    "use strict";
    var t = "+proj=lcc +lat_1=49 +lat_2=44 +lat_0=46.5 +lon_0=3 +x_0=700000 +y_0=6600000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs",
        l = proj4(proj4.WGS84, t, [a, e]), o = (1 * l[0]) / 1, i = (1 * l[1]) / 1;
    $("#xlambert").val(o), $("#ylambert").val(i);
    var s = Math.round(o).toString(), n = Math.round(i).toString(), r = s.length;
    if (5 == r) var c = "E00" + s.substring(0, 1), d = s.substring(1, 2) >= 5 ? "5" : "0",
        u = "E00" + s.substring(0, 1) + d;
    if (6 == r) var c = "E0" + s.substring(0, 2), d = s.substring(2, 3) >= 5 ? "5" : "0",
        u = "E0" + s.substring(0, 2) + d;
    if (7 == r) var c = "E" + s.substring(0, 3), d = s.substring(3, 4) >= 5 ? "5" : "0",
        u = "E" + s.substring(0, 3) + d;
    var p = c + "N" + n.substring(0, 3);
    $("#l93").val(p);
    var m = n.substring(3, 4) >= 5 ? "5" : "0", v = u + "N" + n.substring(0, 3) + m;
    $("#l935").val(v), recupcode(o, i)
}

function chercheutm(lat, lng) {
    with (Math) {
        var Deg2Rad = PI / 180,
            Alpha100km = "VQLFAVQLFAWRMGBWRMGBXSNHCXSNHCYTOJDYTOJDZUPKEZUPKEVQLFAVQLFAWRMGBWRMGBXSNHCXSNHCYTOJDYTOJDZUPKEZUPKE",
            F0 = .9996, A1 = 6378388 * F0, B1 = 6356911.946 * F0, K0 = 0, N0 = 0, E0 = 5e5, N1 = (A1 - B1) / (A1 + B1),
            N2 = N1 * N1;
        N3 = N2 * N1;
        var E2 = (A1 * A1 - B1 * B1) / (A1 * A1), South = 0 > lat, West = 0 > lng, K = lat * Deg2Rad, L = lng * Deg2Rad,
            SINK = sin(K), COSK = cos(K), TANK = SINK / COSK, TANK2 = TANK * TANK, COSK2 = COSK * COSK,
            COSK3 = COSK2 * COSK, K3 = K - K0, K4 = K + K0, Merid = 6 * floor(lng / 6) + 3;
        lat >= 72 && lng >= 0 && (9 > lng ? Merid = 3 : 21 > lng ? Merid = 15 : 33 > lng ? Merid = 27 : 42 > lng && (Merid = 39)), lat >= 56 && 64 > lat && lng >= 3 && 12 > lng && (Merid = 9);
        var L0 = Merid * Deg2Rad, J3 = K3 * (1 + N1 + 1.25 * (N2 + N3)),
            J4 = sin(K3) * cos(K4) * 3 * (N1 + N2 + .875 * N3), J5 = sin(2 * K3) * cos(2 * K4) * 1.875 * (N2 + N3),
            J6 = sin(3 * K3) * cos(3 * K4) * 35 / 24 * N3, M = (J3 - J4 + J5 - J6) * B1, Temp = 1 - E2 * SINK * SINK,
            V = A1 / sqrt(Temp), R = V * (1 - E2) / Temp, H2 = V / R - 1, P = L - L0, P2 = P * P, P4 = P2 * P2,
            J3 = M + N0, J4 = V / 2 * SINK * COSK, J5 = V / 24 * SINK * COSK3 * (5 - TANK2 + 9 * H2),
            J6 = V / 720 * SINK * COSK3 * COSK2 * (61 - 58 * TANK2 + TANK2 * TANK2),
            North = J3 + P2 * J4 + P4 * J5 + P4 * P2 * J6;
        South && (North += 1e7);
        for (var J7 = V * COSK, J8 = V / 6 * COSK3 * (V / R - TANK2), J9 = V / 120 * COSK3 * COSK2, J9 = J9 * (5 - 18 * TANK2 + TANK2 * TANK2 + 14 * H2 - 58 * TANK2 * H2), East = E0 + P * J7 + P2 * P * J8 + P4 * P * J9, IEast = round(East), INorth = round(North), EastStr = "" + abs(IEast), NorthStr = "" + abs(INorth); EastStr.length < 7;) EastStr = "0" + EastStr;
        for (; NorthStr.length < 7;) NorthStr = "0" + NorthStr;
        var GR100km = eval(EastStr.substring(1, 2) + NorthStr.substring(1, 2)),
            GRremainder = EastStr.substring(2, 7) + " " + NorthStr.substring(2, 7), LongZone = (Merid - 3) / 6 + 31;
        if (LongZone % 1 != 0) GR = "non-UTM central meridian"; else if (1e5 > IEast || -80 > lat || IEast > 899999 || lat >= 84) GR = "outside UTM"; else {
            for (Letters = "ABCDEFGHJKLMNPQRSTUVWXYZ", Pos = round(lat / 8 - .5) + 10 + 2, LatZone = Letters.substring(Pos, Pos + 1), LatZone > "X" && (LatZone = "X"), Pos = round(abs(INorth) / 1e5 - .5); Pos > 19;) Pos -= 20;
            for (LongZone % 2 == 0 && (Pos += 5, Pos > 19 && (Pos -= 20)), N100km = Letters.substring(Pos, Pos + 1), Pos = GR100km / 10 - 1, P = LongZone; P > 3;) P -= 3;
            Pos += 8 * (P - 1), E100km = Letters.substring(Pos, Pos + 1), GR = LongZone + LatZone + E100km + N100km + " " + GRremainder
        }
        $("#utm").val(LongZone + LatZone + E100km + N100km + EastStr.substring(2, 3) + NorthStr.substring(2, 3)), $("#utm1").val(E100km + N100km + EastStr.substring(2, 4) + NorthStr.substring(2, 4))
    }
}

function markersite(e) { // Fonction générique pour afficher les markers
    "use strict";
    l && map.removeLayer(l);
    var a = new L.Icon({
        iconUrl: "dist/css/images/marker-vert.png",
        shadowUrl: "dist/css/images/marker-shadow.png",
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });
    markers = new L.featureGroup;
    contoursite = new L.layerGroup;
    for (var t = 0; t < e.length; t++) {
        var l = new L.marker([e[t].lat, e[t].lng], {icon: a, title: e[t].site}).on("click", function (a, t) {
            return function () {
                map.setView(this.getLatLng(), 15); // Zoom au click sur une station
                // RLE : Récup des info du site
                $("#lieub").val(e[t].site);
                $("#pr").val(1);
                $("#idcoord").val(e[t].idcoord);
                $("#codesite").val(e[t].idsite);
                $("#xlambert").val(e[t].x);
                $("#ylambert").val(e[t].y);
                $("#altitude").val(e[t].altitude);
                $("#lat").val(e[t].lat);
                $("#lng").val(e[t].lng);
                $("#l93").val(e[t].codel93);
                "oui" == utm && ($("#utm").val(e[t].utm), $("#utm1").val(e[t].utm1));
                recup_info($("#codesite").val());
                // TODO : rendre éditable en mode vision complète ?
            }
        }(markers, t));
        if (markers.addLayer(l), e[t].geo) {
            var o = JSON.parse(e[t].geo), i = "oui",
                s = L.geoJson(o, {style: {color: "#b300b3", fillOpacity: .1, weight: 3}});
            contoursite.addLayer(s);
        }
    }
    map.addLayer(markers), i && map.addLayer(contoursite);
}

// Récup des info pour modification
function recup_info(id) {
    "use strict";
    $.ajax({
        url: "modeles/ajax/stations/recupinfo.php",
        type: "POST",
        dataType: "json",
        data: {id: id},
        success: function (e) {
            $("#create_station").show();
            $("#save_station").show();
            $(".leaflet-draw").show();
            $("#showcom").show();
            $("#showphoto").show();
            // Remplir avec les valeurs
            $("#typestation").val(e.typestation);
            $("#typestation").prop('disabled', true); // Empêcher le changement de type
            $("#lieub").val(e.site);
            $("#nom_station").html(" : <span style='color: darkred;'> " + e.site + "</span>");
            $("#mare").hide();
            $(".active").html("Vous êtes en mode édition d'une station existante");
            // $("#date").val(e.datedescription);
            // $("#typemare").val(e.idtypemare);
            // $("#menace").val(e.idmenaces);
            // $("#environnement").val(e.idenvironnement);
            // $("#eaulibre").val(e.receaulibre);
            // $("#vegaquatique").val(e.idvegaquatique);
            // $("#vegsemiaquatique").val(e.idvegsemiaquatique);
            // $("#vegrivulaire").val(e.idvegrivulaire);
            // $("#typeexutoire").val(e.idtypeexutoire);
            // $("#taillemare").val(e.idtaillemare);
            // $("#couleureau").val(e.idcouleureau);
            // $("#naturefond").val(e.idnaturefond);
            // $("#recouvrberge").val(e.idrecberge);
            // $("#profondeureau").val(e.profondeureau);
            // $("#alimeau").val(e.idalimeau);
            // $("#commentairemare").val(e.commentairemare);
            $("#codecom").val(e.codecom);
            $("#commentaire").val(e.commentaire);
            $("#save_station").hide();
            $("#update_station").show();
            $("#cancel_update").show();
            $("#liste_photo").html(e.photo);
            $("#dateprisedevue").show();
            $(".dateprisedevue").show();


        }
    });
}

function proche(e, a, t) {
    "use strict";
    $.ajax({
        url: "modeles/ajax/saisie/siteproche.php",
        type: "POST",
        dataType: "json",
        data: {lat: e, lng: a, dist: t},
        success: function (e) {
            if ("Oui" == e.statut && ($("#dia8").modal("show"), e.liste)) {
                var a, t = e.liste;
                markers && map.removeLayer(markers), contoursite && map.removeLayer(contoursite);
                var l = new L.Icon({
                    iconUrl: "dist/css/images/marker-vert.png",
                    shadowUrl: "dist/css/images/marker-shadow.png",
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });
                markers = new L.layerGroup, contoursite = new L.layerGroup, $.each(t, function (e, t) {
                    var o = new L.marker([t.lat, t.lng], {icon: l}).on("click", function (e, a) {
                        return function () {
                            // $("#dia9titre").html(t.site), $("#dia9").modal("show"), $("#coordpr").val(t.idcoord)
                        }
                    }(markers, e));
                    if (o.bindTooltip(t.site).openTooltip(), markers.addLayer(o), t.geo) {
                        var i = JSON.parse(t.geo), s = L.geoJson(i, {
                            style: {
                                color: "#03F",
                                fillOpacity: .1,
                                weight: 3
                            }
                        }).on("click", function (e, a) {
                            return function () {
                                $("#dia9titre").html(t.site), $("#dia9").modal("show"), $("#coordpr").val(t.idcoord)
                            }
                        }(contoursite, e));
                        s.bindPopup(t.site), contoursite.addLayer(s), a = "oui"
                    }
                }), map.addLayer(markers), "oui" == a && map.addLayer(contoursite)
            }
        }
    })
}

function l93com(e, a) {
    "use strict";
    var e = e.toString(), a = a.toString(), t = e.length;
    if (5 == t) var l = "E00" + e.substring(0, 1), o = e.substring(1, 2) >= 5 ? "5" : "0",
        i = "E00" + e.substring(0, 1) + o;
    if (6 == t) var l = "E0" + e.substring(0, 2), o = e.substring(2, 3) >= 5 ? "5" : "0",
        i = "E0" + e.substring(0, 2) + o;
    if (7 == t) var l = "E" + e.substring(0, 3), o = e.substring(3, 4) >= 5 ? "5" : "0",
        i = "E" + e.substring(0, 3) + o;
    var s = l + "N" + a.substring(0, 3);
    $("#l93").val(s);
    var n = a.substring(3, 4) >= 5 ? "5" : "0", r = i + "N" + a.substring(0, 3) + n;
    $("#l935").val(r)
}

function centrersite(e, a, t) { // Centrer lors d'une recherche par nom
    supmarker(), marker && map.removeLayer(marker); // Supp tous les markers de la carte
    "use strict";
    var l = e.split(",", 2), o = parseFloat(l[0]), i = parseFloat(l[1]);
    map.setView([o, i], a);
    var l = new L.Icon({
        iconUrl: "dist/css/images/marker-vert.png",
        shadowUrl: "dist/css/images/marker-shadow.png",
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });
    marker ? marker.setLatLng([o, i], {icon: l}) : marker = L.marker([o, i], {icon: l}).addTo(map);
    "" != t && (t = JSON.parse(t), contoursite = L.geoJson(t, {
        style: {
            color: "#b300b3",
            fillOpacity: .1,
            weight: 3
        }
    }), contoursite.eachLayer(function (e) { e.addTo(drawnItems); })) // Rendre le poly editable
    $(".leaflet-draw").show();
}

function centrer(e, a, t) {
    "use strict";
    supmarker(), marker && map.removeLayer(marker), $.ajax({
        url: "modeles/ajax/saisie/contour.php",
        type: "POST",
        dataType: "json",
        data: {codecom: t},
        success: function (e) {
            "Oui" == e.statut && (proj4.defs("EPSG:2154", "+proj=lcc +lat_1=49 +lat_2=44 +lat_0=46.5 +lon_0=3 +x_0=700000 +y_0=6600000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs"), contour = L.Proj.geoJson(e.carto, {style: stylecontour}).addTo(map))
        }
    });
    var l = e.split(",", 2), o = parseFloat(l[0]), i = parseFloat(l[1]);
    map.setView([o, i], a)

    // Afficher automatiquement les sites lors du centrage sur commune
    if ($("#codecom").val() !== "") {
        var e = $("#codecom").val();
        $.ajax({
            url: "modeles/ajax/saisie/voirsite.php",
            type: "POST",
            dataType: "json",
            data: {codecom: e},
            success: function (e) {
                markersite(e);
                // contoursite.eachLayer(function (e) { e.addTo(drawnItems) });
            }
        });
    } else { // Afficher automatiquement les sites lors du centrage sur département
        var e = $("#choixdep").val();
        $.ajax({
            url: "modeles/ajax/saisie/voirsite.php",
            type: "POST",
            dataType: "json",
            data: {dep: e},
            success: function (e) {
                markersite(e)
            }
        });
    }
}

function supmarker() {
    "use strict";
    markers && map.removeLayer(markers), contour && map.removeLayer(contour), contoursite && map.removeLayer(contoursite)
}

function nonsite() {
    "use strict";
    $("#lieub").val("")
}

function aide() {
    "use strict";
    $(this).hasClass("text-primary") ? ($('[data-toggle="tooltip"]').tooltip("enable", {title: "data-title"}), $(this).removeClass("text-primary").addClass("text-success")) : ($('[data-toggle="tooltip"]').tooltip("disable"), $(this).removeClass("text-success").addClass("text-primary"))
}

function fiche() {
    "use strict";
    var e = $("#idobseror").val();
    $.post("modeles/ajax/saisie/misejourfiche.php", {idobser: e}, function (e) {
        $("#listefiche").html(e)
    })
}

function inserobservateur(e, a) {
    "use strict";
    $.ajax({
        url: "modeles/ajax/saisie/inserobservateur.php",
        type: "POST",
        dataType: "json",
        data: {nom: e, prenom: a},
        success: function (t) {
            var l = t.statut.Ok;
            if ("Ok" == l) {
                var o = $("#observateur2").val(), i = $("#idobser").val();
                i && $("#idobser").val(i + ", " + t.statut.idobser), o ? $("#observateur2").val(o + e + " " + a + ", ") : $("#observateur2").val(e + " " + a + ", ")
            } else alert(t.statut)
        },
        error: function (e) {
            alert("Une erreure est survenue")
        }
    })
}

function verifinfo() {
    "use strict";
    $("#alert1").html("");
    var e, a = $("#date").val(), t = $("#xlambert").val(), et = $("#etude").val();
    if ("" == a || "" == t || "" == et) "" == a && $("#alert1").prepend('<div class="alert alert-danger"><b>Aucune date de saisie !</b> Renseignez une date ou un intervalle de dates</div>'), "" == t && $("#alert1").prepend('<div class="alert alert-danger"><b>Aucune coordonnée !</b> Sélectionnez un site déjà existant ou cliquez sur la carte pour en créer un.</div>'), "" == et && $("#alert1").prepend('<div class="alert alert-danger"> <b>Aucune étude de renseignée !</b> Sélectionner une étude avec la liste déroulante. </div>'), e = "oui"; else {
        var l = $("#pr").val();
        $("#alert1").html(""), "Nouv" == $("#idfiche").val() && veriffiche(), 2 == l ? ($("#dia16").modal("show"), e = "oui") : e = "non"
    }
    return e
}

function veriffiche() {
    "use strict";
    var e = $("#codesite").val(), a = $("#idcoord").val(), t = $("#date").val(), l = $("#idobseror").val();
    $.ajax({
        url: "modeles/ajax/saisie/veriffiche.php",
        type: "POST",
        dataType: "json",
        data: {idsite: e, idcoord: a, date: t, idobser: l},
        success: function (e) {
            "Oui" == e.statut ? 0 == e.verif ? $("#val").show() : ($("#R1").html('<div class="alert alert-danger">Vous avez déjà enregistré une fiche sur ce site à cette date ! <a href="index.php?module=observation&amp;action=fiche&amp;idfiche=' + e.verif + '">Voir le relevé n°' + e.verif + "</a></div>"), $("#val").hide()) : alert("Erreur ! lors de la vérification de la fiche")
        }
    })
}

function afficheobs() {
    "use strict";
    var e = $("#change").attr("class");
    "w-50" == e && ($("#blocmap").toggle(), $("#blocfiche").toggle(), $("#change").removeClass("w-50").addClass("w-100"), $("#blocobs").toggle(), $("#blocsaisie").show())
}

function affichefiche() {
    "use strict";
    var e = $("#change").attr("class");
    "w-100" == e && ($("#blocmap").toggle(), $("#blocfiche").toggle(), $("#change").removeClass("w-100").addClass("w-50"), $("#blocobs").toggle(), $("#blocsaisie").hide())
}

function choixobser(e) {
    "use strict";
    $.ajax({
        url: "modeles/ajax/saisie/choixobser.php",
        type: "POST",
        dataType: "json",
        data: {sel: e},
        success: function (a) {
            if ("Oui" == a.statut) {
                $("#blocsaisie").show(), $("#mes").html(""), $("#valsel").val("oui");
                var t;
                $.each(a.stade, function (e, a) {
                    t += "<option value=" + a + ">" + e + "</option>"
                }), $("#stade").html(t), t = "", $.each(a.methode, function (e, a) {
                    t += "<option value=" + a + ">" + e + "</option>"
                }), $("#obsmethode").html(t), t = "", $.each(a.collecte, function (e, a) {
                    t += "<option value=" + a + ">" + e + "</option>"
                }), $("#obscoll").html(t), t = "", $.each(a.statutbio, function (e, a) {
                    t += "<option value=" + a + ">" + e + "</option>"
                }), $("#bio").html(t), t = "", $.each(a.comportement, function (e, a) {
                    t += "<option value=" + a + ">" + e + "</option>"
                }), $("#comportement").html(t), t = "", "" != a.mort ? (t += "<option value=0>Choix</option>", $.each(a.mort, function (e, a) {
                    t += "<option value=" + a + ">" + e + "</option>"
                })) : t += "<option value=0>Inconnu</option>", $("#mort").html(t), t = "", $.each(a.protocole, function (e, a) {
                    t += "<option value=" + a + ">" + e + "</option>"
                }), $("#protocol").html('<option value="0">Sélectionner un type d\'acquisition</option>'), $("#protocol").append(t), t = "", $.each(a.obdenom, function (e, a) {
                    t += "<option value=" + a + ">" + e + "</option>"
                }), $("#tdenom").html(t), "mort" == a.stbio ? ($("#cmort").show(), $("#etatbio").html('<option value="3">Trouvé mort</option><option value="2">Observé vivant</option><option value="1">Non renseigné</option><option value="0">Inconu</option>')) : ($("#mort").val(0), $("#cmort").hide(), $("#etatbio").html('<option value="2">Observé vivant</option><option value="3">Trouvé mort</option><option value="1">Non renseigné</option><option value="0">Inconu</option>')), "oui" == a.locale ? $("#imgpluslocale").show() : ($("#imgpluslocale").hide(), $("#pluslatin1").hide()), "oui" == a.aves ? ($("#aves").show(), $("#indnid").html(a.avesindice), $("#nicheur").html("")) : ($("#aves").hide(), $("#indnid option").remove(), $("#nicheur").html("")), "oui" == a.bota ? ($("#obscoll").prop("disabled", !0), $("#bio").prop("disabled", !0)) : ($("#obscoll").prop("disabled", !1), $("#bio").prop("disabled", !1)), "oui" == a.col ? $("#imgpluscol").show() : ($("#imgpluscol").hide(), $("#pluscol").hide()), "oui" == a.plteh || "oui" == a.plteb ? $("#plteh").show() : $("#plteh").hide(), $("#sel").val(e), $("#nomb").val(""), $("#latin").val(""), $("#nomf").val(""), $("#cdnom").val(""), $("#cdref").val(""), $("#denom").val("Co"), $("#tdenom").val("IND"), $(".nbexact").prop("disabled", !1), $("#nbtmp").hide(), $("#estim").hide(), $("#nbmin").val(""), $("#nbmax").val(""), $("#validateur").val(a.validateur), a.mf && ($("#male").prop("disabled", !0), $("#femelle").prop("disabled", !0)), $("#" + a.aff).focus()
            } else $("#blocsaisie").hide(), $("#sel").val(e), $("#valsel").val("non"), $("#mes").html(a.mes), $("#stade option").remove(), $("#obsmethode option").remove(), $("#obscoll option").remove()
        }
    })
}

function valider(e) {
    "use strict";
    var a = $("#pr").val(), t = $("#cdnom").val(), l = $("#protocol").val(),
        i = $("#statutobs").val();
    "Nouv" == $("#idfiche").val() ? a && t ? 0 == l ? ($("#valajaxs").hide(), $("#BttV").show(), $("#R1").html("<div class=\"alert alert-danger\">Merci de renseigner le type d'acquisition</div>")) : verifeffectif(e, i) : ($("#BttV").show(), $("#BttN").show(), $("#valajaxs").hide(), $("#R1").html('<div class="alert alert-danger">Aucune localisation ou d\'espèce de saisie !</div>')) : t ? 0 == l ? ($("#valajaxs").hide(), $("#R1").html("<div class=\"alert alert-danger\">Merci de renseigner le type d'acquisition</div>")) : verifeffectif(e, i) : ($("#BttV").show(), $("#BttN").show(), $("#valajaxs").hide(), $("#R1").html('<div class="alert alert-danger">Aucune espèce de saisie !</div>'))
}

function enregistrer(e) {
    "use strict";
    var a = $("#communeb").val(), t = $("#lieub").val(), l = $("#xlambert").val(), o = $("#ylambert").val(),
        i = $("#altitude").val(), s = $("#l93").val(), n = $("#l935").val(), r = $("#lat").val(), c = $("#lng").val(),
        d = $("#idm").val(), u = "oui" == utm ? $("#utm").val() : "", p = "oui" == utm ? $("#utm1").val() : "",
        m = $("#stade option:selected").text(), v = $("#dep").val(), h = $("#nbmin").val(), f = $("#nbmax").val();
    $.ajax({
        url: "modeles/ajax/saisie/validation.php",
        type: "POST",
        dataType: "json",
        data: e.serialize() + "&dep=" + v + "&com=" + a + "&site=" + t + "&x=" + l + "&y=" + o + "&alt=" + i + "&l93=" + s + "&l935=" + n + "&lat=" + r + "&lng=" + c + "&utm=" + u + "&utm1=" + p + "&stadeval=" + m + "&idm=" + d + "&nbmin=" + h + "&nbmax=" + f,
        success: function (e) {
            if ("Oui" == e.statut) {
                var a = $("#Bt").val();
                e.verifobs ? $("#R1").html('<div class="alert alert-danger">Vous avez déjà saisie cette espèce pour cette fiche !</div>') : "Oui" == e.nouveau ? ("BttV" == a && ($("#listeobs").html(""), $("#BttV").show(), $("#R").html('<div class="alert alert-success"><i class="fa fa-check"></i> Donnée enregistrée<b> ' + e.fiche.site + " - " + e.fiche.commune + " le " + e.fiche.date + "</b> - Vous pouvez enregistrer une nouvelle fiche</div>"), $("#R1").html(""), $("#idfiche").val("Nouv"), $("#idobs").val("Nouv"), efface("#formulaire"), affichefiche(), $("#observateur2").focus(), $("html, body").animate({scrollTop: 0}, "slow")), "BttS" == a && ($("#listeobs").html("<li>" + e.vali + " " + e.obs.nb + " " + e.obs.nom + " (" + e.obs.stade + ")</li>"), $("#R1").html('<div class="alert alert-warning"><i class="fa fa-check"></i> Enregistrement en cours sur : <b> ' + e.fiche.site + " - " + e.fiche.commune + " le " + e.fiche.date + "</b></div>"), $("#R").html(""), $("#idfiche").val(e.fiche.idfiche), $("#idobs").val(e.idobs), $("#nb").val(e.obs.nb), cache(), $("#stade").focus()), "BttN" == a && ($("#listeobs").html("<li>" + e.vali + " " + e.obs.nb + " " + e.obs.nom + " (" + e.obs.stade + ")</li>"), $("#R1").html('<div class="alert alert-warning"><i class="fa fa-check"></i> Enregistrement en cours sur : <b> ' + e.fiche.site + " - " + e.fiche.commune + " le " + e.fiche.date + "</b></div>"), $("#R").html(""), $("#idfiche").val(e.fiche.idfiche), $("#idobs").val("Nouv"), cache2(), $("#BttN").show())) : ("BttV" == a && ($("#listeobs").html(""), $("#BttV").show(), "oui" == $("#afiche").val() ? $("#R").html('<div class="alert alert-success"><i class="fa fa-check"></i> Donnée ajoutée à la fiche n°<b> ' + e.fiche.idfiche + "</b></div>") : $("#R").html('<div class="alert alert-success"><i class="fa fa-check"></i> Donnée enregistrée<b> ' + e.fiche.site + " - " + e.fiche.commune + " le " + e.fiche.date + "</b> - Vous pouvez enregistrer une nouvelle fiche</div>"), $("#R1").html(""), $("#idfiche").val("Nouv"), $("#idobs").val("Nouv"), $("#afiche").val(""), efface("#formulaire"), affichefiche(), $("#observateur2").focus(), $("html, body").animate({scrollTop: 0}, "slow")), "BttS" == a && (e.vali ? $("#listeobs").prepend("<li>" + e.vali + " " + e.obs.nb + " " + e.obs.nom + " (" + e.obs.stade + ")</li>") : $("#listeobs").prepend('<li><i class="fa fa-check text-success"></i> ' + e.obs.nom + " (" + e.obs.stade + ")</li>"), $("#R1").html('<div class="alert alert-warning"><i class="fa fa-check"></i> Enregistrement en cours sur : <b> ' + e.fiche.site + " - " + e.fiche.commune + " le " + e.fiche.date + "</b></div>"), $("#R").html(""), $("#idfiche").val(e.fiche.idfiche), $("#idobs").val(e.idobs), $("#nb").val(e.obs.nb), cache(), $("#stade").focus()), "BttN" == a && (e.vali ? $("#listeobs").prepend("<li>" + e.vali + " " + e.obs.nb + " " + e.obs.nom + " (" + e.obs.stade + ")</li>") : $("#listeobs").prepend('<li><i class="fa fa-check text-success"></i> ' + e.obs.nom + " (" + e.obs.stade + ")</li>"), $("#R1").html('<div class="alert alert-warning"><i class="fa fa-check"></i> Enregistrement en cours sur : <b> ' + e.fiche.site + " - " + e.fiche.commune + " le " + e.fiche.date + "</b></div>"), $("#R").html(""), $("#idfiche").val(e.fiche.idfiche), $("#idobs").val("Nouv"), cache2(), $("#BttN").show())), e.photo && ($(".cropit-preview-image").removeAttr("src"), $(".cropit-preview-background").removeAttr("src"), $(".hidden-image-data").val(""), $("#aphoto").val(""), $("#adphoto").removeClass("fa-minus").addClass("fa-camera"), $("#photo").hide(), $("#file").val(""), $("#paysage").attr("checked", "checked"), $("#sexe").val(""))
            } else $("#R").html(""), $("#R1").html(""), $("#listeobs").html(""), alert(e.statut);
            $("#valajaxs").hide()
        }
    })
}

function efface(e) {
    "use strict";
    $(e + " :input").not(":button, :submit, :reset, #det, #stade, #etude, #floutage, #typedon, #org, #precision, #source, #idfiche, #idobs, #sel, input[name=orien], input[name=clabon]").val(""), $("#observateur2").val(""), $("#habitat").val("NR"), $("#habitat2").hide(), $("#habitat3").hide(), $("#cdnombotam").val(""), $("#nbpltem").val(""), $("#statutobs").val("Pr"), $("#denom").val("Co"), $("#tdenom").val("IND"), $("#etatbio").val("2"), $("input[name=clabon]").prop("checked", !1), $("#nbtmp").hide(), $("#estim").hide(), $("#nbmin").val(""), $("#nbmax").val(""), $(".nbexact").val(""), $(".nbexact").prop("disabled", !1), $("#nbtmp1").val(""), $("#iddet").val($("#iddetor").val()), $("#idobser").val($("#idobseror").val()), $(".afcarte").show(), $("#valf").hide(), $(".stadecache").find(":input").css("background-color", "#FFFFFF").css("cursor", "auto"), $("#observateur2").prop("disabled", !1), $("#nomb").prop("disabled", !0).css("cursor", "Not-Allowed"), $("#mrq").prop("checked", !1), $("#collect").is(":checked") && $("#collect").prop("checked", !1), $("#gen").is(":checked") && $("#gen").prop("checked", !1), $("#imgpluscol").hasClass("fa-minus") && ($("#imgpluscol").removeClass("fa-minus").addClass("fa-plus"), $("#pluscol").hide()), marker && (map.removeLayer(marker), marker = ""), drawnItems.getLayers().length > 0 && drawnItems.clearLayers(), nonsite(), supmarker()
}

function cache() {
    "use strict";
    $("#observateur2").prop("disabled", !0).css("cursor", "Not-Allowed"), $(".stadecache").find(":input").css("background-color", "#EEEEEE").css("cursor", "Not-Allowed"), $("#cdnombota").val(""), $("#nbplte").val(""), $(".nbexact").val(""), $("#nbmin").val(""), $("#nbmax").val(""), $("#denom").val("Co"), $("#tdenom").val("IND"), $(".nbexact").prop("disabled", !1), $("#choixplte").val(""), $("#nbpltel").val(""), $("#ulplante").html(""), $("#collect").is(":checked") && ($("#collect").prop("checked", !1), $("#detcol").val(""), $("#iddetcol").val("")), $("#gen").is(":checked") && ($("#gen").prop("checked", !1), $("#codegen").val(""), $("#detgen").val(""), $("#prepgen").val(""), $("#iddetgen").val(""), $("#idprep").val(""), $("#typegen").val("NR"), $("#sexegen").val("")), $("#imgpluscol").hasClass("fa-minus") && ($("#imgpluscol").removeClass("fa-minus").addClass("fa-plus"), $("#pluscol").hide()), $("#mrq").is(":checked") || $("#rq").val("")
}

function cache2() {
    "use strict";
    $("#observateur2").prop("disabled", !0).css("cursor", "Not-Allowed"), $("#cdnombota").val(""), $("#nbplte").val(""), $("#nicheur").html(""), $("#indnid").val("0"), $(".ndecache :input").not(":button, :submit, :reset, #det, #stade, #denom, #tdenom, #etude, #protocol, #etatbio, #habitat, #habitat2, #habitat3").val(""), $(".ndecache").find(":input").css("background-color", "#FFFFFF").css("cursor", "auto"), $("#statutobs").val("Pr"), $(".nbexact").val(""), $("#nbmin").val(""), $("#nbmax").val(""), $("#nbtmp1").val(""), $("input[name=clabon]").prop("checked", !1), $("#cdnom").val(""), $("#cdref").val(""), $("#choixplte").val(""), $("#nbpltel").val(""), $("#ulplante").html("");
    var e = $("#choixauto").val();
    $("#" + e).focus(), $("#collect").is(":checked") && ($("#collect").prop("checked", !1), $("#detcol").val(""), $("#iddetcol").val("")), $("#gen").is(":checked") && ($("#gen").prop("checked", !1), $("#codegen").val(""), $("#detgen").val(""), $("#prepgen").val(""), $("#iddetgen").val(""), $("#idprep").val(""), $("#typegen").val("NR"), $("#sexegen").val("")), $("#imgpluscol").hasClass("fa-minus") && ($("#imgpluscol").removeClass("fa-minus").addClass("fa-plus"), $("#pluscol").hide()), $("#mrq").is(":checked") || $("#rq").val("")
}

function recupfiche(e) {
    "use strict";
    $.ajax({
        url: "modeles/ajax/saisie/recupfiche.php",
        type: "POST",
        dataType: "json",
        data: {idfiche: e},
        success: function (e) {
            if ("Oui" == e.statut) {
                if ($(".afcarte").hide(),
                    $("#xlambert").val(""),
                    $("#ylambert").val(""),
                    $("#altitude").val(""),
                    $("#l93").val(""),
                    $("#l935").val(""),
                    $("#lat").val(""),
                    $("#lng").val(""),
                $("#org").val(e.fiche.idorg) && postorg(e.fiche.idorg, e.fiche.idetude),
                    $("#communeb").val(e.fiche.commune),
                    $("#lieub").val(e.fiche.site),
                    $("#date").val(e.fiche.date1fr),
                    $("#date2").val(e.fiche.date2fr),
                    $("#idfiche").val(e.fiche.idfiche),
                    $("#codecom").val(e.fiche.codecom),
                    $("#codedep").val(e.fiche.iddep),
                    $("#codesite").val(e.fiche.idsite),
                    $("#idcoord").val(e.fiche.idcoord),
                    $("#pr").val(e.fiche.localisation),
                    $("#typepoly").val(e.fiche.geo),
                $("#typedon").val(e.fiche.typedon) && filter(e.fiche.idorg),
                    $("#source").val(e.fiche.source),
                    $("#precision").val(e.fiche.idpreci),
                    $("#floutage").val(e.fiche.floutage),
                    $("#heure").val(e.fiche.hdebut),
                    $("#heure2").val(e.fiche.hfin),
                    $("#tempdeb").val(e.fiche.tempdebut),
                    $("#tempfin").val(e.fiche.tempfin),
                    $("#valf").show(),
                e.fiche.site || (nonsite(),
                0 == e.fiche.idsite && $("#codesite").val("Nouv")),
                e.idobser && ($("#observateur2").val(e.obser),
                    $("#idobser").val(e.idobser)),
                1 == e.fiche.localisation && e.fiche.lat) {
                    supmarker(), mod = "oui";
                    var a = e.fiche.lat + "," + e.fiche.lng, t = 16;
                    centrersite(a, t, e.fiche.geo)
                }
                if (2 == e.fiche.localisation && e.fiche.lat) {
                    supmarker();
                    var a = e.fiche.lat + "," + e.fiche.lng, t = 13;
                    centrer(a, t, e.fiche.codecom)
                }
            } else alert("problème ! pour récupérer les informations de la fiche")
        }
    })
}


var sel, utm, dep, map, marker, markers, contoursite, contour, stylecontour, drawnItems, mod = "non";

$(document).ready(function () {
    "use strict";
    $("#mare").hide(); // Cacher les mares au chargement
    $("#create_station").hide(); // Cacher la création au chargement
    $("#update_station").hide(); // Cacher la mise à jour au chargement
    $("#save_station").hide(); // Cacher l'enregistrement d'une nouvelle station
    $("#showcom").hide(); //
    $("#showphoto").hide(); //
    $("#cancel_update").hide(); //
    $("#dateprisedevue").hide();
    $(".dateprisedevue").hide();

    // Popup pour les images
    $(".popup-gallery").magnificPopup({
        delegate: "a",
        type: "image",
        tLoading: "Loading image #%curr%...",
        mainClass: "mfp-img-mobile",
        gallery: {enabled: !0, navigateByImgClick: !0, preload: [0, 1]},
        image: {
            // A améliorer.
        }
    });
    // Fin popup photo de la la station

    var e = {};
    $.ajax({
        url: "emprise/emprise.json", dataType: "json", success: function (a) {
            e = a, carte(e)
        }
    }), $("#valajaxs").hide(), $("#blocobs").hide(), $("#pluslatin1").hide(), $("#pluscoord").hide(), $("#pluscol").hide(), $("#plushab").hide(), $("#plusproto").show(), $("#valm").removeClass("d-flex").hide(), $("#liste10").hide(), $("#photo").hide(), $("#pltehote").hide(), $("#plusfiche").hide(), $("#valf").hide(), $("#estim").hide(), $("#nbtmp").hide(), $("#habitat2").hide(), $("#habitat3").hide(), $("#observateur").prop("disabled", !0), $("#dep").prop("disabled", !0), $("#communeb").prop("disabled", !0), $("#altitude").prop("disabled", !0), $("#xlambert").prop("disabled", !0), $("#ylambert").prop("disabled", !0), $("#lat").prop("disabled", !0), $("#lng").prop("disabled", !0), $("#l93").prop("disabled", !0), $("#l935").prop("disabled", !0), $("#utm").prop("disabled", !0), $("#utm1").prop("disabled", !0), $("#nomb").prop("disabled", !0).css("cursor", "Not-Allowed"), $("#btnaide").on("click", aide);

    var l = $("#getidfiche").val();
    "" != l && recupfiche(l);
}),

    $("#bttdia9").click(function () {
    "use strict";
    var e = $("#coordpr").val();
    $.ajax({
        url: "modeles/ajax/saisie/recupproche.php",
        type: "POST",
        dataType: "json",
        data: {idcoord: e},
        success: function (e) {
            $("#communeb").val(e.commune), $("#codecom").val(e.codecom), $("#lieub").val(e.site), $("#pr").val(1), $("#idcoord").val(e.idcoord), $("#codesite").val(e.idsite), $("#xlambert").val(e.x), $("#ylambert").val(e.y), $("#altitude").val(e.altitude), $("#lat").val(e.lat), $("#lng").val(e.lng), $("#l93").val(e.codel93), "oui" == utm && ($("#utm").val(e.utm), $("#utm1").val(e.utm1)), $("#date").focus()
        }
    })
}), $(function () {
    "use strict";

    function e() {
        var e = $("#idobser").val().split(", ");
        return e[0]
    }

    $("#choixsite").autocomplete({
        minLength: 2, source: function (a, t) {
            $.getJSON("modeles/ajax/saisie/autositeperso.php", {term: a.term, idobser: e()}, function (e) {
                t($.map(e, function (e) {
                    return {label: e.site + " (" + e.commune + ")", value: e}
                }))
            })
        }, select: function (e, a) {
            var t = a.item.value;
            $("#communeb").val(t.commune), $("#lieub").val(t.site), $("#choixsite").val(t.site), $("#l93").val(t.codel93), $("#l935").val(t.codel935), $("#lat").val(t.lat), $("#lng").val(t.lng), $("#xlambert").val(t.x), $("#ylambert").val(t.y), $("#altitude").val(t.altitude), $("#pr").val(1), $("#choixcom").val(""), $("#codedep").val(t.iddep), $("#codecom").val(t.codecom), $("#idcoord").val(t.idcoord), $("#codesite").val(t.idsite), "oui" == utm && ($("#utm").val(t.utm), $("#utm1").val(t.utm1)), $("#date").focus();
            var l = t.lat + "," + t.lng, o = 16;
            return centrersite(l, o, t.geo), !1
        }
    })
}),


    // Autocomplétion choix des sites
    $("#choixsite1").autocomplete({
    minLength: 2, source: function (e, a) {
        $.getJSON("modeles/ajax/saisie/autosite.php", {term: e.term}, function (e) {
            a($.map(e, function (e) {
                return {label: e.site + " (" + e.commune + ")", value: e}
            }))
        })
    }, select: function (e, a) {
    }, select: function (e, a) {
        var t = a.item.value;
        $("#communeb").val(t.commune), $("#lieub").val(t.site), $("#choixsite1").val(t.site), $("#l93").val(t.codel93), $("#l935").val(t.codel935), $("#lat").val(t.lat), $("#lng").val(t.lng), $("#xlambert").val(t.x), $("#ylambert").val(t.y), $("#altitude").val(t.altitude), $("#pr").val(1), $("#choixcom").val(""), $("#codedep").val(t.iddep), $("#codecom").val(t.codecom), $("#idcoord").val(t.idcoord), $("#codesite").val(t.idsite), "oui" == utm && ($("#utm").val(t.utm), $("#utm1").val(t.utm1)), $("#date").focus();
        var l = t.lat + "," + t.lng, o = 16;
        // Récup de la fiche
        recup_info( $("#codesite").val() );
        return centrersite(l, o, t.geo), !1
    }
}),

    // Autocomplétion liste des départements
    $("#choixdep").autocomplete({
    source: function (e, a) {
        $.getJSON("modeles/ajax/saisie/autodep.php", {term: e.term}, function (e) {
            a($.map(e, function (e) {
                return {label: e.departement, value: e}
            }))
        })
    }, select: function (e, a) {
        var t = a.item.value;
        "oui" == utm && ($("#utm").val(""), $("#utm1").val("")), $("#dep").val(t.departement), $("#choixdep").val(t.departement), $("#pr").val(3), $("#l93").val(""), $("#xlambert").val(""), $("#ylambert").val(""), $("#lieub").val(""), $("#communeb").val(""), $("#codesite").val("Nouv"), $("#altitude").val(""), $("#codedep").val(t.iddep), $("#choixcom").val(""), $("#codecom").val(""), $("#choixsite").val("");
        var l = t.lat + "," + t.lng, o = 9;
        return centrer(l, o), !1
    }
}), $("#choixcom").autocomplete({
    source: function (e, a) {
        $.getJSON("modeles/ajax/saisie/autocom.php", {term: e.term, dep: dep}, function (e) {
            a($.map(e, function (e) {
                return "non" == dep ? {label: e.commune, value: e} : {
                    label: e.commune + " (" + e.departement + ")",
                    value: e
                }
            }))
        })
    }, select: function (e, a) {
        var t = a.item.value;
        "oui" == dep && $("#dep").val(t.departement), $("#choixcom").val(t.commune), $("#communeb").val(t.commune), $("#lieub").val(""), $("#choixdep").val(""), $("#xlambert").val(t.x), $("#ylambert").val(t.y), $("#lat").val(t.lat), $("#lng").val(t.lng), $("#pr").val(2), $("#codesite").val("Nouv"), $("#altitude").val(""), $("#choixsite").val(""), $("#codedep").val(t.iddep), $("#codecom").val(t.codecom);
        var l = t.lat + "," + t.lng, o = 13;
        return centrer(l, o, t.codecom), l93com(t.x, t.y), "oui" == utm && chercheutm(t.lat, t.lng), !1
    }
});

$("#proj, #ycoord, #xcoord").change(function () { // Modif RLE pour rendre fonctionnel la prise du point
    "use strict";
    var e = $("#xcoord").val(), a = $("#ycoord").val();
    if ("" != e && "" != a && "nr" != $("#proj").val() && "w84" == $("#proj").val() && (marker ? marker.setLatLng([a, e]) : marker = L.marker([a, e]).addTo(map)) && map.setView([a, e], 12)){
        transform93(a, e);
        $("#lat").val(a) && $("#lng").val(e);
        $("#idcoord").val("Nouv"); // A vérifier
        $("#codesite").val("Nouv"); // A vérifier
        $("#pr").val(1); // rev-engi : 1 = site pointé, 2 = à la commune
    }
});

$("#btfiche10").click(function () {
    "use strict";
    $(this).hasClass("text-primary") ? ($(this).removeClass("text-primary").addClass("text-success"), $("#liste10").show(), fiche(), $("html, body").animate({scrollTop: $("#listefiche").offset().top}, "slow")) : ($(this).removeClass("text-success").addClass("text-primary"), $("#liste10").hide())
});

$(function () {
        $("#date").datepicker({
            changeMonth: !0, changeYear: !0, onClose: function (e) {
                $("#date2").datepicker("option", "minDate", e), $("#date2").val($(this).val())
            }
        }), $("#date2").datepicker({changeMonth: !0, changeYear: !0})
    }), $.timepicker.regional.fr = {
    timeOnlyTitle: "Choisir une heure",
    timeText: "Heure",
    hourText: "Heures",
    minuteText: "Minutes",
    secondText: "Secondes",
    currentText: "Maintenant",
    closeText: "Fermer"
}, $("#heure").timepicker($.timepicker.regional.fr), $("#heure2").timepicker($.timepicker.regional.fr), $("#imgpluscoord").click(function () {
    "use strict";
    $(this).hasClass("fa-plus") ? ($(this).removeClass("fa-plus").addClass("fa-minus"), $("#pluscoord").slideDown("slow")) : ($(this).removeClass("fa-minus").addClass("fa-plus"), $("#pluscoord").hide())
}), $("#imgplusfiche").click(function () {
    "use strict";
    $(this).hasClass("fa-plus") ? ($(this).removeClass("fa-plus").addClass("fa-minus"), $("#plusfiche").slideDown("slow")) : ($(this).removeClass("fa-minus").addClass("fa-plus"), $("#plusfiche").hide())
}), $("#imgpluslocale").click(function () {
    "use strict";
    $(this).hasClass("fa-plus") ? ($(this).removeClass("fa-plus").addClass("fa-minus"), $("#latin1").val(""), $("#pluslatin1").slideDown("slow")) : ($(this).removeClass("fa-minus").addClass("fa-plus"), $("#pluslatin1").hide())
}), $("#imgplusproto").click(function () {
    "use strict";
    $(this).hasClass("fa-plus") ? ($(this).removeClass("fa-plus").addClass("fa-minus"), $("#plusproto").show()) : ($(this).removeClass("fa-minus").addClass("fa-plus"), $("#plusproto").hide())
}), $("#plusobs").click(function () {
    "use strict";
    $("#nomobs").val(""), $("#prenomobs").val(""), $("#dia1").modal("show")
}), $("#bttdia1").click(function () {
    "use strict";
    $("#dia1").modal("hide");
    var e = $("#nomobs").val().toUpperCase(), a = $("#prenomobs").val().toLowerCase();
    inserobservateur(e, a)
}), $(".afcarte").click(function () {
    "use strict";
    if ($(this).hasClass("btn")) {
        var e = verifinfo();
        "non" == e && ($("#blocmap").toggle(), $("#blocfiche").toggle(), $("#change").removeClass("w-50").addClass("w-100"), $("#blocobs").toggle(), sel = $("#sel").val(), "aucun" != sel ? $("#selm").val() == sel && "oui" == $("#valsel").val() ? $("#blocsaisie").show() : choixobser(sel) : $("#blocsaisie").hide())
    } else $("#blocmap").toggle(), $("#change").removeClass("w-100").addClass("w-50"), $("#blocobs").toggle(), $("#blocfiche").toggle(), $("#selm").val(sel)
}), $("#bttdia16").click(function () {
    "use strict";
    $("#blocmap").toggle(), $("#blocfiche").toggle(), $("#change").removeClass("w-50").addClass("w-100"), $("#blocobs").toggle(), sel = $("#sel").val(), "aucun" != sel ? $("#selm").val() == sel && "oui" == $("#valsel").val() ? $("#blocsaisie").show() : choixobser(sel) : $("#blocsaisie").hide()
}), $(".idvar").click(function () {
    "use strict";
    sel = $(this).attr("id"), $(".list-inline").find("li").removeClass("text-primary"), $(this).addClass("text-primary"), choixobser(sel)
}), $(function () {
    "use strict";

    function e(e) {
        return a(e).pop()
    }

    function a(e) {
        return e.split(/,\s*/)
    }

    // Autocomplete observateurs autres
    $("#observateur2").autocomplete({
        source: function (a, t) {
            $.getJSON("modeles/ajax/saisie/listeobservateur.php", {term: e(a.term)}, function (e) {
                t($.map(e, function (e) {
                    return {label: e.observateur, value: e.idobser}
                }))
            })
        }, search: function () {
            var a = e(this.value);
            if (a.length < 1) return !1
        }, focus: function () {
            return !1
        }, select: function (e, t) {
            var l = a(this.value);
            l.pop(), l.push(t.item.label), l.push(""), this.value = l.join(", ");
            var o = $("#idobser").val();
            return o && $("#idobser").val(o + ", " + t.item.value), !1
        }, change: function (e, a) {
            a.item || ($(this).val(""), $("#idobser").val($("#idobseror").val()), $("#dia2").modal("show"))
        }
    })
}), $(function () {
    $("#det").autocomplete({
        source: function (e, a) {
            $.getJSON("modeles/ajax/saisie/listeobservateur.php", {term: e.term}, function (e) {
                a($.map(e, function (e) {
                    return {label: e.observateur, value: e.idobser}
                }))
            })
        }, select: function (e, a) {
            return this.value = a.item.label, $("#iddet").val(a.item.value), !1
        }, change: function (e, a) {
            a.item || ($(this).val($("#observateur").val()), $("#iddet").val($("#idobseror").val()), $("#dia2").modal("show"))
        }
    })
}), $(".info").click(function () {
    "use strict";
    var e = $(this).attr("id");
    "infolieu" == e ? $.post("modeles/ajax/saisie/infoaide.php", {}, function (e) {
        $("#rinfoaide").html(e), $("#dia15").modal("show")
    }) : $.post("modeles/ajax/saisie/infoattr.php", {id: e, sel: sel}, function (e) {
        $("#rinfo").html(e), $("#dia14").modal("show")
    })
}), $("#imgplushab").click(function () {
    "use strict";
    $(this).hasClass("fa-plus") ? ($(this).removeClass("fa-plus").addClass("fa-minus"), $("#plushab").show()) : ($(this).removeClass("fa-minus").addClass("fa-plus"), $("#plushab").hide())
}), $("#habitat").change(function () {
    "use strict";
    var e = $("#habitat").val(), a = 2;
    "NR" != e ? ($("#cdhab").val(e), $("#habitat2").show(), $("#habitat3").hide(), $.post("modeles/ajax/saisie/listehabitat.php", {
        cdhab: e,
        niv: a
    }, function (e) {
        $("#habitat2").html(e)
    })) : ($("#cdhab").val(""), $("#habitat2").hide())
}), $("#habitat2").change(function () {
    "use strict";
    var e = $("#habitat2").val(), a = 3;
    "NR" != e ? ($("#cdhab").val(e), $("#habitat3").show(), $.post("modeles/ajax/saisie/listehabitat.php", {
        cdhab: e,
        niv: a
    }, function (e) {
        $("#habitat3").html(e)
    })) : ($("#cdhab").val($("#habitat").val()), $("#habitat3").hide())
}), $("#habitat3").change(function () {
    "use strict";
    var e = $("#habitat3").val();
    "NR" != e ? $("#cdhab").val(e) : $("#cdhab").val($("#habitat2").val())
}), $("#statutobs").change(function () {
    "use strict";
    var e = $("#statutobs").val();
    "No" == e ? ($("#denom").prop("disabled", !0), $("#tdenom").prop("disabled", !0), $(".nbexact").prop("disabled", !0), $("#protocol").focus(), $("#estim").hide(), $("#BttS").hide(), $(".nbexact").val(""), $("#nbmax").val(0), $("#nbmin").val(0)) : ($("#denom").prop("disabled", !1), $("#tdenom").prop("disabled", !1), $(".nbexact").prop("disabled", !1), $("#denom").focus(), $("#BttS").show())
}), $("#denom").change(function () {
    "use strict";
    var e = $("#denom").val();
    "NSP" == e ? ($(".nbexact").prop("disabled", !0), $("#obsmethode").focus(), $(".nbexact").val(""), $("#nbmax").val(""), $("#nbmin").val(""), $("#estim").hide(), $("#tdenom").prop("disabled", !0)) : ($("#tdenom").prop("disabled", !1), "Co" == e ? ($("#estim").hide(), $(".nbexact").prop("disabled", !1), $("#nbmax").val(""), $("#nbmin").val(""), $("#ndiff").focus()) : ($(".nbexact").prop("disabled", !1), $("#estim").show(), $(".nbexact").val(""), $("input[name=clabon]").prop("checked", !1), $("#nbtmp").hide()))
}), $("#tdenom").change(function () {
    var e = $("#tdenom").val(), a = $("#denom").val();
    "Co" == a ? ($(".nbexact").val(""), "IND" == e || "NSP" == e ? ($("#nbmin").val(""), $("#nbmax").val(""), $(".nbexact").prop("disabled", !1), $("#ndiff").focus(), $("#nbtmp").hide()) : ($(".nbexact").prop("disabled", !0), $("#nbtmp1").val(""), $("#nbtmp").show(), $("#nbtmp1").focus())) : ($("#nbtmp").hide(), "IND" == e || "NSP" == e ? $(".nbexact").prop("disabled", !1) : $(".nbexact").prop("disabled", !0))
}), $("#nbtmp1").change(function () {
    "use strict";
    var e = $(this).val(), a = $("#tdenom").val();
    $("#nbmin").val(e), $("#nbmax").val(e), "CPL" == a && ($("#male").val(e), $("#femelle").val(e))
}), $(".nbexact").change(function () {
    "use strict";
    var e = "" != $("#ndiff").val() ? $("#ndiff").val() : 0, a = "" != $("#male").val() ? $("#male").val() : 0,
        t = "" != $("#femelle").val() ? $("#femelle").val() : 0, l = parseInt(e) + parseInt(a) + parseInt(t);
    $("#nbmin").val(l), $("#nbmax").val(l)
}), $("input[name=clabon]").change(function () {
    "use strict";
    var e = $("#tdenom").val();
    "IND" != e && "NSP" != e || $(".nbexact").val("");
    var a = $("input[name=clabon]:checked").val();
    "cl1" == a && ($("#nbmin").val(1), $("#nbmax").val(10)), "cl2" == a && ($("#nbmin").val(11), $("#nbmax").val(100)), "cl3" == a && ($("#nbmin").val(101), $("#nbmax").val(1e3)), "cl4" == a && ($("#nbmin").val(1001), $("#nbmax").val(1e4)), "cl5" == a && ($("#nbmin").val(1e4), $("#nbmax").val(""))
}), $("#etatbio").change(function () {
    "use strict";
    3 == $(this).val() ? $("#cmort").show() : ($("#cmort").hide(), $("#mort").val(0))
}), $("#indnid").change(function () {
    "use strict";
    var e = $("#indnid").val();
    0 == e ? $("#nicheur").html("") : e <= 3 ? $("#nicheur").html("Nidification possible") : e > 3 && e <= 10 ? $("#nicheur").html("Nidification probable") : e > 10 && $("#nicheur").html("Nidification certaine")
}), $("#imgpluscol").click(function () {
    "use strict";
    $(this).hasClass("fa-plus") ? ($(this).removeClass("fa-plus").addClass("fa-minus"), $("#pluscol").slideDown("slow"), $("#detcol").prop("disabled", !0), $("#detgen").prop("disabled", !0), $("#codegen").prop("disabled", !0), $("#prepgen").prop("disabled", !0), $("#typegen").prop("disabled", !0), $("#sexegen").prop("disabled", !0)) : ($(this).removeClass("fa-minus").addClass("fa-plus"), $("#pluscol").hide())
}), $("#collect").click(function () {
    "use strict";
    $("#collect").is(":checked") ? ($("#detcol").prop("disabled", !1), $("#detcol").focus(), $("#detcol").val($("#det").val()), $("#iddetcol").val($("#iddet").val())) : ($("#iddetcol").val(""), $("#detcol").prop("disabled", !0).val(""))
}), $("#gen").click(function () {
    "use strict";
    $("#gen").is(":checked") ? ($("#detgen").prop("disabled", !1), $("#codegen").prop("disabled", !1), $("#prepgen").prop("disabled", !1), $("#typegen").prop("disabled", !1), $("#sexegen").prop("disabled", !1), $("#prepgen").val($("#det").val()), $("#idprep").val($("#iddet").val()), $("#detgen").val($("#detcol").val()), $("#iddetgen").val($("#iddetcol").val()), $("#typegen").focus()) : ($("#codegen").prop("disabled", !0).val(""), $("#iddetgen").val(""), $("#idprep").val(""), $("#detgen").prop("disabled", !0).val(""), $("#prepgen").prop("disabled", !0).val(""), $("#typegen").prop("disabled", !0).val("NR"), $("#sexegen").prop("disabled", !0).val(""))
}), $("#detcol").autocomplete({
    source: function (e, a) {
        $.getJSON("modeles/ajax/saisie/listeobservateur.php", {term: e.term}, function (e) {
            a($.map(e, function (e) {
                return {label: e.observateur, value: e.idobser}
            }))
        })
    }, select: function (e, a) {
        return this.value = a.item.label, $("#iddetcol").val(a.item.value), !1
    }
}), $("#detgen").autocomplete({
    source: function (e, a) {
        $.getJSON("modeles/ajax/saisie/listeobservateur.php", {term: e.term}, function (e) {
            a($.map(e, function (e) {
                return {label: e.observateur, value: e.idobser}
            }))
        })
    }, select: function (e, a) {
        return this.value = a.item.label, $("#iddetgen").val(a.item.value), !1
    }
}), $("#prepgen").autocomplete({
    source: function (e, a) {
        $.getJSON("modeles/ajax/saisie/listeobservateur.php", {term: e.term}, function (e) {
            a($.map(e, function (e) {
                return {label: e.observateur, value: e.idobser}
            }))
        })
    }, select: function (e, a) {
        return this.value = a.item.label, $("#idprep").val(a.item.value), !1
    }
}), $("#imgplusplte").click(function () {
    "use strict";
    $(this).hasClass("fa-plus") ? ($(this).removeClass("fa-plus").addClass("fa-minus"), $("#latin1").val(""), $("#pltehote").slideDown("slow"), $("#choixplte").val(""), $("#nbpltel").val("")) : ($(this).removeClass("fa-minus").addClass("fa-plus"), $("#pltehote").hide())
}), $("#choixplte").autocomplete({
    source: function (e, a) {
        $.getJSON("modeles/ajax/saisie/listebota.php", {term: e.term, sel: sel}, function (e) {
            a($.map(e, function (e) {
                return "" == e.nomvern ? {label: e.nom, value: e} : {label: e.nom + " (" + e.nomvern + ")", value: e}
            }))
        })
    }, select: function (e, a) {
        $("#choixplte").val(a.item.value.nom);
        var t = $("#cdnombota").val(), l = $("#nbplte").val();
        return "" != t ? ($("#cdnombota").val(t + "," + a.item.value.cdnom), $("#nbplte").val(l + "," + $("#nbpltel").val()), $("#ulplante").prepend("<li>" + $("#nbpltel").val() + " sur " + a.item.value.nom + "</li>")) : ($("#cdnombota").val(a.item.value.cdnom), $("#nbplte").val($("#nbpltel").val()), $("#ulplante").html("<li>" + $("#nbpltel").val() + " sur " + a.item.value.nom + "</li>")), !1
    }, change: function (e, a) {
        a.item || ($(this).val(""), $("#cdnombota").val(""), $("#nbplte").val(""), $("#nbpltel").val(""), $("#ulplante").html(""))
    }
}), $("#bttAplte").click(function () {
    var e = $("#cdnombota").val();
    "" != e && ($("#choixplte").val(""), $("#nbpltel").val(""))
}), $("#BttV").click(function () {
    "use strict";
    $("#BttV").hide(), $("#Bt").val("BttV"), $("#valajaxs").show()
}), $("#BttS").click(function () {
    "use strict";
    $("#Bt").val("BttS"), $("#valajaxs").show()
}), $("#BttN").click(function () {
    "use strict";
    $("#BttN").hide(), $("#Bt").val("BttN"), $("#valajaxs").show()
}), $("#BttM").click(function () {
    "use strict";
    $("#Bt").val("BttM"), $("#valajaxs").show()
}), $("#formulaire").on("submit", function (e) {
    "use strict";
    if ($("#R1").html(""), e.preventDefault(), "oui" == $("#aphoto").val()) {
        var a = $("#crop").cropit("export", {type: "image/jpeg", quality: .9, originalSize: !1});
        $(".hidden-image-data").val(a)
    }
    if ("BttM" == $("#Bt").val()) {
        var t = $(this);
        valimodif(t)
    } else if ("Nouv" == $("#idfiche").val()) {
        var l = $("#date").val(), o = $("#xlambert").val(), et = $("#etude").val();
        if ("" == l || "" == o || "" == et) $("#valajaxs").hide(), "" == l && $("#R1").prepend('<div class="alert alert-danger">Aucune date de saisie ! Renseigner une date ou un intervalle de date</div>'), "" == o && $("#R1").prepend('<div class="alert alert-danger">Aucune coordonnée ! Sélectionner un site déjà existant ou cliquer sur la carte pour en créer un.</div>'), "" == et && $("#R1").prepend('<div class="alert alert-danger">Aucune étude de renseignée ! Sélectionner une étude avec la liste déroulante. </div>');
        else {
            var t = $(this);
            valider(t)
        }
    } else {
        var t = $(this);
        valider(t)
    }
}), $("#listefiche").on("click", ".voirliste", function () {
    "use strict";
    var e = $(this).parent().parent().attr("id");
    $(".table").find("tr").removeClass("bg-info"), $("#" + e).addClass("bg-info"), affichelisteobs(e), $("#val").hide(), $("#valm").removeClass("d-flex").hide()
}), $("#vobsfiche").on("click", function () {
    var e = $("#getidfiche").val();
    affichelisteobs(e), $("#val").hide(), $("#valm").addClass("d-flex").show(), $("#BttM").hide(), $("#BttSM").hide()
}), $("#vofiche").on("click", function () {
    var e = $("#idfiche").val();
    $("#getidfiche").val(e), affichefiche(), $("html, body").animate({scrollTop: 0}, "slow"), recupfiche(e)
}), $("#listeobs").on("click", ".obst", function () {
    "use strict";
    var e = $(this).parent().attr("id"), a = e.substring(0, 1), t = e.substring(1);
    $.post("modeles/ajax/saisie/listeobs.php", {idfiche: t, ordre: a}, function (e) {
        $("#listeobs").html(e)
    })
}), $("#listeobs").on("click", ".modobs", function () {
    "use strict";
    var e = $(this).parent().attr("id").substring(1);
    $(".modobs").removeClass("text-warning"), $(this).addClass("text-warning"), modobs(e), $("#BttM").show(), $("#BttSM").show()
}), $("#listeobs").on("click", ".suppobs", function () {
    "use strict";
    var e = $(this).parent().attr("id").substring(1);
    suppobs(e, 0)
}), $("#listefiche").on("click", ".ajoutesp", function () {
    "use strict";
    var e = $(this).parent().parent().attr("id");
    $("#idfiche").val(e), afficheobs(), $("html, body").animate({scrollTop: 0}, "slow"), sel = $("#sel").val(), "aucun" != sel && choixobser(sel), $("#afiche").val("oui"), $(".ndecache :input").not(":button, :submit, :reset, #det, #stade, #denom, #etude, #protocol").val(""), $("#statutobs").val("Pr"), $("#etatbio").val("2"), $("#ndiff").val(0), $("#male").val(0), $("#femelle").val(0), $("#cdnom").val(""), $("#cdref").val(""), $("#idobs").val("Nouv"), $("#valm").removeClass("d-flex").hide(), $("#val").show(), $("#BttV").show(), $("#BttS").show(), $("#BttN").show()
}), $("#aobsfiche").on("click", function () {
    $(".ndecache :input").not(":button, :submit, :reset, #det, #stade, #denom, #protocol").val(""), $("#statutobs").val("Pr"), $("#etatbio").val("2"), $("#ndiff").val(0), $("#male").val(0), $("#femelle").val(0), $("#cdnom").val(""), $("#cdref").val(""), $("#idobs").val("Nouv"), $("#afiche").val("oui"), $("#valm").removeClass("d-flex").hide(), $("#val").show(), $("#BttV").show(), $("#BttS").show(), $("#BttN").show()
}), $("#listefiche").on("click", ".modfiche", function () {
    "use strict";
    var e = $(this).parent().parent().attr("id");
    $("#getidfiche").val(e), $(".table").find("tr").removeClass("bg-info"), $("#" + e).addClass("bg-info"), affichefiche(), $("html, body").animate({scrollTop: 0}, "slow"), recupfiche(e)
}), $("#bttdiaN13").click(function () {
    mod = "non", nonsite(), $("#codesite").val("Nouv")
}), $("#BttF").click(function () {
    "use strict";
    var e = $("#idfiche").val(), a = $("#idcoord").val(), t = $("#codesite").val(), l = $("#xlambert").val(),
        o = $("#ylambert").val(), i = $("#lat").val(), s = $("#lng").val(), n = $("#l93").val(), r = $("#l935").val(),
        c = $("#utm").val(), d = $("#utm1").val(), u = $("#typepoly").val(), p = $("#altitude").val(),
        m = $("#codecom").val(), v = $("#codedep").val(), h = $("#pr").val(), f = $("#lieub").val(),
        b = $("#date").val(), g = $("#date2").val(), x = $("#idobser").val(), w = $("#typedon").val(),
        y = $("#floutage").val(), k = $("#source").val(), pre = $("#precision").val(), C = $("#org").val(), et = $("#etude").val(), N = $("#heure").val(),
        S = $("#heure2").val(), T = $("#tempdeb").val(), L = $("#tempfin").val();
    $.ajax({
        url: "modeles/ajax/saisie/vmodfiche.php",
        type: "POST",
        dataType: "json",
        data: {
            idfiche: e,
            idcoord: a,
            codesite: t,
            x: l,
            y: o,
            lat: i,
            lng: s,
            l93: n,
            l935: r,
            utm: c,
            utm1: d,
            typepoly: u,
            alt: p,
            codecom: m,
            iddep: v,
            pr: h,
            site: f,
            date: b,
            date2: g,
            idobser: x,
            typedon: w,
            floutage: y,
            source: k,
            idpreci: pre,
            org: C,
            etude: et,
            heure: N,
            heure2: S,
            tempdeb: T,
            tempfin: L
        },
        success: function (e) {
            "Oui" == e.statut ? $("#R").html('<div class="alert alert-success"><i class="fa fa-check"></i> Les modifications sur la fiches ont été enregistrées</div>') : alert(e.statut)
        }
    })
}), $("#bttdia7").click(function () {
    "use strict";
    var e = $("#encours").val(), a = $("#idobs").val();
    if (0 == e) $.ajax({
        url: "modeles/ajax/saisie/modobs.php",
        type: "POST",
        dataType: "json",
        data: {idobs: a},
        success: function (t) {
            if (1 == t.nbligne) suppression(t.ligne, a, 0, e); else {
                $("#stademod").text(t.nbligne);
                var l = "", o = 1;
                $.each(t.ligne, function (t, i) {
                    l += '<li><i class="fa fa-trash curseurlien text-danger" onclick="suppression(' + i.idligne + "," + a + "," + o + "," + e + ')"></i> ' + i.stade + "</li>"
                }), $("#modligne").html(l), $("#libmod").html("stades pour cette observation. Choisissez le stade a supprimé."), $("#suptous").html('<span><i class="fa fa-trash curseurlien text-danger" onclick="suppressiont(' + a + ")\"></i> Supprimer toute l'observation."), $("#dia6").modal({
                    backdrop: "static",
                    show: !0,
                    keyboard: !1
                })
            }
        }
    }); else {
        var t = $("#idligneobs").val();
        suppression(t, a, 0, e)
    }
}), $("#BttSM").click(function () {
    cache(), $("#stade").focus(), $("#BttSM").hide(), $("#idligneobs").val("Nouv")
}), $(".BttSA").click(function () {
    $("#R").html(""), $("#R1").html(""), $("#listeobs").html(""), efface("#formulaire"), affichefiche(), $("#idfiche").val("Nouv"), $("#idobs").val("Nouv"), $("#observateur2").focus(), $("html, body").animate({scrollTop: 0}, "slow"), $("#valm").removeClass("d-flex").hide(), $("#val").addClass("d-flex").show()
});

// Ajout de photo
$("#adphoto").click(function () {
    "use strict";
    if ($(this).hasClass("fa-camera")) {
        $(this).removeClass("fa-camera").addClass("fa-minus");
        $("#photo").slideDown("slow"), $("#BttP").hide();
        var e = $("#idobser").val(), a = e.split(", ");
        a[1] ? ($("#obserphoto").show(), $.post("modeles/ajax/saisie/listeobservateurp.php", {idobser: e}, function (e) {
            $("#opph").html(e)
        })) : ($("#obserphoto").hide(), $("#opph").html(""))
    } else $(this).removeClass("fa-minus").addClass("fa-camera"), $("#photo").hide()
});

// Gestion de la rotation des photos
$("input[name=orien]").change(function () {
    var e = $("input[name=orien]:checked").val();
    "paysage" == e ? (w = 400, h = 266) : (w = 200, h = 300), $("#crop").cropit("previewSize", {width: w, height: h})
});

$("#crop").cropit({
    exportZoom: 2,
    imageBackground: !0,
    imageBackgroundBorderWidth: 40,
    width: 400,
    height: 266,
    onImageError: function (e) {
        1 === e.code && $(".error-msg").html('<div class="alert alert-danger">Votre photo est trop petite</div>')
    }
});

$(".cropit-image-input").bind("change", function () {
    $("#BttP").show(), $("#aphoto").val("oui")
});

$(".export").click(function () {
    "use strict";
    $("#dia12").modal({backdrop: "static", show: !0, keyboard: !1});
    var e = $("#crop").cropit("export"), a = $('<img src="' + e + '" />');
    $("#imgdia12").html(a)
});

$(".rotate-ccw").click(function () {
    $("#crop").cropit("rotateCCW")
});

$(".rotate-cw").click(function () {
    $("#crop").cropit("rotateCW")
});

// Gestion des stations de type Mares
$("#typestation").change(function(){
    $("#typestation").val() == 1 ? $("#mare").show() : $("#mare").hide();
});

// Gestion des stations de type Mares
$("#btn_create_station").change(function(){
    if ($("#btn_create_station").prop("checked")) { // Nouvelle station
        $("#create_station").show();
        $("#nom_station").html("");
        $("#save_station").show();
        $("#lieub").val("");
        $(".leaflet-draw").show();
        $("#showcom").show(); //
        $("#showphoto").show(); //
        $("#codesite").val('Nouv'); //
    } else { // Mode 'édition' de station existante
        $("#create_station").hide();
        $("#save_station").hide();
        $(".leaflet-draw").hide();
        $("#mare").hide();
        $("#showcom").hide(); //
        $("#showphoto").hide(); //
    }
});

// Enregistrer la station
$("#save_station").on("click", function(){
        "use strict";
        // Controle sur la saisie de la date
    if ($("#date").val() == "") {
        $("#alert1").html('<div class="alert alert-warning">\n' +
            '  <strong>Attention ! </strong>Merci de renseigner la date de la description.\n' +
            '</div>');
        $("#date").select();
        return false;
    }
    $("#alert1").html('');
        var l = $("#xlambert").val(),
            o = $("#ylambert").val(),
            i = $("#altitude").val(),
            s = $("#l93").val(),
            n = $("#l935").val(),
            r = $("#lat").val(),
            c = $("#lng").val(),
            photo = $("#aphoto").val(),
            idobseror = $("#idobseror").val(),
            u = "oui" == utm ? $("#utm").val() : "",
            p = "oui" == utm ? $("#utm1").val() : "",
            copyright = $("input[name=opph]:checked").val() == undefined ? $("#idobser").val() : $("input[name=opph]:checked").val() ;
            var imagedata;
            photo == 'oui' ? imagedata = encodeURIComponent($("#crop").cropit("export", {type: "image/jpeg", quality: .9, originalSize: !1})) : imagedata = "";
            console.log($("form").serialize());
            var element = $("form").serialize();
        $.ajax({
            url: "modeles/ajax/stations/stations.php",
            type: "POST",
            dataType: "json",
            data: element + "&idobseror=" + idobseror + "&copyright=" + copyright + "&imagedata=" + imagedata + "&aphoto=" + photo + "&x=" + l + "&y=" + o + "&alt=" + i + "&l93=" + s + "&l935=" + n + "&lat=" + r + "&lng=" + c + "&utm=" + u + "&utm1=" + p,
            success: function (e) {
                // window.location.reload(false); // Comment on debbug
            },
            error: function() {
                console.log('Erreur!')
            }
        })
});

// Update des infos de la station
$("#update_station").on("click", function(){
    "use strict";
    if ($("#aphoto").val() == "oui" && $("#dateprisedevue").val() == "") {
        $("#alert1").html('<div class="alert alert-warning">\n' +
            '  <strong>Attention ! </strong>Merci de renseigner la date de prise de vue.\n' +
            '</div>');
        $("#dateprisedevue").select();
        return false;
    }
    $("#alert1").html('');
    var l = $("#xlambert").val(),
        o = $("#ylambert").val(),
        i = $("#altitude").val(),
        s = $("#l93").val(),
        n = $("#l935").val(),
        r = $("#lat").val(),
        c = $("#lng").val(),
        codesite = $("#codesite").val(),
        photo = $("#aphoto").val(),
        u = "oui" == utm ? $("#utm").val() : "",
        p = "oui" == utm ? $("#utm1").val() : "",
        copyright = $("input[name=opph]:checked").val() == undefined ? $("#idobser").val() : $("input[name=opph]:checked").val() ;
    var imagedata;
    photo == 'oui' ? imagedata = encodeURIComponent($("#crop").cropit("export", {type: "image/jpeg", quality: .9, originalSize: !1})) : imagedata = "";

    var element = $("form").serialize();
    $.ajax({
        url: "modeles/ajax/stations/stations.php",
        type: "POST",
        dataType: "json",
        data: element + "&codesite=" + codesite + "&copyright=" + copyright + "&imagedata=" + imagedata + "&aphoto=" + photo + "&x=" + l + "&y=" + o + "&alt=" + i + "&l93=" + s + "&l935=" + n + "&lat=" + r + "&lng=" + c + "&utm=" + u + "&utm1=" + p,
        success: function (e) {
            recup_info( $("#codesite").val() );
            $("#aphoto").val("non");
            console.log('Ok')
        },
        error: function() {
            console.log('Erreur!')
        }
    })
});

// Annuler la modification
$("#cancel_update").on("click", function() {
    window.location.reload(false);
});

// Supprimer une photo
function supphoto(id) {
    $.ajax({
        url: "modeles/ajax/stations/photo.php",
        type: "POST",
        dataType: "json",
        data: "id=" + id + "&action=supprimer",
        success: function (e) {
            console.log('Photo supprimée' + e)
            recup_info( $("#codesite").val() );
        },
        error: function(e) {
            console.log('Erreur!' + e)
        }
    });
}

// Date de la prise de vue lors qu'un ajout de photo sans description
$("#dateprisedevue").datepicker({
    changeMonth: !0, changeYear: !0
});