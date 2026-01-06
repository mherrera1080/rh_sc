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
                            <strong class="text-dark">
                                <?= $data['facturas']['contraseña']; ?>
                            </strong>
                        </h3>
                    </div>
                    <div class="d-flex gap-2">

                        <?php if ($data['facturas']['solicitud_estado'] === "Pendiente") { ?>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#validarModal">
                                <i class="fas fa-check"></i> Validar
                            </button>
                            <button class="btn btn-danger btn-sm"
                                onclick="window.open('<?= base_url() ?>/SolicitudFondos/generarSolicitudCombustible/<?= $data['facturas']['contraseña']; ?>', '_blank')">
                                <i class="far fa-file-pdf"></i> PDF
                            </button>
                        <?php } else if ($data['facturas']['solicitud_estado'] === "Validado" && $_SESSION['PersonalData']['area'] == 4) { ?>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#finalizarModal">
                                    <i class="fas fa-check"></i> Pagar
                                </button>
                        <?php } ?>
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
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Facturas -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex align-items-center">
                    <h4 class="card-title mb-0">Detalle de Solicitud</h4>
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
                                    <label class="form-label strong strong">Monto</label>
                                    <input type="text" class="form-control text-end"
                                        value="<?= $data['facturas']['total'] ?? 'N/A'; ?>" disabled>

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
                        value="<?= $data['facturas']['id_solicitud'] ?? 'N/A'; ?>">
                    <input type="hidden" class="form-control" name="area"
                        value="<?= $data['facturas']['id_area'] ?? 'N/A'; ?>">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="corregirModalLabel">
                            Finalizar Anticipo
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar">
                        </button>
                    </div>
                    <div class="modal-body">
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