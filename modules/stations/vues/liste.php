<section class="container-fluid">
    <header class="row color4_bg barreheader blanc">
        <div class="col-md-12 col-lg-12">
            <div class="header-titre"><h1 class="h6 font-bold centreligne text-uppercase"><?php echo $titrep; ?></h1>
            </div>
        </div>
    </header>
</section>
<section class="container-fluid mt-3">
    <div class="row">
        <div class="col-sm-6">
            <div class="card card-body mb-0">
                <p>Liste des stations</p>

            </div>
        </div>
        <div class="col-sm-6">
            <div class="card card-body mb-0">
                <div id="map" style="width: 100%; height: 600px;"></div>
            </div>
        </div>
    </div>
</section>

<!-- Récupération de la couche préférentielle -->
<input type="hidden" id="couchem" value="<?php echo $_SESSION['couche'];?>">

