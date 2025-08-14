<div class="modal fade text-xs-left" id="searchEquiposOrden" tabindex="-1" role="dialog" aria-labelledby="modalOrden" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" onClick="" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalOrden"><i class="icon-file-text"></i> Creación de Orden</h4>
            </div>
            <div class="modal-body">
                <hr> <h5 id="tituloEquiposOrdenmodal">Equipos Activos</h5>
                <div class="position-relative has-icon-left">
                    <div class="table-responsive mt-2">
                        <table class="table table-bordered table-striped" id="searchtablaEquiposModal">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Placa</th>
                                    <th>Serial</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                </tbody>
                        </table>
                    </div>
                </div>
                <hr> <h5 id="tituloEquiposNuevosmodal">Equipos Devueltos</h5>
                <div class="position-relative has-icon-left">
                    <div class="table-responsive mt-2">
                        <table class="table table-bordered table-striped" id="searchtablaEquiposModalCambio">
                            <thead>
                                <tr>
                                    <th>#</th>                                    
                                    <th>Placa</th>
                                    <th>Serial</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal" onClick="">Cerrar</button>
                <button id="btnGuardarOrden" type="button" class="btn btn-outline-primary" onclick="">Guardar Orden</button>
            </div>
        </div>
    </div>
</div>
