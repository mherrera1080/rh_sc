<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['page_title']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/Assets/css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="<?= media(); ?>/images/favicon.ico">
</head>

<body>
    <div class="login-container">
        <h2>Bienvenido</h2>
        <!-- Formulario de Login -->
        <form class="login-form" name="formLogin" id="formLogin">
            <div class="input-group">
                <label for="correo_empresarial">Correo electrónico</label>
                <i class="fas fa-envelope"></i>
                <input type="email" name="correo_empresarial" id="correo_empresarial"
                    placeholder="tucorreo@empresa.com" required>
            </div>
            <button type="submit" id="infoSubmitBtn">
                Ingresar <i class="fas fa-arrow-right"></i>
                <span id="infoSpinner" class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
            </button>
        </form>
    </div>

    <!-- Modal de espera eliminado porque ya no usamos token -->

    <script src="<?= base_url(); ?>/Assets/js/functions_login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const base_url = "<?= base_url(); ?>";
    </script>
</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
