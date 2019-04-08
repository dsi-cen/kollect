function carte(e) {
    "use strict";
    proj4.defs("EPSG:2154", "+proj=lcc +lat_1=49 +lat_2=44 +lat_0=46.5 +lon_0=3 +x_0=700000 +y_0=6600000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs");
    var a = e.utm,
        o = (e.contour2, e.emprise),
        t = e.ne,
        s = e.sw,
        c = e.cleign;
    e.clegoogle;
    if (map = L.map("map"), "fr" != o) $.getJSON("emprise/contour2.geojson", {}, function (a) {
        var o = {color: e.stylecontour2.color, weight: e.stylecontour2.weight},
            t = L.Proj.geoJson(a, {style: o}).addTo(map);
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
    var h = L.tileLayer("https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}", {attribution: "Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community"}),
        d = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
        }).addTo(map),
        v = L.tileLayer("https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: '&copy; Openstreetmap France | &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }),
        m = L.tileLayer("https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png", {
            maxZoom: 16,
            attribution: 'Map data: &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="http://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)'
        });
    if (c) var f = L.tileLayer("https://wxs.ign.fr/" + c + "/geoportail/wmts?LAYER=GEOGRAPHICALGRIDSYSTEMS.MAPS&EXCEPTIONS=text/xml&FORMAT=image/jpeg&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}", {attribution: '&copy; <a href=http://www.ign.fr/">IGN</a>'}),
        y = L.tileLayer("https://wxs.ign.fr/" + c + "/geoportail/wmts?LAYER=ORTHOIMAGERY.ORTHOPHOTOS&EXCEPTIONS=text/xml&FORMAT=image/jpeg&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}", {attribution: '&copy; <a href="http://www.ign.fr/">IGN</a>'}),
        //Ajouter une couche ign irc
        z = L.tileLayer("https://wxs.ign.fr/" + c + "/geoportail/wmts?LAYER=ORTHOIMAGERY.ORTHOPHOTOS.IRC&EXCEPTIONS=text/xml&FORMAT=image/jpeg&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}", {attribution: '&copy; <a href="http://www.ign.fr/">IGN</a>'}),
        b = L.tileLayer("https://wxs.ign.fr/" + c + "/geoportail/wmts?LAYER=", {attribution: '&copy; <a href="http://www.ign.fr/">IGN</a>'}),
        //fin
        g = L.tileLayer("https://wxs.ign.fr/" + c + "/geoportail/wmts?LAYER=CADASTRALPARCELS.PARCELS&EXCEPTIONS=text/xml&FORMAT=image/png&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}", {
            opacity: "0.5",
            attribution: '&copy; <a href="http://www.ign.fr/">IGN</a>'
        }),
        x = {
            "Carte Open Street": d,
            "Carte Open Street FR": v,
            "Carte Open Topo": m,
            "Carte IGN": f,
            "Photo aériennes IGN": y,
            "Photo aériennes ESRI": h,
            "Infra-rouge IGN IRC": z
        },
        E = {Cadastre: g, Macarte: b};
    else var x = {"Carte Open Street": d, "Carte Open Street FR": v, "Carte Open Topo": m, "Photo aériennes ESRI": h},
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
    "fr" != o ? ($.getJSON("emprise/contour.geojson", {}, function (a) {
        var o = {color: e.stylecontour.color, weight: e.stylecontour.weight},
            t = L.Proj.geoJson(a, {style: o}),
            s = "Commune";
        O.addOverlay(t, s)
    }), "oui" == a ? $.getJSON("modeles/ajax/carto/mgrs.php", {}, function (a) {
        if ("Oui" == a.statut) {
            var o = {color: e.stylemaille.color, weight: e.stylemaille.weight, opacity: e.stylemaille.opacity},
                t = L.Proj.geoJson(a.carto, {
                    style: o, onEachFeature: function (e, a) {
                        a.bindPopup(e.properties.id)
                    }
                }),
                s = "Maille UTM/MGRS";
            O.addOverlay(t, s)
        }
    }) : $.getJSON("modeles/ajax/carto/maille93.php", {}, function (a) {
        if ("Oui" == a.statut) {
            var o = {color: e.stylemaille.color, weight: e.stylemaille.weight, fillOpacity: e.stylemaille.opacity},
                t = L.Proj.geoJson(a.carto, {
                    style: o, onEachFeature: function (e, a) {
                        a.bindPopup(e.properties.id)
                    }
                }),
                s = "MailleL93";
            O.addOverlay(t, s)
        }
    })) : ($.getJSON("../emprise/contour.geojson", {}, function (a) {
        var o = {color: e.stylecontour.color, weight: e.stylecontour.weight},
            t = L.Proj.geoJson(a, {style: o}),
            s = "Départements";
        O.addOverlay(t, s)
    }), "oui" == a && $.getJSON("modeles/ajax/carto/mgrs.php", {}, function (a) {
        if ("Oui" == a.statut) {
            var o = {color: e.stylemaille.color, weight: e.stylemaille.weight, opacity: e.stylemaille.opacity},
                t = L.Proj.geoJson(a.carto, {
                    style: o, onEachFeature: function (e, a) {
                        a.bindPopup(e.properties.id)
                    }
                }),
                s = "Maille UTM/MGRS";
            O.addOverlay(t, s)
        }
    })), "oui" == e.biogeo && $.getJSON("emprise/refgeos.geojson", {}, function (e) {
        var a = L.Proj.geoJson(e, {
                style: function (e) {
                    return {color: e.properties.couleur, fillOpacity: .6}
                }, onEachFeature: function (e, a) {
                    a.bindPopup(e.properties.des)
                }
            }),
            o = "biogeographie";
        O.addOverlay(a, o)
    }), e.couchesup && $.each(e.couchesup, function (e, a) {
        "choro" == a.type && ("" != a.stitre ? $("#coucheplus").prepend('<div id="' + e + '"><i id="A' + e + '" class="fa fa-eye-slash curseurlien" title="Cacher/décacher la couche" onclick="cachecouche(' + a.id + ')"></i> <span id="titre' + a.id + '">' + a.titre + '</span><br><span class="small">' + a.stitre + '</span><input id="uni' + a.id + '" value="' + a.uni + '" type="hidden"/></div>') : $("#coucheplus").prepend('<div id="' + e + '"><i id="A' + e + '" class="fa fa-eye-slash curseurlien" title="Cacher/décacher la couche" onclick="cachecouche(' + a.id + ')"></i> <span id="titre' + a.id + '">' + a.titre + '</span> <input id="uni' + a.id + '" value="' + a.uni + '" type="hidden"/></div>')), "gen" == a.type && $.getJSON("emprise/" + e + ".geojson", {}, function (e) {
            var o = L.Proj.geoJson(e, {
                    style: function (e) {
                        return {color: e.properties.couleur, fillOpacity: .6}
                    }, onEachFeature: function (e, a) {
                        a.bindPopup(e.properties.des)
                    }
                }),
                t = a.titre;
            O.addOverlay(o, t)
        })
    }), L.control.scale({
        position: "bottomleft",
        metric: !0,
        imperial: !1
    }).addTo(map), O.addTo(map), map.attributionControl.addAttribution('Données &copy; <a href="https://obsnat.fr/">Nom structure</a>')
}

// Datatable
function make_table() {
    $('#liste_stations thead tr').clone(true).appendTo('#liste_stations thead');
    $('#liste_stations thead tr:eq(1) th').each(function (i) {
        if(i === 0) {
            return;
        }
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Recherche ' + title + '" />');

        $('input', this).on('keyup change', function () {
            if (table.column(i).search() !== this.value) {
                table
                    .column(i)
                    .search(this.value)
                    .draw();
            }
        });
    });
    var table = $('#liste_stations').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        language: { url:"dist/js/datatables/france.json" }
    });
}

