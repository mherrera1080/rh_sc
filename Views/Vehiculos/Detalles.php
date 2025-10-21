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
                        <?php if ($data['facturas']['estado'] === "Validado" ) { ?>
                            <button class="btn btn-success btn-round ms-auto btn-password" id="btnSolicitud"
                                data-bs-toggle="modal" data-bs-target="#solicitarFondosVehiculos"
                                data-id="<?= $data['facturas']['contraseña']; ?>">
                                <i class="fas fa-check"></i> Solicitar Fondos
                            </button>
                        <?php } ?>
                        <?php if ($data['facturas']['estado'] === "Pendiente" && $_SESSION['PersonalData']['area'] == 4
                        || $data['facturas']['estado'] === "Corregido" && $_SESSION['PersonalData']['area'] == 4) { ?>
                            <button class="btn btn-success btn-round ms-auto btn-password" id="btnValidar"
                                data-bs-toggle="modal" data-bs-target="#validarModal"
                                data-id="<?= $data['facturas']['contraseña']; ?>">
                                <i class="fas fa-check"></i> Validacion
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
                                <?php if ($data['facturas']['estado'] === "Corregir") { ?>
                                    <button class="btn btn-info btn-round ms-auto btn-agregar" style="display:none;"
                                        id="btnAgregar" data-bs-toggle="modal" data-bs-target="#agregarFacturaModal"
                                        data-id="<?= $data['facturas']['contraseña']; ?>">
                                        <i class="fas fa-ban"></i> Agregar
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <input type="hidden" id="contraseña" value="<?= $data['facturas']['contraseña']; ?>">
                            <input type="hidden" id="usuario" value="<?= $_SESSION['PersonalData']['area']; ?>">
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
                        <button type="submit" class="btn btn-danger" data-respuesta="Corregir">
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
                        <button type="submit" class="btn btn-success" data-respuesta="Finalizado">
                            <i class="fas fa-save"></i> Validar
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
            const totalInput = document.getElementById("edit_documento");
            const retenIVAInput = document.getElementById("edit_reten_iva");
            const retenISRInput = document.getElementById("edit_reten_isr");
            const totalLiquidoInput = document.getElementById("edit_total");

            const checkIVA = document.getElementById("check_iva");
            const inputIVA = document.getElementById("input_iva");
            const checkISR = document.getElementById("check_isr");
            const inputISR = document.getElementById("input_isr");

            const regimenInput = document.getElementById("edit_id_regimen");

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
                document.getElementById("edit_base").value = base.toFixed(2);
                document.getElementById("edit_base_iva").value = ivaIncluido.toFixed(2);

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
            const modal = document.getElementById("editarModal");
            modal.addEventListener("shown.bs.modal", calcular);
        });
    </script>