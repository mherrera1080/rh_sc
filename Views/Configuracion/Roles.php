<?php headerAdmin($data); ?>
<div class="main p-3">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-3">Roles</h3>
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
                    <a href="#">Roles</a>
                </li>
            </ul>
        </div>
        <!-- Botón de agregar -->
        <div>
            <button class="btn btn-primary  shadow-sm d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#modalnuevoRol">
                <i class="fas fa-plus me-2"></i> Agregar Rol
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableRoles" class="display table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Modulo</th>
                                    <th>Estado</th>
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

<div class="modal fade" id="modalnuevoRol" tabindex="-1" aria-labelledby="modalnuevoRolLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalnuevoRolLabel">
                    <i class="fas fa-plus-circle me-2"></i> Nuevo Rol
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <form id="formRoles">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre_area" class="form-label">Nombre del rol <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre_rol" name="nombre_rol"
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

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editPermissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPermissionsModalLabel">Editar Permisos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- ID y Nombre del Rol -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="edit_role_name" class="form-label">Nombre del Rol</label>
                        <input type="text" class="form-control" id="edit_role_name" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="edit_id" class="form-label">ID del Rol</label>
                        <input type="text" class="form-control" id="edit_id" readonly>
                    </div>
                </div>

                <!-- Permisos por Modulo en una Tabla Horizontal -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Módulo</th>
                                <th>Acceder</th>
                                <th>Crear</th>
                                <th>Editar</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody id="editForm">
                            <!-- Las filas se llenarán dinámicamente con JS -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="submit">Guardar Cambios</button>
            </div>

        </div>
    </div>
</div>

<script>
    let role_id = <?= json_encode($_SESSION['rol_usuario'] ?? 0); ?>;
    let permisos = <?= json_encode($_SESSION['permisos'] ?? []); ?>;
</script>