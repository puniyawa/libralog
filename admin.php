<?php
include 'function/db_conn.php';
if(isset($_GET['search'])){
    $search = strval($_GET['search']);
    $dateToday = date("Y-m-d H:i:sa");
    if(strtolower($search) == 'true'){
        $sql = "SELECT * FROM `log` INNER JOIN `users` ON log.uid = users.uid WHERE isReturned=1";
    }
    else if(strtolower($search) == 'false'){
        $sql = "SELECT * FROM `log` INNER JOIN `users` ON log.uid = users.uid WHERE isReturned=0 AND '$dateToday' < dueDate";
    }
    else if($search[0] == '#'){
        $searchID = substr($search, 1);
        $sql = "SELECT * FROM `log` INNER JOIN `users` ON log.uid = users.uid WHERE log.uid='$searchID'";
    }
    else{
       $sql = "SELECT * FROM `log` INNER JOIN `users` ON log.uid = users.uid WHERE concat(`firstName`, `middleName`, `lastName`, `sex`, `studentID`, `dep`, `gradeYear`, `section`, `dateOfBorrowing`, `dueDate`, `dateReturned`, `isbn`, `isReturned`) LIKE '%$search%'"; 
    }
    
}
else{
    $sql = "SELECT * FROM `log` INNER JOIN `users` ON log.uid = users.uid";
}
if(isset($_GET['filter'])){
    $filter = $_GET['filter'];
    $dateToday = date("Y-m-d H:i:sa");
    if($filter == 'late'){
        $sql = "SELECT * FROM `log` INNER JOIN `users` ON log.uid = users.uid WHERE isReturned=0 AND '$dateToday' > dueDate";
    }
}
if(isset($_GET['beforeDate'])){
    $dateToday = date("Y-m-d H:i:sa");
    $beforeDate = $_GET['date'];
    $sql = "SELECT * FROM `log` INNER JOIN `users` ON log.uid = users.uid WHERE '$dateToday' > dueDate";

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins"/>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/index.css">
    <title>LibraLog</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php"><h1 style="color: #3C573A;"><i class="fa-solid fa-book fa" style="color: #3C573A; padding-right: 20px; padding-left: 10px;"></i><b>LibraLog</b></h1></a>
            <div class="justify-content-end" style="padding-right: 10px;" id="navbarNavDropdown">
                <div class="d-flex align-items-center">
                    <!-- <form class="d-flex" role="search">
                        <div class="search-bar">
                            <i class="fa-solid fa-magnifying-glass me-2 ms-1"></i>
                            <input class="input-default" type="search" name="search" placeholder="Search" required>
                        </div>
                    </form> -->
                    <form role="search">
                        <div class="d-flex">
                            <input type="search" class="form-control" name="search" placeholder="Search" required>
                            <button type="submit" class="search-btn ms-2"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </form>    
                    <div class="dropdown">
                        <div class="nav-icon ms-2">
                            <i class="fa-solid fa-filter"></i>
                        </div>
                        <ul class="dropdown-content">
                            <li>
                                <a class="dropdown-item" href="admin.php?search=true" class="link-dark">Returned</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="admin.php?search=false" class="link-dark">Not Returned</a>                                         
                            </li>
                            <li>
                                <a class="dropdown-item" href="admin.php?filter=late" class="link-dark">Late</a>                                         
                            </li>
                        </ul>                                
                    </div>
                    <div class="dropdown">
                        <div class="nav-icon ms-2">
                            <i class="fa-solid fa-bars"></i>
                        </div>
                        <ul class="dropdown-content" style="min-width:200px;">
                            <li>
                                <a href="form/add_user.php" class="nav-link active p-3"><i class="fa-solid fa-plus pe-2"></i>Add</a>
                            </li>
                            <li>
                                <a class="nav-link active p-3" href="form/returned.php"><i class="fa-solid fa-right-from-bracket pe-2"></i>Add Returned Book Log</a>                                        
                            </li>
                            <li>
                                <a href="data_table.php" class="nav-link active p-3" aria-current="page"><i class="fa-solid fa-pen-to-square pe-2"></i>Edit</a>                             
                            </li>
                           
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="nav-link active p-3" href="form/clearance.php"><i class="fa-solid fa-check pe-2"></i>Student Library Clearance Checker</a>                                       
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="nav-link active p-3" href="form/user_info.php"><i class="fa-regular fa-user pe-2"></i>Student Borrower Info</a>
                            </li>
                            <li>
                                <a class="nav-link active p-3" href="form/book.php"><i class="fa-solid fa-book pe-2"></i>View Book Info</a>                                       
                            </li>
                        </ul>                                
                    </div>
                    
                   
                </div>                 
            </div>
        </div>
  
           
        
    </nav>
    <div class="container-fluid">        
        <div class="row">
            <div class="col-12">
                <?php
                    if(isset($_GET['msg'])){
                        $msg = $_GET['msg'];
                        echo 
                        '<div class="alert alert-warning alert-dismissible fade show" role="alert" style="margin-top: 20px;">
                            '.$msg.'
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                    }
                    ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="shadow-lg rounded p-3 m-5" style="background-color: white;">
                    <div class="row shadow-sm border-none rounded p-3 m-2 color-danger">                       
                            <div class="col-1">UID</div>
                            <div class="col">Name</div>
                            <div class="col">Student ID</div>
                            <div class="col-1">Sex</div>
                            <div class="col">Grade/ Year and Section</div>   
                            <div class="col">ISBN Number</div>  
                            <div class="col">Due In</div>    
                            <div class="col">Status</div>               
                    </div>
                    <?php                                                 
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($result)){
                            // Calculate the Status
                            $origin = date_create(date('Y-m-d H:i:s'));
                            $target = date_create(date('Y-m-d H:i:s',strtotime($row['dueDate'])));                        
                            $interval = date_diff($origin, $target);
                            $dueIn = $interval->format('%a days <br> %H:%I:%S');
                        
                            if ($interval->format('%R%a') > 0){
                                $dueIn = $interval->format('%a days');
                                $statusFromCalcDate = "Not Returned";
                                
                                
                            }
                            else if ($interval->format('%R%a') == 0){
                                $dueIn = 'Due Today';
                                $statusFromCalcDate = "Not Returned";
                            }
                            else if ($interval->format('%R%a') < 0){
                                $dueIn = $interval->format('Late of %a days');
                                $statusFromCalcDate = "Late";
                            }   
                            else{
                                $dueIn = '';
                            }
                        ?>

                        <a class="nav-link" data-bs-toggle="modal" data-bs-target="#userInfo-<?php echo $row['uid']?>" >
                            <div class="row row-data card-hoverable rounded p-3 m-2">     
                            
                                <div class="col-1 text-truncate"> #<?php echo $row['uid']?></div>
                                <div class="col text-truncate"> <?php echo $row['lastName'] . ', <br>' . $row['firstName'] . ', <br>' . $row['middleName'][0] . '.' ?></div>
                                <div class="col text-truncate"> <?php echo $row['studentID']?></div>
                                <div class="col-1 text-truncate"> <?php echo $row['sex'][0]?></div>
                                <div class="col text-truncate"> <?php echo $row['gradeYear'] . ' - ' . $row['section']?></div>     
                                <div class="col text-truncate"> <?php echo $row['isbn']?></div>                               
                                <div class="col text-truncate"> <?php echo $dueIn;?></div>                        
                                <div class="col text-truncate"> <?php if($row['isReturned'] == 1){ echo 'Returned';} else{ echo $statusFromCalcDate;}?></div>          
                            </div>
                        </a>
                        <div class="modal fade" id="userInfo-<?php echo $row['uid']?>" tabindex="-1" aria-labelledby="userInfoLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="userInfoLabel">Selected UID: <?php echo $row['uid']?></h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Name: <?php echo $row['lastName'] . ', ' . $row['firstName'] . ', ' . $row['middleName'][0] . '.' ?> <br>
                                        Student ID: <?php echo $row['studentID']?> <br>
                                        Sex: <?php echo $row['sex']?> <br>
                                        Grade/Year and Section : <?php echo $row['gradeYear'] . ' - ' . $row['section']?>   <br>
                                        ISBN: <?php echo $row['isbn']?> <br>
                                        Due In: <?php echo $dueIn?> <br>
                                        Is Returned: <?php if($row['isReturned'] == 1){ echo 'Returned';} else{ echo $statusFromCalcDate;}?> <br> 
                                        
                                        Date of Borrowing: <?php echo $row['dateOfBorrowing']?> <br>
                                        Due Date: <?php echo $row['dueDate']?> <br>
                                        Date Returned: <?php echo $row['dateReturned']?> <br>
                                    </div>                            
                                </div>
                            </div>
                        </div>    
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>      
    </div>
    
    <!-- BOOTSTRAP -->    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        (() => {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }

            form.classList.add('was-validated')
            }, false)
        })
        })()
    </script>
</body>
</html>