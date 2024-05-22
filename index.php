<?php
include 'function/db_conn.php';
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
            <a class="navbar-brand" href="index.php"><h1 style="color: #3C573A;"><i class="fa-solid fa-book fa" style="color: #3C573A; padding-right: 20px; padding-left: 10px;"></i><b>LibraLog</b></h1></a>
        </div>
    </nav>
<div class="container-fluid"> 
    <div class="row">
        <div class="col-3">
            <div class="rounded p-3 mt-3" style="background-color: rgba(255,255,255,0.85); backdrop-filter: blur(6px); position: sticky;">
                <div class="shadow rounded pt-3 ps-3 pb-2" style="background-color: white;">
                    <h2 class="text-wrap"><b>Dangal Greetings!</b></h2>         
                </div>
                <div class="shadow rounded p-3 mt-3" style="background-color: white; padding:30px;">
                    <form role="search">
                        <label for="" class="form-label"><b>Search Book ID</b></label>
                        <div class="d-flex">
                            <input type="search" class="form-control" name="search" placeholder="Search" required value="<?php echo isset($uid) ? $uid : ''?>">
                            <button type="submit" class="search-btn ms-2"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </form>   
                </div>
                <div class="btn-home shadow rounded p-3 mt-3" style="background-color: white; padding:30px;">
                    <a class="text-center nav-link" href="form/add_user.php"><b>Borrow a Book</b> <i class="fa-solid fa-plus ms-2"></i></a>
                    <a class="text-center nav-link" href="form/returned.php"><b>Return the Book</b><i class="fa-solid fa-right-from-bracket ms-2"></i></a>                                   
                    <hr>
                    <a class="text-center nav-link" href="form/user_info.php"><b>View Student Info</b><i class="fa-regular fa-user ms-2"></i></a>
                    <a class="text-center nav-link" href="form/clearance.php"><b>Clearance Checker</b><i class="fa-solid fa-check ms-2"></i></a> 
                    <a class="text-center nav-link" href="form/book.php"><b>View Book Info</b><i class="fa-solid fa-book ms-2"></i></a>
                </div>
          
            </div>
        </div>
    
        <div class="col-9">
            <div class="shadow rounded mt-3" style="padding:30px; min-height: 96vh; background-color: white;">
                    <div class="row shadow-sm border-none rounded p-3 m-2 color-danger">      
                            <div class="col">Tracking ID</div>                     
                            <div class="col">ISBN Number</div>  
                            <div class="col">Date of Borrowing</div>   
                            <div class="col">Due Date</div> 
                            <div class="col">Date Returned</div>     
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
                                <div class="row row-data card-hoverable shadow-sm border-none rounded p-3 m-2 focus-ring <?php echo $colorStatus?>">
                                    <div class="col text-truncate"> <?php echo $row['logID']?></div>         
                                    <div class="col text-truncate"> <?php echo $row['isbn']?></div>  
                                    <div class="col text-wrap"> <?php echo $row['dateOfBorrowing']?></div>
                                    <div class="col text-wrap"> <?php echo $row['dueDate']?></div>
                                    <div class="col text-wrap"> <?php echo $row['dateReturned']?></div>                             
                                    <div class="col text-wrap"> <?php echo $dueIn;?></div>                        
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
</body>
</html>