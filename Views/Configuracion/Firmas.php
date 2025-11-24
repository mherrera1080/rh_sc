<?php headerAdmin($data); ?>
<div class="main p-4">
    <!-- Encabezado de página -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-3">Gestion de Firmas</h3>
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
                    <a href="#">Firmas</a>
                </li>
            </ul>
        </div>
        <div>
            <?php if (!empty($_SESSION['permisos'][FIRMAS]['crear'])) { ?>
<button class="btn btn-primary shadow-sm" type="button" data-bs-toggle="modal"
                data-bs-target="#modalGrupoFirmas">
                <i class="fas fa-plus me-1"></i> Nuevo Grupo de Firmas
            </button>
            <?php } ?>
            
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="tableFirmas" class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">ID</th>
                            <th>Nombre</th>
                            <th>Área</th>
                            <th>Categoria</th>
                            <th>Firmas</th>
                            <th>Estado</th>
                            <th class="text-center" style="width: 80px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Cuerpo generado dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php footerAdmin($data); ?>

<div class="modal fade" id="modalGrupoFirmas" tabindex="-1" data-bs-backdrop="static"
    aria-labelledby="modalGrupoFirmasLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-semibold" id="modalGrupoFirmasLabel">
                    <i class="fas fa-file-signature me-2"></i>Crear Grupo de Firmas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <form id="formGrupoFirmas">
                <div class="modal-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="nombre_grupo" class="form-label fw-semibold mb-1">Nombre del grupo</label>
                            <input type="text" class="form-control" id="nombre_grupo" name="nombre_grupo"
                                placeholder="Ej: Grupo de Autorizaciones de Compras" required>
                        </div>

                        <div class="col-md-6">
                            <label for="areas" class="form-label fw-semibold mb-1 d-block">Áreas</label>
                            <select class="form-control selectpicker w-100" data-live-search="true" id="areas"
                                name="areas" required title="Seleccione una o area" data-width="100%">
                            </select>
                            <div class="form-text mt-1">
                                Puedes seleccionar varias áreas (Ej: Finanzas, Recursos Humanos, TI)
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="areas" class="form-label fw-semibold mb-1 d-block">Categoria</label>
                            <select class="form-control selectpicker w-100" data-live-search="true" name="categoria"
                                required title="Seleccione una o area" data-width="100%">
                                <option selected disabled>Selecciona una categoria</option>
                                <option value="Mayor">Mayor Al Limite</option>
                                <option value="Menor">Menor Al Limite</option>
                                <option value="Anticipo">Anticipo</option>
                            </select>
                        </div>
                    </div>

                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-semibold text-secondary">
                                <i class="fas fa-users me-2 text-primary"></i>Usuarios Firmantes
                            </h6>
                            <button type="button" class="btn btn-primary btn-sm" id="btnAgregarFirmante">
                                <i class="fas fa-user-plus me-1"></i>Agregar Firmante
                            </button>
                        </div>
                        <div id="contenedorFirmantes" class="d-flex flex-wrap gap-2 overflow-auto"
                            style="max-height:450px;">
                            <!-- Tarjetas dinámicas -->
                        </div>

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

<div class="modal fade" id="modalGrupoFirmasEdit" tabindex="-1" data-bs-backdrop="static"
    aria-labelledby="modalGrupoFirmasLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-semibold" id="modalGrupoFirmasLabel">
                    <i class="fas fa-file-signature me-2"></i>Editar Grupo de Firmas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <form id="formGrupoFirmasEdit">
                <div class="modal-body">
                    <input type="hidden" id="firmas_eliminadas" name="firmas_eliminadas" value="[]">
                    <input type="hidden" id="id_grupo_edit" name="id_grupo_edit">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="nombre_grupo_edit" class="form-label fw-semibold">Nombre del Grupo</label>
                            <input type="text" class="form-control" id="nombre_grupo_edit" name="nombre_grupo_edit"
                                placeholder="Ej: Grupo de Autorizaciones de Compras" required>
                        </div>
                        <div class="col-md-4">
                            <label for="areas_edit" class="form-label fw-semibold">Área</label>
                            <select class="form-control selectpicker" data-live-search="true" id="areas_edit"
                                name="areas">
                            </select>
                            <div class="form-text mt-1">
                                Selecciona el área correspondiente al grupo.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="areas" class="form-label fw-semibold mb-1 d-block">Categoria</label>
                            <input type="text" class="form-control" id="categoria" name="categoria" readonly>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-semibold text-secondary">
                                <i class="fas fa-users me-2 text-primary"></i>Usuarios Firmantes
                            </h6>
                            <button type="button" class="btn btn-primary btn-sm" id="btnAgregarFirmanteEdit">
                                <i class="fas fa-user-plus me-1"></i>Agregar Firmante
                            </button>
                        </div>

                        <div id="contenedorFirmantesEdit" class="d-flex flex-wrap gap-3 overflow-auto"
                            style="max-height:350px;">
                            <!-- Tarjetas dinámicas -->
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-arrow-left me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Guardar Cambios
                        <span id="spinnerGrupo" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    #contenedorFirmantes .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    #contenedorFirmantes .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
</style>

<script>
    let role_id = <?= json_encode($_SESSION['rol_usuario'] ?? 0); ?>;
    let permisos = <?= json_encode($_SESSION['permisos'] ?? []); ?>;
</script>