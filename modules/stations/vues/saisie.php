<section class="container-fluid">
    <header class="row color4_bg barreheader blanc">
        <div class="col-md-12 col-lg-12">
            <div class="d-flex">
                <h1 class="h6 centreligne">OUTIL DE SAISIE DE DONNÉES NATURALISTES</h1>
                <span class="curseurlien ml-4">
					<i id="btnaide" class="text-primary centreligne fa fa-info fa-lg" title="Activer l'aide"></i>
					<i id="btfiche10" class="text-primary centreligne fa fa-pencil-square-o fa-lg ml-2"
                       title="Vos dix dernières fiches"></i>
				</span>
                <form class="form-custom-saisie ml-auto w-50">
                    <div class="row">
                        <div class="col-sm-4">
                            <input type="text" id="observateur" name="observateur" class="form-control"
                                   value="<?php echo $_SESSION['nom'] . ' ' . $_SESSION['prenom'] . ''; ?>">
                        </div>
                        <div class="col-sm-8">
                            <input type="text" id="observateur2" name="observateur2" class="form-control"
                                   placeholder="Ajouter observateur(s)" data-toggle="tooltip" data-placement="bottom"
                                   title="Vous pouvez ajouter des observateurs (laissez la virgule entre chaque observateur) déjà enregistrés dans la base ou en ajouter en cliquant sur la croix à droite">
                        </div>
                    </div>
                </form>
                <span id="plusobs" class="ml-auto curseurlien" title="Créer un observateur"><i
                            class="centreligne fa fa-user-plus fa-lg"></i></span>
            </div>
        </div>
    </header>
