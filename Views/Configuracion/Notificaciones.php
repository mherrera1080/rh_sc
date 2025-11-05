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
                    <a href="#">Notificaciones</a>
                </li>
            </ul>
        </div>
        <!-- Botón de agregar -->
        <div>
            <button class="btn btn-primary  shadow-sm d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#modalNuevaArea">
                <i class="fas fa-plus me-2"></i> Nueva Modulo
            </button>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableGrupoCorreos" class="display table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Área</th>
                                    <th>Categoria</th>
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
                    <i class="fas fa-plus-circle me-2"></i> Nuevo Grupo de Correos
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <form id="formGrupoCorreo">
                <input type="hidden" name="id_area" value="0">
                <input type="hidden" name="estado" value="Activo">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre_grupo" class="form-label">Nombre Grupo <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="set_nombre" name="nombre_grupo"
                            placeholder="Ejemplo: Recursos Humanos" required>
                    </div>
                    <div class="mb-3">
                        <label for="areas" class="form-label fw-semibold mb-1 d-block">Area</label>
                        <select class="form-control selectpicker w-100" data-live-search="true" id="set_area"
                            name="area" required title="Seleccione una categoria" data-width="100%">
                        </select>
                        <div class="form-text mt-1">
                            Puedes seleccionar varias áreas (Ej: Finanzas, Recursos Humanos, TI)
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="areas" class="form-label fw-semibold mb-1 d-block">Categoria</label>
                        <select class="form-control selectpicker w-100" data-live-search="true" id="set_categoria"
                            name="categoria" required title="Seleccione una categoria" data-width="100%">
                        </select>
                        <div class="form-text mt-1">
                            Puedes seleccionar varias áreas (Ej: Finanzas, Recursos Humanos, TI)
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

<!-- Modal Editar Área -->
<!-- Modal Crear/Editar Grupo de Correos -->
<div class="modal fade" id="modalGrupoCorreos" tabindex="-1" data-bs-backdrop="static"
    aria-labelledby="modalGrupoCorreosLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-semibold" id="modalGrupoCorreosLabel">
                    <i class="fas fa-envelope me-2"></i>Crear Grupo de Correos
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <form id="formGrupoCorreos">
                <input type="hidden" id="edit_id_grupo" name="id_grupo">
                <div class="modal-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="nombre_grupo" class="form-label fw-semibold mb-1">Nombre del grupo</label>
                            <input type="text" class="form-control" id="edit_nombre_grupo" name="nombre_grupo"
                                placeholder="Ej: Grupo de Autorizaciones de Compras" required>
                        </div>
                        <div class="col-md-6">
                            <label for="areas" class="form-label fw-semibold mb-1 d-block">Área</label>
                            <select class="form-control w-100" data-live-search="true" id="edit_areas" name="areas"
                                required title="Seleccione una o varias áreas" data-width="100%">
                                <!-- Cargar áreas desde tb_areas -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="categoria" class="form-label fw-semibold mb-1 d-block">Categoría</label>
                            <select class="form-control w-100" data-live-search="true" id="edit_categoria"
                                name="categoria" required title="Seleccione una categoría" data-width="100%" disabled>
                                <!-- Cargar categorías desde tb_categoria -->
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div id="contenedorFasesCorreos" style="
                            max-height: 400px; /* Ajusta según necesites */
                            overflow-y: auto;
                            padding-right: 5px; /* opcional, para que no corte contenido con scrollbar */
                        ">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-arrow-left me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Guardar Grupo
                        <span id="spinnerGrupo" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>