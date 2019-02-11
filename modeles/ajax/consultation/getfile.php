<?php

if (isset($_GET['f'])){
    $_GET['t'] === "tsv" ? header("Content-type: text/csv") : header("Content-type: application/vnd.ms-excel");
    $_GET['n'] !== "" ? header("Content-disposition: attachment; filename = " . str_replace(' ', '_', $_GET['n']) . "-" . date("d/m/Y") . "." . $_GET['t']) : header("Content-disposition: attachment; filename = export-du-" . date("d/m/Y") . "." . $_GET['t']);
    readfile('../../../exports/' . $_GET['f'] . ".tsv" );
}