function affiche_stations(geo) {
    "use strict";
    var contourstations = new L.FeatureGroup;
    var pointstations = new L.FeatureGroup;
    var a = new L.Icon({
        iconUrl: "dist/css/images/marker-vert.png",
        shadowUrl: "dist/css/images/marker-shadow.png",
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    for (var o = 0; o < geo.geo.length; o++) {
        var l = JSON.parse(geo.geo[o]),
            s = L.geoJson(l, {style: {color: "#b300b3", fillOpacity: .1, weight: 3}});
        contourstations.addLayer(s)
    }
    for (var o = 0; o < geo.lat.length; o++) {

        var m = new L.marker([geo.lat[o], geo.lng[o]], {icon: a});

        m.id = geo.idsite[o];

        m.bindPopup(geo.nom[o]);
        m.on('mouseover', function (e) {
            this.openPopup();
            $("#liste_stations").find("tr").removeClass("table-primary");
            $("#" + e.target.id).addClass("table-primary");
        });
        m.on('mouseout', function () {
            this.closePopup();
        });

        // Focus
        $("#liste").on("click", ".focus", function () {
            "use strict";
            console.log(m);
            var e = $(this).parent().parent().attr("id");
            var latLngs = [ m.getLatLng() ];
            var markerBounds = L.latLngBounds(latLngs);
            map.fitBounds(markerBounds);

        });

        pointstations.addLayer(m)
    }

    map.addLayer(contourstations)
    map.addLayer(pointstations)
    map.fitBounds(contourstations.getBounds())
}


$(document).ready(function () {

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

    $(".close").on("click", function () {
        $("#droite").empty();
        $("#gauche").empty();
        $("#detail").hide();
    });

    // Chargement de la carte
    var e = {};
    $.ajax({
        url: "emprise/emprise.json", dataType: "json", success: function (a) {
            e = a, carte(e);
        }
    });

    // Centrer sur un département
    $("#departement, #typestation").on('change', function () {
        var iddep = $("#departement").val();
        var type = $("#typestation").val();
        $.ajax({
            url: "modeles/ajax/stations/recupliste.php",
            type: "POST",
            dataType: "json",
            data: {iddep: iddep, type: type},
            success: function (e) {
                $("#liste").html(e.liste);
                make_table(); // Apply Datatable
                affiche_stations(e.geo);
            },
            error: function () {
                console.log('error')
            }
        })
    });
});

function detail(idstation) {

    $.ajax({
        url: "modeles/ajax/stations/recupdetails.php",
        type: "POST",
        dataType: "json",
        data: {idstation: idstation},
        success: function (e) {
            $("#detail .modal-title").html(e.detail.site + " (" + e.detail.libidstatusstation + ")");
            // e.detail.idmembre == e.detailidm ? $("#detail .modal-title").append( ' <i onclick="delstation()" class="fa fa-trash curseurlien text-danger"></i>' ) : null ;
            console.log(e.detail.idmembre);
            console.log( e.detailidm );

            var str =  "<em>Station de type '" + e.detail.libtypestation + "' enregistré(e) par " + e.detail.nom + " " + e.detail.prenom + "</em>";
            $("#gauche").html(str + "<hr>" + e.detail.commentaire);

            e.detail.idtypestation == 1 ? $("#gauche").append('<hr><strong>Liste des descriptions</strong> (ajouter une nouvelle description <i onclick="adddescription(' + idstation + ')" class="fa fa-plus-circle curseurlien text-success"></i> )<hr>') : null ;

            $("#gauche").append(e.descriptions);
            $("#detail").modal("show");
            $("#gallery").html(e.gallery);

        },
        error: function () {
        }
    });
}

function minitable(idinfosmare) {
    console.log("ok");
    $.ajax({
        url: "modeles/ajax/stations/recupdetails.php",
        type: "POST",
        dataType: "json",
        data: {idinfosmare: idinfosmare},
        success: function (e) {
            $("#droite").empty();
            $("#droite").html(e.description);
        },
        error: function () {
        }
    });
}

function delstation() { // TODO : sup station
}

function deldescription(idstation) { // TODO
    $('#modal_deldescription').modal("show");
}

// validersuppdescription

function adddescription(idstation) {
        "use strict";
        document.location.href = "index.php?module=stations&action=saisie&addto=" + idstation ;
}