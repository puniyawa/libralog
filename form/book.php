<?php
include '../function/db_conn.php';
$selectedUser;
$result_queryNotReturned;
date_default_timezone_set('Asia/Taipei');

if(isset($_GET['search'])){
    $search = strval($_GET['search']);
    $sql_display = "SELECT * FROM `book` WHERE isbn='$search';";
    $result_display = mysqli_query($conn, $sql_display);
    $selectedUser_display = mysqli_fetch_assoc($result_display);
    
    $sql = "SELECT * FROM `book` INNER JOIN `log` ON book.isbn = log.isbn INNER JOIN `users` ON log.uid = users.uid WHERE log.isbn='$search';";
    $result = mysqli_query($conn, $sql);
    $selectedUser = mysqli_fetch_assoc($result);
}    


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Returned Book Log</title>
     <!-- BOOTSTRAP -->
     <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins"/>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/index.css">
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body style="background-image: url(../image/LibraLogL.jpg); background-size: 1440px; min-width: 1024px; font-family: 'Poppins', serif;">   
<div class="container-fluid">   
        <div class="row">
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

            <div class="col-3">
                <div class="rounded p-3 mt-3" style="background-color: rgba(255,255,255,0.85); backdrop-filter: blur(6px);">
                    <div class="shadow rounded pt-3 ps-3 pb-1" style="background-color: white;">
                        <?php 
                        if(isset($selectedUser_display)){
                        echo 
                        '
                        <h3 class="text-wrap"><b>' . $selectedUser_display['bookName'] . ' </b></h3>
                        <p>
                        ' . $selectedUser_display['bookAuthor'] . '<br>
                        ' . $selectedUser_display['isbn'] . '               
                        </p>
                        ';
                        }
                        else{
                            echo '<h3 class="text-wrap"><b> ------- <br> ISBN </b></h3>';
                        }
                        ?>  

                        
                                    
                    </div>
                    <!-- <div class="shadow rounded p-3 mt-3" style="background-color: white; padding:30px;">
                        <form role="search">
                            <label for="" class="form-label"><b>Search Book ID</b></label>
                            <div class="d-flex ">
                                <input type="search" class="form-control" name="search" placeholder="Search" required value="<?php echo isset($uid) ? $uid : ''?>">
                                <button type="submit" class="btn btn-outline-success ms-2">Search</button>
                            </div>
                        </form>   
                    </div> -->
                    <div class="rounded p-3 mt-3" style="background-color: white;">
                        <form role="search">
                        <label for="" class="form-label"><b>Search Book ID</b></label>
                            <div class="d-flex">
                                <input type="search" class="form-control" name="search" placeholder="Search" required value="<?php echo isset($uid) ? $uid : ''?>">
                                <button type="submit" class="search-btn ms-2"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </form>   
                    </div>
                </div>
            </div>
           
            <div class="col-9">
                <div class="shadow rounded mt-3" style="padding:30px; min-height: 96vh; background-color: white;">
                        <nav class="navbar">
                            <div class="container-fluid">
                                <a class="navbar-brand"><b>LibraLog</b></a>
                                <div class="nav-icon d-flex justify-content-start align-items-center">                                
                                  
                                    <a href="../index.php" class="nav-link active ms-2" aria-current="page"><i class="fa-solid fa-house"></i></a>
                                </div>                      
                            </div>
                        </nav>
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
                        if(isset($_GET['search'])){                                                 
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
                                        $colorStatus = "";
                                        
                                    }
                                    else if ($interval->format('%R%a') == 0){
                                        $dueIn = 'Due Today';
                                        $statusFromCalcDate = "Not Returned";
                                        $colorStatus = "table-warning";
                                        
                                    }
                                    else if ($interval->format('%R%a') < 0){
                                        $dueIn = $interval->format('Late of %a days');
                                        $statusFromCalcDate = "Late";
                                        $colorStatus = "table-danger";
                                        
                                    
                                    }   
                                    else{
                                        $dueIn = "";
                                        $colorStatus = "table-success";
                                    }   
                                ?>

                                <a class="nav-link" data-bs-toggle="modal" data-bs-target="#userInfo-<?php echo $row['uid']?>">
                                    <div class="row shadow-sm border-none rounded p-3 m-2 focus-ring <?php echo $colorStatus?>">     
                                    
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
                                                <form action="" method="post">
                                                    <div class="mb-3">
                                                        <div class="col">
                                                            <label for="" class="form-label">Date Returned:</label>
                                                            <input type="date" class="form-control" name="dateReturned" 
                                                            value="<?php 
                                                            if(date('Y-m-d',strtotime($row["dateReturned"]) != '0000-00-00'))
                                                            {
                                                                echo date('Y-m-d',strtotime($row["dateReturned"]));
                                                            }
                                                            ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label>Returned?</label> &nbsp;
                                                        <input type="radio" class="form-check-input" name="isReturned" 
                                                        id="true" value="1" <?php echo ($row['isReturned'] == '1') ? "checked":"";?>>
                                                        <label for="true" class="form-input-label">Yes</label>
                                                        &nbsp;
                                                        <input type="radio" class="form-check-input" name="isReturned" 
                                                        id="false" value="0" <?php echo ($row['isReturned'] == '0') ? "checked":"";?>>
                                                        <label for="false" class="form-input-label">No</label>
                                                    </div>

                                                    <div>
                                                        <button href="returned.php?submit=update&uid=<?php echo $row['uid']?>&isReturned=<?php echo ($row['isReturned'] == '1') ? "1":"0";?>" class="btn btn-success" name="submit">Update</button>
                                                        <a href="../index.php" class="btn btn-danger">Cancel</a>
                                                    </div>
                                                </form>
                                            </div>                            
                                        </div>
                                    </div>
                                </div>    
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>