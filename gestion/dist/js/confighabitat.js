function modlocale(t,e,a){"use strict";$("html").css("cursor","Wait"),$.ajax({url:"modeles/ajax/habitat/modhabitat.php",type:"POST",dataType:"json",data:{id:e,niv:a,coche:t},success:function(t){"Oui"==t.statut?($("html").css("cursor","default"),$("#mes").html("")):($("#mes").html(t.mes),$("html").css("cursor","default"),$("html, body").animate({scrollTop:0},"slow"))}})}function sup(t,e){var a=$("#souvenir1").val();"non"==a?($("#rang").val(e),$("#idsup").val(t),$("#dia1").modal("show")):suppression(t,e)}function suppression(t,e){"use strict";$("html").css("cursor","Wait"),$.ajax({url:"modeles/ajax/habitat/suphabitat.php",type:"POST",dataType:"json",data:{id:t,niv:e},success:function(a){"Oui"==a.statut?($("html").css("cursor","default"),$("#mes").html(""),"n"==e&&($("#h"+t+" > li").remove(),$("#n-"+t).remove()),"n2"==e&&($("#"+t+" > ul").remove(),$("#"+t).remove()),"n2"==e&&"n"==e||$("#"+t+" > li").remove()):($("#mes").html(a.mes),$("html").css("cursor","default"),$("html, body").animate({scrollTop:0},"slow"))}})}$(".idniv").click(function(){"use strict";var t=$(this).attr("id");$(this).children().hasClass("fa-plus")?$(this).children().removeClass("fa-plus").addClass("fa-minus"):$(this).children().removeClass("fa-minus").addClass("fa-plus"),$("#h"+t).toggle()}),$(".niv").change(function(){"use strict";var t=$(this).is(":checked")?"oui":"non",e=this.id,a=e.split("-"),r=a[0],n=a[1];if("oui"==t){if("n6"==r){var p=$("#"+n).parent().parent().attr("id"),s=$("#"+n).parent().parent().parent().parent().attr("id"),c=$("#"+n).parent().parent().parent().parent().parent().parent().attr("id"),i=$("#"+n).parent().parent().parent().parent().parent().parent().parent().attr("id"),o=$("#"+n).parent().parent().parent().parent().parent().parent().parent().parent().attr("id");$("#n5-"+p).prop("checked",!0),$("#n4-"+s).prop("checked",!0),$("#n3-"+c).prop("checked",!0),$("#n2-"+i).prop("checked",!0),$("#n1-"+o).prop("checked",!0)}if("n5"==r){var s=$("#"+n).parent().parent().attr("id"),c=$("#"+n).parent().parent().parent().parent().attr("id"),i=$("#"+n).parent().parent().parent().parent().parent().attr("id"),o=$("#"+n).parent().parent().parent().parent().parent().parent().attr("id");$("#n4-"+s).prop("checked",!0),$("#n3-"+c).prop("checked",!0),$("#n2-"+i).prop("checked",!0),$("#n1-"+o).prop("checked",!0)}if("n4"==r){var c=$("#"+n).parent().parent().attr("id"),i=$("#"+n).parent().parent().parent().attr("id"),o=$("#"+n).parent().parent().parent().parent().attr("id");$("#n3-"+c).prop("checked",!0),$("#n2-"+i).prop("checked",!0),$("#n1-"+o).prop("checked",!0)}if("n3"==r){var i=$("#"+n).parent().attr("id"),o=$("#"+n).parent().parent().attr("id");$("#n2-"+i).prop("checked",!0),$("#n1-"+o).prop("checked",!0)}if("n2"==r){var o=$("#"+n).parent().attr("id");$("#n1-"+o).prop("checked",!0)}}else $("#"+n).find(":checkbox").prop("checked",!1);modlocale(t,n,r)}),$(".supn").click(function(){"use strict";var t=$(this).parent().attr("id"),e=t.split("-"),a=e[1],r=e[0];$("#"+t).css("color","red"),$("#h"+a+" > li").css("color","red"),$("#"+a).children().hasClass("fa-plus")&&($("#"+a).children().removeClass("fa-plus").addClass("fa-minus"),$("#h"+a).toggle()),sup(a,r)}),$(".sup").click(function(){"use strict";var t=$(this).prev().attr("id"),e=t.split("-"),a=e[0],r=e[1];"n2"==a?($("#"+r+" > ul").css("color","red"),$("#"+r).css("color","red")):$("#"+r+" > li").css("color","red"),sup(r,a)}),$("#bttdia1").click(function(){"use strict";var t=$("#rang").val(),e=$("#idsup").val();suppression(e,t)});