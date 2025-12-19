<?php headerAdmin($data); ?>
<div class="main p-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header info-section text-dark d-flex align-items-center">
                    <h3 style="margin-right: 20px; display: inline-block;">CONTRASEÑA:
                        <strong><?= $data['facturas']['contraseña']; ?></strong>
                    </h3>
                    <button class="btn btn-danger me-2 btn-password"
                        onclick="window.open( '<?= base_url() ?>/Contraseñas/generarContraseña/<?= $data['facturas']['contraseña']; ?>', '_blank')">
                        <i class="far fa-file-pdf"></i>
                    </button>
                    <button class="btn btn-warning btn-password">
                        <i class="far fa-solid fa-envelope"></i>
                    </button>
                    <?php $_SESSION['PersonalData']['area'] ?>
                    <div class="ms-auto d-flex align-items-center gap-2">

                        <?php if ($data['facturas']['estado'] === "Validado Area" && $_SESSION['PersonalData']['area'] == 4) { ?>
                            <button class="btn btn-success btn-round ms-auto btn-password" id="btnValidar"
                                data-bs-toggle="modal" data-bs-target="#solicitarFondos"
                                data-id="<?= $data['facturas']['contraseña']; ?>">
                                <i class="fas fa-check"></i> Solicitar Fondos
                            </button>
                            <button class="btn btn-danger btn-round ms-auto btn-corregir" id="btnCorregir"
                                data-bs-toggle="modal" data-bs-target="#corregirModal"
                                data-id="<?= $data['facturas']['contraseña']; ?>">
                                <i class="fas fa-ban"></i> Regresar
                            </button>
                            <?php if ($data['facturas']['anticipo'] != null) { ?>
                                <button class="btn-warning-circle ms-auto" id="btnValidar" data-bs-toggle="modal"
                                    data-bs-target="#anticipoModal" data-id="<?= $data['facturas']['contraseña']; ?>"
                                    title="Solicitar Fondos">
                                    <i class="fas fa-exclamation"></i>
                                </button>
                            <?php } ?>
                        <?php } ?>

                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Relizador</label>
                            <input type="text" class="form-control" id="" name=""
                                value="<?= $data['facturas']['realizador'] ?? 'N/A'; ?>" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Proveedor</label>
                            <input type="text" class="form-control" id="" name=""
                                value="<?= $data['facturas']['proveedor'] ?? 'N/A'; ?>" disabled>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="" class="form-label">Monto Total</label>
                            <input type="text" class="form-control" id="" name=""
                                value="<?= $data['facturas']['monto_total'] ?? 'N/A'; ?>" disabled>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="" class="form-label">Area</label>
                            <input type="text" class="form-control" id="" name=""
                                value="<?= $data['facturas']['area'] ?? 'N/A'; ?>" disabled>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="" class="form-label">Fecha Registro</label>
                            <input type="text" class="form-control" id="" name=""
                                value="<?= $data['facturas']['fecha_registro'] ?? 'N/A'; ?>" disabled>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="fecha_pago" class="form-label">Fecha Pago</label>
                            <input type="date" class="form-control btn-input" id="fecha_pago" name="fecha_pago"
                                value="<?= $data['facturas']['fecha_pago'] ?? 'N/A'; ?>" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Contraseñas</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <input type="hidden" id="contraseña" value="<?= $data['facturas']['contraseña']; ?>">
                            <table id="tableFacturas" class="display table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>No. Factura</th>
                                        <th>Bien</th>
                                        <th>Valor</th>
                                        <th>Estado</th>
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

    <div class="modal fade" id="corregirModal" tabindex="-1" aria-labelledby="corregirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="correccionContraseña">

                    <input type="hidden" class="form-control" name="contraseña"
                        value="<?= $data['facturas']['contraseña']; ?>">

                    <!-- Encabezado -->
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="corregirModalLabel">
                            Corrección de Contraseña de Pago
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
                                    <input type="text" class="form-control"
                                        value="<?= $data['facturas']['monto_total'] ?? 'N/A'; ?>" disabled>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Área</label>
                                    <input type="text" class="form-control"
                                        value="<?= $data['facturas']['area'] ?? 'N/A'; ?>" disabled>
                                    <input type="hidden" name="area" value="<?= $data['facturas']['id_area']; ?>">
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
                        <!-- Detalles de corrección -->
                        <div>
                            <h6 class="fw-bold text-dark mb-3">Detalles de Corrección</h6>
                            <p class="text-muted small mb-2">
                                Especifique los inconvenientes encontrados en esta contraseña antes de enviarla a
                                corrección:
                            </p>
                            <textarea class="form-control" name="correciones" id="correciones" rows="4"
                                placeholder="Describa los inconvenientes..." required></textarea>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cerrar
                        </button>
                        <button type="submit" class="btn btn-success" data-respuesta="Pendiente">
                            <i class="fas fa-save"></i> Regresar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="descartarModal" tabindex="-1" aria-labelledby="corregirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="descartarContraseña">
                    <input type="hidden" class="form-control" name="contraseña"
                        value="<?= $data['facturas']['contraseña']; ?>">
                    <input type="hidden" class="form-control" name="area" value="<?= $data['facturas']['id_area']; ?>">
                    <!-- Encabezado -->
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="corregirModalLabel">
                            Descartar Contraseña de Pago
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
                                    <input type="text" class="form-control"
                                        value="<?= $data['facturas']['monto_total'] ?? 'N/A'; ?>" disabled>
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
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-save"></i> Descartar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="regresarModal" tabindex="-1" aria-labelledby="corregirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="regresarContraseña">
                    <input type="hidden" class="form-control" name="contraseña"
                        value="<?= $data['facturas']['contraseña']; ?>">
                    <input type="hidden" class="form-control" name="realizador"
                        value="<?= $data['facturas']['realizador']; ?>">
                    <input type="hidden" class="form-control" name="area" value="<?= $data['facturas']['id_area']; ?>">
                    <!-- Encabezado -->
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="corregirModalLabel">
                            Regresar Contraseña a Recepcion
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
                                    <input type="text" class="form-control"
                                        value="<?= $data['facturas']['monto_total'] ?? 'N/A'; ?>" disabled>
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
                        <!-- Detalles de corrección -->
                        <div>
                            <h6 class="fw-bold text-dark mb-3">Detalles de Corrección</h6>
                            <p class="text-muted small mb-2">
                                Especifique los inconvenientes encontrados en esta contraseña antes de enviarla a
                                corrección:
                            </p>
                            <textarea class="form-control" name="correciones" id="correciones" rows="4"
                                placeholder="Describa los inconvenientes..." required></textarea>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cerrar
                        </button>
                        <button type="submit" class="btn btn-success" data-respuesta="Corregir">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="validarModal" tabindex="-1" aria-labelledby="corregirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="validarForm">
                    <input type="hidden" class="form-control" name="contraseña"
                        value="<?= $data['facturas']['contraseña']; ?>">
                    <input type="hidden" class="form-control" name="area" value="<?= $data['facturas']['id_area']; ?>">
                    <input type="hidden" class="form-control" name="area_user"
                        value="<?= $_SESSION['PersonalData']['nombre_completo'] ?>">

                    <!-- Encabezado -->
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="corregirModalLabel">
                            Revision Contraseña de Pago
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
                                    <input type="text" class="form-control"
                                        value="<?= $data['facturas']['monto_total'] ?? 'N/A'; ?>" disabled>
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
                                    <input type="date" class="form-control"
                                        value="<?= $data['facturas']['fecha_pago'] ?? ''; ?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" id="id_proveedor"
                                    value="<?= $data['facturas']['id_proveedor']; ?>">
                                <input type="hidden" id="id_area" value="<?= $data['facturas']['id_area']; ?>">
                                <hr class="my-4">
                                <div class="col-md-12 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="chkAnticipo">
                                            <label class="form-check-label" for="chkAnticipo">Adjuntar Anticipo</label>
                                        </div>
                                    </div>
                                    <select class="form-control selectpicker" id="anticipo" name="anticipo" disabled>
                                    </select>
                                </div>
                            </div>
                            <?php if ($data['facturas']['correcciones'] != null) { ?>
                                <div class="row">
                                    <input type="hidden" id="id_solicitud"
                                        value="<?= $data['facturas']['id_proveedor']; ?>">
                                    <hr class="my-4">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Observación</label>
                                        <div class="form-control bg-light" style="min-height: 80px;">
                                            <?= !empty($data['facturas']['correcciones']) ? nl2br($data['facturas']['correcciones']) : 'Sin observaciones'; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" data-respuesta="Descartado">
                            <i class="fas fa-times"></i> Descartar
                        </button>
                        <button type="submit" class="btn btn-success" data-respuesta="Validado Area">
                            <i class="fas fa-save"></i> Validar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="validarConta" tabindex="-1" aria-labelledby="corregirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="validarContaForm">
                    <input type="hidden" class="form-control" name="contraseña"
                        value="<?= $data['facturas']['contraseña']; ?>">
                    <input type="hidden" class="form-control" name="area" value="<?= $data['facturas']['id_area']; ?>">
                    <input type="hidden" class="form-control" name="conta_user"
                        value="<?= $_SESSION['PersonalData']['nombre_completo'] ?>">
                    <!-- Encabezado -->
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="corregirModalLabel">
                            Revision Contabilidad
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
                                    <input type="text" class="form-control"
                                        value="<?= $data['facturas']['monto_total'] ?? 'N/A'; ?>" disabled>
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
                                    <input type="date" class="form-control"
                                        value="<?= $data['facturas']['fecha_pago'] ?? ''; ?>" disabled>
                                </div>
                            </div>
                            <?php if ($data['facturas']['anticipo'] != null) { ?>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-semibold text-warning">Anticipo cargado</label>
                                        <input type="text" class="form-control bg-light"
                                            value="Proveedor: <?= $data['anticipoinfo']['proveedor']; ?> | No. Transferencia: <?= $data['anticipoinfo']['no_transferencia']; ?> | Fecha: <?= $data['anticipoinfo']['fecha_transaccion']; ?>"
                                            disabled>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" data-respuesta="Descartado">
                            <i class="fas fa-times"></i> Descartar
                        </button>
                        <button type="submit" class="btn btn-success" data-respuesta="Validado Conta">
                            <i class="fas fa-save"></i> Validar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="solicitarFondos" tabindex="-1" aria-labelledby="corregirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="solicitarFondosForm">
                    <input type="hidden" class="form-control" name="contraseña"
                        value="<?= $data['facturas']['contraseña']; ?>">
                    <input type="hidden" class="form-control" name="area" value="<?= $data['facturas']['id_area']; ?>">
                    <input type="hidden" class="form-control" name="solicitante"
                        value="<?= $_SESSION['PersonalData']['nombre_completo'] ?>">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="corregirModalLabel">
                            Solicitud de Fondos - <?= $data['facturas']['area']; ?>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4 border-bottom pb-2">
                            <h5 class="fw-bold mb-0">
                                CONTRASEÑA: <span class="text-dark"><?= $data['facturas']['contraseña']; ?></span>
                            </h5>
                        </div>
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
                                        value="<?= $data['facturas']['monto_total'] ?? 'N/A'; ?>" disabled>
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
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Solicitar Fondos
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
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

    <div class="modal fade" id="impuestoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Revisión Factura</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="DetalleImpuesto">
                    <div class="modal-body">
                        <input type="hidden" id="impuesto_id" name="edit_id">
                        <input type="hidden" id="impuesto_id_regimen">

                        <div class="row">
                            <div class="mb-4 border-bottom pb-2">
                                <h5 class="fw-bold mb-0">Datos Principales</h5>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="impuesto_factura" class="form-label">No. de Factura</label>
                                <input type="text" class="form-control" id="impuesto_factura" name="impuesto_factura"
                                    disabled>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="impuesto_documento" class="form-label">Valor Documento</label>
                                <input type="text" class="form-control" id="impuesto_documento"
                                    name="impuesto_documento" disabled>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="impuesto_servicio" class="form-label">Bien/Servicio</label>
                                <input type="text" class="form-control" id="impuesto_servicio" name="impuesto_servicio"
                                    disabled>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="impuesto_codax" class="form-label">Registro AX</label>
                                <input type="text" class="form-control" id="impuesto_codax" name="edit_codax">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="impuesto_base" class="form-label">Base</label>
                                <input type="text" class="form-control" id="impuesto_base" name="edit_base" readonly>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="impuesto_base_iva" class="form-label">IVA Base</label>
                                <input type="text" class="form-control" id="impuesto_base_iva" name="edit_base_iva"
                                    readonly>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="impuesto_regimen" class="form-label">Régimen</label>
                                <input type="text" class="form-control" id="impuesto_regimen" readonly>
                            </div>

                            <hr>

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

                            <div class="col-md-3 mb-3">
                                <label for="impuesto_reten_iva" class="form-label">Retención IVA</label>
                                <input type="text" class="form-control" id="impuesto_reten_iva" name="edit_reten_iva"
                                    readonly>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="impuesto_reten_isr" class="form-label">Retención ISR</label>
                                <input type="text" class="form-control" id="impuesto_reten_isr" name="edit_reten_isr"
                                    readonly>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="impuesto_fecha_registro" class="form-label">Fecha Registro</label>
                                <input type="text" class="form-control" id="impuesto_fecha_registro"
                                    name="impuesto_fecha_registro" readonly>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="impuesto_total" class="form-label">Total Líquido</label>
                                <input type="text" class="form-control" id="impuesto_total" name="edit_total" readonly>
                            </div>

                            <hr>

                            <div class="col-md-12 mb-3">
                                <label for="impuesto_observacion" class="form-label">Observación</label>
                                <textarea class="form-control" id="impuesto_observacion" name="edit_observacion"
                                    rows="2"></textarea>
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

    <script>
        // IVA
        document.getElementById('check_iva').addEventListener('change', function () {
            const ivaInput = document.getElementById('input_iva');
            ivaInput.disabled = !this.checked;
            if (!this.checked) ivaInput.value = "";
        });

        // ISR
        document.getElementById('check_isr').addEventListener('change', function () {
            const isrInput = document.getElementById('input_isr');
            isrInput.disabled = !this.checked;
            if (!this.checked) isrInput.value = "";
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const totalInput = document.getElementById("impuesto_documento");
            const retenIVAInput = document.getElementById("impuesto_reten_iva");
            const retenISRInput = document.getElementById("impuesto_reten_isr");
            const totalLiquidoInput = document.getElementById("impuesto_total");

            const checkIVA = document.getElementById("check_iva");
            const inputIVA = document.getElementById("input_iva");
            const checkISR = document.getElementById("check_isr");
            const inputISR = document.getElementById("input_isr");

            const regimenInput = document.getElementById("impuesto_id_regimen");

            function obtenerRegimen() {
                return parseInt(regimenInput.value) || 1;
            }

            function calcular() {
                const tipoRegimen = obtenerRegimen();
                let total = parseFloat(totalInput.value) || 0;
                let porcIVA = checkIVA.checked ? parseFloat(inputIVA.value) || 0 : 0;
                let porcISR = checkISR.checked ? parseFloat(inputISR.value) || 0 : 0;

                let base = 0;
                let ivaIncluido = 0;
                let ret_iva = 0;
                let ret_isr = 0;

                if (tipoRegimen === 1) {
                    // Régimen 1: calcular base y IVA
                    base = total / 1.12;
                    ivaIncluido = total - base;

                    if (checkIVA.checked && porcIVA > 0) ret_iva = ivaIncluido * (porcIVA / 100);
                    if (checkISR.checked && porcISR > 0) ret_isr = base * (porcISR / 100);

                    checkISR.disabled = false;
                    inputISR.disabled = !checkISR.checked;
                } else if (tipoRegimen === 2) {
                    // Régimen 2: no separar IVA
                    base = total;
                    ivaIncluido = 0;

                    checkISR.checked = false;
                    checkISR.disabled = true;
                    inputISR.disabled = true;
                    inputISR.value = "";
                    retenISRInput.value = "";

                    if (checkIVA.checked && porcIVA > 0) {
                        ret_iva = base * (porcIVA / 100);
                    }
                }

                // Mostrar base e IVA base
                document.getElementById("impuesto_base").value = base.toFixed(2);
                document.getElementById("impuesto_base_iva").value = ivaIncluido.toFixed(2);

                let totalLiquido = total - ret_iva - ret_isr;
                retenIVAInput.value = ret_iva > 0 ? ret_iva.toFixed(2) : "";
                retenISRInput.value = ret_isr > 0 ? ret_isr.toFixed(2) : "";
                totalLiquidoInput.value = totalLiquido.toFixed(2);
            }

            [checkIVA, checkISR, inputIVA, inputISR, totalInput].forEach(el => {
                if (el) {
                    el.addEventListener("input", calcular);
                    el.addEventListener("change", calcular);
                }
            });

            // Al mostrar el modal, calcular una vez
            const modal = document.getElementById("impuestoModal");
            modal.addEventListener("shown.bs.modal", calcular);
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const chkProveedor = document.getElementById("chkAnticipo");
            const selectProveedor = document.getElementById("anticipo");

            chkProveedor.addEventListener("change", function () {
                selectProveedor.disabled = !this.checked;
            });
        });

    </script>

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

    <div class="modal fade" id="correosModal" tabindex="-1" aria-labelledby="correosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formCorreos">
                    <div class="modal-header">
                        <h5 class="modal-title" id="correosModalLabel">Seleccionar destinatarios de notificación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="tableCorreos" class="table table-bordered table-hover text-center align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Correo</th>
                                        <th>Notificar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí se insertarán las filas con AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="btnEnviarCorreos" class="btn btn-primary">Enviar correos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>