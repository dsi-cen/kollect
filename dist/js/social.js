function supnotif(){"use strict";var s=$("#idsession").val();$.ajax({url:"modeles/ajax/social/reset_notifs.php",type:"POST",dataType:"json",data:{idm:s},success:function(s){"Oui"==s.statut?$("#nouvnotif").hide():alert("Erreur !")}})}$(document).ready(function(){"use strict";var s=$("#postmes").val();"oui"==s&&CKEDITOR.inline("message")}),$("#suivre").click(function(){"use strict";var s=parseInt($("#abo").val()),a=$("#idcompare").val(),e=$("#idsession").val();$.ajax({url:"modeles/ajax/social/abonnement.php",type:"POST",dataType:"json",data:{idcompare:a,idsession:e},success:function(a){"Oui"==a.statut?($("#abo").val(s+1),$("#suivre").hide()):alert("Erreur !")}})}),$("#btpost").click(function(){"use strict";var s=$("#idsession").val(),a=$("#message").val();""!=a&&""!=s?$.ajax({url:"modeles/ajax/social/post_message.php",type:"POST",dataType:"json",data:{idm:s,mess:a},success:function(s){"Oui"==s.statut?$("#listepost").prepend('<li><i class="pe-7s-date"></i> <span class="small"> '+s.date+"</span> - "+a+"</li>"):alert("Erreur !")}}):alert("Message vide !")}),$('a[data-toggle="tab"]').on("shown.bs.tab",function(s){"use strict";var a=$(s.target).attr("data-id");"nouvabo"==a&&supnotif()}),$(".sup").click(function(){var s=this.id;alert("A faire suppresion de ligne "+s)});