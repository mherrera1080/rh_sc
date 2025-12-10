<?php headerAdmin($data); ?>

<div class="main p-3">

<div class="page-header d-flex justify-content-end">
    <h2 class="fw-bold">Reportes</h2>
</div>

    <!-- Filtros
    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-2">
                    <label class="form-label">Fecha Creacion</label>
                    <input type="date" id="f_inicio" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Fecha Pago</label>
                    <input type="date" id="f_fin" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Fecha Transaccion</label>
                    <input type="date" id="f_transaccion" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select id="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Aprobado">Aprobado</option>
                        <option value="Rechazado">Rechazado</option>
                    </select>
                </div>

                <div class="col-md-12 text-end">
                    <button class="btn btn-secondary mt-2 me-2" id="btnReset">Limpiar</button>
                    <button class="btn btn-primary mt-2" id="btnFiltrar">Aplicar Filtros</button>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Tabla -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="col-md-3">
                            <label class="form-label">Tipo de Tabla</label>
                            <select id="tipo_tabla" class="form-select">
                                <option value="contraseñas">Contraseñas</option>
                                <option value="solicitudes">Solicitudes de Fondos</option>
                                <option value="facturas">Facturas</option>
                                <option value="anticipos">Anticipos</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableReporte" class="display table table-striped table-hover" style="width:100%">
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