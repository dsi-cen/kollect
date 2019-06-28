<?php
if (isset($_GET['f'])){
    switch ($_GET['t']) {
        case "tsv":
            header("Content-type: text/csv");
            break;
        case "xls":
            header("Content-type: application/vnd.ms-excel");
            break;
        case "txt":
            header("Content-type: text/plain");
            break;
    }
    $_GET['n'] !== "" ? header("Content-disposition: attachment; filename = " . str_replace(' ', '_', $_GET['n']) . "-" . date("d/m/Y") . "." . $_GET['t']) : header("Content-disposition: attachment; filename = export-du-" . date("d/m/Y") . "." . $_GET['t']);
    switch ($_GET['t']) {
        case "tsv":
            readfile('../../../exports/' . $_GET['f'] . ".tsv" );
            break;
        case "xls":
            readfile('../../../exports/' . $_GET['f'] . ".tsv" );
            break;
        case "txt":
            readfile('../../../exports/' . $_GET['f'] . ".txt" );
            break;
    }
}