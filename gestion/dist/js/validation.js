function recupliste(a, t) {
    "use strict";
    $.ajax({
        url: "modeles/ajax/validation/valiliste.php",
        type: "POST",
        dataType: "json",
        data: {sel: a, nouv: t},
        success: function (a) {
            "Oui" == a.statut ? ($("#liste").html(a.liste), a.data ? (remplirtable(a.data), "non" == t ? $("#btchoix").show() : $("#btchoix").hide()) : $("#btchoix").hide()) : $("#liste").html("")
        }
    })
}

function format(a) {
    return '<table cellpadding="5" cellspacing="0" border="0" class="pl-2"><tr><td>Résultat filtre automatique :</td><td>' + a.dec + "</td></tr></table>"
}

function remplirtable(a) {
    "use strict";
    // Cellules de recherche
    $('#tblliste thead tr').clone(true).appendTo( '#tblliste thead' );
    $('#tblliste thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Rechercher" />' );
        $( 'input', this ).on( 'keyup change', function () {
            if ( t.column(i).search() !== this.value ) {
                t
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        });
    });
    var t = $("#tblliste").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        language: {url: "../dist/js/datatables/france.json"},
        data: a,
        deferRender: !0,
        scrollY: 600,
        scrollCollapse: !0,
        scroller: !0,
        columns: [{data: 0}, {
            data: {
                _: "1.date",
                sort: "1.tri"
            }
        }, {data: 2}, {data: 3}, {data: 4}, {data: 5}, {data: 6}, {data: 7}, {data: 8}, {data: 9}, {data: 10}],
        columnDefs: [{orderable: !1, targets: 0}]
    });

    $("#tblliste").on("click", ".detail", function () {
        var a = $(this).closest("tr"), e = t.row(a), i = a.attr("id");
        e.child.isShown() ? (e.child.hide(), $(this).removeClass("fa-minus").addClass("fa-plus")) : (e.child(format(e.data())).show(), $(this).removeClass("fa-plus").addClass("fa-minus"), e.child().attr("id", "e" + i))
    })
}

function validation() {
    var a = $("#idobs").val(), t = $("#vali").val(), e = $("#rq").val(), i = $("#new").val(), s = $("#observa").val();
    $.ajax({
        url: "modeles/ajax/validation/validation.php",
        type: "POST",
        dataType: "json",
        data: {idobs: a, choix: t, rq: e, nouv: i, observa: s},
        success: function (t) {
            "Oui" == t.statut ? ($("#e" + a).remove(), $("#" + a).remove(), t.mes && ($("#mes").html(t.mes), $("#dia2").modal("show"))) : ($("#mes").html(t.mes), $("#dia2").modal("show"))
        }
    })
}

$(document).ready(function () {
    "use strict";
    $("#infoaide").hide(), $("#btchoix").hide(), $("#valajax").hide();
    var a = $("#observa").val(), t = $("#new").val();
    "NR" != a && ($('#choix option[value="' + a + '"]').prop("selected", !0), recupliste(a, t))
}), $("#aide").click(function () {
    "use strict";
    $(this).hasClass("btn-info") ? ($(this).removeClass("btn-info").addClass("btn-success"), $("#btn-aide-txt", this).text("Cacher"), $("#infoaide").show()) : ($(this).removeClass("btn-success").addClass("btn-info"), $("#btn-aide-txt", this).text("Aide"), $("#infoaide").hide())
}), $("#choix").change(function () {
    "use strict";
    var a = $("#choix option:selected").val(), t = $("#new").val();
    "NR" != a ? ($("#observa").val(a), recupliste(a, t)) : ($("#liste").html(""), $("#observa").val(""))
}), $("#liste").on("click", ".fa-pencil", function () {
    "use strict";
    var a = $(this).parent().parent().attr("id");
    $("#idobs").val(a), $("#tdia1").html(a), $("#vali").val("NR"), $("#rq").val(""), $("#dia1").modal("show")
}), $("#vali").change(function () {
    "use strict";
    var a = $("#vali").val();
    1 != a && 2 != a || ($("#dia1").modal("hide"), validation())
}), $("#BttV").click(function () {
    "use strict";
    $("#dia1").modal("hide"), validation()
}), $("#Btvalits").click(function () {
    "use strict";
    $("#valajax").show();
    var a = $("#observa").val();
    $.ajax({
        url: "modeles/ajax/validation/validationts.php",
        type: "POST",
        dataType: "json",
        data: {observa: a},
        success: function (a) {
            "Oui" == a.statut ? ($("#liste").html("Aucune observation à valider"), $("#btchoix").hide(), $("#mes").html('<div class="alert alert-success" role="alert">' + a.nb + " observations ont été validées</div>"), $("#dia2").modal("show")) : ($("#mes").html(a.mes), $("#dia2").modal("show")), $("#valajax").hide()
        }
    })
});