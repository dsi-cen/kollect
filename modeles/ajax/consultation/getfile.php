<?php

if (isset($_GET['f'])){
    header("Content-type: text/csv");
    header("Content-disposition: attachment; filename = export.csv");
    readfile('../../../exports/' . $_GET['f'] . '.csv');
}