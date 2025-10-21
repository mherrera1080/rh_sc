<?php headerAdmin($data); ?>

<div class="main p-3">

    <div class="page-header">
        <h3 class="fw-bold mb-3"><?= $_SESSION['PersonalData']['nombre_area'] ?? 'N/A' ?></h3>
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
                <div class="card-body border-bottom pb-3">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="filtroArea" class="form-label">Área</label>
                            <select id="filtroArea" class="form-select">
                                <option value="">Todas</option>
                                <option value="Finanzas">Finanzas</option>
                                <option value="Recursos Humanos">Recursos Humanos</option>
                                <option value="Operaciones">Operaciones</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtroEstado" class="form-label">Estado</label>
                            <select id="filtroEstado" class="form-select">
                                <option value="">Todos</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="Aprobado">Aprobado</option>
                                <option value="Rechazado">Rechazado</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtroFecha" class="form-label">Fecha Registro</label>
                            <input type="date" id="filtroFecha" class="form-control">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary w-100" id="btnFiltrar">
                                <i class="fa fa-filter"></i> Filtrar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableFacturas" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Contraseña</th>
                                    <th>Area</th>
                                    <th>Registro</th>
                                    <th>id_proveedor</th>
                                    <th>Total</th>
                                    <th>Fecha Pago</th>
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