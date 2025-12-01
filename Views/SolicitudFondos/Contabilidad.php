<?php headerAdmin($data); ?>

<div class="main p-3">

    <!-- Encabezado de página -->
    <div class="page-header mb-4">
        <h3 class="fw-bold mb-2">Solicitud de fondos</h3>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="<?= base_url(); ?>/Dashboard">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Solicitud de Fondos</a>
            </li>
        </ul>
        <button class="btn btn-primary btn-round ms-auto btn-password" data-bs-toggle="modal"
            data-bs-target="#setContraseñaModal">
            <i class="fa fa-plus"></i>
            Añadir Solicitud sin Contra
        </button>
    </div>

    <!-- Tabla -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableSolicitudes"
                            class="table table-striped table-hover table-bordered align-middle w-100">
                            <thead class="">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Contraseña</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Fecha Pago Estimado</th>
                                    <th scope="col">Area</th>
                                    <th scope="col">No. Transaccion</th>
                                    <th scope="col">Fecha Transaccion</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Se llena dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php footerAdmin($data); ?>

<div class="modal fade" id="setContraseñaModal" tabindex="-1" aria-labelledby="setUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="setSolicitud">
                <input type="hidden" id="realizador" name="realizador"
                    value="<?php echo $_SESSION['PersonalData']['id_usuario']; ?>">
                <div class="modal-header ">
                    <h5 class="modal-title" id="setUserModalLabel">Crear Solicitud de Fondos sin Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <label for="fecha_registro" class="form-label">Fecha Registro</label>
                            <input type="date" class="form-control" id="fecha_registro" name="fecha_registro" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="fecha_pago" class="form-label">Fecha Pago <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" required>
                        </div>
                        <div class="col-md-3">
                            <label for="area" class="form-label">Área</label>
                            <input type="hidden" name="area" value="<?php echo $_SESSION['PersonalData']['area']; ?>"
                                readonly>
                            <input type="text" class="form-control"
                                value="<?php echo $_SESSION['PersonalData']['nombre_area']; ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="role_id" class="form-label">Recibimos de: <span
                                    class="text-danger">*</span></label>
                            <select class="form-control selectpicker" id="proveedor" name="proveedor" required>
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
                                        <th>Tipo</th>
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