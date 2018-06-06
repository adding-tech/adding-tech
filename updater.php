<?php
/*
 * This file is part of FacturaScripts
 * Copyright (C) 2015-2018  Carlos Garcia Gomez  neorazorx@gmail.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (!file_exists('config.php')) {
    die('Archivo config.php no encontrado. No puedes actualizar sin instalar.');
}

require_once 'config.php';
require_once 'base/fs_updater.php';

set_time_limit(0);
$updater = new fs_updater();

if (isset($_GET['idplu'])&&isset($_GET['key'])) {
    $updater->actualizador_plugins();
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" >
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Actualizador</title>
        <meta name="description" content="Script de actualización de FacturaScripts." />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="generator" content="FacturaScripts" />
        <link rel="shortcut icon" href="view/img/favicon.png" />
        <link rel="stylesheet" href="view/css/bootstrap-yeti.min.css" />
        <link rel="stylesheet" href="view/css/font-awesome.min.css" />
        <script type="text/javascript" src="view/js/jquery.min.js"></script>
        <script type="text/javascript" src="view/js/bootstrap.min.js"></script>
    </head>
    <body>
        <br/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <a href="index.php?page=admin_home&updated=TRUE" class="btn btn-sm btn-default">
                        <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
                        <span class="hidden-xs">&nbsp;Panel de control</span>
                    </a>
                    <!--<a href="https://www.facturascripts.com/comm3/index.php?page=community_tus_plugins" target="_blank" class="btn btn-sm btn-default">
                        <i class="fa fa-key" aria-hidden="true"></i>
                        <span class="hidden-xs">&nbsp;Claves</span>
                    </a>-->
                    <div class="page-header">
                        <h1>
                            <span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Actualizador de plugins y core
                        </h1>
                    </div>
                    <?php
                    if (count($updater->core_log->get_errors()) > 0) {
                        echo '<div class="alert alert-danger"><ul>';
                        foreach ($updater->core_log->get_errors() as $error) {
                            echo '<li>' . $error . '</li>';
                        }
                        echo '</ul></div>';
                    }

                    if (count($updater->core_log->get_messages()) > 0) {
                        echo '<div class="alert alert-info"><ul>';
                        foreach ($updater->core_log->get_messages() as $msg) {
                            echo '<li>' . $msg . '</li>';
                        }
                        echo '</ul></div>';

                        if ($updater->btn_fin) {
                            echo '<a href="index.php?page=admin_home&updated=TRUE" class="btn btn-sm btn-info">'
                            . '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> &nbsp; Finalizar'
                            . '</a></br/></br/>';
                        }
                    }

                   if (isset($_GET['men'])&&$_GET['men']=='2') {
                       echo '<div class="alert alert-danger"><ul>';
                       echo '<li>Error al validar archivo clave de la actualización</li>';
                       echo '</ul></div>';

                       if (isset($_GET['men'])&&$_GET['men']=='2'&&isset($_GET['file'])&&isset($_GET['nomplugin'])) {
                            $updater->eliminar_clave($_GET['file'],$_GET['nomplugin']);
                        }
                   }elseif (isset($_GET['men'])&&$_GET['men']=='3') {
                       echo '<div class="alert alert-danger"><ul>';
                       echo '<li>Error al actualizar el plugins, ya que la clave es incorrecta o esta caducada.</li>';
                       echo '</ul></div>';
                   }

                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <p class="help-block">
                        Este actualizador permite actualizar <b>tanto el núcleo</b> de Adding.tech
                        <b>como sus plugins</b>, incluso los de pago y los privados.
                        Si hay una actualización del núcleo tendrás que actualizar antes de poder ver si
                        también hay actualizaciones de plugins.
                    </p>
                    <br/>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#actualizaciones" aria-controls="actualizaciones" role="tab" data-toggle="tab">
                                <span class="glyphicon glyphicon-upload" aria-hidden="true"></span>
                                <span class="hidden-xs">&nbsp;Actualizaciones</span>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#opciones" aria-controls="opciones" role="tab" data-toggle="tab">
                                <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>
                                <span class="hidden-xs">&nbsp;Opciones</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="actualizaciones">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-left">Nombre</th>
                                            <th class="text-left">Descripción</th>
                                            <th class="text-right">Versión</th>
                                            <th class="text-right">Nueva versión</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <?php echo $updater->tr_updates; ?>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="opciones">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-left">Opción</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <?php echo $updater->tr_options; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if (!isset($updater->updates)) {
            /// nada
        } else if ($updater->updates['plugins']) {
            foreach ($updater->check_for_plugin_updates() as $plug) {
                if ($plug['depago']) {

                    ?>
                    <form action="<?= $updater->urlserv ?>/index.php?page=editar_plugin&idpluginact=<?php echo $plug['idpluginact'] . '&name=' . $plug['name'].'"'; ?> method="post" class="form formget-<?= $plug['name']; ?>" enctype="multipart/form-data" name="form_clave" id="form_clave">
                        <input type="hidden" name='url_ser' value="<?= $updater->url()?>">
                        <input type="hidden" name='nomplugin' value="<?= $plug['name'] ?>">
                        <div class="modal" id="modal_key_<?php echo $plug['name']; ?>" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h4 class="modal-title">
                                            <i class="fa fa-key" aria-hidden="true"></i> Añadir clave de actualización
                                        </h4>
                                        <p class="help-block">Imprescindible para actualizar el plugin <b><?php echo $plug['name']; ?></b>.</p>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <!--<div class="form-group">
                                                    Clave:
                                                    <input type="text" name="key" class="form-control" autocomplete="off" autofocus=""/>
                                                    <p class="help-block">
                                                        ¿No sabes cual es tu clave? Puedes consultarla pulsando el botón
                                                        <b>ver mis claves</b>.
                                                    </p>
                                                </div>-->
                                                <label for="archivokey">Cargar archivo clave</label>
                                                <input type="file" id="archivokey-<?= $plug['name']; ?>" name="archivokey" class="form-control">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#list-key">
                                                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                                    <span class="hidden-xs">&nbsp;Ver mis claves</span>
                                                </button>
                                            </div>
                                            <div class="col-xs-6 text-right">
                                                <button type="button" class="btn btn-sm btn-primary btnanndir-<?= $plug['name']; ?>">
                                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                                    <span class="hidden-xs">&nbsp;Añadir</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="modal fade" tabindex="-1" role="dialog" id="list-key">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="fa fa-list"></i>&nbsp;Listado de clave de <?= $plug['name']?></h4>
                          </div>
                          <div class="modal-body">
                           <?php foreach ($updater->list_key($plug['name']) as $key) {
                               echo '
                                <div class="row">
                                    <div class="col-sm-10"><h4>'.$key.'</h4></div>
                                    <div class="col-sm-2">
                                        <a href="base/fs_updater.php?keysend='.$key.'&nomplugin='.$plug['name'].'" class="btn btn-info btn-sm" ><i class="fa fa-send"></i>&nbsp;Activar</a>
                                    </div>
                                </div>
                               ';
                            } ?>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                          </div>
                        </div><!-- /.modal-content -->
                      </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    </form>

                    <script>
                        $('.btnanndir-<?= $plug['name']; ?>').click(function() {
                            if ($('#archivokey-<?= $plug['name']; ?>').val() != '') 
                            {
                                let formData = new FormData($(".formget-<?= $plug['name']; ?>")[0]);
                                $.ajax({
                                    url: 'base/fs_updater.php',
                                    type: 'POST',
                                    data: formData,
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    success: function(data){
                                        if (data=='si') {
                                            $(".formget-<?= $plug['name']; ?>").submit();
                                        }else{
                                            alert('Error al cargar el archivo');
                                        }
                                    },
                                    error: function(er){
                                        console.log(er);
                                    }
                                });

                            }else{
                                alert('Debe agregar una clave.');
                            }
                        });
                    </script>
                    <?php
                }
            }
        }

        ?>
        <br/><br/>
        
        <?php
        if (!FS_DEMO) {
            $url = 'https://www.facturascripts.com/comm3/index.php?page=community_stats'
                . '&add=TRUE&version=' . $updater->version . '&plugins=' . join(',', $updater->plugins);

            ?>
            <div style="display: none;">
                <iframe src="<?php echo $url; ?>" height="0"></iframe>
            </div>
            <?php
        }

        ?>
    </body>
</html>