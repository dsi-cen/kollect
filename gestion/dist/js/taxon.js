$(document).ready(function(){"use strict";var e=$("#glo").val();"oui"==e&&$("#blocinfo").glossarizer({sourceURL:"src/js/glossaire.json",callback:function(){$(".glossarizer_replaced").tooltip({html:!0})}}),$("#locale").prop("disabled",!0).css("cursor","Not-Allowed"),$("#taxref").prop("disabled",!0).css("cursor","Not-Allowed")}),$("#rtax").autocomplete({source:function(e,o){$.getJSON("modeles/ajax/liste.php",{term:e.term},function(e){o($.map(e,function(e){return""==e.nomvern?{label:e.nom,value:e}:{label:e.nom+" ("+e.nomvern+")",value:e}}))})},select:function(e,o){var l=o.item.value;return document.location.href="index.php?module=taxon&action=fiche&d="+l.observatoire+"&id="+l.cdnom,!1}}),$("#localec").change(function(){"use strict";var e=($("#locale").val(),$("#localec").val());alert("A faire -> ajax pour changer en "+e)});