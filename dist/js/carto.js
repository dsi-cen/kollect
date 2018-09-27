function carte(e) {
    "use strict";
    proj4.defs("EPSG:2154", "+proj=lcc +lat_1=49 +lat_2=44 +lat_0=46.5 +lon_0=3 +x_0=700000 +y_0=6600000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs");
    var a = e.utm,
        o = (e.contour2, e.emprise),
        t = e.ne,
        s = e.sw,
        c = e.cleign;
    e.clegoogle;
    if (map = L.map("map"), "fr" != o) $.getJSON("emprise/contour2.geojson", {}, function(a) {
        var o = { color: e.stylecontour2.color, weight: e.stylecontour2.weight },
            t = L.Proj.geoJson(a, { style: o }).addTo(map);
        map.fitBounds(t.getBounds())
    });
    else {
        var r = t.split(",", 2),
            n = s.split(",", 2),
            l = parseFloat(r[0]),
            i = parseFloat(r[1]),
            u = parseFloat(n[0]),
            p = parseFloat(n[1]);
        map.fitBounds([
            [u, p],
            [l, i]
        ])
    }
    var h = L.tileLayer("https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}", { attribution: "Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community" }),
        d = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", { maxZoom: 19, attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>' }).addTo(map),
        v = L.tileLayer("https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png", { maxZoom: 19, attribution: '&copy; Openstreetmap France | &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>' }),
        m = L.tileLayer("https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png", { maxZoom: 16, attribution: 'Map data: &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="http://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)' });
    if (c) var f = L.tileLayer("https://wxs.ign.fr/" + c + "/geoportail/wmts?LAYER=GEOGRAPHICALGRIDSYSTEMS.MAPS&EXCEPTIONS=text/xml&FORMAT=image/jpeg&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}", { attribution: '&copy; <a href=http://www.ign.fr/">IGN</a>' }),
        y = L.tileLayer("https://wxs.ign.fr/" + c + "/geoportail/wmts?LAYER=ORTHOIMAGERY.ORTHOPHOTOS&EXCEPTIONS=text/xml&FORMAT=image/jpeg&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}", { attribution: '&copy; <a href="http://www.ign.fr/">IGN</a>' }),
        //Ajouter une couche ign irc
        z = L.tileLayer("https://wxs.ign.fr/" + c + "/geoportail/wmts?LAYER=ORTHOIMAGERY.ORTHOPHOTOS.IRC&EXCEPTIONS=text/xml&FORMAT=image/jpeg&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}", { attribution: '&copy; <a href="http://www.ign.fr/">IGN</a>' }),
        b = L.tileLayer("https://wxs.ign.fr/" + c + "/geoportail/wmts?LAYER=", { attribution: '&copy; <a href="http://www.ign.fr/">IGN</a>' }),
        //fin
        g = L.tileLayer("https://wxs.ign.fr/" + c + "/geoportail/wmts?LAYER=CADASTRALPARCELS.PARCELS&EXCEPTIONS=text/xml&FORMAT=image/png&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}", { opacity: "0.5", attribution: '&copy; <a href="http://www.ign.fr/">IGN</a>' }),

        //Ajout layers carto
        // x = { "Carte Open Street": d, "Carte Open Street FR": v, "Carte Open Topo": m, "Carte IGN": f, "Photo aériennes IGN": y, "Photo aériennes ESRI": h },
        x = { "Carte Open Street": d, "Carte Open Street FR": v, "Carte Open Topo": m, "Carte IGN": f, "Photo aériennes IGN": y, "Photo aériennes ESRI": h, "Infra-rouge IGN IRC": z },
        E = { Cadastre: g, Macarte: b };
    //fin
    else var x = { "Carte Open Street": d, "Carte Open Street FR": v, "Carte Open Topo": m, "Photo aériennes ESRI": h },
        E = {};
    var T = $("#couchem").val();
    if ("" != T) {
        var S = "osm" == T ? d : "osmfr" == T ? v : "topo" == T ? m : "ign" == T ? f : y;
        S.addTo(map)
    } else if (e.couche) {
        var S = "osm" == e.couche ? d : "osmfr" == e.couche ? v : "topo" == e.couche ? m : "ign" == e.couche ? f : y;
        S.addTo(map)
    } else d.addTo(map);
    var O = L.control.layers(x, E);
    "fr" != o ? ($.getJSON("emprise/contour.geojson", {}, function(a) {
        var o = { color: e.stylecontour.color, weight: e.stylecontour.weight },
            t = L.Proj.geoJson(a, { style: o }),
            s = "Commune";
        O.addOverlay(t, s)
    }), "oui" == a ? $.getJSON("modeles/ajax/carto/mgrs.php", {}, function(a) {
        if ("Oui" == a.statut) {
            var o = { color: e.stylemaille.color, weight: e.stylemaille.weight, opacity: e.stylemaille.opacity },
                t = L.Proj.geoJson(a.carto, { style: o, onEachFeature: function(e, a) { a.bindPopup(e.properties.id) } }),
                s = "Maille UTM/MGRS";
            O.addOverlay(t, s)
        }
    }) : $.getJSON("modeles/ajax/carto/maille93.php", {}, function(a) {
        if ("Oui" == a.statut) {
            var o = { color: e.stylemaille.color, weight: e.stylemaille.weight, fillOpacity: e.stylemaille.opacity },
                t = L.Proj.geoJson(a.carto, { style: o, onEachFeature: function(e, a) { a.bindPopup(e.properties.id) } }),
                s = "MailleL93";
            O.addOverlay(t, s)
        }
    })) : ($.getJSON("../emprise/contour.geojson", {}, function(a) {
        var o = { color: e.stylecontour.color, weight: e.stylecontour.weight },
            t = L.Proj.geoJson(a, { style: o }),
            s = "Départements";
        O.addOverlay(t, s)
    }), "oui" == a && $.getJSON("modeles/ajax/carto/mgrs.php", {}, function(a) {
        if ("Oui" == a.statut) {
            var o = { color: e.stylemaille.color, weight: e.stylemaille.weight, opacity: e.stylemaille.opacity },
                t = L.Proj.geoJson(a.carto, { style: o, onEachFeature: function(e, a) { a.bindPopup(e.properties.id) } }),
                s = "Maille UTM/MGRS";
            O.addOverlay(t, s)
        }
    })), "oui" == e.biogeo && $.getJSON("emprise/refgeos.geojson", {}, function(e) {
        var a = L.Proj.geoJson(e, { style: function(e) { return { color: e.properties.couleur, fillOpacity: .6 } }, onEachFeature: function(e, a) { a.bindPopup(e.properties.des) } }),
            o = "biogeographie";
        O.addOverlay(a, o)
    }), e.couchesup && $.each(e.couchesup, function(e, a) {
        "choro" == a.type && ("" != a.stitre ? $("#coucheplus").prepend('<div id="' + e + '"><i id="A' + e + '" class="fa fa-eye-slash curseurlien" title="Cacher/décacher la couche" onclick="cachecouche(' + a.id + ')"></i> <span id="titre' + a.id + '">' + a.titre + '</span><br><span class="small">' + a.stitre + '</span><input id="uni' + a.id + '" value="' + a.uni + '" type="hidden"/></div>') : $("#coucheplus").prepend('<div id="' + e + '"><i id="A' + e + '" class="fa fa-eye-slash curseurlien" title="Cacher/décacher la couche" onclick="cachecouche(' + a.id + ')"></i> <span id="titre' + a.id + '">' + a.titre + '</span> <input id="uni' + a.id + '" value="' + a.uni + '" type="hidden"/></div>')), "gen" == a.type && $.getJSON("emprise/" + e + ".geojson", {}, function(e) {
            var o = L.Proj.geoJson(e, { style: function(e) { return { color: e.properties.couleur, fillOpacity: .6 } }, onEachFeature: function(e, a) { a.bindPopup(e.properties.des) } }),
                t = a.titre;
            O.addOverlay(o, t)
        })
    }), L.control.scale({ position: "bottomleft", metric: !0, imperial: !1 }).addTo(map), O.addTo(map), map.attributionControl.addAttribution('Données &copy; <a href="https://obsnat.fr/">Nom structure</a>')
}

