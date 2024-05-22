<?php
include "../function/db_conn.php";
date_default_timezone_set('Asia/Taipei');
$uid= "";
$firstName = "";
$middleName = "";
$lastName = "";
$studentID = "";
$sex = "";
$dep = "";
$gradeYear = "";
$section = "";
$dueDate = "";
$isbn = "";
$isInDatabase = false;

if(isset($_POST['next'])){
    $uid = $_POST['uid'];
    $sql_get = "SELECT * FROM `users` WHERE uid = '$uid'";
    $result_get = mysqli_query($conn, $sql_get);
    $row_get = mysqli_fetch_assoc($result_get);
    
    if(isset($row_get)){
        $firstName = $row_get['firstName'];
        $middleName = $row_get['middleName'];
        $lastName = $row_get['lastName'];
        $studentID = $row_get['studentID'];
        $sex = isset($row_get['sex']) ? $row_get['sex'] : 0;
        $dep = isset($row_get['dep']) ? $row_get['dep'] : 0;
        $gradeYear = $row_get['gradeYear'];
        $section = $row_get['section'];
        $isInDatabase = true;
        
    }

}
if(isset($_POST['submit'])){
    // POLICY
    $uid = $_POST['uid'];
    $sql_users = "SELECT * FROM `users` WHERE uid = '$uid'";
    $result_users = mysqli_query($conn, $sql_users);
    $row_users = mysqli_fetch_assoc($result_users);
    // Check if the uid is in the users
    if($row_users['uid'] != $uid){
        $firstName = $_POST['firstName'];
        $middleName = $_POST['middleName'];
        $lastName = $_POST['lastName'];
        $sex = isset($_POST['sex']) ? $_POST['sex'] : 0;
        $dep = isset($_POST['dep']) ? $_POST['dep'] : 0;
        $gradeYear = $_POST['gradeYear'];
        $section = $_POST['section'];
        $studentID = $_POST['studentID'];

        $sql = "INSERT INTO `users`(`uid`, `firstName`, `middleName`, `lastName`, `sex`, `studentID`, `dep`, `gradeYear`, `section`) VALUES ('$uid','$firstName','$middleName','$lastName','$sex','$studentID','$dep','$gradeYear','$section')";
        $result = mysqli_query($conn, $sql);
    }
    else{
        $firstName = $row_users['firstName'];
        $middleName = $row_users['middleName'];
        $lastName = $row_users['lastName'];
        $studentID = $row_users['studentID'];
        $sex = isset($row_users['sex']) ? $row_users['sex'] : 0;
        $dep = isset($row_users['dep']) ? $row_users['dep'] : 0;
        $gradeYear = $row_users['gradeYear'];
        $section = $row_users['section'];
    }
        $dateOfBorrowing = date("Y-m-d H:i:sa");
        $dueDate = $_POST['dueDate'];
        $isbn = $_POST['isbn'];
    
    $sql = "INSERT INTO `log`(`uid`, `dateOfBorrowing`, `dueDate`, `isbn`, `isReturned`) VALUES ('$uid','$dateOfBorrowing','$dueDate','$isbn', 0)";
    $result = mysqli_query($conn, $sql);

    if($result){
        header("Location: ../index.php?msg=New Record Created Successfully");
    }
    else{
        echo "Failed: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins"/>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>LibraLog User Form</title>
</head>
<body style="background-image: url(../image/LibraLogL.jpg); background-size: 1440px; min-width: 1024px; font-family: 'Poppins', serif;">
    <div style="padding:50px;"> 
        <div class="container d-flex justify-content-center">
            <div class="card p-5 shadow-lg rounded">
                <div class="text-center mb-4">
                    <h1><b>Add New</b> </h1>
                    <p class="text-muted">Fill out the form to Borrow a Book</p>
                </div>
                <?php
                if ($uid == ""){
                ?>               
                
                
                <form class="needs-validation" method="post" style="width:50vw; min-width:300px;" novalidate>
                    <div class="mb-3">
                        <div class="col">
                            <label for="" class="form-label">UID:</label>
                            <input type="text" class="form-control" name="uid" required value="<?php echo $uid?>" placeholder="Click me then scan the RFID">
                            <div class="invalid-feedback">Enter your UID</div>
                        </div>
                    </div>
                    
                    <div>
                        <button type="submit" class="btn btn-success" name="next">Next</button>
                        <a href="../index.php" class="btn btn-danger">Cancel</a>
                    </div>
                   
                </form> 
                <?php
                }
                ?>
                <?php
                if ($uid != ""){
                ?>
                <form class="needs-validation" method="post" style="width:50vw; min-width:300px;" novalidate>
                    <div class="row mb-3">
                        <div class="mb-3">
                            <div class="col">
                                <label for="" class="form-label">UID:</label>
                                <input type="text" class="form-control" id="text-input" name="uid" value="<?php echo $uid?>" <?php echo ($isInDatabase == true) ? "readonly": ""?>>
                                <div class="invalid-feedback">Enter your UID</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="col">
                                <label for="" class="form-label">Student ID:</label>
                                <input type="text" class="form-control" id="text-input" name="studentID" required value="<?php echo $studentID?>" <?php echo ($isInDatabase == true) ? "disabled": ""?>>
                                <div class="invalid-feedback">Enter your Student ID</div>
                            </div>
                        </div>                        
                        <div class="col">
                            <label for="" class="form-label">First Name:</label>
                            <input type="text" class="form-control" id="text-input" name="firstName" required value="<?php echo $firstName?>" <?php echo ($isInDatabase == true) ? "disabled": ""?>>
                            <div class="invalid-feedback">You must fill out the form</div>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Middle Name:</label>
                            <input type="text" class="form-control" id="text-input" name="middleName" required value="<?php echo $middleName?>" <?php echo ($isInDatabase == true) ? "disabled": ""?>>
                            <div class="invalid-feedback">Please provide your complete middle name</div>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Last Name:</label>
                            <input type="text" class="form-control" id="text-input" name="lastName" required value="<?php echo $lastName?>" <?php echo ($isInDatabase == true) ? "disabled": ""?>>
                            <div class="invalid-feedback">Please provide your surname</div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label>Sex:</label> &nbsp;
                        <input type="radio" class="form-check-input" name="sex" 
                        id="Male" value="Male" required="" <?php echo ($sex == 'Male') ? "checked":"";?> <?php echo ($isInDatabase == true) ? "disabled": ""?>>
                        <label for="Male" class="form-input-label">Male</label>
                        &nbsp;
                        <input type="radio" class="form-check-input" name="sex" 
                        id="Female" value="Female" required="" <?php echo ($sex == 'Female') ? "checked":"";?> <?php echo ($isInDatabase == true) ? "disabled": ""?>>
                        <label for="Female" class="form-input-label">Female</label>
                        
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="" class="form-label">Department:</label>
                            <select class="form-select" aria-label="Default select example" name="dep" required value="<?php echo $dep?>" <?php echo ($isInDatabase == true) ? "disabled": ""?>>
                                <option selected disabled value="">...</option>                        
                                <option value="Senior High School" <?php echo ($dep == 'Senior High School') ? "selected":"";?>>Senior High School</option>
                                <option value="College of Arts and Science" <?php echo ($dep == 'College of Arts and Science') ? "selected":"";?>>College of Arts and Science</option>
                                <option value="College of Business, Accountancy, and Administration" <?php echo ($dep == 'College of Business, Accountancy, and Administration') ? "selected":"";?>>College of Business, Accountancy, and Administration</option>
                                <option value="College of Computing Studies" <?php echo ($dep == 'College of Computing Studies') ? "selected":"";?>>College of Computing Studies</option>
                                <option value="College of Education" <?php echo ($dep == 'College of Education') ? "selected":"";?>>College of Education</option>
                                <option value="College of Engineering" <?php echo ($dep == 'College of Engineering') ? "selected":"";?>>College of Engineering</option>
                                <option value="College of Health and Allied Science" <?php echo ($dep == 'College of Health and Allied Science') ? "selected":"";?>>College of Health and Allied Science</option>
                                <option value="Graduate School" <?php echo ($dep == 'Graduate School') ? "selected":"";?>>Graduate School</option>
                            </select>
                            <div class="invalid-feedback">Please choose your department</div>
                        </div>
                        <div class="col-2">
                            <label for="" class="form-label">Grade/Year:</label>
                            <input type="text" class="form-control" id="text-input" name="gradeYear" required value="<?php echo $gradeYear?>" <?php echo ($isInDatabase == true) ? "disabled": ""?>>
                            <div class="invalid-feedback">Provide you grade/year level</div>
                        </div>
                        <div class="col-4">
                            <label for="" class="form-label">Section:</label>
                            <input type="text" class="form-control" id="text-input" name="section" required value="<?php echo $section?>" <?php echo ($isInDatabase == true) ? "disabled": ""?>>
                            <div class="invalid-feedback">Provide your section</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="col">
                            <label for="" class="form-label">Due Date:</label>
                            <input type="date" class="form-control" name="dueDate" required value="">
                            <div class="invalid-feedback">Please choose a due date</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="col">
                            <label for="" class="form-label">ISBN Number:</label >
                            <input type="text" class="form-control" id="text-input" name="isbn" required value="" placeholder="Click me then scan the RFID">
                            <div class="invalid-feedback">Provide the ISBN number of the book that you'll borrow</div>
                        </div>
                    </div>
                    

                    <div>
                        <button type="submit" class="btn btn-success" name="submit">Submit</button>
                        <a href="add_user.php" class="btn btn-danger">Cancel</a>
                    </div>
                </form>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
    
    <!-- BOOTSTRAP -->    
    <script>
        const inp = document.getElementByID("text-input");
        inp.addEventListener("input", ()=>{
            inp.value = inp.value.toUpperCase();
            console.log(inp.value);
        });
        
    </script>
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
        });

        

        })()
    </script>
</body>
</html>