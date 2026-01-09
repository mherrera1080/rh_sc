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
                        <button class="btn btn-danger btn-sm"
                            onclick="window.open('<?= base_url() ?>/SolicitudFondos/generarAnticipo/<?= $data['facturas']['id_solicitud']; ?>', '_blank')">
                            <i class="far fa-file-pdf"></i> PDF
                        </button>
                        <?php if ($data['facturas']['solicitud_estado'] === "Pendiente" && $_SESSION['PersonalData']['area'] == 4 || $data['facturas']['solicitud_estado'] === "Corregido" && $_SESSION['PersonalData']['area'] == 4) { ?>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#finalizarModal">
                                <i class="fas fa-check"></i> Pagar
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
                            <label for="fecha_pago" class="form-label fw-bold">Fecha Pago Estimado</label>
                            <input type="date" class="form-control" id="fecha_pago"
                                value="<?= $data['facturas']['fecha_pago'] ?? ''; ?>" disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Monto Total</label>
                            <input type="text" class="form-control text-end"
                                value="<?= $data['facturas']['total'] ?? 'N/A'; ?>" disabled>
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
                            <thead class="table-light">
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
                    <input type="hidden" class="form-control" name="area"
                        value="<?= $data['facturas']['id_area'] ?? 'N/A'; ?>">
                        <input type="hidden" class="form-control" name="tipo"
                        value="Anticipo">
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
                                        value="<?= $data['facturas']['total'] ?? 'N/A'; ?>" disabled>
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
                                    <label for="fecha_pago" class="form-label">Fecha de Pago Transferencia</label>
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
                        <button type="submit" class="btn btn-danger" data-respuesta="Descartado">
                            <i class="fas fa-save"></i> Descartar
                        </button>
                        <button type="submit" class="btn btn-success" data-respuesta="Pagado">
                            <i class="fas fa-save"></i> Pagar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>