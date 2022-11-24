<?php
    $title = "Login Club Forno";
    include_once('includes/header.php');
// foo.php
$errors = array (
    1 => "Nombre de usuario o contraseña incorrecto.",
    2 => "Inicia sesión para acceder al sistema."
);

$error_id = isset($_GET['err']) ? (int)$_GET['err'] : 0;

?>
<main class="container mt-4">
    <div class="row justify-content-center">
        <h2 class="text-center mb-4">CLUB FORNO</h2>
        

        <form class="col-10 col-lg-4 col-md-6" action="modules/authenticate.php" method="POST">
            <?php if($error_id !== 0){?>
                <div class="alert alert-danger" role="alert">
                    <?php echo($errors[$error_id]);?>
                </div>
            <?php } ?>
            <div class="mb-3">
                <label for="username" class="form-label">Usuario:</label>
                <input type="text" class="form-control" id="username" name="username" >
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div>
            <button type="submit" class="btn btn-primary primary w-100">Submit</button>
            </div>
        </form> 
    </div>

</main>
    
</body>
</html>