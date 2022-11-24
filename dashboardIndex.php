<?php
    session_start();
    if(!isset($_SESSION['id'])){
        header("Location: ./dashboardLogin.php?err=2");
    }
    include('includes/header.php');
    include('modules/db.php');

    $num_per_page = 10;

    if(isset($_GET['page']) && $_GET['page'] >= 1){
        $page = $_GET['page'];
    }else{
        header("Location: dashboardIndex.php?page=1");
        $page = 1;
    }

    //If the page is 0 then it'll be 0-1*10 = 0 so the first result will be 0 and the last 10
    $start_from = ($page-1)*10;

    $sql = 'SELECT * FROM clients WHERE subscribedForno = ? limit ? , ?';
    $subscribedForno = 1;

?>
<div>

    <div>
    <main class="col-11 m-auto col-lg-9 mt-4">
        <h1>CLUB FORNO</h1>
        <h2>Clientes suscritos</h2>
        <table class="table table-bordered content-table" id="eventsTable">
            <thead>
                <tr>
                <th scope="col"  class="d-none">#</th>
                <th scope="col" class="fw-bold">Nombre</th>
                <th scope="col" class="fw-bold">Apellido</th>
                <th scope="col" class="fw-bold">Cédula</th>
                <th scope="col" class="fw-bold">Teléfono</th>
                </tr>
            </thead>
            <tbody >
                <?php
                
                    $visibility = 'public';
                    // $stmt = $db->prepare($sql);
                    if (!($stmt = $db->prepare($sql))) {
                        echo "Prepare failed: (" . $db->errno . ") " . $db->error;
                    }

                    // $stmt->bind_param('ii',$start_from,$num_per_page);
                    if (!$stmt->bind_param('iii',$subscribedForno,$start_from,$num_per_page)) {
                        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                    }
                    // $stmt->execute();
                    if (!$stmt->execute()) {
                        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    }
                    $result = $stmt->get_result();
                    $events = $result->fetch_all(MYSQLI_ASSOC);
                    if(count($events) < 1){ ?>
                        <div class="alert alert-warning" role="alert">
                            No se encontraron clientes.
                        </div>
                    <?php }
                    foreach($events as $event){ ?>
                            <tr class="table-row">
                                <td class="d-none"><?php echo $event['id']?></td>
                                <td data-title="Nombre:"><?php echo $event['firstName']?></td>
                                <td data-title="Apellido:"><?php echo $event['lastName']?></td>
                                <td data-title="Cédula:"><?php echo $event['identification']?></td>
                                <td data-title="Teléfono:"><?php echo $event['phoneNumber']?></td>
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

                if (!$stmt->bind_param('i',$subscribedForno)) {
                    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                }
               
                if (!$stmt->execute()) {
                    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                }

                $result = $stmt->get_result();
                $clients = $result->fetch_all(MYSQLI_ASSOC);
                $totalPages = 1;
                $pagesFromDB = (int)ceil($clients[0]['clientsCount']/$num_per_page);
                if($pagesFromDB > 1){
                    $totalPages = $pagesFromDB;
                }
                echo $clients[0]['clientsCount'];
                echo $pagesFromDB;
                echo $totalPages;
                ?>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                    <li class="page-item <?php echo ($page == 1 ? 'disabled' : null)?>">
                        <a class="page-link" href="dashboardIndex.php?page=1" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item <?php echo ($page == 1 ? 'disabled' : null)?>"><a class="page-link link-primary" href="dashboardIndex.php?page=<?php echo $page-1?>">Anterior</a></li>
                        <?php for($i = 1; $i <= $totalPages; $i++){?>
                        
                        <li class="page-item <?php echo($page == $i ? 'disabled' : null)?>"><a class="page-link link-primary" href="dashboardIndex.php?page=<?php echo $i ?>"><?php echo $i?></a></li>
                    
                        <?php }?>
                        <li class="page-item <?php echo ($page == $totalPages ? 'disabled' : null)?>"><a class="page-link link-primary" href="dashboardIndex.php?page=<?php echo $page+1?>">Siguiente</a></li>
                        <li class="page-item <?php echo ($page == $totalPages ? 'disabled' : null)?>">
                            <a class="page-link" href="dashboardIndex.php?page=<?php echo $totalPages?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                    
                </nav>
                    
                
    </main></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://kit.fontawesome.com/701b1fbb0c.js" crossorigin="anonymous"></script>
    <script src="public/scripts/dashboard/dashboardEvents.js"></script>

</div>
</body>
</html>