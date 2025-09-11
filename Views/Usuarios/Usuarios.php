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
                        <h4 class="card-title">Add Row</h4>
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
                            <tfoot>
                                <tr>
                                    <th>Id</th>
                                    <th>Identificacion</th>
                                    <th>No Empleado</th>
                                    <th>Nombre Completo</th>
                                    <th>Correo</th>
                                    <th>Estado</th>
                                </tr>
                            </tfoot>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="setUsuarios">
                <input type="hidden" id="id_usuario" name="id_usuario" value="0">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="setUserModalLabel">Agregar Usuario</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombres" class="form-label"> Nombres<i style="color: red;">*</i></label>
                        <input type="text" class="form-control" id="set_nombres" name="set_nombres" required>
                    </div>
                    <div class="mb-3">
                        <label for="primer_apellido" class="form-label"> Primer Apellido<i style="color: red;">*</i></label>
                        <input type="text" class="form-control" id="set_primer_apellido" name="set_primer_apellido" required>
                    </div>
                    <div class="mb-3">
                        <label for="segundo_apellido" class="form-label"> Segundo Apellido<i style="color: red;">*</i></label>
                        <input type="text" class="form-control" id="set_segundo_apellido" name="set_segundo_apellido" required>
                    </div>
                    <div class="mb-3">
                        <label for="identificacion" class="form-label"> Identificacion<i style="color: red;">*</i></label>
                        <input type="text" class="form-control" id="set_identificacion" name="set_identificacion" required>
                    </div>
                    <div class="mb-3">
                        <label for="codigo_empleado" class="form-label"> Cod. Empleado </label>
                        <input type="text" class="form-control" id="set_codigo" name="set_codigo"
                            placeholder="En caso de no tener, dejar vacio el campo">
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label"> Correo<i style="color: red;">*</i></label>
                        <input type="email" class="form-control" id="set_correo" name="set_correo" required>
                    </div>
                    <div class="mb-3">
                        <label for="rol" class="form-label"> Rol<i style="color: red;">*</i></label>
                        <input type="text" class="form-control" id="set_rol" name="set_rol" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>