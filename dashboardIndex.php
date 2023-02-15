<?php
session_start();
include_once('./modules/getClients.php');
if (!isset($_SESSION['id'])) {
    header("Location: ./dashboard.php?err=2");
}
include('includes/header.php');
include('modules/db.php');

$num_per_page = 20;

if (isset($_GET['page']) && $_GET['page'] >= 1) {
    $page = $_GET['page'];
} else {
    header("Location: dashboardIndex.php?page=1");
    $page = 1;
}

//If the page is 0 then it'll be 0-1*10 = 0 so the first result will be 0 and the last 10
$start_from = ($page - 1) * 20;

$sql = 'SELECT * FROM clients ORDER BY subscriptionDate DESC limit ? , ? ';
$subscribedForno = 1;

?>
<div>

    <div>
        <main class="col-11 m-auto col-lg-9 mt-4">
            <h1>CLUB FORNO</h1>
            <h2>Clientes suscritos: <?php echo $totalClients ?></h2>
            <p>Logueado como: <?php echo $_SESSION['name'] ?>

                <?php if ($_SESSION['level'] == 'read') {
                    echo "(Solo lectura)";
                } ?>
            </p>
            <button id="btn-export-excel" class="mb-3">Exportar tabla a Excel</button>
            <table class="table table-bordered content-table col-12 col-lg-11" id="clientsTable">
                <thead>
                    <tr>
                        <th scope="col" class="d-none exclude">ID</th>
                        <th scope="col" class="fw-bold">Nombre</th>
                        <th scope="col" class="fw-bold">Apellido</th>
                        <!-- <th scope="col" class="fw-bold">Cédula</th> -->
                        <th scope="col" class="fw-bold">Teléfono</th>
                        <th scope="col" class="fw-bold">Correo Electrónico</th>
                        <th scope="col" class="fw-bold">Fecha de suscripción</th>
                        <th scope="col" class="fw-bold">Fecha de nacimiento</th>

                        <?php
                        if ($_SESSION['level'] == 'write') {
                        ?>
                            <th scope="col" class="fw-bold exclude">Acción</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $visibility = 'public';
                    // $stmt = $db->prepare($sql);
                    if (!($stmt = $db->prepare($sql))) {
                        echo "Prepare failed: (" . $db->errno . ") " . $db->error;
                    }

                    // $stmt->bind_param('ii',$start_from,$num_per_page);
                    if (!$stmt->bind_param('ii', $start_from, $num_per_page)) {
                        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                    }
                    // $stmt->execute();
                    if (!$stmt->execute()) {
                        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    }


                    $result = $stmt->get_result();


                    $clients = $result->fetch_all(MYSQLI_ASSOC);

                    if (count($clients) < 1) { ?>
                        <div class="alert alert-warning" role="alert">
                            No se encontraron clientes.
                        </div>

                    <?php }
                    foreach ($clients as $client) { ?>
                        <tr class="table-row">
                            <td class="d-none exclude"><?php echo $client['id'] ?></td>
                            <td data-title="Nombre:"><?php echo $client['firstName'] ?></td>
                            <td data-title="Apellido:"><?php echo $client['lastName'] ?></td>
                            <!-- <td data-title="Cédula:"><?php echo $client['identification'] ?></td> -->
                            <td data-title="Teléfono:"><?php echo $client['phoneNumber'] ?></td>
                            <td data-title="Correo"><?php echo $client['email'] ?></td>
                            <td data-title="Suscripción"><?php echo $client['subscriptionDate'] ?></td>
                            <td data-title="Nacimiento"><?php echo $client['birthdate'] ?></td>

                            <?php if ($_SESSION['level'] == 'write') { ?>
                                <td class="d-flex justify-content-center align-items-center exclude" data-title="Acción"><button class="table-button" data-bs-toggle="modal" data-bs-target="#exampleModal" id="btn-delete-client"><i class="fa-solid fa-trash"></i></button></td>
                            <?php } ?>
                        </tr>
                    <?php }


                    ?>
                </tbody>
            </table>

            <?php
            $sql = 'SELECT COUNT(*) as clientsCount FROM clients WHERE subscribedForno = ?';
            if (!($stmt = $db->prepare($sql))) {
                echo "Prepare failed: (" . $db->errno . ") " . $db->error;
            }

            if (!$stmt->bind_param('i', $subscribedForno)) {
                echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            }

            if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }

            $result = $stmt->get_result();
            $clients = $result->fetch_all(MYSQLI_ASSOC);
            $totalPages = 1;
            $pagesFromDB = (int)ceil($clients[0]['clientsCount'] / $num_per_page);
            $stmt->close();
            if ($pagesFromDB > 1) {
                $totalPages = $pagesFromDB;
            }
            ?>
            <nav aria-label="Page navigation example" id="pagination-demo" class="mb-3 d-flex justify-content-center align-items-center">
                <ul class="pagination w- m-auto">
                    <li class="page-item <?php echo ($page == 1 ? 'disabled' : null) ?>">
                        <a class="page-link link-primary" href="dashboardIndex.php?page=1" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item prev <?php echo ($page == 1 ? 'disabled' : null) ?>"><a class="page-link link-primary" href="dashboardIndex.php?page=<?php echo $page - 1 ?>">Anterior</a></li>

                    <?php if ($_GET['page'] - 5 >= 1) { ?>
                        <li class="page-item disabled"><a class="page-link link-primary">...</a></li>
                    <?php } ?>
                    <!-- pagination before actual page -->
                    <ul class="pagination d-flex flex-row-reverse">


                        <?php for ($i = $_GET['page']; $i > $_GET['page'] - 5; $i--) {
                            if ($i > 0) { ?>
                                <li class="page-item <?php echo ($page == $i ? 'disabled' : null) ?>"><a class="page-link link-primary" href="dashboardIndex.php?page=<?php echo $i ?>"><?php echo $i ?></a></li>
                        <?php }
                        } ?>


                        <?php
                        if ($_GET['page'] > 5) {
                        }
                        ?>
                    </ul>
                    <!-- pagination after actual page -->
                    <ul class="pagination d-flex flex-row">


                        <?php for ($i = $_GET['page'] + 1; $i <= $_GET['page'] + 4; $i++) {

                            if ($i <= $totalPages) {
                        ?>



                                <li class="page-item <?php echo ($page == $i ? 'disabled' : null) ?>"><a class="page-link link-primary" href="dashboardIndex.php?page=<?php echo $i ?>"><?php echo $i ?></a></li>

                        <?php }
                        } ?>


                        <?php

                        ?>
                    </ul>
                    <?php
                    ?>
                    <ul class="pagination">
                        <?php if ($_GET['page'] + 4 < $totalPages) { ?>
                            <li class="page-item disabled"><a class="page-link link-primary">...</a></li>
                        <?php } ?>
                        <li class="page-item next <?php echo ($page == $totalPages ? 'disabled' : null) ?>"><a class="page-link link-primary" href="dashboardIndex.php?page=<?php echo $page + 1 ?>">Siguiente</a></li>
                        <li class="page-item <?php echo ($page == $totalPages ? 'disabled' : null) ?>">
                            <a class="page-link link-primary" href="dashboardIndex.php?page=<?php echo $totalPages ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>

            </nav>
            <a href="modules/logout.php" class="btn btn-danger mb-2" id="btn-cerrar-sesion">Cerrar sesión</a>


        </main>

    </div>
    <input type="hidden" value="$_SESSION['level']" class="user_level">
    <!-- THIS TABLE IS NOT VISIBLE -->
    <table class="d-none" id="full-clients">
        <thead>
            <tr>
                <th scope="col" class="fw-bold">Nombre</th>
                <th scope="col" class="fw-bold">Apellido</th>
                <!-- <th scope="col" class="fw-bold">Cédula</th> -->
                <th scope="col" class="fw-bold">Teléfono</th>
                <th scope="col" class="fw-bold">Correo Electrónico</th>
                <th scope="col" class="fw-bold">Fecha de suscripción</th>
            </tr>
        </thead>
        <tbody class="clients-body">


        </tbody>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar cliente</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Estás seguro de que deseas elminar al cliente?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="btn-delete-confirm">Si, eliminar</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://kit.fontawesome.com/701b1fbb0c.js" crossorigin="anonymous"></script>
        <!-- <script src="public/scripts/dashboard/dashboardEvents.js"></script> -->
        <!-- <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script> -->
        <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
        <script src="js/dashbaordIndex.js"></script>
</div>
</body>

</html>