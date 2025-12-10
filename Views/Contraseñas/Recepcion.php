<?php headerAdmin($data); ?>

<div class="main p-3">

    <div class="page-header">
        <h3 class="fw-bold mb-3">Recepcion</h3>
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
                <a href="#">Recepcion</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Contraseñas</h4>
                        <?php if (!empty($_SESSION['permisos'][RECEPCION]['crear'])) { ?>
                            <button class="btn btn-primary btn-round ms-auto btn-password" data-bs-toggle="modal"
                                data-bs-target="#setContraseñaModal">
                                <i class="fa fa-plus"></i>
                                Añadir Contraseña
                            </button>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableContraseña" class="display table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Contraseña</th>
                                    <th>Area</th>
                                    <th>Fecha </th>
                                    <th>Proveedor</th>
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
                <input type="hidden" id="realizador" name="realizador"
                    value="<?= $_SESSION['PersonalData']['nombre_completo'] ?>">
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
                            <label for="fecha_registro" class="form-label"><strong>Fecha Registro</strong></label>
                            <input type="date" class="form-control" id="fecha_registro" name="fecha_registro" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="fecha_pago" class="form-label"><strong>Fecha Pago Programado</strong> <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" required>
                        </div>
                        <div class="col-md-3">
                            <label for="role_id" class="form-label"><strong>Area:</strong> <span class="text-danger">*</span></label>
                            <select class="form-control selectpicker" id="area" name="area" required>

                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="role_id" class="form-label"><strong>Recibimos de: </strong><span
                                    class="text-danger">*</span></label>
                            <select class="form-control selectpicker" id="proveedor_recibimiento"
                                name="proveedor_recibimiento" required>

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

<div class="modal fade" id="setContraseñaEditModal" tabindex="-1" aria-labelledby="setUserModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="updateContraseña">
                <input type="hidden" id="edit_realizador" name="realizador"
                    value="<?= $_SESSION['PersonalData']['nombre_completo'] ?>">

                <div class="modal-header ">
                    <h5 class="modal-title" id="setUserModalLabel">Crear Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <label class="form-label fw-bold fs-4 mb-0">Contraseña:</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control text-center fw-bold" id="edit_contraseña"
                                        name="contraseña" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <label for="fecha_registro" class="form-label"><strong>Fecha Registro</strong></label>
                            <input type="date" class="form-control" id="edit_fecha_registro" name="fecha_registro"
                                readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="fecha_pago" class="form-label"><strong>Fecha Pago</strong> <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_fecha_pago" name="fecha_pago" required>
                        </div>
                        <div class="col-md-3">
                            <label for="role_id" class="form-label"><strong>Area: </strong><span class="text-danger">*</span></label>
                            <select class="form-control selectpicker" id="edit_area" name="area" required>

                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="role_id" class="form-label"><strong>Recibimos de:</strong> <span
                                    class="text-danger">*</span></label>
                            <select class="form-control selectpicker" id="edit_id_proveedor"
                                name="proveedor_recibimiento" required>

                            </select>
                        </div>
                    </div>

                    <!-- Sección de detalles -->
                    <div class="row align-items-center mb-2">
                        <div class="col-md-9">
                            <h2 class="fw-bold">Detalles</h2>
                        </div>
                        <div class="col-md-3 text-end">
                            <button type="button" class="btn btn-success w-580" id="agregarFacturaEdit">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <hr>
                    <!-- Tabla de facturas -->
                    <div class="mb-3">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle text-center"
                                id="tablaFacturasEdit">
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

<div class="modal fade" id="correccionContraseñaModal" tabindex="-1" aria-labelledby="corregirModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="correccionContraseña">
                <!-- Encabezado -->
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="corregirModalLabel">
                        Corrección Contraseña de Pago
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar">
                    </button>
                </div>
                <input type="hidden" class="form-control" id="c_contraseña_hidden" name="contraseña">
                <!-- Cuerpo -->
                <div class="modal-body">
                    <!-- Número de contraseña -->
                    <div class="mb-4 border-bottom pb-2">
                        <h5 class="fw-bold mb-0">
                            DATOS GENERALES
                        </h5>
                    </div>
                    <!-- Datos generales -->
                    <div class="mb-4">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Contraseña</label>
                                <input type="text" class="form-control" id="c_contraseña" disabled>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Fecha Registro</label>
                                <input type="date" class="form-control" id="c_fecha_registro" disabled>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Area</label>
                                <select class="form-select" id="c_area" disabled>
                                </select>
                                <input type="hidden" class="form-control" id="c_area_dos" name="area">

                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label">Proveedor</label>
                                <select class="form-select" id="c_id_proveedor" name="proveedor" required>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="fecha_pago" class="form-label">Fecha Pago</label>
                                <input type="date" class="form-control btn-input" id="c_fecha_pago" name="fecha_pago">
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 border-bottom pb-2">

                        <div class="row align-items-center mb-2">
                            <div class="col-md-9">
                                <h3 class="fw-bold mb-0">
                                    FACTURAS
                                </h3>
                            </div>
                            <div class="col-md-3 text-end">
                                <button type="button" class="btn btn-success w-580" id="agregarFacturaCorreccion">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle text-center"
                                id="tablaFacturasCorreccion">
                                <thead class="table-light">
                                    <tr>
                                        <th>No. Factura</th>
                                        <th>Bien o Servicio</th>
                                        <th>Valor</th>
                                        <th>Observ.</th>
                                        <th>Estado</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Contenido dinámico -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Detalles de corrección -->
                    <div>
                        <h6 class="fw-bold text-dark mb-3">Correccion</h6>
                        <textarea class="form-control" name="c_correcciones" id="c_correcciones" disabled>
                        </textarea>
                    </div>
                </div>
                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let role_id = <?= json_encode($_SESSION['rol_usuario'] ?? 0); ?>;
    let permisos = <?= json_encode($_SESSION['permisos'] ?? []); ?>;
</script>