function cacher(e, a) {
    "use strict";
    $("#" + a).toggle()
}

function taxon(e) {
    "use strict";
    nbt <= 3 ? (nbt += 1, 0 == nbtaxon ? (nbtaxon = 1, recherchepoint(e)) : 1 == nbtaxon ? (nbtaxon += 1, recherchepoint(e)) : 2 == nbtaxon && (nbtaxon += 1, recherchepoint(e))) : $("#dia1").modal("show")
}

function recherchepoint(e) {
    "use strict";
    $.ajax({ url: "modeles/ajax/carto/pointtaxon.php", type: "POST", dataType: "json", data: { cdnom: e }, success: function(a) { "Oui" == a.statut && (a.sensible ? ($("#dia3").modal("show"), nbtaxon -= 1, nbt -= 1) : ($("#rtaxon").prepend('<span id="srd' + nbtaxon + '"><i id="rd' + nbtaxon + '" class="fa fa-eye-slash curseurlien rd' + nbtaxon + '" title="Cacher/décacher la couche" onclick="cachetaxon(' + nbtaxon + "," + e + ')"></i> <i id="' + e + '" class="fa fa-circle rd' + nbtaxon + '"></i> ' + a.nom + ' <i class="fa fa-trash curseurlien" title="Enlever la couche" onclick="supcouche(' + nbtaxon + ')"></i><br></span>'), a.point ? affichepoint(a.point, e) : $("#dia2").modal("show"))) } })
}

