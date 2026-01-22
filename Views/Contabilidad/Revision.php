<?php headerAdmin($data); ?>
<div class="main p-3">
    <div class="row">
        <!-- Información de la Contraseña -->
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header text-dark d-flex justify-content-between align-items-center">
                    <!-- Sección títulos -->
                    <div>
                        <h2 class="mb-1 fs-4 fw-bold">Solicitud de Fondos</h2>
                        <h3 class="mb-0 fs-6 text-muted">
                            NO. CONTRASEÑA:
                            <strong class="text-dark"><?= $data['facturas']['contraseña']; ?></strong>
                        </h3>
                        <?php if ($data['facturas']['anticipo'] != null) { ?>
                            <button class="btn-warning-circle ms-auto" id="btnValidar" data-bs-toggle="modal"
                                data-bs-target="#anticipoModal" data-id="<?= $data['facturas']['contraseña']; ?>"
                                title="Solicitar Fondos">
                                <i class="fas fa-exclamation"></i>
                            </button>
                        <?php } ?>
                    </div>
                    <div class="d-flex gap-2">
                        <?php if ($data['facturas']['area_id'] == 2): ?>
                            <?php if ($data['facturas']['solicitud_estado'] === "Validado" && $_SESSION['PersonalData']['area'] == 4): ?>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#finalizarModal">
                                    <i class="fas fa-check"></i> Pagar
                                </button>
                            <?php endif; ?>
                            <?php if ($data['facturas']['solicitud_estado'] === "Pagado" && $_SESSION['PersonalData']['area'] == 4): ?>
                                <?php if ($data['facturas']['categoria'] === "Servicios" && $_SESSION['PersonalData']['area'] == 4): ?>
                                    <button class="btn btn-danger btn-sm"
                                        onclick="window.open('<?= base_url() ?>/SolicitudFondos/generarSolicitudServicios/<?= $data['facturas']['contraseña']; ?>', '_blank')">
                                        <i class="far fa-file-pdf"></i> PDF
                                    </button>
                                <?php endif; ?>
                                <?php if ($data['facturas']['categoria'] === "Rentas" && $_SESSION['PersonalData']['area'] == 4): ?>
                                    <button class="btn btn-danger btn-sm"
                                        onclick="window.open('<?= base_url() ?>/SolicitudFondos/generarSolicitudRentas/<?= $data['facturas']['contraseña']; ?>', '_blank')">
                                        <i class="far fa-file-pdf"></i> PDF
                                    </button>
                                <?php endif; ?>
                                <?php if ($data['facturas']['categoria'] === "Combustible" && $_SESSION['PersonalData']['area'] == 4): ?>
                                    <button class="btn btn-danger btn-sm"
                                        onclick="window.open('<?= base_url() ?>/SolicitudFondos/generarSolicitudCombustible/<?= $data['facturas']['contraseña']; ?>', '_blank')">
                                        <i class="far fa-file-pdf"></i> PDF
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php elseif ($data['facturas']['area_id'] != 2): ?>
                            <?php if ($data['facturas']['solicitud_estado'] === "Pendiente" && $_SESSION['PersonalData']['area'] == 4): ?>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#finalizarModal">
                                    <i class="fas fa-check"></i> Pagar
                                </button>
                            <?php endif; ?>
                            <button class="btn btn-danger btn-sm"
                                onclick="window.open('<?= base_url() ?>/SolicitudFondos/generarSolicitud/<?= $data['facturas']['contraseña']; ?>', '_blank')">
                                <i class="far fa-file-pdf"></i> PDF
                            </button>
                        <?php endif ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Proveedor</label>
                            <input type="text" class="form-control"
                                value="<?= $data['facturas']['proveedor'] ?? 'N/A'; ?>" disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Área</label>
                            <input type="text" class="form-control" value="<?= $data['facturas']['area'] ?? 'N/A'; ?>"
                                disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Fecha Registro</label>
                            <input type="text" class="form-control"
                                value="<?= $data['facturas']['fecha_registro'] ?? 'N/A'; ?>" disabled>
                        </div>
                        <div class="col-md-3">
                            <label for="fecha_pago" class="form-label fw-bold">Fecha Pago</label>
                            <input type="date" class="form-control" id="fecha_pago"
                                value="<?= $data['facturas']['fecha_pago'] ?? ''; ?>" disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Monto Total</label>
                            <input type="text" class="form-control text-end"
                                value="<?= $data['facturas']['total_calc'] ?? 'N/A'; ?>" disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">12% IVA</label>
                            <input type="text" class="form-control text-end"
                                value="<?= ($data['facturas']['regimen'] == 2) ? 0 : ($data['facturas']['iva_calc'] ?? 'N/A'); ?>"
                                disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Ret. IVA Total</label>
                            <input type="text" class="form-control text-end"
                                value="<?= $data['facturas']['reten_iva'] ?? 'N/A'; ?>" disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Ret. ISR Total</label>
                            <input type="text" class="form-control text-end"
                                value="<?= $data['facturas']['reten_isr'] ?? 'N/A'; ?>" disabled>
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
                        <input type="hidden" id="contraseña" value="<?= $data['facturas']['contraseña']; ?>">
                        <input type="hidden" id="solicitud_estado"
                            value="<?= $data['facturas']['solicitud_estado']; ?>">
                        <input type="hidden" id="area_usuario" value="<?= $_SESSION['PersonalData']['area'] ?>">

                        <?php if ($data['facturas']['area_id'] == 2): ?>
                            <?php if ($data['facturas']['categoria'] === "Servicios"): ?>

                                <table id="tableServicios" class="table table-hover table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Se llena dinámicamente -->
                                    </tbody>
                                </table>

                            <?php elseif ($data['facturas']['categoria'] === "Rentas"): ?>

                                <table id="tableRentas" class="table table-hover table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Se llena dinámicamente -->
                                    </tbody>
                                </table>

                            <?php endif; ?>

                        <?php else: ?>
                            <table id="tableFacturas" class="table table-hover table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Contraseña</th>
                                        <th>Área</th>
                                        <th>Registro</th>
                                        <th>Proveedor</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th>Registro AX</th>
                                        <th>Observación</th>
                                        <th>IVA</th>
                                        <th>Retención IVA</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Se llena dinámicamente -->
                                </tbody>
                            </table>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php footerAdmin($data); ?>

    <div class="modal fade" id="validarModal" tabindex="-1" aria-labelledby="corregirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="validarSolicitud">
                    <input type="hidden" class="form-control" name="id_solicitud"
                        value="<?= $data['facturas']['id_solicitud']; ?>">
                    <input type="hidden" class="form-control" name="area" value="<?= $data['facturas']['area_id']; ?>">
                    <!-- Encabezado -->
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="corregirModalLabel">
                            Solicitud de Fondos
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar">
                        </button>
                    </div>
                    <!-- Cuerpo -->
                    <div class="modal-body">
                        <!-- Número de contraseña -->
                        <div class="mb-4 border-bottom pb-2">
                            <h5 class="fw-bold mb-0">
                                CONTRASEÑA: <span class="text-dark"><?= $data['facturas']['contraseña']; ?></span>
                            </h5>
                        </div>
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
                                    <input type="text" class="form-control text-end"
                                        value="<?= ($data['facturas']['regimen'] == 2) ? ($data['facturas']['total_pequeño'] ?? 'N/A') : ($data['facturas']['total'] ?? 'N/A'); ?>"
                                        disabled>
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
                            </div>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" data-respuesta="Descartado">
                            <i class="fas fa-times"></i> Descartar
                        </button>
                        <?php if ($data['facturas']['area_id'] == 2) { ?>
                            <button type="submit" class="btn btn-success" data-respuesta="Validado Conta">
                                <i class="fas fa-save"></i> Validar
                            </button>
                        <?php } else if ($data['facturas']['area_id'] != 2) { ?>
                                <button type="submit" class="btn btn-success" data-respuesta="Validado Area">
                                    <i class="fas fa-save"></i> Validar
                                </button>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="finalizarModal" tabindex="-1" aria-labelledby="corregirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="finalizarSolicitud">
                    <input type="hidden" class="form-control" name="id_solicitud"
                        value="<?= $data['facturas']['id_solicitud']; ?>">
                    <input type="hidden" class="form-control" name="area" value="<?= $data['facturas']['area_id']; ?>">
                    <!-- Encabezado -->
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="corregirModalLabel">
                            Finalizar solicitud de fondos
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar">
                        </button>
                    </div>
                    <!-- Cuerpo -->
                    <div class="modal-body">
                        <!-- Número de contraseña -->
                        <div class="mb-4 border-bottom pb-2">
                            <h5 class="fw-bold mb-0">
                                CONTRASEÑA: <span class="text-dark"><?= $data['facturas']['contraseña']; ?></span>
                            </h5>
                        </div>
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
                                    <input type="text" class="form-control text-end"
                                        value="<?= ($data['facturas']['regimen'] == 2) ? ($data['facturas']['total_pequeño'] ?? 'N/A') : ($data['facturas']['total'] ?? 'N/A'); ?>"
                                        disabled>
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
                            </div>
                        </div>
                        <hr>
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">Datos Finales</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">No. Transferencia</label>
                                    <input type="text" class="form-control" name="no_transferencia">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fecha de Pago</label>
                                    <input type="date" class="form-control" name="fecha_pago">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">Comentario de Revision</h6>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <textarea class="form-control" name="observacion" rows="4"
                                        placeholder="Escribe aquí tus observaciones..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" data-respuesta="Descartado">
                            Descartar
                        </button>
                        <button type="submit" class="btn btn-success" data-respuesta="Pagado">
                            <i class="fas fa-save"></i> Pagar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Revisión Factura</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="DetalleEdit">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="edit_id">
                        <input type="hidden" id="edit_id_regimen">
                        <div class="row">
                            <!-- Título -->
                            <div class="mb-4 border-bottom pb-2">
                                <h5 class="fw-bold mb-0">Datos Principales</h5>
                            </div>

                            <!-- Datos principales -->
                            <div class="col-md-3 mb-3">
                                <label for="edit_factura" class="form-label">No. de Factura</label>
                                <input type="text" class="form-control" id="edit_factura" name="edit_factura" disabled>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="edit_documento" class="form-label">Valor Documento</label>
                                <input type="text" class="form-control" id="edit_documento" name="edit_documento"
                                    disabled>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_servicio" class="form-label">Bien/Servicio</label>
                                <input type="text" class="form-control" id="edit_servicio" name="edit_servicio"
                                    disabled>
                            </div>
                            <!-- Cod AX -->
                            <div class="col-md-3 mb-3">
                                <label for="edit_codax" class="form-label">Registro AX</label>
                                <input type="text" class="form-control" id="edit_codax" name="edit_codax">
                            </div>
                            <!-- Cod AX -->
                            <div class="col-md-3 mb-3">
                                <label for="edit_base" class="form-label">Base</label>
                                <input type="text" class="form-control" id="edit_base" name="edit_base" readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="edit_base" class="form-label">IVA Base</label>
                                <input type="text" class="form-control" id="edit_base_iva" name="edit_base_iva"
                                    readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="edit_base" class="form-label">Regimen</label>
                                <input type="text" class="form-control" id="edit_regimen" readonly>
                            </div>


                            <hr>
                            <!-- IVA -->
                            <div class="col-md-3 mb-3">
                                <label for="input_iva" class="form-label">IVA</label>
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0" type="checkbox" id="check_iva">
                                    </div>
                                    <input type="number" class="form-control" id="input_iva" name="input_iva"
                                        placeholder="%" disabled>
                                </div>
                            </div>

                            <!-- ISR -->
                            <div class="col-md-3 mb-3">
                                <label for="input_isr" class="form-label">ISR</label>
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0" type="checkbox" id="check_isr">
                                    </div>
                                    <input type="number" class="form-control" id="input_isr" name="input_isr"
                                        placeholder="%" disabled>
                                </div>
                            </div>

                            <!-- Retención IVA -->
                            <div class="col-md-3 mb-3">
                                <label for="edit_reten_iva" class="form-label">Retención IVA</label>
                                <input type="text" class="form-control" id="edit_reten_iva" name="edit_reten_iva"
                                    readonly>
                            </div>
                            <!-- Retención ISR -->
                            <div class="col-md-3 mb-3">
                                <label for="edit_reten_isr" class="form-label">Retención ISR</label>
                                <input type="text" class="form-control" id="edit_reten_isr" name="edit_reten_isr"
                                    readonly>
                            </div>

                            <!-- Fecha Registro -->
                            <div class="col-md-3 mb-3">
                                <label for="edit_fecha_registro" class="form-label">Fecha Registro</label>
                                <input type="text" class="form-control" id="edit_fecha_registro"
                                    name="edit_fecha_registro" readonly>
                            </div>

                            <!-- Estado -->
                            <div class="col-md-3 mb-3">
                                <label for="edit_total" class="form-label">Total Líquido</label>
                                <input type="text" class="form-control" id="edit_total" name="edit_total" readonly>
                            </div>
                            <hr>
                            <!-- Observación -->
                            <div class="col-md-12 mb-3">
                                <label for="edit_observacion" class="form-label">Observación</label>
                                <textarea class="form-control" id="edit_observacion" name="edit_observacion"
                                    rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="anticipoModal" tabindex="-1" aria-labelledby="modalAnticipoInfoLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Encabezado -->
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold" id="modalAnticipoInfoLabel">
                        Información de Anticipo - <?= $data['anticipoinfo']['area']; ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <!-- Cuerpo -->
                <div class="modal-body">
                    <!-- Advertencia -->
                    <div class="alert alert-warning text-dark border-warning fw-semibold" role="alert">
                        ⚠️ Este registro corresponde a un <strong>ANTICIPO DE FONDOS</strong>.
                        La información mostrada es únicamente de carácter informativo.
                    </div>

                    <!-- Datos generales -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-3">Datos Generales</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Realizador</label>
                                <div class="form-control bg-light">
                                    <?= $data['anticipoinfo']['usuario'] ?? 'N/A'; ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Proveedor</label>
                                <div class="form-control bg-light">
                                    <?= $data['anticipoinfo']['proveedor'] ?? 'N/A'; ?>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Monto Total</label>
                                <div class="form-control bg-light">
                                    <?= $data['anticipoinfo']['monto_total'] ?? 'N/A'; ?>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Categoría</label>
                                <div class="form-control bg-light">
                                    <?= $data['anticipoinfo']['categoria'] ?? 'N/A'; ?>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Área</label>
                                <div class="form-control bg-light">
                                    <?= $data['anticipoinfo']['area'] ?? 'N/A'; ?>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Estado</label>
                                <div class="form-control bg-light fw-semibold text-capitalize">
                                    <?= $data['anticipoinfo']['estado'] ?? 'N/A'; ?>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Fecha de Registro</label>
                                <div class="form-control bg-light">
                                    <?= $data['anticipoinfo']['fecha_creacion'] ?? 'N/A'; ?>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Fecha Estimada de Pago</label>
                                <div class="form-control bg-light">
                                    <?= $data['anticipoinfo']['fecha_pago'] ?? 'N/A'; ?>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Información bancaria y observaciones -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-3">Detalle de Transacción</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Número de Transferencia</label>
                                <div class="form-control bg-light">
                                    <?= $data['anticipoinfo']['no_transferencia'] ?? 'N/A'; ?>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Fecha de Transacción</label>
                                <div class="form-control bg-light">
                                    <?= $data['anticipoinfo']['fecha_transaccion'] ?? 'N/A'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pie -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <style>
        .btn-warning-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: #ffc107;
            color: #fff;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            box-shadow: 0 2px 6px rgba(255, 193, 7, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: pulse 2.2s infinite;
            cursor: pointer;
        }

        /* Pequeño efecto de latido */
        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.08);
            }
        }

        /* Rebote del ícono */
        .btn-warning-circle i {
            animation: bounceIcon 3s infinite;
        }

        @keyframes bounceIcon {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-3px);
            }
        }

        /* Hover con brillo */
        .btn-warning-circle:hover {
            transform: scale(1.15);
            box-shadow: 0 4px 10px rgba(255, 193, 7, 0.6);
        }
    </style>