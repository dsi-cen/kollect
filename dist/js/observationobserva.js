function carte(t,e,a){"use strict";var i=e.split(",",2),o=parseFloat(i[0]),r=parseFloat(i[1]);if("oui"==nbmap){nbmap="non",map=new L.map("mapobser");var n=new L.TileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",{maxZoom:19,attribution:'&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'});map.setView(new L.LatLng(o,r),a),map.addLayer(n),1==t&&(marker=L.marker([o,r]).addTo(map))}else map.setView(new L.LatLng(o,r),a),1==t&&(marker=L.marker([o,r]).addTo(map))}function modfiche(t){"use strict";$.ajax({url:"../modeles/ajax/saisie/attribution.php",type:"POST",dataType:"json",data:{idfiche:t},success:function(t){"Oui"==t.statut?document.location.href="../index.php?module=saisie&action=saisie":alert("Erreur ! ")}})}function adson(t){"use strict";$.ajax({url:"../modeles/ajax/saisie/attribution.php",type:"POST",dataType:"json",data:{idobs:t},success:function(t){"Oui"==t.statut?document.location.href="../index.php?module=membre&action=ajoutson":alert("Erreur ! ")}})}function adphoto(t){"use strict";$.ajax({url:"../modeles/ajax/saisie/attribution.php",type:"POST",dataType:"json",data:{idobs:t},success:function(t){"Oui"==t.statut?document.location.href="../index.php?module=membre&action=ajoutphoto":alert("Erreur ! ")}})}function traitement(t){"use strict";var e=$("#perso").is(":checked")?"oui":"non",a=$("#id").val(),i=$("#regroup option:selected").val(),o=$("#tri option:selected").val(),r=$("#dep").val(),n=$("#sen").val();$.ajax({url:"modeles/ajax/observation/observation.php",type:"POST",dataType:"json",data:{id:a,regroup:i,tri:o,dep:r,page:t,sen:n,perso:e},success:function(t){"Oui"==t.statut?($("#listeobs").html(t.listeobs),$("#pagination").html(t.pagination),$('[data-toggle="tooltip"]').tooltip()):alert("Erreur ! ")}})}var map,marker,nbmap="oui";$(document).ready(function(){"use strict";traitement(1)}),$("#regroup").change(function(){"use strict";$("#p").val(1),traitement(1)}),$("#tri").change(function(){"use strict";$("#p").val(1),traitement(1)}),$("#perso").change(function(){"use strict";$("#p").val(1),traitement(1)}),$("#obs").on("show.bs.modal",function(t){"use strict";var e=$(t.relatedTarget),a=e.data("idobs"),i=e.data("photo"),o=e.data("idmor"),r=$(this);$.ajax({url:"modeles/ajax/observation/infoobs.php",type:"POST",dataType:"json",data:{idobs:a,photo:i},success:function(t){if("Oui"==t.statut)if(r.find(".modal-title").html("Informations sur l'observation n° "+a),t.diffcdref?r.find(".diffcdref").html("Saisie sous le nom de : <i>"+t.diffcdref+"</i>"):r.find(".diffcdref").html(""),r.find(".obsdatefr").html(t.date),r.find(".obsfloutage").html(t.lieu),r.find(".obsobservateur").html(t.observateur),r.find(".obsdeterminateur").html("Déterminateur : "+t.determinateur),r.find(".obsligne").html(t.ligne),r.find("#idobscom").val(a),r.find("#idmor").val(o),r.find(".lienidobs").html('<a class="color1" href="'+t.lien+'">Plus de détail</a>'),o?$("#postcom").show():$("#postcom").hide(),t.mod?r.find(".modobs").html('<i class="fa fa-pencil curseurlien ml-3" onclick="modfiche('+t.idfiche+')" title="Modifier votre observation"></i><i class="fa fa-camera curseurlien ml-2" onclick="adphoto('+a+')" title="Ajouter une photo"></i><i class="fa fa-volume-off curseurlien ml-2" onclick="adson('+a+')" title="Ajouter un son"></i>'):t.adphoto?r.find(".modobs").html('<i class="fa fa-camera curseurlien ml-2" onclick="adphoto('+a+')" title="Ajouter une photo"></i><i class="fa fa-volume-off curseurlien ml-2" onclick="adson('+a+')" title="Ajouter un son"></i>'):r.find(".modobs").html(""),t.commentaire?r.find(".obscommentaire").html(t.commentaire):r.find(".obscommentaire").html(""),t.photo?r.find(".obsphoto").html(t.photo):r.find(".obsphoto").html(""),t.coord){var e=t.pre,i=t.coord,n=1==e?14:12;carte(e,i,n),setTimeout(function(){map.invalidateSize()},400)}else{var e=0,n=8,s=$("#lat").val(),l=$("#lng").val(),i=s+","+l;carte(e,i,n),setTimeout(function(){map.invalidateSize()},400)}else alert("erreur")}})}),$("#obs").on("hidden.bs.modal",function(){"use strict";marker&&(map.removeLayer(marker),marker=null)}),$("#BttVcom").click(function(){"use strict";var t=$("#idobscom").val(),e=$("#idmcom").val(),a=$("#idmor").val(),i=$("#commentaire").val();""!=i?$.ajax({url:"../modeles/ajax/observation/commentaire.php",type:"POST",dataType:"json",data:{idm:e,idobs:t,com:i,idmor:a},success:function(t){"Oui"!=t.statut&&alert("Erreur ! lors insertion table"),$("#commentaire").val("")}}):alert("Aucun commentaire de saisie !")}),$(".popup-gallery").magnificPopup({delegate:"a",type:"image",tLoading:"Loading image #%curr%...",mainClass:"mfp-img-mobile",gallery:{enabled:!0,navigateByImgClick:!0,preload:[0,1]},image:{tError:'<a href="%url%">The image #%curr%</a> est absente..',titleSrc:function(t){return t.el.attr("title")}}}),$("#bttrhaut").click(function(){"use strict";$("html, body").animate({scrollTop:0},"slow")}),$("#pagination").on("click",".page-item",function(){"use strict";var t=$(this).attr("id"),e=t.substring(0,2);if("pp"==e)var a=t.substring(2);else var a=t.substring(1);$("#p").val(a),$("html, body").animate({scrollTop:0},"slow"),traitement(a)});