function generateGeojsonPoint(e) {
    "use strict";
    return geojson = { type: "FeatureCollection", features: [] }, e.forEach(function(e) {
        var a = e.geojson_point,
            o = { sp: e.nom };
        geojson.features.push({ type: "Feature", properties: o, geometry: a })
    }), geojson
}

function affichepoint(e, a) {
    "use strict";
    var o = $("#" + a).css("Color"),
        t = { radius: 5, fillColor: o, color: o, fillOpacity: 1 };
    geojson = generateGeojsonPoint(e), 1 == nbtaxon ? (layertaxon1 = L.geoJson(geojson, { onEachFeature: onEachFeature, pointToLayer: function(e, a) { return L.circleMarker(a, t) } }), map.addLayer(layertaxon1)) : 2 == nbtaxon ? (layertaxon2 = L.geoJson(geojson, { onEachFeature: onEachFeature, pointToLayer: function(e, a) { return L.circleMarker(a, t) } }), map.addLayer(layertaxon2)) : 3 == nbtaxon && (layertaxon3 = L.geoJson(geojson, { onEachFeature: onEachFeature, pointToLayer: function(e, a) { return L.circleMarker(a, t) } }), map.addLayer(layertaxon3))
}

function onEachFeature(e, a) {
    "use strict";
    var o = e.properties.sp;
    a.bindPopup(o)
}

function cachetaxon(e, a) {
    "use strict";
    var o;
    1 == e ? o = layertaxon1 : 2 == e ? o = layertaxon2 : 3 == e && (o = layertaxon3), map.hasLayer(o) ? ($("#" + a).removeClass("rd" + e), $("#rd" + e).removeClass("rd" + e), map.removeLayer(o)) : (map.addLayer(o), $("#rd" + e).addClass("rd" + e), $("#" + a).addClass("rd" + e))
}

function supcouche(e) {
    "use strict";
    var a;
    1 == e ? a = layertaxon1 : 2 == e ? a = layertaxon2 : 3 == e && (a = layertaxon3), a.clearLayers(), $("#srd" + e).remove(), nbt -= 1, 1 == e ? nbtaxon = 0 : 2 == e ? nbtaxon = 1 : 3 == e && (nbtaxon = 2)
}

function creainfo() {
    "use strict";
    info.onAdd = function(e) { return this._div = L.DomUtil.create("div", "infoleg"), this.update(), this._div }, info.addTo(map)
}

function highlightFeature(e) {
    "use strict";
    var a = e.target;
    a.setStyle({ fillOpacity: 1 }), L.Browser.ie || L.Browser.opera || L.Browser.edge || a.bringToFront(), info.update(a.feature.properties)
}

function resetHighlight(e) {
    "use strict";
    couchesup.resetStyle(e.target), info.update()
}

function interaction(e, a) {
    "use strict";
    a.on({ mouseover: highlightFeature, mouseout: resetHighlight })
}

function cachecouche(e) {
    "use strict";
    titre = $("#titre" + e).text(), uni = $("#uni" + e).val(), "oui" == couchesupoui ? map.hasLayer(couchesup) ? ($("#Acouche" + coucheid).removeClass("text-success"), map.removeLayer(couchesup), map.removeControl(info), $(".legend").remove(), coucheid != e && creationcouchesup(e)) : coucheid == e ? (map.addLayer(couchesup), $("#Acouche" + e).addClass("text-success"), coucheid = e, couchesupoui = "oui", creainfo()) : creationcouchesup(e) : creationcouchesup(e)
}

