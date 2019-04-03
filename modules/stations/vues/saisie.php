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
                    </div>
                </form>
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
                                                                 placeholder="Chercher un département"></div>
                                    <?php
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
                            <legend class="legendesaisie">
                                Informations sur la station
                                <span class="" id="nom_station"></span>
                            </legend>


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
                    </div>
                    <div class="min p-2 mt-3">
                        <input id="btn_create_station" data-onstyle="info" data-offstyle="success" type="checkbox"
                               data-toggle="toggle"
                               data-on="Cliquer pour rentrer en mode : éditer une station existante"
                               data-off="Cliquer pour rentrer en mode : créer une nouvelle station">
                        <fieldset id="create_station" class="mt-2">
                            <div class="form-group row">
                                <div class="col-4">
                                    <label for="lieub" class="">Nom de la station</label>
                                    <input type="text" class="form-control" id="lieub" name="lieub"
                                           placeholder="Nom de la nouvelle station"></div>
                                <div class="col-sm-6">
                                    <label for="typestation" class="">Type de station</label>
                                    <select id="typestation" name="typestation" class="form-control">
                                        <option value="0" selected>Sélectionner un type</option>
                                        <?php
                                        foreach ($typestation as $n) {
                                            if ($n['idtypestation'] == $idstation) {
                                                ?>
                                                <option value="<?php echo $n['idtypestation']; ?>"
                                                        selected><?php echo $n['idtypestation']; ?></option><?php
                                            } else {
                                                ?>
                                                <option
                                                value="<?php echo $n['idtypestation']; ?>"><?php echo $n['libtypestation']; ?></option><?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                <label for="libstatus" class="">Status de la station</label>
                                <select id="libstatus" name="libstatus" class="form-control">
                                    <?php
                                    foreach ($libstatus as $n) {
                                        if ($n['idstatusstation'] == $idstatusstation) {
                                            ?>
                                            <option value="<?php echo $n['idstatusstation']; ?>"
                                                    selected><?php echo $n['idstatusstation']; ?></option><?php
                                        } else {
                                            ?>
                                            <option
                                            value="<?php echo $n['idstatusstation']; ?>"><?php echo $n['libidstatusstation']; ?></option><?php
                                        }
                                    }
                                    ?>
                                </select>
                                </div>
                            </div>
                            <div class="form-group row">

                                <legend class="mt-3 mb-3 legendesaisie">Ajouter des observateurs supplémentaires si besoin
                                </legend>
                                <div class="col-sm-8">
                                    <input type="text" id="observateur2" name="observateur2" class="form-control"
                                           placeholder="Ajouter d'autres observateurs si besoin"
                                           data-toggle="tooltip" data-placement="bottom"
                                           title="Vous pouvez ajouter des observateurs (laissez la virgule entre chaque observateur) déjà enregistrés dans la base ou en ajouter en cliquant sur la croix à droite">
                                </div>
                                <div class="col-sm-4">
                            <span id="plusobs" class="ml-auto curseurlien" title="Créer un observateur"><i
                                        class="centreligne fa fa-user-plus fa-lg"></i></span>
                                </div>

                            </div>
                        </fieldset>
                    </div>
                    <div id="mare" class="min p-2 mt-3">
                        <legend>Description de la mare</legend>
                        <fieldset>
                            <hr>
                            <div class="row col-sm-12 mb-3">
                                <label for="date" class="">Date</label>
                                <input type="text" class="form-control" id="date" name="date"
                                       pattern="\d{1,2}/\d{1,2}/\d{4}">
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="typemare" class="">Type de mare</label>
                                    <select id="typemare" name="typemare" class="form-control">
                                        <?php
                                        foreach ($typemare as $n) {
                                            if ($n['idtypemare'] == $idtypemare) {
                                                ?>
                                                <option value="<?php echo $n['idtypemare']; ?>"
                                                        selected><?php echo $n['libtypemare']; ?></option><?php
                                            } else {
                                                ?>
                                                <option
                                                value="<?php echo $n['idtypemare']; ?>"><?php echo $n['libtypemare']; ?></option><?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="environnement" class="">Environnement</label>
                                    <select id="environnement" name="environnement" class="form-control">
                                        <?php
                                        foreach ($environnement as $n) {
                                            if ($n['idenvironnement'] == $idenvironnement) {
                                                ?>
                                                <option value="<?php echo $n['idenvironnement']; ?>"
                                                        selected><?php echo $n['libenvironnement']; ?></option><?php
                                            } else {
                                                ?>
                                                <option
                                                value="<?php echo $n['idenvironnement']; ?>"><?php echo $n['libenvironnement']; ?></option><?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="menaces" class="">Menaces</label>
                                    <select id="menaces" name="menaces[]" class="form-control" multiple>
                                        <?php
                                        foreach ($menaces as $n) {
                                            if ($n['idmenaces'] == $idmenaces) {
                                                ?>
                                                <option value="<?php echo $n['idmenaces']; ?>"
                                                        selected><?php echo $n['libmenaces']; ?></option><?php
                                            } else {
                                                ?>
                                                <option
                                                value="<?php echo $n['idmenaces']; ?>"><?php echo $n['libmenaces']; ?></option><?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-6">

                                    <label for="eaulibre" class="">Recouvrement eau libre (%)</label>
                                    <div class="col-sm-6"><input type="number" min="0" max="100"
                                                                 class="form-control"
                                                                 id="eaulibre" name="eaulibre" placeholder="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="vegaquatique" class="">Végétation aquatique</label>
                                    <select id="vegaquatique" name="vegaquatique" class="form-control">
                                        <?php
                                        foreach ($vegaquatique as $n) {
                                            if ($n['idvegaquatique'] == $idvegaquatique) {
                                                ?>
                                                <option value="<?php echo $n['idvegaquatique']; ?>"
                                                        selected><?php echo $n['libvegaquatique']; ?></option><?php
                                            } else {
                                                ?>
                                                <option
                                                value="<?php echo $n['idvegaquatique']; ?>"><?php echo $n['libvegaquatique']; ?></option><?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="vegsemiaquatique" class="">Végétation semi-aquatique</label>
                                    <select id="vegsemiaquatique" name="vegsemiaquatique" class="form-control">
                                        <?php
                                        foreach ($vegsemiaquatique as $n) {
                                            if ($n['idvegsemiaquatique'] == $idvegsemiaquatique) {
                                                ?>
                                                <option value="<?php echo $n['idvegsemiaquatique']; ?>"
                                                        selected><?php echo $n['libvegsemiaquatique']; ?></option><?php
                                            } else {
                                                ?>
                                                <option
                                                value="<?php echo $n['idvegsemiaquatique']; ?>"><?php echo $n['libvegsemiaquatique']; ?></option><?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="vegrivulaire" class="">Végétation rivulaire</label>
                                    <select id="vegrivulaire" name="vegrivulaire" class="form-control">
                                        <?php
                                        foreach ($vegrivulaire as $n) {
                                            if ($n['idvegrivulaire'] == $idvegrivulaire) {
                                                ?>
                                                <option value="<?php echo $n['idvegrivulaire']; ?>"
                                                        selected><?php echo $n['libvegrivulaire']; ?></option><?php
                                            } else {
                                                ?>
                                                <option
                                                value="<?php echo $n['idvegrivulaire']; ?>"><?php echo $n['libvegrivulaire']; ?></option><?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="typeexutoire" class="">Type d'exutoire</label>
                                    <select id="typeexutoire" name="typeexutoire" class="form-control">
                                        <?php
                                        foreach ($typeexutoire as $n) {
                                            if ($n['idtypeexutoire'] == $idtypeexutoire) {
                                                ?>
                                                <option value="<?php echo $n['idtypeexutoire']; ?>"
                                                        selected><?php echo $n['libtypeexutoire']; ?></option><?php
                                            } else {
                                                ?>
                                                <option
                                                value="<?php echo $n['idtypeexutoire']; ?>"><?php echo $n['libtypeexutoire']; ?></option><?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="taillemare" class="l">Taille de la mare</label>
                                    <select id="taillemare" name="taillemare" class="form-control">
                                        <?php
                                        foreach ($taillemare as $n) {
                                            if ($n['idtaillemare'] == $idtaillemare) {
                                                ?>
                                                <option value="<?php echo $n['idtaillemare']; ?>"
                                                        selected><?php echo $n['libtaillemare']; ?></option><?php
                                            } else {
                                                ?>
                                                <option
                                                value="<?php echo $n['idtaillemare']; ?>"><?php echo $n['libtaillemare']; ?></option><?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="couleureau" class="">Couleur de l'eau</label>
                                    <select id="couleureau" name="couleureau" class="form-control">
                                        <?php
                                        foreach ($couleureau as $n) {
                                            if ($n['idcouleureau'] == $idcouleureau) {
                                                ?>
                                                <option value="<?php echo $n['idcouleureau']; ?>"
                                                        selected><?php echo $n['libcouleureau']; ?></option><?php
                                            } else {
                                                ?>
                                                <option
                                                value="<?php echo $n['idcouleureau']; ?>"><?php echo $n['libcouleureau']; ?></option><?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-sm-6">
                                    <label for="naturefond" class="">Nature du fond</label>
                                    <select id="naturefond" name="naturefond" class="form-control">
                                        <?php
                                        foreach ($naturefond as $n) {
                                            if ($n['idnaturefond'] == $idnaturefond) {
                                                ?>
                                                <option value="<?php echo $n['idnaturefond']; ?>"
                                                        selected><?php echo $n['libnaturefond']; ?></option><?php
                                            } else {
                                                ?>
                                                <option
                                                value="<?php echo $n['idnaturefond']; ?>"><?php echo $n['libnaturefond']; ?></option><?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="recouvrberge" class="">Recouvrement des berges en pente
                                        douce</label>
                                    <select id="recouvrberge" name="recouvrberge" class="form-control">
                                        <?php
                                        foreach ($recouvrberge as $n) {
                                            if ($n['idrecberge'] == $idrecouvrberge) {
                                                ?>
                                                <option value="<?php echo $n['idrecberge']; ?>"
                                                        selected><?php echo $n['librecberge']; ?></option><?php
                                            } else {
                                                ?>
                                                <option
                                                value="<?php echo $n['idrecberge']; ?>"><?php echo $n['librecberge']; ?></option><?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="profondeureau" class="">Profondeur d'eau maximale observée</label>
                                    <select id="profondeureau" name="profondeureau" class="form-control">
                                        <?php
                                        foreach ($profondeureau as $n) {
                                            if ($n['idprofondeureau'] == $idprofondeureau) {
                                                ?>
                                                <option value="<?php echo $n['idprofondeureau']; ?>"
                                                        selected><?php echo $n['libprofondeureau']; ?></option><?php
                                            } else {
                                                ?>
                                                <option
                                                value="<?php echo $n['idprofondeureau']; ?>"><?php echo $n['libprofondeureau']; ?></option><?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="alimeau" class="">Alimentation en eau</label>
                                    <select id="alimeau" name="alimeau[]" class="form-control" multiple>
                                        <?php
                                        foreach ($alimeau as $n) {
                                            if ($n['idalimeau'] == $idalimeau) {
                                                ?>
                                                <option value="<?php echo $n['idalimeau']; ?>"
                                                        selected><?php echo $n['libalimeau']; ?></option><?php
                                            } else {
                                                ?>
                                                <option
                                                value="<?php echo $n['idalimeau']; ?>"><?php echo $n['libalimeau']; ?></option><?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-3 mb-3">

                                <legend class="legendesaisie">Commentaire sur la mare</legend>
                                <textarea class="form-control" rows="5" id="commentairemare" name="commentairemare"
                                          placeholder="Commentaire sur la mare"></textarea>

                            </div>

                    </div>

                    <div id="showphoto" class="min p-2 mt-3 mb-3">
                        <fieldset>
                            <legend class="legendesaisie">Ajouter une photo de la station<i
                                        class="fa fa-camera text-success curseurlien ml-3" id="adphoto"
                                        data-toggle="tooltip" data-placement="bottom"
                                        data-title="Chercher dans les espèces non inclusent (espèces nouvelles)"></i>
                            </legend>
                            <div class="row mb-3" id="photo">
                                <div class="row col-sm-12 mb-3 dateprisedevue">
                                    <label for="dateprisedevue" class="date">Date de la prise de vue</label>
                                    <input type="text" class="form-control" id="dateprisedevue" name="dateprisedevue"
                                           pattern="\d{1,2}/\d{1,2}/\d{4}">
                                </div>
                                <div class="col-md-5 col-lg-5">
                                    <p>
                                        <b>La photo doit représenter la station</b><br/>
                                        Fichiers autorisés : ".JPG". <br/>
                                        Paysage - Mettre au minimum des photos de 800 de largeur x 600 de
                                        hauteur.<br/>
                                        Portrait - Mettre au minimum des photos de 400 de largeur x 600 de
                                        hauteur.<br/>
                                    </p>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="paysage"
                                               name="orien"
                                               value="paysage" checked>
                                        <label class="custom-control-label" for="paysage">Paysage</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="portrait"
                                               name="orien"
                                               value="portrait">
                                        <label class="custom-control-label" for="portrait">Portrait</label>
                                    </div>
                                    <div id="obserphoto">
                                        <p><b>Si la photo n'est pas de vous mais d'un co-observateur, cocher son
                                                nom</b>
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
                            <div id="liste_photo" class="popup-gallery">
                            </div>
                        </fieldset>
                    </div>

                    <div id="showcom" class="min p-2 mt-3 mb-3">
                        <fieldset>
                            <legend class="legendesaisie">Commentaire sur la station</legend>
                            <textarea class="form-control" rows="5" id="commentaire" name="commentaire"
                                      placeholder="Commentaire"></textarea>
                        </fieldset>
                    </div>
                    <div class="row ml-1">

                        <button id="save_station" type="button" class="btn btn-danger" data-placement="bottom"
                                data-title="">Enregistrer la station
                        </button>
                        <button id="update_station" type="button" class="btn btn-success" data-placement="bottom"
                                data-title="">Enregistrer la modification
                        </button>
                        <button id="cancel_update" type="button" class="btn btn-warning ml-3" data-placement="bottom"
                                data-title="">Retour
                        </button>

                    </div>
                    <div id="alert1" class="mt-2"></div>
                </div>
                <!--input hidden -->
                <!--localisation, fiche, obs -->
                <input id="codecom" name="codecom" type="hidden"/>
                <input id="codedep" type="hidden" name="codedep"/>
                <input id="codesite" name="codesite" type="hidden" <?php if (isset($_GET['addto'])) { echo 'value="' . $_GET['addto'] . '"'; } else { } ?> />
                <input id="idcoord" type="hidden" name="idcoord"/>
                <input id="idobser" name="idobser" type="hidden" value="<?php echo $idobser; ?>"/>
                <input id="iddet" name="iddet" type="hidden" value="<?php echo $idobser; ?>"/>
                <!-- <input id="cdnom" name="cdnom" type="hidden"/> -->
                <!-- <input id="cdref" name="cdref" type="hidden"/> -->
                <input id="idfiche" name="idfiche" type="hidden" value="Nouv"/>
                <input id="idobs" name="idobs" type="hidden" value="Nouv"/>
                <!-- <input id="cdhab" name="cdhab" type="hidden"/> -->
                <!-- <input id="pr" name="pr" type="hidden"/> -->
                <!--  <input id="nb" name="nb" type="hidden"/> -->
                <!-- <input id="newsp" name="newsp" type="hidden"/> -->
                <input id="biogeo" name="biogeo" type="hidden" value="<?php echo $biogeo; ?>"/>
                <input id="typepoly" name="typepoly" type="hidden" size="200"/>
                <input id="idm" type="hidden" value="<?php echo $_SESSION['idmembre']; ?>"/>
                <input id="parent" type="hidden" value="0"/>
                <input id="adddescription" type="hidden" <?php if (isset($_GET['addto'])) { echo 'value="oui"'; } else { } ?> />
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
                    Il existe une ou plusieurs stations à moins de <?php echo $dist; ?> km<br>
                    Cliquez sur un des <img class="" src="dist/css/images/marker-vert.png" alt="" height="20"
                                            width="12"/> pour modifier si besoin l'emprise d'une station déjà présente.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="bttdia8">Fermer</button>
            </div>
        </div>
    </div>
</div>
<!--<div id="dia9" class="modal" tabindex="-1" role="dialog">
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
</div>-->
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
                <h4 class="modal-title">Vous avez modifié une géométrie d'un station existante</h4>
            </div>
            <div class="modal-body">
                <p>Si vous réalisez une modification pour le site : <b><span id="spandia13"></span></b>, cliquer sur Oui. Cela aura pour incidence de déplacer l'ensemble des observations réalisées
                    sur l'ancienne géométrie vers la nouvelle.</p>
                <p>Si vous souhaitez concerver les anciennes observations sur l'ancienne géométrie, cliquez sur Nouveau pour créer une station 'fille'. </p>
                <p>Si votre nouvelle station n'est pas liée à la station modifier, merci de recharger la page pour créer une station sans passer par le champ de recherche d'une station.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal" id="bttdiaN13">Nouvelle station 'fille'</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Oui, modifier la station et TOUTES les obs</button>
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