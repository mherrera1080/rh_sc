<?php headerAdmin($data); ?>
<div class="main p-3">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Vehiculos</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="<?= base_url(); ?>/Dashboard">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Contabilidad</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Vehiculos</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Vehiculos</h4>
                        <button class="btn btn-primary btn-round ms-auto btn-password" data-bs-toggle="modal"
                            data-bs-target="#setContraseñaModal">
                            <i class="fa fa-plus"></i>
                            Añadir Contraseña
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableVehiculos" class="display table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Contraseña</th>
                                    <th>Area</th>
                                    <th>Registro</th>
                                    <th>id_proveedor</th>
                                    <th>Total</th>
                                    <th>Fecha Pago</th>
                                    <th>Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php footerAdmin($data); ?>

<!-- Modal -->
<div class="modal fade" id="setContraseñaModal" tabindex="-1" aria-labelledby="setUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="setContraseña">
                <input type="hidden" id="realizador" name="realizador" value="Prueba Administrador">
                <div class="modal-header ">
                    <h5 class="modal-title" id="setUserModalLabel">Crear Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <label class="form-label fw-bold fs-4 mb-0">No. Contraseña:</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control text-center fw-bold" id="contraseña"
                                        name="contraseña" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <label for="fecha_registro" class="form-label">Fecha Registro</label>
                            <input type="date" class="form-control" id="fecha_registro" name="fecha_registro" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="role_id" class="form-label">Area: </label>
                            <input type="text" class="form-control" value="Vehiculos" disabled>
                            <input type="hidden" class="form-control" id="area" name="area" value="2">
                        </div>
                        <div class="col-md-3">
                            <label for="fecha_pago" class="form-label">Fecha Pago <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" required>
                        </div>
                        <div class="col-md-4">
                            <label for="role_id" class="form-label">Recibimos de: <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="proveedor_recibimiento" name="proveedor_recibimiento"
                                required>

                            </select>
                        </div>
                    </div>

                    <!-- Sección de detalles -->
                    <div class="row align-items-center mb-2">
                        <div class="col-md-9">
                            <h2 class="fw-bold">Detalles</h2>
                        </div>
                        <div class="col-md-3 text-end">
                            <button type="button" class="btn btn-success w-100" id="agregarFactura">Agregar
                                Factura</button>
                        </div>
                    </div>
                    <hr>
                    <!-- Tabla de facturas -->
                    <div class="mb-3">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle text-center"
                                id="tablaFacturas">
                                <thead class="table-light">
                                    <tr>
                                        <th>No. Factura</th>
                                        <th>Bien o Servicio</th>
                                        <th>Valor</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Contenido dinámico -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <!-- Footer con botones -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                        <span id="spinerSubmit" class="spinner-border spinner-border-sm d-none" role="status"
                            aria-hidden="true"></span>
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>