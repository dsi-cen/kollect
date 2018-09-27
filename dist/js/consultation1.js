function carte1(e){"use strict";proj4.defs("EPSG:2154","+proj=lcc +lat_1=49 +lat_2=44 +lat_0=46.5 +lon_0=3 +x_0=700000 +y_0=6600000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs");var a=new L.TileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",{maxZoom:19,attribution:'&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'});$.getJSON("emprise/contour2.geojson",{},function(a){var t={color:e.stylecontour2.color,weight:e.stylecontour2.weight},o=L.Proj.geoJson(a,{style:t}).addTo(map);map.fitBounds(o.getBounds())}),map=L.map("carte",{layers:[a]}),drawnItems=new L.FeatureGroup,map.addLayer(drawnItems),map.addControl(new L.Control.Draw({draw:{polygon:{allowIntersection:!1,showArea:!0,shapeOptions:{color:"#03F"}},rectangle:!1,circle:!1,marker:!1,polyline:!1}})),$(".leaflet-draw-draw-polygon").click(function(){$("#rayon").val(0),drawnItems.getLayers().length>0&&drawnItems.clearLayers()}),map.on("draw:created",function(e){var a=(e.layerType,e.layer);drawnItems.addLayer(a);for(var t="((",o=a.getLatLngs(),i=0;i<o[0].length;i++)0!=i&&(t+="),("),t+=o[0][i].lng+","+o[0][i].lat;t+="))",$("#poly").val(t),$("#choixloca").val("poly"),$("#codecom").val(""),$("#commune").val(""),$("#site").val(""),$("#inchoixloca").val(""),$("#Vchoixloca").hide(),$("#imgpluscom").hide(),$("#idsite").val(""),$("#imgplussite").hide(),$("#BttS").show()}),map.on("click",function(e){var a=1e3*$("#rayon").val();if(0!=a){drawnItems.getLayers().length>0&&drawnItems.clearLayers();var t=L.circle(e.latlng,{fillOpacity:.1,radius:a});drawnItems.addLayer(t),map.fitBounds(t.getBounds());var o=Math.round(1e4*e.latlng.lat)/1e4,i=Math.round(1e4*e.latlng.lng)/1e4;$("#choixloca").val("cercle"),$("#latc").val(o),$("#lngc").val(i),$("#BttS").show()}})}function remplirtableexport(e){var a=$("#nomfichier").val();if(""!=a)var t=a;else var o=new Date,i=o.getMonth().length+1===1?o.getMonth()+1:"0"+(o.getMonth()+1),t="Export-"+o.getDate()+"-"+i+"-"+o.getFullYear();var l=$("#tblexport").DataTable({language:{url:"dist/js/datatables/france.json",buttons:{colvis:"Choix des champs à exporter"}},data:e,deferRender:!0,scrollY:600,scrollCollapse:!0,scroller:!0,columnDefs:[{targets:[5],data:5,render:{_:"date",sort:"tri"}},{targets:[0,6,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30],visible:!1}],dom:"Bfrtip",buttons:[{extend:"csvHtml5",exportOptions:{columns:":visible"},title:t},{extend:"excelHtml5",exportOptions:{columns:":visible"},title:t},"colvis"],initComplete:function(){setTimeout(function(){l.buttons().container().appendTo("#tblexport_wrapper .col-md-6:eq(0)")},10)}})}function remplirtable(e){if("oui"==e)var a=$("#statut").DataTable({language:{url:"dist/js/datatables/france.json"},order:[],scrollY:"600px",scrollCollapse:!0,paging:!1,dom:"Bfrtip",buttons:["csvHtml5","excelHtml5"],initComplete:function(){setTimeout(function(){a.buttons().container().appendTo("#statut_wrapper .col-md-6:eq(0)")},10)}});else var a=$("#statut").DataTable({language:{url:"dist/js/datatables/france.json"},order:[],scrollY:"600px",scrollCollapse:!0,paging:!1})}function traitement(e){"use strict";var a,t=$("#choixtax").val(),o=$("#choixloca").val(),i=$("#date").val(),l=$("#dates").val(),s=$("#idobser").val(),c=$("#decade").val(),n=$("#etude").val(),r=$("#orga").val(),d=$("#cdnom").val(),u=$("#choixobserva").val(),v=$("#date2").val(),m=$("#dates2").val(),h=$("#vali").val(),p=$("#latc").val(),b=$("#lngc").val();if($("#choixconsult").hide(),$("#rchoix").show(),$("#listeobs").show(),$("#perso").is(":checked")){s=$("#idobseror").val();var f="oui"}else var f="non";""!=o&&("commune"==o?a=$("#codecom").val():"site"==o?a=$("#idsite").val():"sitee"==o?a=$("#sitee").val():"poly"==o?a=$("#poly").val():"cercle"==o&&(a=$("#rayon").val()));var x=$("#photo").is(":checked")?"oui":"non",g=$("#son").is(":checked")?"oui":"non";$.ajax({url:"modeles/ajax/consultation/observation1.php",type:"POST",dataType:"json",data:{choixtax:t,choixloca:o,loca:a,idobser:s,cdnom:d,observa:u,date:i,date2:v,dates:l,dates2:m,vali:h,photo:x,son:g,page:e,lat:p,lng:b,decade:c,etude:n,d:f,orga:r},success:function(a){if("Oui"==a.statut){var c=a.nbobs;if($("#listeobs").html(a.listeobs),a.pagination&&($("#afpage").show(),$("#pagination").html(a.pagination)),c>0&&1==e){var n="";n+=""!=s?$("#perso").is(":checked")?1==c?"Votre observation ":"Vos observations ("+c+")":1==c?"Observation de <b>"+$("#obser").val()+"</b>":"Observations ("+c+") de <b>"+$("#obser").val()+"</b>":1==c?"Observation ":"Observations ("+c+") ",""!=t&&("observa"==t&&(n+=" de <b>"+$("#inchoixtax").val()+"</b>"),"espece"==t&&(n+=" de <b>"+$("#inchoixtax").val()+"</b>")),""!=o&&("commune"==o?n+=" sur <b>"+$("#inchoixloca").val()+"</b>":"site"==o?n+=" sur le site <b>"+$("#site").val()+"</b>":"sitee"==o?n+=" sur les sites contenant <b>"+$("#sitee").val()+"</b>":"poly"==o?n+=" sur l'emprise du polygone":"cercle"==o&&(n+=" sur une distance de "+$("#rayon").val()+" km du point de la latitude "+$("#latc").val()+" et longitude "+$("#lngc").val())),""!=i&&(n+=i==v?" le <b>"+i+"</b>":" du <b>"+i+" au "+v+"</b>"),""!=l&&(n+=l==m?" saisie le <b>"+i+"</b>":" saisie du <b>"+i+" au "+v+"</b>"),"NR"!=h&&(n+=" (validation : <b>"+$("#vali option:selected").text()+"</b>)"),"oui"==x&&(n+=" avec photo"),"oui"==g&&(n+=" avec son"),$("#lchoix").html(n)}}else alert("Erreur ! ")}})}function modfiche(e){"use strict";$.ajax({url:"modeles/ajax/saisie/attribution.php",type:"POST",dataType:"json",data:{idfiche:e},success:function(e){"Oui"==e.statut?document.location.href="index.php?module=saisie&action=saisie":alert("Erreur ! ")}})}function carte(e,a,t){"use strict";var o=a.split(",",2),i=parseFloat(o[0]),l=parseFloat(o[1]);if("oui"==nbmap){nbmap="non",map=new L.map("mapobser");var s=new L.TileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",{maxZoom:19,attribution:'&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'});map.setView(new L.LatLng(i,l),t),map.addLayer(s),1==e&&(marker=L.marker([i,l]).addTo(map))}else map.setView(new L.LatLng(i,l),t),1==e&&(marker=L.marker([i,l]).addTo(map))}function adson(e){"use strict";$.ajax({url:"modeles/ajax/saisie/attribution.php",type:"POST",dataType:"json",data:{idobs:e},success:function(e){"Oui"==e.statut?document.location.href="index.php?module=membre&action=ajoutson":alert("Erreur ! ")}})}function adphoto(e){"use strict";$.ajax({url:"modeles/ajax/saisie/attribution.php",type:"POST",dataType:"json",data:{idobs:e},success:function(e){"Oui"==e.statut?document.location.href="index.php?module=membre&action=ajoutphoto":alert("Erreur ! ")}})}var dep="non",map,marker,nbmap="oui",drawnItems;$(document).ready(function(){"use strict";$("#listeobs").hide(),$("#rchoix").hide(),$("#afpage").hide(),$("#Vchoixes").hide(),$("#Vchoixloca").hide(),$("#imgplustaxon").hide(),$("#imgpluscom").hide(),$("#imgplussite").hide(),$("#infoaide").hide(),$("#dlink").hide(),$("#BttS").hide(),$("#perso").is(":checked")?$("#idobser").val($("#idobseror").val()):"oui"==$("#cperso").val()&&($("#obser").val($("#observateur").val()),$("#perso").prop("checked",!0));var e=$("#droit").val();"oui"!=$("#cperso").val()||e?($("#BttE").hide(),$("#BttG").hide()):($("#BttE").show(),$("#BttG").show()),"oui"==e&&($("#BttE").show(),$("#BttG").show());var a={};$.ajax({url:"emprise/emprise.json",dataType:"json",success:function(e){a=e,carte1(a)}}),$("#carte").css("cursor","crosshair")}),$("#BttA").click(function(){"use strict";$(this).hasClass("btn-info")?($(this).removeClass("btn-info").addClass("btn-success"),$("#btn-aide-txt",this).text("Cacher"),$("#infoaide").show()):($(this).removeClass("btn-success").addClass("btn-info"),$("#btn-aide-txt",this).text("Aide"),$("#infoaide").hide())}),$("#date").datepicker({changeMonth:!0,changeYear:!0,onClose:function(e){$("#date2").datepicker("option","minDate",e),$("#date2").val($(this).val()),$("#dates").val(""),$("#dates2").val(""),$("#decade").val("NR")}}),$("#date2").datepicker({changeMonth:!0,changeYear:!0}),$("#dates").datepicker({changeMonth:!0,changeYear:!0,onClose:function(e){$("#dates2").datepicker("option","minDate",e),$("#dates2").val($(this).val()),$("#date").val(""),$("#date2").val(""),$("#decade").val("NR")}}),$("#dates2").datepicker({changeMonth:!0,changeYear:!0}),$("#decade").change(function(){"use strict";$("#date").val(""),$("#date2").val(""),$("#dates").val(""),$("#dates2").val("")}),$("#obser").autocomplete({source:function(e,a){$.getJSON("modeles/ajax/saisie/listeobservateur.php",{term:e.term},function(e){a($.map(e,function(e){return{label:e.observateur,value:e.idobser}}))})},select:function(e,a){this.value=a.item.label,$("#idobser").val(a.item.value);var t=$("#idobseror").val(),o=$("#droit").val();return o?t==a.item.value?$("#perso").prop("checked",!0):$("#perso").prop("checked",!1):t==a.item.value?($("#perso").prop("checked",!0),$("#BttE").show(),$("#BttG").show()):($("#perso").prop("checked",!1),$("#BttE").hide(),$("#BttG").hide()),!1}}),$("#perso").change(function(){"use strict";var e=$("#droit").val();e?$("#perso").is(":checked")?$("#obser").val($("#observateur").val()):$("#obser").val(""):$("#perso").is(":checked")?($("#obser").val($("#observateur").val()),$("#BttE").show(),$("#BttG").show()):($("#obser").val(""),$("#BttE").hide(),$("#BttG").hide())}),$("#obser").change(function(){"use strict";""==$(this).val()&&$("#idobser").val("")}),$("#observa").change(function(){"use strict";$("#imgplustaxon").hide();var e=$("#observa").val(),a=$("#inchoixtax").val(),t=$("#choixobserva").val();if("NR"!=e){var o=$("#observa option:selected").text();$("#taxon").val(""),$("#cdnom").val(""),"observa"==$("#choixtax").val()?($("#inchoixtax").val(a+", "+o),$("#choixobserva").val(t+",'"+e+"'")):($("#Vchoixes").show(),$("#inchoixtax").val(o),$("#choixtax").val("observa"),$("#choixobserva").val("'"+e+"'")),""!=$("#choixloca").val()?$("#BttS").show():$("#BttS").hide()}else $("#choixtax").val(""),$("#inchoixtax").val(""),$("#choixobserva").val("")}),$("#taxon").autocomplete({source:function(e,a){$.getJSON("modeles/ajax/liste.php",{term:e.term},function(e){a($.map(e,function(e){return""==e.nomvern?{label:e.nom,value:e}:{label:e.nom+" ("+e.nomvern+")",value:e}}))})},select:function(e,a){this.value=a.item.label,$("#observa").val("NR"),$("#choixobserva").val(""),$("#BttS").hide();var t=$("#inchoixtax").val(),o=$("#cdnom").val();return"espece"==$("#choixtax").val()?($("#inchoixtax").val(t+", "+a.item.label),$("#cdnom").val(o+",'"+a.item.value.cdnom+"'")):($("#Vchoixes").show(),$("#choixtax").val("espece"),$("#imgplustaxon").show(),$("#inchoixtax").val(a.item.label),$("#cdnom").val("'"+a.item.value.cdnom+"'")),!1}}),$("#taxon").change(function(){"use strict";""==$(this).val()&&($("#choixtax").val(""),$("#cdnom").val(""),$("#inchoixtax").val(""),$("#Vchoixes").hide(),$("#imgplustaxon").hide())}),$("#imgplustaxon").click(function(){"use strict";$("#taxon").val(""),$("#taxon").focus()}),$("#supchoixes").click(function(){"use strict";$("#inchoixtax").val(""),$("#observa").val("NR"),$("#taxon").val(""),$("#Vchoixes").hide(),$("#imgplustaxon").hide(),$("#choixobserva").val(""),$("#choixtax").val("")}),$("#commune").autocomplete({source:function(e,a){$.getJSON("modeles/ajax/listecommune.php",{term:e.term,dep:dep},function(e){a($.map(e,function(e){return"non"==dep?{label:e.commune,value:e}:{label:e.commune+" ("+e.departement+")",value:e}}))})},select:function(e,a){this.value=a.item.label,$("#site").val(""),$("#sitee").val(""),$("#idsite").val("");var t=$("#inchoixloca").val(),o=$("#codecom").val();return"commune"==$("#choixloca").val()?($("#inchoixloca").val(t+", "+a.item.label),$("#codecom").val(o+",'"+a.item.value.codecom+"'")):($("#Vchoixloca").show(),$("#choixloca").val("commune"),$("#imgpluscom").show(),$("#inchoixloca").val(a.item.label),$("#codecom").val("'"+a.item.value.codecom+"'")),$("#BttS").show(),drawnItems.getLayers().length>0&&(drawnItems.clearLayers(),$("#poly").val("")),!1}}),$("#commune").change(function(){"use strict";""==$(this).val()&&($("#choixloca").val(""),$("#codecom").val(""),$("#commune").val(""),$("#inchoixloca").val(""),$("#Vchoixloca").hide(),$("#imgpluscom").hide())}),$("#imgpluscom").click(function(){"use strict";$("#commune").val(""),$("#commune").focus()}),$("#site").autocomplete({minLength:2,source:function(e,a){$.getJSON("modeles/ajax/listesite.php",{term:e.term},function(e){a($.map(e,function(e){return{label:e.site+" ("+e.commune+")",value:e}}))})},select:function(e,a){this.value=a.item.label,$("#commune").val(""),$("#sitee").val(""),$("#codecom").val("");var t=$("#inchoixloca").val(),o=$("#idsite").val();return"site"==$("#choixloca").val()?($("#inchoixloca").val(t+", "+a.item.label),$("#idsite").val(o+",'"+a.item.value.idsite+"'")):($("#Vchoixloca").show(),$("#choixloca").val("site"),$("#imgplussite").show(),$("#inchoixloca").val(a.item.label),$("#idsite").val("'"+a.item.value.idsite+"'")),$("#BttS").show(),drawnItems.getLayers().length>0&&(drawnItems.clearLayers(),$("#poly").val("")),!1}}),$("#site").change(function(){""==$(this).val()&&($("#choixloca").val(""),$("#idsite").val(""))}),$("#imgplussite").click(function(){"use strict";$("#site").val(""),$("#site").focus()}),$("#sitee").change(function(){"use strict";$("#site").val(""),$("#commune").val(""),""!=$(this).val()?($("#choixloca").val("sitee"),$("#BttS").show()):($("#choixloca").val(""),$("#BttS").hide())}),$("#supchoixloca").click(function(){"use strict";$("#inchoixloca").val(""),$("#commune").val(""),$("#site").val(""),$("#Vchoixloca").hide(),$("#imgpluscom").hide(),$("#choixloca").val(""),$("#codecom").val(""),$("#idsite").val(""),$("#BttS").hide()}),$("#etude").change(function(){"use strict";0!=$(this).val()?$("#BttS").show():$("#BttS").hide()}),$("#rchoix").click(function(){"use strict";$("#rchoix").hide(),$("#listeobs").hide(),$("#afpage").hide(),$("#choixconsult").show(),$("#lchoix").html("")}),$("#BttS").click(function(){"use strict";var e,a=$("#choixtax").val(),t=$("#choixloca").val(),o=$("#date").val(),i=$("#dates").val(),l=$("#idobser").val(),s=$("#decade").val(),c=$("#etude").val(),n=$("#choixobserva").val(),r=$("#date2").val(),d=$("#dates2").val(),u=$("#vali").val(),v=$("#latc").val(),m=$("#lngc").val();if($("#choixconsult").hide(),$("#rchoix").show(),$("#listeobs").html(""),$("#listeobs").show(),$("#perso").is(":checked")){l=$("#idobseror").val();var h="oui"}else var h="non";""!=t||0!=c?("commune"==t?e=$("#codecom").val():"site"==t?e=$("#idsite").val():"sitee"==t?e=$("#sitee").val():"poly"==t?e=$("#poly").val():"cercle"==t&&(e=$("#rayon").val()),$("#mes").html(""),$.ajax({url:"modeles/ajax/consultation/listestatut.php",type:"POST",dataType:"json",data:{choixtax:a,choixloca:t,loca:e,idobser:l,observa:n,date:o,date2:r,dates:i,dates2:d,vali:u,lat:v,lng:m,decade:s,etude:c,d:h},success:function(e){if("Oui"==e.statut){$("#listeobs").html(e.tbl),remplirtable(e.d);var a="";a+=" Statut des <b>"+$("#inchoixtax").val()+"</b>",""!=t&&("commune"==t?a+=" sur <b>"+$("#inchoixloca").val()+"</b>":"site"==t?a+=" sur le site <b>"+$("#site").val()+"</b>":"sitee"==t&&(a+=" sur les sites contenant <b>"+$("#sitee").val()+"</b>")),""!=o&&(a+=o==r?" le <b>"+o+"</b>":" du <b>"+o+" au "+r+"</b>"),""!=i&&(a+=i==d?" saisie le <b>"+o+"</b>":" saisie du <b>"+o+" au "+r+"</b>"),"NR"!=u&&(a+=" (validation : <b>"+$("#vali option:selected").text()+"</b>)"),$("#lchoix").html(a)}else alert("Erreur ! ")}})):$("#mes").html('<div class="alert alert-danger">Vous devez sélectionner une localisation</div>')}),$("#BttE").click(function(){"use strict";var e,a=$("#choixtax").val(),t=$("#choixloca").val(),o=$("#date").val(),i=$("#dates").val(),l=$("#idobser").val(),s=$("#decade").val(),c=$("#etude").val(),n=$("#orga").val(),r=$("#choixobserva").val(),d=$("#date2").val(),u=$("#dates2").val(),v=$("#vali").val(),m=$("#latc").val(),h=$("#lngc").val(),p=$("#cdnom").val();if($("#choixconsult").hide(),$("#rchoix").show(),$("#listeobs").html(""),$("#listeobs").show(),$("#perso").is(":checked")){l=$("#idobseror").val();var b="oui"}else var b="non";var f=$("#photo").is(":checked")?"oui":"non",x=$("#son").is(":checked")?"oui":"non";""!=t||0!=c||""!=a?("commune"==t?e=$("#codecom").val():"site"==t?e=$("#idsite").val():"sitee"==t?e=$("#sitee").val():"poly"==t?e=$("#poly").val():"cercle"==t&&(e=$("#rayon").val()),$("#mes").html(""),$.ajax({url:"modeles/ajax/consultation/export1.php",type:"POST",dataType:"json",data:{choixtax:a,choixloca:t,loca:e,idobser:l,cdnom:p,observa:r,date:o,date2:d,dates:i,dates2:u,vali:v,lat:m,lng:h,decade:s,etude:c,d:b,photo:f,son:x,orga:n},success:function(e){"Oui"==e.statut?($("#listeobs").html(e.tbl),e.tblok&&remplirtableexport(e.data)):alert("Erreur ! ")}})):$("#mes").html('<div class="alert alert-danger">Vous devez sélectionner une localisation</div>')}),$("#BttG").click(function(){"use strict";var e,a=$("#choixtax").val(),t=$("#choixloca").val(),o=$("#date").val(),i=$("#dates").val(),l=$("#idobser").val(),s=$("#decade").val(),c=$("#etude").val(),n=$("#choixobserva").val(),r=$("#date2").val(),d=$("#dates2").val(),u=$("#vali").val(),v=$("#latc").val(),m=$("#lngc").val(),h=$("#cdnom").val();if($("#perso").is(":checked")){l=$("#idobseror").val();var p="oui"}else var p="non";var b=$("#photo").is(":checked")?"oui":"non",f=$("#son").is(":checked")?"oui":"non";""!=t||0!=c||""!=a?("commune"==t?e=$("#codecom").val():"site"==t?e=$("#idsite").val():"sitee"==t?e=$("#sitee").val():"poly"==t?e=$("#poly").val():"cercle"==t&&(e=$("#rayon").val()),$("#mes").html(""),$.ajax({url:"modeles/ajax/consultation/exportgeo.php",type:"POST",dataType:"json",data:{choixtax:a,choixloca:t,loca:e,idobser:l,cdnom:h,observa:n,date:o,date2:r,dates:i,dates2:d,vali:u,lat:v,lng:m,decade:s,etude:c,d:p,photo:b,son:f},success:function(e){if("Oui"==e.statut){$("#dlink").show();var a="data:application/json;charset=utf-8;base64,"+btoa(e.tbl);$("#dlink").attr("href",a).attr("download","export.geojson")}else alert("Erreur ! ou pas de données")}})):$("#mes").html('<div class="alert alert-danger">Vous devez sélectionner une localisation</div>')}),$("#dlink").click(function(){"use strict";$("#dlink").hide()}),$("#BttV").click(function(){"use strict";var e,a=$("#choixtax").val(),t=$("#choixloca").val(),o=$("#date").val(),i=$("#dates").val(),l=$("#idobser").val(),s=$("#etude").val(),c=$("#orga").val();""==a&&""==t&&""==o&&""==i&&0==s&&"NR"==c?$("#perso").is(":checked")||""!=l?(e="oui",$("#mes").html("")):$("#mes").html('<div class="alert alert-danger">Vous devez au minimum soit sélectionner un organisme, un observateur, soit une localisation, soit une espèce / groupe, soit une date, ou une etude</div>'):(e="oui",$("#mes").html("")),"oui"==e&&traitement(1)}),$("#bttrhaut").click(function(){"use strict";$("html, body").animate({scrollTop:0},"slow")}),$("#pagination").on("click",".page-item",function(){"use strict";var e=$(this).attr("id"),a=e.substring(0,2);if("pp"==a)var t=e.substring(2);else var t=e.substring(1);$("#p").val(t),$("html, body").animate({scrollTop:0},"slow"),traitement(t)}),$("#fiche").on("show.bs.modal",function(e){"use strict";var a=$(e.relatedTarget),t=a.data("idfiche"),o=$(this);$.ajax({url:"modeles/ajax/observation/infofiche.php",type:"POST",dataType:"json",data:{idfiche:t},success:function(e){"Oui"==e.statut?(o.find(".modal-title").html("Information sur le relevé n° "+t),e.mod?o.find(".lienidobs").html('<a class="color1" href="'+e.lien+'">Plus de détail</a><i class="fa fa-pencil curseurlien ml-3" onclick="modfiche('+t+')" title="Modifier votre relevé"></i>'):o.find(".lienidobs").html('<a class="color1" href="'+e.lien+'">Plus de détail</a>'),o.find("#listefiche").html(e.liste)):alert("erreur")}})}),$("#obs").on("show.bs.modal",function(e){"use strict";var a=$(e.relatedTarget),t=a.data("nomlat"),o=a.data("idobs"),i=a.data("latin"),l=a.data("nomfr"),s=a.data("photo"),c=a.data("idmor"),n=$(this);$.ajax({url:"modeles/ajax/observation/infoobs.php",type:"POST",dataType:"json",data:{idobs:o,photo:s},success:function(e){if("Oui"==e.statut)if(e.diffcdref?n.find(".diffcdref").html("Saisie sous le nom de : <i>"+e.diffcdref+"</i>"):n.find(".diffcdref").html(""),n.find(".obsdatefr").html(e.date),n.find(".obsidobs").html(o),n.find(".obsfloutage").html(e.lieu),n.find(".obsobservateur").html(e.observateur),n.find(".obsdeterminateur").html("Déterminateur : "+e.determinateur),n.find(".obsligne").html(e.ligne),n.find("#idobscom").val(o),n.find("#idmor").val(c),n.find(".lienidobs").html('<a class="color1" href="'+e.lien+'">Plus de détail</a>'),e.mod?n.find(".modobs").html('<i class="fa fa-pencil curseurlien ml-3" onclick="modfiche('+e.idfiche+')" title="Modifier votre observation"></i><i class="fa fa-camera curseurlien ml-2" onclick="adphoto('+o+')" title="Ajouter une photo"></i><i class="fa fa-volume-off curseurlien ml-2" onclick="adson('+o+')" title="Ajouter un son"></i>'):e.adphoto?n.find(".modobs").html('<i class="fa fa-camera curseurlien ml-2" onclick="adphoto('+o+')" title="Ajouter une photo"></i><i class="fa fa-volume-off curseurlien ml-2" onclick="adson('+o+')" title="Ajouter un son"></i>'):n.find(".modobs").html(""),e.commentaire?n.find(".obscommentaire").html(e.commentaire):n.find(".obscommentaire").html(""),e.photo?n.find(".obsphoto").html(e.photo):n.find(".obsphoto").html(""),e.coord){var a=e.pre,t=e.coord,i=1==a?14:12;carte(a,t,i),setTimeout(function(){map.invalidateSize()},400)}else{var a=0,i=8,l=$("#lat").val(),s=$("#lng").val(),t=l+","+s;carte(a,t,i),setTimeout(function(){map.invalidateSize()},400)}else alert("erreur")}}),"oui"==i?n.find(".modal-title").html("<i>"+t+"</i>"):n.find(".modal-title").html(l+" <i>"+t+"</i>")}),$("#obs").on("hidden.bs.modal",function(){"use strict";marker&&(map.removeLayer(marker),marker=null)}),$("#BttVcom").click(function(){"use strict";var e=$("#idobscom").val(),a=$("#idmcom").val(),t=$("#idmor").val(),o=$("#commentaire").val();""!=o?$.ajax({url:"modeles/ajax/observation/commentaire.php",type:"POST",dataType:"json",data:{idm:a,idobs:e,com:o,idmor:t},success:function(e){"Oui"!=e.statut&&alert("Erreur ! lors insertion table"),$("#commentaire").val("")}}):alert("Aucun commentaire de saisie !")}),$(".popup-gallery").magnificPopup({delegate:"a",type:"image",tLoading:"Loading image #%curr%...",mainClass:"mfp-img-mobile",gallery:{enabled:!0,navigateByImgClick:!0,preload:[0,1]},image:{tError:'<a href="%url%">The image #%curr%</a> est absente..',titleSrc:function(e){return e.el.attr("title")}}});