function insertag(t){"use strict";$.ajax({url:"modeles/ajax/actu/insertag.php",type:"POST",dataType:"json",data:{tag:t},success:function(e){var i=e.statut.Ok;if("Ok"===i){var a=$("#tag").val();$("#tag").val(a+t+", ")}else alert(i)},error:function(t){alert("Une erreure est survenue")}})}function CKupdate(){for(instance in CKEDITOR.instances)CKEDITOR.instances[instance].updateElement()}$(document).ready(function(){"use strict";$("#infophoto").hide(),$("#infopdf").hide(),$("#valajax").hide(),$("#mes").hide(),$("#changeph").hide(),$("#titre").focus();var t=$("#themeor").val(),e=$("#visibleor").val();""!==t&&$("#theme").val(t),1==e?$("input[name=visible]").val(["1"]):$("input[name=visible]").val(["0"]),CKEDITOR.replace("actu",{uiColor:"#FFCC99"})}),$("#bttplus").click(function(){"use strict";$("#taga").val(""),$("#dia3").modal("show")}),$("#bttplusV").click(function(){"use strict";var t=$("#taga").val();""!==t&&insertag(t)}),$(function(){"use strict";function t(t){return e(t).pop()}function e(t){return t.split(/,\s*/)}$("#tag").autocomplete({source:function(e,i){$.getJSON("modeles/ajax/actu/listetag.php",{term:t(e.term)},i)},position:{my:"bottom",at:"top"},search:function(){var e=t(this.value);if(e.length<1)return!1},focus:function(){return!1},select:function(t,i){var a=e(this.value);return a.pop(),a.push(i.item.value),a.push(""),this.value=a.join(", "),!1}})}),$("#nouv_actu").find('input[name="image"]').on("change",function(t){"use strict";var e=$(this)[0].files;if(e.length>0){$("#infophoto").show();var i=e[0];$(".nomimg").html(i.name),$(".sizeimg").html(i.size+" bytes");var a=document.createElement("img");a.src=window.URL.createObjectURL(i),a.onload=function(){var t,e,i=a.width,n=a.height,o=i/200,u=n/200,c=Math.max(o,u);t=i/c,e=n/c,a.height=e,a.width=t,$(a).attr({width:t,height:e}),$(".imgPreview").html(a),window.URL.revokeObjectURL(this.src)}}}),$("#BttAph").click(function(){"use strict";$("#nouv_actu").find('input[name="image"]').val(""),$(".nomimg").html(""),$(".sizeimg").html(""),$(".imgPreview").html(""),$("#auteurph").val(""),$("#infoph").val(""),$("#infophoto").hide()}),$("#nouv_actu").find('input[name="pdf"]').on("change",function(t){"use strict";$("#infopdf").show()}),$("#BttApdf").click(function(){$("#nouv_actu").find('input[name="pdf"]').val(""),$("#infopdf").hide()}),$("#nouv_actu").on("submit",function(t){"use strict";$("#valajax").show(),t.preventDefault(),CKupdate();var e=$(this),i=window.FormData?new FormData(e[0]):null,a=null!==i?i:e.serialize();$.ajax({url:"modeles/ajax/actu/actuinser.php",type:"POST",dataType:"json",data:a,contentType:!1,processData:!1,success:function(t){"Oui"==t.statut?document.location.href="index.php?module=actu&action=liste":($("#mes").show(),$("#mes").html(t.mes),$("#valajax").hide())}})}),$("#BttSph").click(function(){$(".imgPreview").html(""),$("#auteurph").val(""),$("#infoph").val(""),$("#infophotoor").hide(),$("#supphoto").val("oui"),$("#mod_actu").find('input[name="image"]').val(""),$("#changeph").hide()}),$("#BttMph").click(function(){$("#auteurph").val(""),$("#infoph").val(""),$("#changeph").show(),$("#supphoto").val("nouv")}),$("#mod_actu").find('input[name="image"]').on("change",function(t){var e=$(this)[0].files,i=$("#supphoto").val();if(e.length>0){"nouv"!=i&&$("#infophoto").show(),$(".imgPreview").html("");var a=e[0];$(".sizeimg").html(a.size+" bytes");var n=document.createElement("img");n.src=window.URL.createObjectURL(a),n.onload=function(){var t,e,i=n.width,a=n.height,o=i/200,u=a/200,c=Math.max(o,u);t=i/c,e=a/c,n.height=e,n.width=t,$(n).attr({width:t,height:e}),$(".imgPreview").html(n),window.URL.revokeObjectURL(this.src)}}}),$("#BttAphmod").click(function(){"use strict";$("#mod_actu").find('input[name="image"]').val(""),$(".nomimg").html(""),$(".sizeimg").html(""),$(".imgPreview").html(""),$("#auteurph").val(""),$("#infoph").val(""),$("#infophoto").hide()}),$("#mod_actu").on("submit",function(t){"use strict";$("#valajax").show(),t.preventDefault(),CKupdate();var e=$(this),i=window.FormData?new FormData(e[0]):null,a=null!==i?i:e.serialize();$.ajax({url:"modeles/ajax/actu/actumod.php",type:"POST",dataType:"json",data:a,contentType:!1,processData:!1,success:function(t){"Oui"==t.statut?document.location.href="index.php?module=actu&action=liste":($("#mes").show(),$("#mes").html(t.mes),$("#valajax").hide())}})}),$("#BttSpdf").click(function(){"use strict";$("#mod_actu").find('input[name="pdf"]').val(""),$("#infopdfor").hide()});