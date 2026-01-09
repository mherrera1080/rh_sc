<?php headerAdmin($data); ?>

<div class="main p-3">

    <div class="page-header">
        <h3 class="fw-bold mb-3">Facturas</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="<?= base_url(); ?>/Dashboard">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#"><?= $_SESSION['PersonalData']['nombre_area'] ?? 'N/A' ?></a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#"> Facturas</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Facturas</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableFacturas" class="display table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Factura</th>
                                    <th>Contraseña</th>
                                    <th>Area</th>
                                    <th>Bien/Servicio</th>
                                    <th>Valor</th>
                                    <th>Total</th>
                                    <th></th>
                                    <th></th>
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

<div class="modal fade" id="editFactura" tabindex="-1" aria-labelledby="editFacturaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editFacturaLabel">Revisión de Factura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="formEditFactura">

                    <!-- ID necesario para actualizar -->
                    <input type="hidden" id="id_detalle" name="id_detalle">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">No. Factura</label>
                            <input type="text" class="form-control" id="no_factura" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Área</label>
                            <input type="text" class="form-control" id="area" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Bien / Servicio</label>
                            <input type="text" class="form-control" id="bien_servicio" disabled>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Valor Documento</label>
                            <input type="text" class="form-control" id="valor_documento" disabled>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Base</label>
                            <input type="text" class="form-control" id="base" disabled>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Retención IVA</label>
                            <input type="text" class="form-control" id="reten_iva" disabled>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Retención ISR</label>
                            <input type="text" class="form-control" id="reten_isr" disabled>
                        </div>
                    </div>

                    <!-- CAMPO EDITABLE -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Código AX</label>
                            <input type="number" class="form-control" id="codigo_ax" name="codigo_ax"
                                placeholder="Ingrese el código AX">
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
                <button type="submit" form="formEditFactura" class="btn btn-primary">
                    Guardar cambios
                </button>
            </div>

        </div>
    </div>
</div>