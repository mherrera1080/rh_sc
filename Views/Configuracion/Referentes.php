<?php headerAdmin($data); ?>
<div class="main p-3">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-3">Correos - Areas</h3>
            <ul class="breadcrumbs mb-0">
                <li class="nav-home">
                    <a href="<?= base_url(); ?>/Dashboard">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>|
                </li>
                <li class="nav-item">
                    <a href="#">Correos</a>
                </li>
            </ul>
        </div>
        <!-- Botón agregar -->
        <div>
            <button class="btn btn-primary shadow-sm d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#modalNuevoReferente">
                <i class="fas fa-plus me-2"></i> Añadir Referente
            </button>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableReferentes" class="display table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Area</th>
                                    <th>Usuario</th>
                                    <th>Correo</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Datos dinámicos -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php footerAdmin($data); ?>


<!-- Modal Crear Proveedor -->
<div class="modal fade" id="modalNuevoReferente" tabindex="-1" aria-labelledby="modalNuevoProveedorLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalNuevoProveedorLabel">
                    <i class="fas fa-plus-circle me-2"></i> Nueva Referencia
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <form id="formSetReferencia">
                <input type="hidden" name="id_referencia" value=null>
                <input type="hidden" name="estado" value=null>
                <!-- ✅ Una sola modal-body con los campos en columna -->
                <div class="modal-body">
                    <div class="row g-3 flex-column">
                        <div class="col-md-12">
                            <label for="area" class="form-label">Area: <span class="text-danger">*</span></label>
                            <select class="form-control" id="area" name="area" required>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="usuario" class="form-label">Usuario: <span class="text-danger">*</span></label>
                            <select class="form-control" id="usuario" name="usuario" required>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarReferencia" tabindex="-1" aria-labelledby="modalNuevoProveedorLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalNuevoProveedorLabel">
                    <i class="fas fa-plus-circle me-2"></i> Nueva Referencia
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <form id="formEditReferencia">
                <input type="hidden" id="edit_id_referencia" name="id_referencia">
                <!-- ✅ Una sola modal-body con los campos en columna -->
                <div class="modal-body">
                    <div class="row g-3 flex-column">
                        <div class="col-md-12">
                            <label for="area" class="form-label">Area: <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_area" name="area" required>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="usuario" class="form-label">Usuario: <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_usuario" name="usuario" required>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="estado" class="form-label">Estado: <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_estado" name="estado" required>
                                <option value="Activo">Activo</option>
                                <option value="Baja">Baja</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>