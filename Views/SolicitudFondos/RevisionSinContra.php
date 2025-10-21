<?php headerAdmin($data); ?>
<div class="main p-3">
    <div class="row">
        <!-- Información de la Contraseña -->
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header text-dark d-flex justify-content-between align-items-center">
                    <!-- Sección títulos -->
                    <div>
                        <h2 class="mb-1 fs-4 fw-bold">Solicitud de Fondos sin contraseña</h2>
                    </div>
                    <div class="d-flex gap-2">
                        <?php if ($data['facturas']['solicitud_estado'] === "Pendiente" && $_SESSION['PersonalData']['area'] == 4) { ?>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#finalizarModal">
                                <i class="fas fa-check"></i> Finalizar
                            </button>
                        <?php } else if ($data['facturas']['solicitud_estado'] === "Corregir" && $_SESSION['PersonalData']['area'] == 4) { ?>
                                <button type="button" class="btn btn-primary m-0 d-flex justify-content-left btnFacturaEditar"
                                    data-bs-toggle="modal" data-bs-target="#editarSolicitud"
                                    data-id="<?= $data['facturas']['id_solicitud']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Proveedor</label>
                            <input type="text" class="form-control"
                                value="<?= $data['facturas']['proveedor'] ?? 'N/A'; ?>" disabled>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Área</label>
                            <input type="text" class="form-control" value="<?= $data['facturas']['area'] ?? 'N/A'; ?>"
                                disabled>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Fecha Registro</label>
                            <input type="text" class="form-control"
                                value="<?= $data['facturas']['fecha_registro'] ?? 'N/A'; ?>" disabled>
                        </div>
                        <div class="col-md-2">
                            <label for="fecha_pago" class="form-label fw-bold">Fecha Pago</label>
                            <input type="date" class="form-control" id="fecha_pago"
                                value="<?= $data['facturas']['fecha_pago'] ?? ''; ?>" disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Monto Total</label>
                            <input type="text" class="form-control text-end"
                                value="<?= $data['facturas']['total_calc'] ?? 'N/A'; ?>" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Facturas -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex align-items-center">
                    <h4 class="card-title mb-0">Detalle de Facturas</h4>
                    <div class="ms-auto d-flex gap-2">
                        <button class="btn btn-danger btn-sm btn-password" onclick="CancelEdit()" id="btnCancelar"
                            style="display:none;">
                            <i class="fas fa-ban"></i> Cancelar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <input type="hidden" id="id_solicitud" value="<?= $data['facturas']['id_solicitud']; ?>">
                        <input type="hidden" id="solicitud_estado"
                            value="<?= $data['facturas']['solicitud_estado']; ?>">
                        <input type="hidden" id="area_usuario" value="<?= $_SESSION['PersonalData']['area'] ?>">
                        <table id="tableFacturas" class="table table-hover table-bordered align-middle">
                            <thead>
                                <tr>

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
    <?php footerAdmin($data); ?>

    <div class="modal fade" id="finalizarModal" tabindex="-1" aria-labelledby="corregirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="finalizarSolicitud">
                    <input type="hidden" class="form-control" name="id_solicitud"
                        value="<?= $data['facturas']['id_solicitud'] ?? 'N/A'; ?>">
                    <!-- Encabezado -->
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="corregirModalLabel">
                            Finalizar Anticipo
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar">
                        </button>
                    </div>
                    <!-- Cuerpo -->
                    <div class="modal-body">
                        <!-- Datos generales -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">Datos Generales</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Realizador</label>
                                    <input type="text" class="form-control"
                                        value="<?= $data['facturas']['realizador'] ?? 'N/A'; ?>" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Proveedor</label>
                                    <input type="text" class="form-control"
                                        value="<?= $data['facturas']['proveedor'] ?? 'N/A'; ?>" disabled>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Monto Total</label>
                                    <input type="text" class="form-control"
                                        value="<?= $data['facturas']['total_calc'] ?? 'N/A'; ?>" disabled>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Área</label>
                                    <input type="text" class="form-control"
                                        value="<?= $data['facturas']['area'] ?? 'N/A'; ?>" disabled>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Fecha Registro</label>
                                    <input type="text" class="form-control"
                                        value="<?= $data['facturas']['fecha_registro'] ?? 'N/A'; ?>" disabled>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="fecha_pago" class="form-label">Fecha Pago</label>
                                    <input type="date" class="form-control btn-input"
                                        value="<?= $data['facturas']['fecha_pago'] ?? ''; ?>" disabled>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="fecha_pago" class="form-label">Fecha Pago</label>
                                    <input type="date" class="form-control btn-input"
                                        value="<?= $data['facturas']['fecha_pago'] ?? ''; ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">Datos Finales</h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="no_transferencia" class="form-label">No. Transferencia</label>
                                    <input type="text" class="form-control" id="no_transferencia"
                                        name="no_transferencia">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="fecha_pago" class="form-label">Fecha de Pago</label>
                                    <input type="date" class="form-control" id="fecha_pago" name="fecha_pago">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="observacion" class="form-label">Observaciones</label>
                                    <textarea class="form-control" id="observacion" name="observacion" rows="4"
                                        placeholder="Escribe aquí tus observaciones..."></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" data-respuesta="Corregir">
                            <i class="fas fa-save"></i> Corregir
                        </button>
                        <button type="submit" class="btn btn-success" data-respuesta="Finalizado">
                            <i class="fas fa-save"></i> Finalizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editarSolicitud" tabindex="-1" aria-labelledby="setUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="setSolicitud">
                    <input type="hidden" id="realizador" name="realizador"
                        value="<?php echo $_SESSION['PersonalData']['id_usuario']; ?>">
                    <input type="hidden" id="edit_id_solicitud" name="id_solicitud">
                    <div class="modal-header">
                        <h5 class="modal-title" id="setUserModalLabel">Editar Solicitud de Fondos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-md-2">
                                <label for="edit_fecha_creacion" class="form-label">Fecha Registro</label>
                                <input type="date" class="form-control" id="edit_fecha_creacion" name="fecha_registro"
                                    readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="fecha_pago" class="form-label">Fecha Pago <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit_fecha_pago" name="fecha_pago" required>
                            </div>
                            <div class="col-md-3">
                                <label for="area" class="form-label">Área</label>
                                <input type="hidden" name="area"
                                    value="<?php echo $_SESSION['PersonalData']['area']; ?>">
                                <input type="text" class="form-control"
                                    value="<?php echo $_SESSION['PersonalData']['nombre_area']; ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="proveedor" class="form-label">Recibimos de: <span
                                        class="text-danger">*</span></label>
                                <select class="form-control selectpicker" id="edit_proveedor" name="proveedor" required
                                    data-live-search="true">
                                </select>
                            </div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-md-9">
                                <h2 class="fw-bold">Detalles</h2>
                            </div>
                        </div>
                        <hr>

                        <div class="mb-3">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle text-center"
                                    id="tablaFacturasEdit">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Tipo</th>
                                            <th>Bien o Servicio</th>
                                            <th>Valor Documento</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Contenido dinámico -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="observacion_general" class="form-label">Observación General</label>
                            <textarea class="form-control" id="observacion_general" name="observacion_general"
                                rows="3"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" data-respuesta="Descartado">
                            <i class="fas fa-save"></i> Descartar
                        </button>
                        <button type="submit" class="btn btn-success" data-respuesta="Pendiente">
                            <i class="fas fa-save"></i> Re-Enviar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>