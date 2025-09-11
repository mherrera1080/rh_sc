<?php headerAdmin($data); ?>
<div class="main p-3">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-3">Áreas</h3>
            <ul class="breadcrumbs mb-0">
                <li class="nav-home">
                    <a href="<?= base_url(); ?>/Dashboard">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Áreas</a>
                </li>
            </ul>
        </div>
        <!-- Botón de agregar -->
        <div>
            <button class="btn btn-primary  shadow-sm d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#modalNuevaArea">
                <i class="fas fa-plus me-2"></i> Nueva Área
            </button>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableAreas" class="display table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Área</th>
                                    <th>Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se llenan los datos dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php footerAdmin($data); ?>

<!-- Modal Crear Área -->
<div class="modal fade" id="modalNuevaArea" tabindex="-1" aria-labelledby="modalNuevaAreaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalNuevaAreaLabel">
                    <i class="fas fa-plus-circle me-2"></i> Nueva Área
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <form id="formNuevaArea">
                <input type="hidden" name="id_area" value="0">
                <input type="hidden" name="estado" value="Activo">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre_area" class="form-label">Nombre del Área <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre_area" name="nombre_area"
                            placeholder="Ejemplo: Recursos Humanos" required>
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

<!-- Modal Editar Área -->
<div class="modal fade" id="modalEditarArea" tabindex="-1" aria-labelledby="modalEditarAreaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold" id="modalEditarAreaLabel">
                    <i class="fas fa-edit me-2"></i> Editar Área
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formEditarArea">
                <div class="modal-body">
                    <input type="hidden" id="edit_id_area" name="id_area">
                    <div class="mb-3">
                        <label for="edit_nombre_area" class="form-label">Nombre del Área <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre_area" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_estado_area" class="form-label">Estado</label>
                        <select class="form-select" id="edit_estado" name="estado">
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>