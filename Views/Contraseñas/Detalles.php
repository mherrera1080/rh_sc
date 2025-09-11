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
                            <button class="btn btn-secondary btn-round ms-auto btn-corregir" id="btnCorregir"
                                data-bs-toggle="modal" data-bs-target="#corregirModal"
                                data-id="<?= $data['facturas']['contraseña']; ?>">
                                <i class="fas fa-ban"></i> Corregir
                            </button>
                        <?php } ?>
                        <?php if ($data['facturas']['estado'] === "Pendiente Contabilidad" ||  $_SESSION['PersonalData']['area'] === 4) { ?>
                            <button class="btn btn-success btn-round ms-auto btn-password" id="btnValidar"
                                data-bs-toggle="modal" data-bs-target="#validarModal"
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
                        <button type="submit" class="btn btn-success" data-respuesta="Pendiente Contabilidad">
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