</section>
<section class="container-fluid p-0 font13">
    <div class="row no-gutters">
        <div class="w-50" id="blocmap">
            <div id="map"></div>
        </div>
        <div class="w-50" id="change">
            <form id="formulaire">
                <div class="card card-body" id="blocfiche">
                    <div id="R"></div>
                    <div class="min p-2">
                        <fieldset>
                            <legend class="legendesaisie">Centrer la carte sur une station <i id="infolieu"
                                                                                              class="fa fa-info-circle curseurlien text-info info"
                                                                                              title="Aide à la saisie"></i>
                            </legend>
                            <div class="form-group row pt-2">
                                <?php
                                if ($dep == 'oui') {
                                    ?>
                                    <div class="col-sm-5"><input type="text" class="form-control" id="choixdep"
                                                                 placeholder="Chercher un département"></div><?php
                                }
                                ?>
                                <div class="col-sm-6"><input type="text" class="form-control" id="choixcom"
                                                             placeholder="Centrer sur une commune"></div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-5"><input type="text" class="form-control" id="choixsite"
                                                             placeholder="Chercher une de vos stations (2 lettres)"
                                                             data-toggle="tooltip" data-placement="bottom"
                                                             data-title="Si vous connaissez le nom du site, vous pouvez le rechercher ici. Autrement cliquer sur la carte">
                                </div>
                                <div class="col-sm-5"><input type="text" class="form-control" id="choixsite1"
                                                             placeholder="Chercher une station existante (toutes)"
                                                             data-toggle="tooltip" data-placement="bottom"
                                                             data-title="Si vous connaissez le nom du site, vous pouvez le rechercher ici. Autrement cliquer sur la carte">
                                </div>
                                <div class="col-sm-1"><i class="fa fa-plus text-success curseurlien" id="imgpluscoord"
                                                         data-toggle="tooltip" data-placement="top"
                                                         data-title="A partir de coordonnées (GPS,..)"></i></div>
                            </div>
                            <div id="pluscoord" class="form-group row">
                                <div class="col-sm-2"><input type="text" class="form-control" id="xcoord"
                                                             placeholder="x / lng"></div>
                                <div class="col-sm-2"><input type="text" class="form-control" id="ycoord"
                                                             placeholder="y / lat"></div>
                                <label for="proj" class="col-sm-2 col-form-label">Projection</label>
                                <div class="col-sm-2">
                                    <select id="proj" class="form-control">
                                        <option value="nr">Choisir</option>
                                        <option value="w84">WGS84</option>
                                        <option value="l93">Lambert 93</option>
                                        <option value="l2">Lambert 2</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="min p-2 mt-3">
                        <fieldset class="mt-2">
                            <legend class="legendesaisie">Informations sur la station</legend>
                            <div class="form-group row">
                                <?php
                                if ($dep == 'oui') {
                                    ?>
                                    <div class="col-sm-6"><input type="text" class="form-control" id="dep"
                                                                 placeholder="Département"></div><?php
                                }
                                ?>
                                <div class="col-sm-6"><input type="text" class="form-control" id="communeb"
                                                             placeholder="Commune"></div>
                            </div>
                            <div class="form-group row">
                                <div class="col-4"><input type="text" class="form-control" id="lieub" name="lieub"
                                                             placeholder="Créer une nouvelle station"></div>

                                <fieldset>
                                    <div class="form-group row">
                                        <label for="typestation" class="col-sm-5 col-form-label">Type de station</label>
                                        <div class="col-sm-6">
                                            <select id="typestation" name="typestation" class="form-control">
                                                <option value="0" selected>Sélectionner un type</option>
                                                <?php
                                                foreach ($typestation as $n) {
                                                    if ($n['idstation'] == $idstation) {
                                                        ?>
                                                        <option value="<?php echo $n['idstation']; ?>"
                                                                selected><?php echo $n['libelle']; ?></option><?php
                                                    } else {
                                                        ?>
                                                        <option value="<?php echo $n['idstation']; ?>"><?php echo $n['libelle']; ?></option><?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>

                            </div>
                            <div class="form-group row">
                                <div class="col-sm-2"><input type="text" class="form-control" id="xlambert"
                                                             name="xlambert" placeholder="X"></div>
                                <div class="col-sm-3"><input type="text" class="form-control" id="ylambert"
                                                             name="ylamber" placeholder="Y"></div>
                                <div class="col-sm-3"><input type="text" class="form-control" id="lat" name="lat"
                                                             placeholder="lat"></div>
                                <div class="col-sm-3"><input type="text" class="form-control" id="lng" name="lng"
                                                             placeholder="lng"></div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4"><input type="text" class="form-control" id="l93" name="l93"
                                                             placeholder="Maille 10 km"></div>
                                <div class="col-sm-4"><input type="text" class="form-control" id="l935" name="l935"
                                                             placeholder="Maille 5 km"></div>
                                <div class="col-sm-2"><input type="text" class="form-control" id="altitude"
                                                             name="altitude" placeholder="Alt."></div>
                            </div>
                            <?php
                            if ($utm == 'oui') {
                                ?>
                                <div class="form-group row">
                                    <div class="col-sm-3"><input type="text" class="form-control" id="utm" name="utm"
                                                                 placeholder="UTM"></div>
                                    <div class="col-sm-3"><input type="text" class="form-control" id="utm1" name="utm1"
                                                                 placeholder="UTM 1"></div>
                                </div>
                                <?php
                            }
                            ?>
                        </fieldset>
                        <fieldset>
                            <legend class="legendesaisie">Précision de la géométrie (à titre d'information, non-utilisée
                                dans les traitements)
                            </legend>
                            <div class="form-group row">
                                <label for="prec" class="col-sm-3 col-form-label">Echelle de précision</label>
                                <div class="col-sm-4">
                                    <select id="precision" name="precision" class="form-control">
                                        <?php
                                        foreach ($precision as $n) {
                                            if ($n['idpreci'] == $idpreci) {
                                                ?>
                                                <option value="<?php echo $n['idpreci']; ?>"
                                                        selected><?php echo $n['lbpreci']; ?></option><?php
                                            } else {
                                                ?>
                                                <option
                                                value="<?php echo $n['idpreci']; ?>"><?php echo $n['lbpreci']; ?></option><?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="min p-2 mt-3">
                        <fieldset>
                            <legend class="legendesaisie">Dates</legend>
                            <div class="form-group row">
                                <label for="date" class="col-sm-1 col-form-label">Du</label>
                                <div class="col-sm-3"><input type="text" class="form-control" id="date" name="date"
                                                             pattern="\d{1,2}/\d{1,2}/\d{4}"></div>
                                <label for="date2" class="col-sm-1 col-form-label">au</label>
                                <div class="col-sm-3"><input type="text" class="form-control" id="date2" name="date2"
                                                             pattern="\d{1,2}/\d{1,2}/\d{4}"></div>
                                <div class="col-sm-1"><i class="fa fa-plus text-success curseurlien"
                                                         id="imgplusfiche"></i></div>
                            </div>
                            <div id="plusfiche">
                                <div class="form-group row">
                                    <label for="heure" class="col-sm-1 col-form-label">De</label>
                                    <div class="col-sm-2"><input type="text" class="form-control" id="heure"
                                                                 name="heure"></div>
                                    <label for="heure2" class="col-form-label">a</label>
                                    <div class="col-sm-2"><input type="text" class="form-control" id="heure2"
                                                                 name="heure2"></div>
                                    <label for="tempdeb" class="ml-2 col-form-label">°C debut</label>
                                    <div class="col-sm-2"><input type="number" min="-50" max="50" class="form-control"
                                                                 id="tempdeb" name="tempdeb"></div>
                                    <label for="tempfin" class="col-form-label">°C fin</label>
                                    <div class="col-sm-2"><input type="number" min="-50" max="50" class="form-control"
                                                                 id="tempfin" name="tempfin"></div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div id="mare" class="min p-2 mt-3">
                        <fieldset>
                            <legend class="legendesaisie">Mares</legend>
                            <div class="form-group row">
                                <label for="typemare" class="col-sm-3 col-form-label">Type de mare</label>
                                <fieldset>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <select id="typemare" name="typemare" class="form-control">
                                                <?php
                                                foreach ($typemare as $n) {
                                                    if ($n['idtypemare'] == $idtypemare) {
                                                        ?>
                                                        <option value="<?php echo $n['idtypemare']; ?>"
                                                                selected><?php echo $n['libelle']; ?></option><?php
                                                    } else {
                                                        ?>
                                                        <option
                                                        value="<?php echo $n['idtypemare']; ?>"><?php echo $n['libelle']; ?></option><?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>

                                <label for="environnement" class="col-sm-3 col-form-label">Environnement</label>
                                <fieldset>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <select id="environnement" name="environnement" class="form-control">
                                                <?php
                                                foreach ($environnement as $n) {
                                                    if ($n['idenvironnement'] == $idenvironnement) {
                                                        ?>
                                                        <option value="<?php echo $n['idenvironnement']; ?>"
                                                                selected><?php echo $n['libelle']; ?></option><?php
                                                    } else {
                                                        ?>
                                                        <option
                                                        value="<?php echo $n['idenvironnement']; ?>"><?php echo $n['libelle']; ?></option><?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>

                                <label for="menaces" class="col-sm-3 col-form-label">Menaces</label>
                                <fieldset>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <select id="menaces" name="menaces" class="form-control">
                                                <?php
                                                foreach ($menaces as $n) {
                                                    if ($n['idmenaces'] == $idmenaces) {
                                                        ?>
                                                        <option value="<?php echo $n['idmenaces']; ?>"
                                                                selected><?php echo $n['libelle']; ?></option><?php
                                                    } else {
                                                        ?>
                                                        <option
                                                        value="<?php echo $n['idmenaces']; ?>"><?php echo $n['libelle']; ?></option><?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>

                                <label for="atterissement" class="col-sm-3 col-form-label">Atterissement</label>
                                <fieldset>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <select id="atterissement" name="atterissement" class="form-control">
                                                <?php
                                                foreach ($atterissement as $n) {
                                                    if ($n['idatterissement'] == $idatterissement) {
                                                        ?>
                                                        <option value="<?php echo $n['idatterissement']; ?>"
                                                                selected><?php echo $n['libelle']; ?></option><?php
                                                    } else {
                                                        ?>
                                                        <option
                                                        value="<?php echo $n['idatterissement']; ?>"><?php echo $n['libelle']; ?></option><?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>


                                <label for="eaulibre" class="col-sm-3 col-form-label">Recouvrement eau libre (%)</label>
                                <fieldset>
                                    <div class="form-group row">
                                        <div class="col-sm-6"><input type="number" min="0" max="100" class="form-control" id="eaulibre" name="eaulibre" placeholder=""></div>
                                    </div>
                                </fieldset>


                                <label for="vegaquatique" class="col-sm-3 col-form-label">Végétation aquatique</label>
                                <fieldset>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <select id="vegaquatique" name="vegaquatique" class="form-control">
                                                <?php
                                                foreach ($vegaquatique as $n) {
                                                    if ($n['idvegaquatique'] == $idvegaquatique) {
                                                        ?>
                                                        <option value="<?php echo $n['idvegaquatique']; ?>"
                                                                selected><?php echo $n['libelle']; ?></option><?php
                                                    } else {
                                                        ?>
                                                        <option
                                                        value="<?php echo $n['idvegaquatique']; ?>"><?php echo $n['libelle']; ?></option><?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>

                                <label for="vegsemiaquatique" class="col-sm-3 col-form-label">Végétation
                                    semi-aquatique</label>
                                <fieldset>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <select id="vegsemiaquatique" name="vegsemiaquatique" class="form-control">
                                                <?php
                                                foreach ($vegsemiaquatique as $n) {
                                                    if ($n['idvegsemiaquatique'] == $idvegsemiaquatique) {
                                                        ?>
                                                        <option value="<?php echo $n['idvegsemiaquatique']; ?>"
                                                                selected><?php echo $n['libelle']; ?></option><?php
                                                    } else {
                                                        ?>
                                                        <option
                                                        value="<?php echo $n['idvegsemiaquatique']; ?>"><?php echo $n['libelle']; ?></option><?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>


                                <label for="vegrivulaire" class="col-sm-3 col-form-label">Végétation rivulaire</label>
                                <fieldset>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <select id="vegrivulaire" name="vegrivulaire" class="form-control">
                                                <?php
                                                foreach ($vegrivulaire as $n) {
                                                    if ($n['idvegrivulaire'] == $idvegrivulaire) {
                                                        ?>
                                                        <option value="<?php echo $n['idvegrivulaire']; ?>"
                                                                selected><?php echo $n['libelle']; ?></option><?php
                                                    } else {
                                                        ?>
                                                        <option
                                                        value="<?php echo $n['idvegrivulaire']; ?>"><?php echo $n['libelle']; ?></option><?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>

                                <label for="typeexutoire" class="col-sm-3 col-form-label">Type d'exutoire</label>
                                <fieldset>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <select id="typeexutoire" name="typeexutoire" class="form-control">
                                                <?php
                                                foreach ($typeexutoire as $n) {
                                                    if ($n['idtypeexutoire'] == $idtypeexutoire) {
                                                        ?>
                                                        <option value="<?php echo $n['idtypeexutoire']; ?>"
                                                                selected><?php echo $n['libelle']; ?></option><?php
                                                    } else {
                                                        ?>
                                                        <option
                                                        value="<?php echo $n['idtypeexutoire']; ?>"><?php echo $n['libelle']; ?></option><?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>

                                <label for="taillemare" class="col-sm-3 col-form-label">Taille de la mare</label>
                                <fieldset>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <select id="taillemare" name="taillemare" class="form-control">
                                                <?php
                                                foreach ($taillemare as $n) {
                                                    if ($n['idtaillemare'] == $idtaillemare) {
                                                        ?>
                                                        <option value="<?php echo $n['idtaillemare']; ?>"
                                                                selected><?php echo $n['libelle']; ?></option><?php
                                                    } else {
                                                        ?>
                                                        <option
                                                        value="<?php echo $n['idtaillemare']; ?>"><?php echo $n['libelle']; ?></option><?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>

                                <label for="couleureau" class="col-sm-3 col-form-label">Couleur de l'eau</label>
                                <fieldset>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <select id="couleureau" name="couleureau" class="form-control">
                                                <?php
                                                foreach ($couleureau as $n) {
                                                    if ($n['idcouleureau'] == $idcouleureau) {
                                                        ?>
                                                        <option value="<?php echo $n['idcouleureau']; ?>"
                                                                selected><?php echo $n['libelle']; ?></option><?php
                                                    } else {
                                                        ?>
                                                        <option
                                                        value="<?php echo $n['idcouleureau']; ?>"><?php echo $n['libelle']; ?></option><?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>

                                <label for="naturefond" class="col-sm-3 col-form-label">Nature du fond</label>
                                <fieldset>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <select id="naturefond" name="naturefond" class="form-control">
                                                <?php
                                                foreach ($naturefond as $n) {
                                                    if ($n['idnaturefond'] == $idnaturefond) {
                                                        ?>
                                                        <option value="<?php echo $n['idnaturefond']; ?>"
                                                                selected><?php echo $n['libelle']; ?></option><?php
                                                    } else {
                                                        ?>
                                                        <option
                                                        value="<?php echo $n['idnaturefond']; ?>"><?php echo $n['libelle']; ?></option><?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>

                                <label for="recouvrberge" class="col-sm-3 col-form-label">Recouvrement des berges en
                                    pente douce</label>
                                <fieldset>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <select id="recouvrberge" name="recouvrberge" class="form-control">
                                                <?php
                                                foreach ($recouvrberge as $n) {
                                                    if ($n['idrecouvrberge'] == $idrecouvrberge) {
                                                        ?>
                                                        <option value="<?php echo $n['idrecouvrberge']; ?>"
                                                                selected><?php echo $n['libelle']; ?></option><?php
                                                    } else {
                                                        ?>
                                                        <option
                                                        value="<?php echo $n['idrecouvrberge']; ?>"><?php echo $n['libelle']; ?></option><?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>

                                <label for="profondeureau" class="col-sm-3 col-form-label">Profondeur d'eau maximale
                                    observée</label>
                                <fieldset>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <select id="profondeureau" name="profondeureau" class="form-control">
                                                <?php
                                                foreach ($profondeureau as $n) {
                                                    if ($n['idprofondeureau'] == $idprofondeureau) {
                                                        ?>
                                                        <option value="<?php echo $n['idprofondeureau']; ?>"
                                                                selected><?php echo $n['libelle']; ?></option><?php
                                                    } else {
                                                        ?>
                                                        <option
                                                        value="<?php echo $n['idprofondeureau']; ?>"><?php echo $n['libelle']; ?></option><?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>

                                <label for="alimeau" class="col-sm-3 col-form-label">Alimentation en eau</label>
                                <fieldset>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <select id="alimeau" name="alimeau" class="form-control">
                                                <?php
                                                foreach ($alimeau as $n) {
                                                    if ($n['idalimeau'] == $idalimeau) {
                                                        ?>
                                                        <option value="<?php echo $n['idalimeau']; ?>"
                                                                selected><?php echo $n['libelle']; ?></option><?php
                                                    } else {
                                                        ?>
                                                        <option
                                                        value="<?php echo $n['idalimeau']; ?>"><?php echo $n['libelle']; ?></option><?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>


                        </fieldset>
                    </div>
                    <div class="min p-2 mt-3 mb-3">
                        <fieldset>
                            <legend class="legendesaisie">Ajouter une photo de la station<i
                                        class="fa fa-camera text-success curseurlien ml-3" id="adphoto"
                                        data-toggle="tooltip" data-placement="bottom"
                                        data-title="Chercher dans les espèces non inclusent (espèces nouvelles)"></i>
                            </legend>
                            <div class="row mb-3" id="photo">
                                <div class="col-md-5 col-lg-5">
                                    <p>
                                        <b>La photo doit représenter la station</b><br/>
                                        Fichiers autorisés : ".JPG". <br/>
                                        Paysage - Mettre au minimum des photos de 800 de largeur x 600 de hauteur.<br/>
                                        Portrait - Mettre au minimum des photos de 400 de largeur x 600 de hauteur.<br/>
                                    </p>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="paysage" name="orien"
                                               value="paysage" checked>
                                        <label class="custom-control-label" for="paysage">Paysage</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="portrait" name="orien"
                                               value="portrait">
                                        <label class="custom-control-label" for="portrait">Portrait</label>
                                    </div>
                                    <div id="obserphoto">
                                        <p><b>Si la photo n'est pas de vous mais d'un co-observateur, cocher son nom</b>
                                        </p>
                                        <div id="opph"></div>
                                    </div>
                                </div>
                                <div class="col-md-7 col-lg-7">
                                    <div id="crop">
                                        <div class="error-msg"></div>
                                        <input type="file" class="cropit-image-input mb-3" id="file">
                                        <div class="cropit-preview ml-3"></div>
                                        <div class="ml-3 mt-3">
                                            <i class="fa fa-picture-o fa-lg"></i>
                                            <input type="range" class="cropit-image-zoom-input">
                                            <i class="fa fa-picture-o fa-2x"></i>
                                        </div>
                                        <input type="hidden" name="image-data" class="hidden-image-data"/>
                                    </div>
                                    <p class="ml-3 mt-3">
                                        <span class="rotate-ccw curseurlien" title="rotation gauche"><i
                                                    class="fa fa-undo fa-lg"></i></span>
                                        <span class="rotate-cw curseurlien ml-3" title="rotation droite"><i
                                                    class="fa fa-repeat fa-lg"></i></span>
                                    </p>
                                    <div class="mt-3 mb-2">
                                        <button type="button" class="export btn btn-warning" id="BttP">
                                            Prévisualisation
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                        <div class="min p-2 mt-3 mb-3">
                            <fieldset>
                                <legend class="legendesaisie">Commentaire sur la station</legend>
                                <input type="text" class="form-control" id="commentaire" name="commentaire" placeholder="Commentaire">
                            </fieldset>
                        </div>
                    <div class="row ml-1">

                    <button type="button" class="btn btn-danger" data-placement="bottom" data-title="">Enregistrer la station</button>

                    </div>
                    <div id="alert1" class="mt-2"></div>
                </div>


            </form>
        </div>
    </div>
    <div class="row no-gutters" id="liste10">
        <div class="col-md-9 col-lg-9">
            <div class="card card-body min">
                <p id="titrer"><b>Vos dix dernières fiches enregistrées</b></p>
                <div id="listefiche"></div>
            </div>
        </div>
    </div>
    <!--input hidden pour traitement js -->
    <input id="idobseror" type="hidden" value="<?php echo $idobser; ?>"/><input id="iddetor" type="hidden"
                                                                                value="<?php echo $idobser; ?>"/><input
            id="getidfiche" type="hidden" value="<?php echo $getidfiche; ?>"/><input id="couche" type="hidden"
                                                                                     value="<?php echo $couche; ?>"/><input
            id="proche" type="hidden" value="<?php echo $dist; ?>"/>
    <input id="flou" type="hidden" value="<?php echo $flou; ?>"/><input id="tdon" type="hidden"
                                                                        value="<?php echo $typedon; ?>"/><input
            id="idligneobs" type="hidden"/><input id="valsel" type="hidden"/><input id="Bt" type="hidden"
                                                                                    name="Bt"><input id="aphoto"
                                                                                                     type="hidden"><input
            id="afiche" type="hidden"><input id="choixauto" type="hidden">
    <input id="coordpr" type="hidden"/>
</section>
<!-- Boite dialogue -->
<div id="dia1" class="modal" tabindex="-1" role="dialog" aria-labelledby="Modalajoutobs" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ajouter un observateur</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form">
                            <div class="form-group row">
                                <label for="nomobs" class="col-sm-2 col-form-label">Nom</label>
                                <div class="col-sm-8"><input type="text" class="form-control" id="nomobs"></div>
                            </div>
                            <div class="form-group row">
                                <label for="prenomobs" class="col-sm-2 col-form-label">Prénom</label>
                                <div class="col-sm-8"><input type="text" class="form-control" id="prenomobs"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia1">Valider</button>
            </div>
        </div>
    </div>
</div>
<div id="dia2" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p>Le nom doit-être présent dans la liste proposée (La recherche se fait sur le nom de famille).<br/>Vous
                    pouvez créer un observateur en cliquant sur le <i class="fa fa-plus text-success"></i> à droite du
                    champ.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<div id="dia3" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p>Le point cliqué ne fait pas parti de l'emprise du site.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<div id="dia4" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p>L'espèce doit-être présente dans la liste proposée.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<div id="dia6" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Choisir le stade</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>
                            <b><span id="stademod"></span></b> <span id="libmod"></span>
                        </p>
                        <ul id="modligne"></ul>
                        <p id="suptous"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="dia7" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Suppression</h4>
            </div>
            <div class="modal-body">
                <p>Voulez vraiment supprimer cette observation ?</p>
            </div>
            <input id="encours" type="hidden"/>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia7">Oui</button>
            </div>
        </div>
    </div>
</div>
<div id="dia8" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Site proche</h4>
            </div>
            <div class="modal-body">
                <p>
                    Il existe un ou plusieurs sites d'observations à moins de <?php echo $dist; ?> km<br>
                    Cliquez sur un des <img class="" src="dist/css/images/marker-vert.png" alt="" height="20"
                                            width="12"/> si vous voulez enregistrer votre observation dessus.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia8">Fermer</button>
            </div>
        </div>
    </div>
</div>
<div id="dia9" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="dia9titre"></span></h4>
            </div>
            <div class="modal-body">
                <p>Enregistrer sur ce point d'observation ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia9">Oui</button>
            </div>
        </div>
    </div>
</div>
<div id="dia10" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p>Vous devez indiquer un nom de site.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<div id="dia11" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p>Il faut d'abord selectionner un site pour créer une station..</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<div id="dia12" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Prévisualisation</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="imgdia12"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<div id="dia13" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Dessin</h4>
            </div>
            <div class="modal-body">
                <p>Si vous réalisez un contour pour le site : <b><span id="spandia13"></span></b>, cliquer sur Oui,
                    autrement cliquer sur Nouveau</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal" id="bttdiaN13">Nouveau</button>
                <button type="button" class="btn btn-success" data-dismiss="modal">Oui</button>
            </div>
        </div>
    </div>
</div>
<div id="dia14" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Information sur les attributs</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="rinfo"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<div id="dia15" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Aide à la saisie</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="rinfoaide"></div>
                        <p>
                            Une aide vous est également proposée pour certains champs lorsque vous passez la souris
                            dessus. Pour activer l'aide, cliquez sur le <i class="text-primary fa fa-info"></i> en haut
                            au dessus de la carte.
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<div id="dia16" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Précision</h4>
            </div>
            <div class="modal-body">
                <p><b>Attention !</b> Votre observation est saisie à l'échelle communale.<br/>
                    Cliquez sur modifier si besoin, puis cliquez sur la carte pour désigner votre point exact
                    d'observation</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Modifier</button>
                <button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia16">Oui</button>
            </div>
        </div>
    </div>
</div>