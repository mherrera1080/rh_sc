<?php headerAdmin($data); ?>

<div class="main p-3">

    <div class="page-header">
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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                            data-bs-target="#setUserModal">
                            <i class="fa fa-plus"></i>
                            Agregar Usuario
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableUsuarios" class="display table table-striped table-hover">
                            <thead>
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
                                <label for="set_password" class="form-label fw-semibold">Contraseña <i
                                        class="text-danger">*</i></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="set_password" name="set_password"
                                        placeholder="Ingrese una contraseña" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye"></i>
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
                                <label for="edit_correo" class="form-label fw-semibold">Estado <i
                                        class="text-danger">*</i></label>
                                <select class="form-select selectpicker w-100" data-live-search="true" id="edit_estado"
                                    name="set_area" required title="Seleccione un área" data-width="100%">
                                    <option value="Activo">Activo</option>
                                    <option value="Baja">Baja</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="edit_password" class="form-label fw-semibold">Nueva Contraseña</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="edit_password" name="set_password"
                                        placeholder="Opcional, solo si desea cambiarla">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleEditPassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Déjelo vacío si no desea cambiar la contraseña.</small>
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
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('set_password');
        const icon = this.querySelector('i');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    });
</script>