function creationcouchesup(e) {
    $.getJSON("emprise/couche" + e + ".geojson", {}, function(a) {
        var o = a.choro,
            t = a.nbchoro;
        couchesup = L.Proj.geoJson(a, { style: function(e) { return { fillColor: couleurchoro(e.properties.val, o, t), weight: 0, fillOpacity: .7 } }, onEachFeature: interaction }).addTo(map), $("#Acouche" + e).addClass("text-success"), coucheid = e, couchesupoui = "oui", creainfo(), crealegend(e, o, t)
    })
}

function couleurchoro(e, a, o) { "use strict"; return 2 == o ? e >= a.class1.val && e < a.class2.val ? a.class1.co : a.class2.co : 3 == o ? e >= a.class1.val && e < a.class2.val ? a.class1.co : e >= a.class2.val && e < a.class3.val ? a.class2.co : a.class3.co : 4 == o ? e >= a.class1.val && e < a.class2.val ? a.class1.co : e >= a.class2.val && e < a.class3.val ? a.class2.co : e >= a.class3.val && e < a.class4.val ? a.class3.co : a.class4.co : 5 == o ? e >= a.class1.val && e < a.class2.val ? a.class1.co : e >= a.class2.val && e < a.class3.val ? a.class2.co : e >= a.class3.val && e < a.class4.val ? a.class3.co : e >= a.class4.val && e < a.class5.val ? a.class4.co : a.class5.co : 6 == o ? e >= a.class1.val && e < a.class2.val ? a.class1.co : e >= a.class2.val && e < a.class3.val ? a.class2.co : e >= a.class3.val && e < a.class4.val ? a.class3.co : e >= a.class4.val && e < a.class5.val ? a.class4.co : e >= a.class5.val && e < a.class6.val ? a.class5.co : a.class6.co : 7 == o ? e >= a.class1.val && e < a.class2.val ? a.class1.co : e >= a.class2.val && e < a.class3.val ? a.class2.co : e >= a.class3.val && e < a.class4.val ? a.class3.co : e >= a.class4.val && e < a.class5.val ? a.class4.co : e >= a.class5.val && e < a.class6.val ? a.class5.co : e >= a.class6.val && e < a.class7.val ? a.class6.co : a.class7.co : 8 == o ? e >= a.class1.val && e < a.class2.val ? a.class1.co : e >= a.class2.val && e < a.class3.val ? a.class2.co : e >= a.class3.val && e < a.class4.val ? a.class3.co : e >= a.class4.val && e < a.class5.val ? a.class4.co : e >= a.class5.val && e < a.class6.val ? a.class5.co : e >= a.class6.val && e < a.class7.val ? a.class6.co : e >= a.class7.val && e < a.class8.val ? a.class7.co : a.class8.co : void 0 }

function crealegend(e, a, o) {
    var t, s = [];
    $.each(a, function(e, a) { s.push(a.val) }), console.log(s), t = function() { for (var e = L.DomUtil.create("div", "infoleg legend mt-1"), t = 0; t < s.length; t++) e.innerHTML += '<i style="background:' + couleurchoro(s[t], a, o) + '"></i> ' + s[t] + (s[t + 1] ? " &ndash; " + s[t + 1] + "<br>" : " +"); return e }, $("#couche" + e).append(t)
}
var map, nbtaxon = 0,
    nbt = 0;
$(document).ready(function() {
    "use strict";
    var e = {};
    $.ajax({ url: "emprise/emprise.json", dataType: "json", success: function(a) { e = a, carte(e) } })
}), $(".rmot").keyup(function() {
    "use strict";
    var e = $(this).parent().parent().attr("id"),
        a = $(this).val(),
        a2up = "";
    a ? a2up = a[0].toUpperCase() + a.substring(1) : a2up = ""
    a ? $("." + e + ">li").show().not(":contains(" + a + "), :contains(" + a2up + ")").hide() : $("." + e + ">li").show()
});
var layertaxon1, layertaxon2, layertaxon3, geojson, couchesup, couchesupoui, coucheid, info = L.control(),
    titre, uni;
info.update = function(e) {
    "use strict";
    this._div.innerHTML = '<h4 class="h5">' + titre + "</h4>" + (e ? "<b>" + e.val + "</b> " + uni : "Passer la souris")
};