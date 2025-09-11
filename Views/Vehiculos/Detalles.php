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
                    <div class="ms-auto">
                        <?php if ($data['facturas']['estado'] === "Pendiente" || $data['facturas']['estado'] === "Corregido") { ?>
                            <button class="btn btn-success btn-round ms-auto btn-password" id="btnValidar"
                                data-bs-toggle="modal" data-bs-target="#validarModal"
                                data-id="<?= $data['facturas']['contraseña']; ?>">
                                <i class="fas fa-check"></i> Validacion
                            </button>

                            <?php if ($data['facturas']['area'] === "Vehiculos") { ?>
                                <button class="btn btn-danger btn-round ms-auto btn-descartar" id="btnCorregir"
                                    data-bs-toggle="modal" data-bs-target="#descartarModal"
                                    data-id="<?= $data['facturas']['contraseña']; ?>">
                                    <i class="fas fa-ban"></i> Descartar
                                </button>
                            <?php } else { ?>
                                <button class="btn btn-secondary btn-round ms-auto btn-corregir" id="btnCorregir"
                                    data-bs-toggle="modal" data-bs-target="#corregirModal"
                                    data-id="<?= $data['facturas']['contraseña']; ?>">
                                    <i class="fas fa-ban"></i> Corregir
                                </button>
                            <?php } ?>

                        <?php } ?>
                        <?php if ($data['facturas']['estado'] === "Validado" && $data['facturas']['solicitud'] ===  0) { ?>

                            <?php if ($data['facturas']['area'] === "Vehiculos") { ?>
                                <button class="btn btn-success btn-round ms-auto btn-password" id="btnSolicitud"
                                    data-bs-toggle="modal" data-bs-target="#solicitarFondosVehiculos"
                                    data-id="<?= $data['facturas']['contraseña']; ?>">
                                    <i class="fas fa-check"></i> Solicitar Fondos
                                </button>
                                <button class="btn btn-danger btn-round ms-auto btn-descartar" id="btnCorregir"
                                    data-bs-toggle="modal" data-bs-target="#descartarModal"
                                    data-id="<?= $data['facturas']['contraseña']; ?>">
                                    <i class="fas fa-ban"></i> Descartar
                                </button>
                            <?php } else { ?>
                                <button class="btn btn-success btn-round ms-auto btn-password" id="btnSolicitud"
                                    data-bs-toggle="modal" data-bs-target="#solicitarFondosModal"
                                    data-id="<?= $data['facturas']['contraseña']; ?>">
                                    <i class="fas fa-check"></i> Solicitar Fondos
                                </button>
                                <button class="btn btn-danger btn-round ms-auto btn-descartar" id="btnCorregir"
                                    data-bs-toggle="modal" data-bs-target="#descartarModal"
                                    data-id="<?= $data['facturas']['contraseña']; ?>">
                                    <i class="fas fa-ban"></i> Descartar
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
                        <div class="ms-auto">

                            <div class="ms-auto">
                                <?php if ($data['facturas']['estado'] === "Pendiente" || $data['facturas']['estado'] === "Corregido") { ?>
                                    <button class="btn btn-warning btn-round ms-auto btn-password" onclick="toggleInputs()"
                                        id="btnEditar">
                                        <i class="fas fa-pencil-alt"></i> Editar
                                    </button>
                                <?php } ?>
                                <?php if ($data['facturas']['area'] === "Vehiculos") { ?>
                                    <button class="btn btn-info btn-round ms-auto btn-agregar" style="display:none;"
                                        id="btnAgregar" data-bs-toggle="modal" data-bs-target="#agregarFacturaModal"
                                        data-id="<?= $data['facturas']['contraseña']; ?>">
                                        <i class="fas fa-ban"></i> Agregar
                                    </button>
                                <?php } ?>
                                <button class="btn btn-info btn-round ms-auto btn-agregar" style="display:none;"
                                    id="btnAgregar" data-bs-toggle="modal" data-bs-target="#agregarFacturaModal"
                                    data-id="<?= $data['facturas']['contraseña']; ?>">
                                    <i class="fas fa-ban"></i> Agregar
                                </button>
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

    <!-- MODAL ACCIONES -->

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

    <div class="modal fade" id="descartarModal" tabindex="-1" aria-labelledby="corregirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="descartarContraseña">

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
                    </div>
                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cerrar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Descartar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                        <button type="submit" class="btn btn-success" data-respuesta="Validado">
                            <i class="fas fa-save"></i> Validar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="agregarFacturaModal" tabindex="-1" aria-labelledby="detalleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="detalleModalLabel">Agregar Detalle de Solicitud</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>

                <form id="agregarFactura">
                    <div class="modal-body">
                        <input type="hidden" class="form-control" name="contraseña"
                            value="<?= $data['facturas']['contraseña']; ?>">
                        <div class="mb-3">
                            <label for="no_factura" class="form-label">No. Factura</label>
                            <input type="text" class="form-control factura" name="no_factura" required>
                        </div>

                        <div class="mb-3">
                            <label for="bien_servicio" class="form-label">Bien o Servicio</label>
                            <input type="text" class="form-control" name="bien_servicio" required>
                        </div>

                        <div class="mb-3">
                            <label for="valor_documento" class="form-label">Valor Documento</label>
                            <input type="text" class="form-control valor" name="valor_documento" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="solicitarFondosModal" tabindex="-1" aria-labelledby="corregirModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="solicitudForm">
                    <input type="hidden" class="form-control" name="contraseña"
                        value="<?= $data['facturas']['contraseña']; ?>">
                    <input type="hidden" class="form-control" name="area" value="<?= $data['facturas']['area']; ?>">
                    <!-- Encabezado -->
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="corregirModalLabel">
                            Solicitud de Fondos - <?= $data['facturas']['area']; ?>
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

    <div class="modal fade" id="solicitarFondosVehiculos" tabindex="-1" aria-labelledby="corregirModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="solicitudVehiculosForm">
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
                                    <label for="categoria" class="form-label">Categoría<i style="color: red;">*</i>
                                    </label>
                                    <select class="form-select" id="categoria" name="categoria" required>
                                        <option value="" selected disabled>Seleccione una categoría</option>
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