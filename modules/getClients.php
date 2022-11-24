<?php
    session_start();
    if(!isset($_SESSION['id'])){
        header("Location: ./dashboardLogin.php?err=2");
    }
    include('includes/Header.php');
    include('modules/db.php');

    $num_per_page = 10;

    if(isset($_GET['page']) && $_GET['page'] >= 1){
        $page = $_GET['page'];
    }else{
        header("Location: dashboard.php?page=1");
        $page = 1;
    }

    //If the page is 0 then it'll be 0-1*10 = 0 so the first result will be 0 and the last 10
    $start_from = ($page-1)*10;

    //This it the query to get the events, the first ? is for where to start and the second is for when to end
    $sql = 'SELECT * FROM eventos ORDER BY visibility DESC, created_at DESC limit ? , ?';


?>
<div class="m-0 d-flex flex-row h-100">
<?php include_once('includes/dashboardNavbar.php');
     ?>

    <div class="w-100">
    <?php include_once('includes/dashboardNavbarToggler.php');?>
    <main class="container mt-4 col-10 d-flex flex-column align-items-start">
        <h1 class="primary">Eventos</h1>
        <a class="quick-actions-btn d-block" href="dashboardCreateEvent.php"><i class="fa-solid fa-bullhorn"></i>Crear evento</a>

        <p class="text-muted">Haz click en un evento para editarlo o eliminarlo</p>
        <table class="table table-bordered content-table" id="eventsTable">
            <thead>
                <tr>
                <th scope="col"  class="d-none">#</th>
                <th scope="col" class="fw-bold">Título</th>
                <th scope="col" class="fw-bold">Contenido</th>
                <th scope="col" class="fw-bold">Fecha de creación</th>
                <th scope="col" class="fw-bold">Visibilidad</th>
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
                    if (!$stmt->bind_param('ii',$start_from,$num_per_page)) {
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
                            No se encontraron eventos.
                        </div>
                    <?php }
                    foreach($events as $event){ 
                    $eventContent = (strlen($event['content']) > 50 ? substr($event['content'],0,50).'...' : $event['content']);?>
                            <tr class="table-row">
                                <td class="d-none"><?php echo $event['id']?></td>
                                <td data-title="Título:"><?php echo $event['title']?></td>
                                <td data-title="Contenido:"><?php echo $eventContent ?></td>
                                <td data-title="Fecha de creación:"><?php echo $event['created_at']?></td>
                                <?php 
                                if($event['visibility'] == 'public'){?>
                                    <td data-title="Visibilidad:" class="text-success">Público</td>

                                <?php }else{?>
                                    <td data-title="Visibilidad:" class="text-danger">Privado</td>

                                    <?php } ?>
                            </tr>
                        <?php } 

                    
                ?>
            </tbody>
            </table>

            <?php
                $sql = 'SELECT COUNT(*) as eventosCount FROM eventos ';
                $visibility = 'public';
                if (!($stmt = $db->prepare($sql))) {
                    echo "Prepare failed: (" . $db->errno . ") " . $db->error;
                }
                
                // if (!$stmt->bind_param('s',$visibility)) {
                //     echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                // }
               
                if (!$stmt->execute()) {
                    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                }
                // $row = $result->fetch_assoc();
                // mysqli_free_result($result);
                // mysqli_close($db);

                $result = $stmt->get_result();
                $events = $result->fetch_all(MYSQLI_ASSOC);
                $totalPages = 1;
                $pagesFromDB = (int)ceil($events[0]['eventosCount']/$num_per_page);
                if($pagesFromDB > 1){
                    $totalPages = $pagesFromDB;
                }
                ?>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                    <li class="page-item <?php echo ($page == 1 ? 'disabled' : null)?>"><a class="page-link primary" href="dashboardEvents.php?page=<?php echo $page-1?>">Anterior</a></li>
                        <?php for($i = 1; $i <= $totalPages; $i++){?>
                        
                        <li class="page-item <?php echo($page == $i ? 'disabled' : null)?>"><a class="page-link primary" href="dashboardEvents.php?page=<?php echo $i ?>"><?php echo $i?></a></li>
                       
                        <?php }?>
                        <li class="page-item <?php echo ($page == $totalPages ? 'disabled' : null)?>"><a class="page-link primary" href="dashboardEvents.php?page=<?php echo $page+1?>">Siguiente</a></li>
                    </ul>
                </nav>
                    
                
    </main></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="public/scripts/dashboard/dashboardEvents.js"></script>

</div>
</body>
</html>