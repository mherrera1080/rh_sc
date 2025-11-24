<?php headerAdmin($data); ?>
<div class="main p-3">
  <div class="page-header d-flex justify-content-between align-items-center">
    <div>
      <h3 class="fw-bold mb-3">Proveedores</h3>
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
          <a href="#">Proveedores</a>
        </li>
      </ul>
    </div>
    <!-- Botón agregar -->
    <div>
      <?php if (!empty($_SESSION['permisos'][PROVEEDORES]['crear'])) { ?>
<button class="btn btn-primary shadow-sm d-flex align-items-center" id="btnNuevoProveedor" data-bs-toggle="modal"
        data-bs-target="#modalNuevoProveedor">
        <i class="fas fa-plus me-2"></i> Nuevo Proveedor
      </button>
            <?php } ?>
      
    </div>
  </div>

  <div class="row mt-3">
    <div class="col-md-12">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="table-responsive">
            <table id="tableProveedores" class="display table table-striped table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Proveedor</th>
                  <th>Nombre Social</th>
                  <th>Regimen</th>
                  <th>NIT</th>
                  <th>Fecha Creación</th>
                  <th>Días Crédito</th>
                  <th>Estado</th>
                  <th></th>
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
<div class="modal fade" id="modalNuevoProveedor" tabindex="-1" aria-labelledby="modalNuevoProveedorLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg border-0">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold" id="modalNuevoProveedorLabel">
          <i class="fas fa-plus-circle me-2"></i> Nuevo Proveedor
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="formNuevoProveedor">
        <input type="hidden" name="id_proveedor" value="0">
        <input type="hidden" name="estado" value="Activo">

        <!-- ✅ Una sola modal-body con los campos en columna -->
        <div class="modal-body">
          <div class="row g-3 flex-column">
            <div class="col-12">
              <label for="proveedor" class="form-label">Proveedor <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="nombre_proveedor" required>
            </div>
            <div class="col-12">
              <label for="nombre_social" class="form-label">Nombre Social</label>
              <input type="text" class="form-control" name="nombre_social">
            </div>
            <div class="col-12">
              <label for="nit" class="form-label">NIT</label>
              <input type="text" class="form-control" name="nit_proveedor">
            </div>
            <div class="col-12">
              <label for="dias_credito" class="form-label">Días de Crédito</label>
              <input type="number" class="form-control" name="dias_credito" min="0">
            </div>
            <div class="col-12">
              <label for="filtroEstado" class="form-label">Regimen</label>
              <select id="regimen" class="form-select" name="regimen" required>
                <option value="" disabled selected>Escoga un Regimen</option>
                <option value="1">Régimen General</option>
                <option value="2">Pequeño Contribuyente</option>
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



<!-- Modal Editar Proveedor -->
<div class="modal fade" id="modalEditarProveedor" tabindex="-1" aria-labelledby="modalEditarProveedorLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg border-0">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title fw-bold" id="modalEditarProveedorLabel">
          <i class="fas fa-edit me-2"></i> Editar Proveedor
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="formEditarProveedor">
        <div class="modal-body row g-3">
          <input type="hidden" id="edit_id_proveedor" name="id_proveedor">
          <div class="col-md-6">
            <label for="edit_proveedor" class="form-label">Proveedor <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="edit_nombre_proveedor" name="nombre_proveedor" required>
          </div>
          <div class="col-md-6">
            <label for="edit_nombre_social" class="form-label">Nombre Social</label>
            <input type="text" class="form-control" id="edit_nombre_social" name="nombre_social">
          </div>
          <div class="col-md-6">
            <label for="edit_nit" class="form-label">NIT</label>
            <input type="text" class="form-control" id="edit_nit_proveedor" name="nit_proveedor">
          </div>
          <div class="col-md-6">
            <label for="edit_dias_credito" class="form-label">Días de Crédito</label>
            <input type="number" class="form-control" id="edit_dias_credito" name="dias_credito" min="0">
          </div>
          <div class="col-md-6">
            <label for="edit_regimen" class="form-label">Regimen</label>
            <select class="form-select" id="edit_regimen" name="regimen">
              <option value="1">Régimen General</option>
              <option value="2">Pequeño Contribuyente</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="edit_estado" class="form-label">Estado</label>
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

<script>
    let role_id = <?= json_encode($_SESSION['rol_usuario'] ?? 0); ?>;
    let permisos = <?= json_encode($_SESSION['permisos'] ?? []); ?>;
</script>