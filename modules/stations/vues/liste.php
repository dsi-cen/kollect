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
            <div class="card card-body mb-3">
                <h3>Critères de recherche</h3>
                <div class="form-group row">
                    <label for="departement" class="col-sm-5 col-form-label">Département</label>
                    <div class="col-sm-6">
                        <select id="departement" name="departement" class="form-control">
                            <option value="0" selected>Sélectionner un département</option>
                            <?php foreach ($departements as $n) { ?>
                                <option value="<?php echo $n['iddep']; ?>"><?php echo $n['departement']; ?></option><?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="typestation" class="col-sm-5 col-form-label">Type de station</label>
                    <div class="col-sm-6">
                        <select id="typestation" name="typestation" class="form-control">
                            <option value="0" selected>Sélectionner un type de station</option>
                            <?php foreach ($types as $n) { ?>
                                <option value="<?php echo $n['idtypestation']; ?>"><?php echo $n['libtypestation']; ?></option><?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card card-body mb-0">
                <h3>Liste des stations</h3>
                <div id="liste" class="mt-2"></div>
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
<input type="hidden" id="couchem" value="<?php echo $_SESSION['couche']; ?>">
