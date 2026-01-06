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
                        <?php if ($data['facturas']['solicitud_estado'] === "Pendiente"): ?>
                            <?php if ($data['facturas']['renta'] === null): ?>
                                <button class="btn btn-danger btn-sm btnPDF" data-bs-toggle="modal"
                                    data-bs-target="#pdfRentasModal" data-id="<?= $data['facturas']['contraseña']; ?>">
                                    <i class="far fa-file-pdf"></i> Generar PDF
                                </button>
                            <?php elseif ($data['facturas']['renta'] != null): ?>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#validarModal">
                                    <i class="fas fa-check"></i> Validar
                                </button>
                                <button class="btn btn-danger btn-sm"
                                    onclick="window.open('<?= base_url() ?>/SolicitudFondos/generarSolicitudRentas/<?= $data['facturas']['contraseña']; ?>', '_blank')">
                                    <i class="far fa-file-pdf"></i> PDF
                                </button>
                                <button class="btn btn-secondary btn-sm btnPDF" data-bs-toggle="modal"
                                    data-bs-target="#pdfRentasModal" data-id="<?= $data['facturas']['contraseña']; ?>">
                                    <i class="fa-solid fa-arrow-rotate-left"></i>Actualizar Datos
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="modal fade" id="pdfRentasModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <form id="GenerarPDF">
                                        <div class="modal-header bg-dark text-white">
                                            <h5 class="modal-title">Generar PDF de Rentas</h5>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">

                                            <input type="hidden" name="contraseña"
                                                value="<?= $data['facturas']['contraseña']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">MES RENTA</label>
                                                <input type="date" class="form-control" id="mes_renta" name="mes_renta"
                                                    required>
                                            </div>

                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="habilitarExtras">
                                                <label class="form-check-label fw-bold" for="habilitarExtras">
                                                    Agregar Nota de Credito
                                                </label>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label fw-bold">Factura</label>
                                                    <input type="text" class="form-control extra-input" id="no_factura"
                                                        name="no_factura" disabled>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label fw-bold">Monto</label>
                                                    <input type="text" class="form-control extra-input"
                                                        id="monto_credito" name="monto_credito" inputmode="decimal"
                                                        placeholder="0.00" disabled>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cancelar
                                            </button>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="far fa-file-pdf"></i> Generar PDF
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label strong strong fw-bold">Proveedor</label>
                            <input type="text" class="form-control"
                                value="<?= $data['facturas']['proveedor'] ?? 'N/A'; ?>" disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label strong strong fw-bold">Área</label>
                            <input type="text" class="form-control" value="<?= $data['facturas']['area'] ?? 'N/A'; ?>"
                                disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label strong strong fw-bold">Fecha Registro</label>
                            <input type="text" class="form-control"
                                value="<?= $data['facturas']['fecha_registro'] ?? 'N/A'; ?>" disabled>
                        </div>
                        <div class="col-md-3">
                            <label for="fecha_pago" class="form-label strong strong fw-bold">Fecha Pago</label>
                            <input type="date" class="form-control" id="fecha_pago"
                                value="<?= $data['facturas']['fecha_pago'] ?? ''; ?>" disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label strong strong fw-bold">Monto Total</label>
                            <input type="text" class="form-control text-end"
                                value="<?= $data['facturas']['total_calc'] ?? 'N/A'; ?>" disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label strong strong fw-bold">12% IVA</label>
                            <input type="text" class="form-control text-end"
                                value="<?= ($data['facturas']['regimen'] == 2) ? 0 : ($data['facturas']['iva_calc'] ?? 'N/A'); ?>"
                                disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label strong strong fw-bold">Ret. IVA Total</label>
                            <input type="text" class="form-control text-end"
                                value="<?= $data['facturas']['reten_iva'] ?? 'N/A'; ?>" disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label strong strong fw-bold">Ret. ISR Total</label>
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
                                    <label class="form-label strong strong">Realizador</label>
                                    <input type="text" class="form-control"
                                        value="<?= $data['facturas']['realizador'] ?? 'N/A'; ?>" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label strong strong">Proveedor</label>
                                    <input type="text" class="form-control"
                                        value="<?= $data['facturas']['proveedor'] ?? 'N/A'; ?>" disabled>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label strong strong">Monto Total</label>
                                    <input type="text" class="form-control text-end"
                                        value="<?= ($data['facturas']['regimen'] == 2) ? ($data['facturas']['total_pequeño'] ?? 'N/A') : ($data['facturas']['total'] ?? 'N/A'); ?>"
                                        disabled>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label strong strong">Área</label>
                                    <input type="text" class="form-control"
                                        value="<?= $data['facturas']['area'] ?? 'N/A'; ?>" disabled>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label strong strong">Fecha Registro</label>
                                    <input type="text" class="form-control"
                                        value="<?= $data['facturas']['fecha_registro'] ?? 'N/A'; ?>" disabled>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="fecha_pago" class="form-label strong">Fecha Pago</label>
                                    <input type="date" class="form-control btn-input"
                                        value="<?= $data['facturas']['fecha_pago'] ?? ''; ?>" disabled>
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
                    </div>
                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" data-respuesta="Descartado">
                            <i class="fas fa-times"></i> Descartar
                        </button>
                        <button type="submit" class="btn btn-success" data-respuesta="Validado">
                            <i class="fas fa-save"></i> Validar
                        </button>
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
                                    <label class="form-label strong">Realizador</label>
                                    <input type="text" class="form-control"
                                        value="<?= $data['facturas']['realizador'] ?? 'N/A'; ?>" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label strong">Proveedor</label>
                                    <input type="text" class="form-control"
                                        value="<?= $data['facturas']['proveedor'] ?? 'N/A'; ?>" disabled>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label strong">Monto Total</label>
                                    <input type="text" class="form-control text-end"
                                        value="<?= ($data['facturas']['regimen'] == 2) ? ($data['facturas']['total_pequeño'] ?? 'N/A') : ($data['facturas']['total'] ?? 'N/A'); ?>"
                                        disabled>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label strong">Área</label>
                                    <input type="text" class="form-control"
                                        value="<?= $data['facturas']['area'] ?? 'N/A'; ?>" disabled>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label strong">Fecha Registro</label>
                                    <input type="text" class="form-control"
                                        value="<?= $data['facturas']['fecha_registro'] ?? 'N/A'; ?>" disabled>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="fecha_pago" class="form-label strong">Fecha Pago</label>
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
                                    <label class="form-label strong">No. Transferencia</label>
                                    <input type="text" class="form-control" name="no_transferencia">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label strong">Fecha de Pago</label>
                                    <input type="date" class="form-control" name="fecha_pago">
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Renta Vehicular</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="ArrendamientoEdit">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="edit_id">
                        <input type="hidden" id="edit_id_regimen">
                        <input type="hidden" name="edit_contraseña" value="<?= $data['facturas']['contraseña']; ?>">
                        <div class="row">
                            <!-- Título -->
                            <div class="mb-4 border-bottom pb-2">
                                <h5 class="fw-bold mb-0">Datos Principales</h5>
                            </div>

                            <!-- Datos principales -->
                            <div class="col-md-3 mb-3">
                                <label for="edit_factura" class="form-label strong">No. de Factura</label>
                                <input type="text" class="form-control" id="edit_factura" name="edit_factura" disabled>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="edit_documento" class="form-label strong">Valor Documento</label>
                                <input type="text" class="form-control" id="edit_documento" name="edit_documento"
                                    disabled>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_servicio" class="form-label strong">Bien/Servicio</label>
                                <input type="text" class="form-control" id="edit_servicio" name="edit_servicio"
                                    disabled>
                            </div>
                            <!-- Cod AX -->
                            <div class="col-md-3 mb-3">
                                <label for="edit_codax" class="form-label strong">Registro AX</label>
                                <input type="text" class="form-control" id="edit_codax" name="edit_codax">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="edit_base" class="form-label strong">Base</label>
                                <input type="text" class="form-control" id="edit_base" name="edit_base" readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="edit_base" class="form-label strong">IVA Base</label>
                                <input type="text" class="form-control" id="edit_base_iva" name="edit_base_iva"
                                    readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="edit_base" class="form-label strong">Regimen</label>
                                <input type="text" class="form-control" id="edit_regimen" readonly>
                            </div>

                            <hr>
                            <div class="col-md-12">
                                <div class="mt-2">
                                    <button type="button" class="btn btn-primary btn-sm" id="btnAgregarVehiculo">
                                        Agregar Vehiculos
                                    </button>
                                </div>
                                <br>
                                <div class="row" id="contenedorRentas"></div>
                            </div>
                            <br>
                            <hr>
                            <div class="col-md-12 mb-3">
                                <label for="edit_observacion" class="form-label strong strong">Observación</label>
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
                                <label class="form-label strong strong">Realizador</label>
                                <div class="form-control bg-light">
                                    <?= $data['anticipoinfo']['usuario'] ?? 'N/A'; ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label strong strong">Proveedor</label>
                                <div class="form-control bg-light">
                                    <?= $data['anticipoinfo']['proveedor'] ?? 'N/A'; ?>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label strong strong">Monto Total</label>
                                <div class="form-control bg-light">
                                    <?= $data['anticipoinfo']['monto_total'] ?? 'N/A'; ?>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label strong strong">Categoría</label>
                                <div class="form-control bg-light">
                                    <?= $data['anticipoinfo']['categoria'] ?? 'N/A'; ?>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label strong strong">Área</label>
                                <div class="form-control bg-light">
                                    <?= $data['anticipoinfo']['area'] ?? 'N/A'; ?>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label strong strong">Estado</label>
                                <div class="form-control bg-light fw-semibold text-capitalize">
                                    <?= $data['anticipoinfo']['estado'] ?? 'N/A'; ?>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label strong strong">Fecha de Registro</label>
                                <div class="form-control bg-light">
                                    <?= $data['anticipoinfo']['fecha_creacion'] ?? 'N/A'; ?>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label strong strong">Fecha Estimada de Pago</label>
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
                                <label class="form-label strong strong">Número de Transferencia</label>
                                <div class="form-control bg-light">
                                    <?= $data['anticipoinfo']['no_transferencia'] ?? 'N/A'; ?>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label strong strong">Fecha de Transacción</label>
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const contenedor = document.getElementById("contenedorRentas");
            const btnAgregar = document.getElementById("btnAgregarVehiculo");

            let contador = 0;
            let placas = [];

            fetch(api_url + "ApiContabilidad/getSelectPlacas")
                .then(res => res.json())
                .then(data => {
                    placas = data;
                });

            function agregarInputMaterial(valor = "") {
                contador++;

                const col = document.createElement("div");
                col.classList.add("col-md-3", "mb-3");
                col.setAttribute("id", "arrendamiento_" + contador);

                col.innerHTML = `
                <label class="form-label">Vehículo</label>
                <div class="input-group position-relative">

                    <input 
                        type="text"
                        class="form-control input-placa"
                        name="arrendamientos[]"
                        value="${valor}"
                        placeholder="Placa"
                        autocomplete="off"
                        required
                    >

                    <button type="button"
                        class="btn btn-danger btn-sm btnEliminar"
                        data-id="${contador}">
                        X
                    </button>

                    <ul class="list-group placa-list d-none"
                        style="position:absolute; top:100%; left:0; width:100%; z-index:1055;">
                    </ul>

                </div>
                `;

                contenedor.appendChild(col);
            }

            btnAgregar.addEventListener("click", () => {
                agregarInputMaterial();
            });

            contenedor.addEventListener("input", function (e) {

                if (!e.target.classList.contains("input-placa")) return;

                const input = e.target;
                const list = input.closest(".input-group").querySelector(".placa-list");
                const value = input.value.toLowerCase();

                list.innerHTML = "";

                if (!value) {
                    list.classList.add("d-none");
                    return;
                }

                const results = placas.filter(p =>
                    p.placa.toLowerCase().includes(value)
                );

                if (results.length === 0) {
                    list.classList.add("d-none");
                    return;
                }

                results.forEach(p => {
                    const li = document.createElement("li");
                    li.className = "list-group-item list-group-item-action";
                    li.textContent = p.placa;

                    li.addEventListener("click", () => {
                        input.value = p.placa;
                        list.classList.add("d-none");
                    });

                    list.appendChild(li);
                });

                list.classList.remove("d-none");
            });

            contenedor.addEventListener("focusout", function (e) {
                if (e.target.classList.contains("input-placa")) {
                    setTimeout(() => {
                        const list = e.target.closest(".input-group").querySelector(".placa-list");
                        list.classList.add("d-none");
                    }, 200);
                }
            });

            contenedor.addEventListener("click", function (e) {
                if (e.target.classList.contains("btnEliminar")) {
                    const id = e.target.dataset.id;
                    document.getElementById("arrendamiento_" + id).remove();
                }
            });

            window.agregarInputMaterial = agregarInputMaterial;
            window.resetContenedorMateriales = function () {
                contenedor.innerHTML = "";
                contador = 0;
            };

        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const checkbox = document.getElementById("habilitarExtras");
            const extraInputs = document.querySelectorAll(".extra-input");

            checkbox.addEventListener("change", function () {
                extraInputs.forEach(input => {
                    input.disabled = !checkbox.checked;

                    if (!checkbox.checked) {
                        input.value = ""; // Limpia los campos al desactivar
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const montoInput = document.querySelector('input[name="monto_credito"]');

            montoInput.addEventListener("input", function () {
                let valor = this.value;

                valor = valor.replace(/[^0-9.]/g, '');

                const partes = valor.split('.');
                if (partes.length > 2) {
                    valor = partes[0] + '.' + partes.slice(1).join('');
                }

                this.value = valor;
            });

        });
    </script>