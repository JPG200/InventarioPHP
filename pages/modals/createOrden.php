<div class="modal fade text-xs-left" id="createOrden" tabindex="-1" role="dialog" aria-labelledby="modalOrden" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" onClick="cerrarModal();" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalOrden"><i class="icon-file-text"></i> Creación de Orden</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_Orden">
                <div class="form-group">                    
                    <div class="form-group">
                        <label for="txtOrdenCompra">Orden de Compra</label>
                        <div class="position-relative has-icon-left">
                            <input type="text" id="txtOrdenCompra" class="form-control" placeholder="Número de Orden de Compra" autofocus required></textarea>
                            <div class="form-control-position"><i class="icon-file2"></i></div>	
                        </div>
                    </div>
                    <label for="txtNumeroRegistro">Numero de Registro</label>
                        <div class="row"> 
                            <div class="col-md-8 position-relative has-icon-left">
                                <input type="number" id="txtNumeroRegistro" class="form-control" placeholder="Número de Registro" autofocus pattern="[0-9]*" inputmode="numeric"></input>
                                <div class="form-control-position"><i class="icon-bag"></i></div>
                            </div>
                            <div class="col-md-4">
                                <button id="btnBuscarNumero" type="button" onClick="" class="btn btn-outline-primary">
                                <i class="bi bi-search me-2"> Buscar</i>
                                </button>
                            </div>
                        </div>
                </div>
                <label for="txtOrdenServicio">Orden de Servicio</label>
                <div class="position-relative has-icon-left">
                    <div class="position-relative has-icon-left">
                        <input type="text" id="txtOrdenServicio" class="form-control" placeholder="Número de Orden de Servicio" autofocus required>
                        <div class="form-control-position"><i class="icon-clipboard"></i></div>
                    </div>
                </div>
                <label for="txtFechaEntrega">Fecha de Entrega</label>
                <div class="position-relative has-icon-left">
                    <div class="position-relative has-icon-left">
                        <input type="date" id="txtFechaEntrega" class="form-control" autofocus required>
                        <div class="form-control-position"><i class="icon-calendar"></i></div>
                    </div>
                </div>
                <div class="form-group">                                           
                <label for="sltTipoOrden">Tipo de Orden</label>
                <div class="position-relative has-icon-left">
                    <div class="position-relative has-icon-left">
                        <select id="sltTipoOrden" class="form-control" autofocus required>
                            <option value="">Seleccione un Tipo de Orden</option>
                            </select>
                        <div class="form-control-position"><i class="icon-list"></i></div>
                    </div>
                </div>
                    <div class="position-relative has-icon-left">
                        <label for="txtNumeroContrato">Número de Contrato</label>
                            <div class="row"> 
                                <div class="col-md-8 position-relative has-icon-left">
                                    <input type="text" id="txtNumeroContrato" class="form-control" placeholder="Número de Contrato Asociado" autofocus required>
                                    <div class="form-control-position"><i class="icon-book-open"></i></div>
                                </div>
                                <div class="col-md-4">
                                    <button id="btnBuscarContrato" type="button" onClick="" class="btn btn-outline-primary">
                                    <i class="bi bi-search me-2"> Buscar</i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <hr> <h5 id="tituloEquiposOrden">Equipos Nuevos</h5>
                <div class="position-relative has-icon-left">
                    <button type="button" class="btn btn-sm btn-success" id="btnAddEquipo">
                        <i class="icon-plus"></i> Agregar Equipo
                    </button>
                    <div class="table-responsive mt-2">
                        <table class="table table-bordered table-striped" id="tablaEquiposModal">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Placa</th>
                                    <th>Serial</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                </tbody>
                        </table>
                    </div>
                </div>
                <hr> <h5 id="tituloEquiposNuevos">Equipos para Cambiar</h5>
                <div class="position-relative has-icon-left">
                    <button type="button" class="btn btn-sm btn-success" id="btnAddEquipoCambio">
                        <i class="icon-plus"></i> Agregar Equipo
                    </button>
                    <div class="table-responsive mt-2">
                        <table class="table table-bordered table-striped" id="tablaEquiposModalCambio">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Placa</th>
                                    <th>Serial</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal" onClick="cerrarModal();">Cerrar</button>
                <button id="btnGuardarOrden" type="button" class="btn btn-outline-primary" onclick="">Guardar Orden</button>
            </div>
        </div>
    </div>
</div>
