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
                        <?php if ($data['facturas']['estado'] === "Pendiente Contabilidad" && $_SESSION['PersonalData']['area'] == 4 && $data['facturas']['anticipo'] != null) { ?>
                            <button class="btn-warning-circle ms-auto" id="btnValidar" data-bs-toggle="modal"
                                data-bs-target="#anticipoModal" data-id="<?= $data['facturas']['contraseña']; ?>"
                                title="Solicitar Fondos">
                                <i class="fas fa-exclamation"></i>
                            </button>
                        <?php } ?>
                        <?php if ($data['facturas']['estado'] === "Pendiente" || $data['facturas']['estado'] === "Corregido") { ?>
                            <button class="btn btn-success btn-round ms-auto btn-password" id="btnValidar"
                                data-bs-toggle="modal" data-bs-target="#validarModal"
                                data-id="<?= $data['facturas']['contraseña']; ?>">
                                <i class="fas fa-check"></i> Validacion
                            </button>
                            <button class="btn btn-secondary btn-round ms-auto btn-corregir" id="btnCorregir"
                                data-bs-toggle="modal" data-bs-target="#corregirModal"
                                data-id="<?= $data['facturas']['contraseña']; ?>">
                                <i class="fas fa-ban"></i> Corregir
                            </button>
                        <?php } ?>
                        <?php if ($data['facturas']['estado'] === "Pendiente Contabilidad" && $_SESSION['PersonalData']['area'] == 4) { ?>
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
                        <div class="ms-auto">
                            <div class="ms-auto">
                                <?php if ($data['facturas']['estado'] === "Pendiente" || $data['facturas']['estado'] === "Corregido") { ?>
                                    <button class="btn btn-warning btn-round ms-auto btn-password" onclick="toggleInputs()"
                                        id="btnEditar">
                                        <i class="fas fa-pencil-alt"></i> Editar
                                    </button>
                                <?php } ?>
                                <button class="btn btn-danger btn-round ms-auto btn-password" onclick="CancelEdit()"
                                    id="btnCancelar" style="display:none;">
                                    <i class="fas fa-ban"></i> Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <input type="hidden" id="contraseña" value="<?= $data['facturas']['contraseña']; ?>">
                            <table id="tableFacturas" class="display table table-striped table-hover">
                                <thead>
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
                        <button type="submit" class="btn btn-primary">
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

                            <?php if ($data['anticipo']) { ?>
                                <div class="row">
                                    <input type="hidden" id="id_solicitud"
                                        value="<?= $data['facturas']['id_proveedor']; ?>">
                                    <hr class="my-4">
                                    <div class="col-md-12 mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label mb-0">El área tiene un Anticipo sin consumir</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="chkAnticipo">
                                                <label class="form-check-label" for="chkAnticipo">Añadir</label>
                                            </div>
                                        </div>
                                        <select class="form-control selectpicker" id="anticipo" name="anticipo" disabled>
                                        </select>
                                    </div>
                                </div>
                            <?php } ?>
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
                        <button type="submit" class="btn btn-success" data-respuesta="Pendiente Contabilidad">
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
                                <div class="col-md-6 mb-3">
                                    <label for="categoria" class="form-label">Categoría<i
                                            style="color: red;">*</i><i>-</i><i style="color: dimgrey;"> seleccione N/A
                                            si
                                            no es de vehiculos</i>
                                    </label>
                                    <select class="form-select" id="categoria" name="categoria" required>
                                        <option value="" selected disabled>Seleccione una categoría</option>
                                        <option value="N/A">N/A</option>
                                        <option value="Combustible">Combustible</option>
                                        <option value="Servicios">Servicios</option>
                                        <option value="Arrendamiento">Arrendamiento</option>
                                    </select>
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

    <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Revision Factura</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="FacturaEdit">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="edit_id">
                        <div class="row">
                            <div class="col-md-6 mb-12">
                                <label for="nombres" class="form-label">No. de Factura</label>
                                <input type="text" class="form-control" id="edit_factura" name="edit_factura" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nombres" class="form-label">Valor Documento</label>
                                <input type="text" class="form-control" id="edit_documento" name="edit_documento"
                                    readonly>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="nombres" class="form-label">Bien/Servicio</label>
                                <input type="text" class="form-control" id="edit_servicio" name="edit_servicio"
                                    readonly>
                            </div>
                            <div class="col-md- mb-3">
                                <label for="nombres" class="form-label">Estado</label>
                                <select class="form-control valor-select" id="edit_estado" name="edit_estado" required>
                                    <option value="Validado">Validado</option>
                                    <option value="Corregir">Corregir</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="nombres" class="form-label">Observacion</label>
                                <textarea class="form-control" id="comentario_solicitud" name="comentario_solicitud"
                                    rows="3" placeholder="Escribe aquí una descripción detallada..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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