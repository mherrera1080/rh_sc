<?php headerAdmin($data); ?>
<div class="main p-3">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-3">Panel Usuarios</h3>
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
                    <a href="#">Usuarios</a>
                </li>
            </ul>
        </div>
        <div>
            <?php if (!empty($_SESSION['permisos'][USUARIOS]['crear'])) { ?>
                <button class="btn btn-primary  shadow-sm d-flex align-items-center" data-bs-toggle="modal"
                    data-bs-target="#setUserModal">
                    <i class="fa fa-plus"></i>
                    Agregar Usuario
                </button>
            <?php } ?>

        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableUsuarios" class="display table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Id</th>
                                    <th>Identificacion</th>
                                    <th>No Empleado</th>
                                    <th>Nombre Completo</th>
                                    <th>Correo</th>
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

<!-- Modal -->
<div class="modal fade" id="setUserModal" tabindex="-1" aria-labelledby="setUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            <form id="setUsuarios">
                <input type="hidden" id="id_usuario" name="id_usuario" value="0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="setUserModalLabel">
                        <i class="bi bi-person-plus-fill me-2"></i>Agregar Usuario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="set_nombres" class="form-label fw-semibold">Nombres <i
                                        class="text-danger">*</i></label>
                                <input type="text" class="form-control" id="set_nombres" name="set_nombres"
                                    placeholder="Ej. Juan Carlos" required>
                            </div>

                            <div class="col-md-6">
                                <label for="set_identificacion" class="form-label fw-semibold">Identificación <i
                                        class="text-danger">*</i></label>
                                <input type="text" class="form-control" id="set_identificacion"
                                    name="set_identificacion" placeholder="Ej. 12345678" required>
                            </div>

                            <div class="col-md-6">
                                <label for="set_primer_apellido" class="form-label fw-semibold">Primer Apellido <i
                                        class="text-danger">*</i></label>
                                <input type="text" class="form-control" id="set_primer_apellido"
                                    name="set_primer_apellido" placeholder="Ej. López" required>
                            </div>

                            <div class="col-md-6">
                                <label for="set_segundo_apellido" class="form-label fw-semibold">Segundo Apellido <i
                                        class="text-danger">*</i></label>
                                <input type="text" class="form-control" id="set_segundo_apellido"
                                    name="set_segundo_apellido" placeholder="Ej. Gómez" required>
                            </div>

                            <div class="col-md-6">
                                <label for="set_codigo" class="form-label fw-semibold">Código Empleado</label>
                                <input type="text" class="form-control" id="set_codigo" name="set_codigo"
                                    placeholder="Opcional">
                            </div>

                            <div class="col-md-6">
                                <label for="set_correo" class="form-label fw-semibold">Correo Empresarial <i
                                        class="text-danger">*</i></label>
                                <input type="email" class="form-control" id="set_correo" name="set_correo"
                                    placeholder="nombre@empresa.com" required>
                            </div>

                            <div class="col-md-6">
                                <label for="set_area" class="form-label fw-semibold">Área <i
                                        class="text-danger">*</i></label>
                                <select class="form-select selectpicker w-100" data-live-search="true" id="set_area"
                                    name="set_area" required title="Seleccione un área" data-width="100%">
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_correo" class="form-label fw-semibold">Rol <i
                                        class="text-danger">*</i></label>
                                <select class="form-select selectpicker w-100" data-live-search="true" id="set_rol"
                                    name="set_rol" required title="Seleccione un rol" data-width="100%">
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="set_password" class="form-label fw-semibold">Contraseña <i
                                        class="text-danger">*</i></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="set_password" name="set_password"
                                        placeholder="Ingrese una contraseña" required>
                                    <button class="btn btn-outline-secondary togglePassword" type="button">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="set_confirm_password" class="form-label fw-semibold">Confirmar Contraseña <i
                                        class="text-danger">*</i></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="set_confirm_password"
                                        name="set_confirm_password" placeholder="Confirme la contraseña" required>
                                    <button class="btn btn-outline-secondary togglePassword" type="button">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="updateUser" tabindex="-1" aria-labelledby="updateUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            <form id="editUsuarios">
                <input type="hidden" id="edit_usuario" name="id_usuario">

                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-semibold" id="updateUserLabel">
                        <i class="bi bi-pencil-square me-2"></i>Editar Información del Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_nombres" class="form-label fw-semibold">Nombres <i
                                        class="text-danger">*</i></label>
                                <input type="text" class="form-control" id="edit_nombres" name="set_nombres"
                                    placeholder="Ej. Juan Carlos" required>
                            </div>

                            <div class="col-md-6">
                                <label for="edit_identificacion" class="form-label fw-semibold">Identificación <i
                                        class="text-danger">*</i></label>
                                <input type="text" class="form-control" id="edit_identificacion"
                                    name="set_identificacion" placeholder="Ej. 12345678" required>
                            </div>

                            <div class="col-md-6">
                                <label for="edit_primer_apellido" class="form-label fw-semibold">Primer Apellido <i
                                        class="text-danger">*</i></label>
                                <input type="text" class="form-control" id="edit_primer_apellido"
                                    name="set_primer_apellido" placeholder="Ej. López" required>
                            </div>

                            <div class="col-md-6">
                                <label for="edit_segundo_apellido" class="form-label fw-semibold">Segundo Apellido <i
                                        class="text-danger">*</i></label>
                                <input type="text" class="form-control" id="edit_segundo_apellido"
                                    name="set_segundo_apellido" placeholder="Ej. Gómez" required>
                            </div>

                            <div class="col-md-6">
                                <label for="edit_codigo" class="form-label fw-semibold">Código Empleado</label>
                                <input type="text" class="form-control" id="edit_codigo" name="set_codigo"
                                    placeholder="Opcional">
                            </div>

                            <div class="col-md-6">
                                <label for="edit_correo" class="form-label fw-semibold">Correo Empresarial <i
                                        class="text-danger">*</i></label>
                                <input type="email" class="form-control" id="edit_correo" name="set_correo"
                                    placeholder="nombre@empresa.com" required>
                            </div>

                            <div class="col-md-6">
                                <label for="edit_area" class="form-label fw-semibold">Área <i
                                        class="text-danger">*</i></label>
                                <select class="form-select selectpicker w-100" data-live-search="true" id="edit_area"
                                    name="set_area" required title="Seleccione un área" data-width="100%">
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="edit_correo" class="form-label fw-semibold">Rol <i
                                        class="text-danger">*</i></label>
                                <select class="form-select selectpicker w-100" data-live-search="true" id="edit_rol"
                                    name="set_rol" required title="Seleccione un área" data-width="100%">
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="set_password" class="form-label fw-semibold">Contraseña <i
                                        class="text-danger">*</i></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="edit_password" name="set_password"
                                        placeholder="Ingrese una contraseña">
                                    <button class="btn btn-outline-secondary togglePassword" type="button">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="set_confirm_password" class="form-label fw-semibold">Confirmar Contraseña <i
                                        class="text-danger">*</i></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="edit_confirm_password"
                                        name="set_confirm_password" placeholder="Confirme la contraseña">
                                    <button class="btn btn-outline-secondary togglePassword" type="button">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning text-dark fw-semibold">
                        <i class="bi bi-save2 me-1"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    document.querySelectorAll('.togglePassword').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    });
</script>

<script>
    let role_id = <?= json_encode($_SESSION['rol_usuario'] ?? 0); ?>;
    let permisos = <?= json_encode($_SESSION['permisos'] ?? []); ?>;
</script>