<?php require_once('db-connect.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduling</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./fullcalendar/lib/main.min.css">
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./fullcalendar/lib/main.min.js"></script>

<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}

.topnav {
  overflow: hidden;
  background-color: royalblue;
}

.topnav a {
  float: left;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

/* Create a right-aligned (split) link inside the navigation bar */
.topnav a.split {
  float: right;
  
}
table, tbody, td, tfoot, th, thead, tr {
            border-color: darkgrey!important;
            border-style: solid;
            border-width: 1px !important;
        }
      
       
</style>
</head>
<body>

<div class="topnav">
  
  
  <a href="recievefile.php">Messages</a>
  <a href="logout.php" class="split" >Logout</a>
 

  <?php
if (isset($_SESSION["user"]) && isset($_SESSION["fullname"])) {
    echo '<a href="my_account.php" class="split">' . htmlspecialchars($_SESSION["fullname"]) . '</a>';
} else {
    echo '<a href="my_account.php" class="split">My account</a>';
}
?>

</div>
</div>
<div class="container">
<div>
<b class="text-light"></b>
 </div>
</div>
    </nav>
    <div class="container py-5" id="page-container">
        <div class="row">
            <div class="col-md-9">
                <div id="calendar"></div>
            </div>
            <div class="col-md-3">
                <div class="cardt rounded-0 shadow">
                    <div class="card-header bg-gradient bg-primary text-light">
                        <h5 class="card-title">Schedule Form</h5>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid">
                            <form action="save_schedule.php" method="post" id="schedule-form">
                                <input type="hidden" name="id" value="">

                                
     <div class="form-group mb-2">
    <label for="title" class="control-label"></label>
    <select name="title" id="title">
        <option value="Morning Shift">Morning Shift</option>
        <option value="Night Shift">Night Shift</option>
        <option value="Leave">Leave</option>
    </select>
</div>
Employee: 

<?php
// Connect to the database (you'll need to replace the placeholders with your actual database credentials)
$mysqli = new mysqli("localhost", "root", "09102445986", "dummy_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Query the database to retrieve the 'fullname' values
$query = "SELECT fullname FROM register";

$result = $mysqli->query($query);

// Check if the query was successful
if ($result) {
    echo '<div class="form-group mb-2">';
    echo '<label for="description" class="control-label"> </label>';
    echo '<select name="description" id="description">';

    // Generate <option> elements with 'fullname' as both the text and value
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['fullname'] . '">' . $row['fullname'] . '</option>';
    }

    echo '</select>';
    echo '</div>';

    // Close the database connection
    $mysqli->close();
} else {
    // Handle database query error
    echo "Error: " . $mysqli->error;
}
?>


    

<div class="form-group mb-2">
    <label for="start_datetime" class="control-label">Start</label>
    <input type="datetime-local" class="form-control form-control-sm rounded-0" name="start_datetime" id="start_datetime" required>
</div>
<div class="form-group mb-2">
    <label for="end_datetime" class="control-label">End</label>
    <input type="datetime-local" class="form-control form-control-sm rounded-0" name="end_datetime" id="end_datetime" required>
</div>

</div>
</form>
</div>
</div>
<div class="card-footer">
    <div class="text-center">
        <!-- Add an id attribute to the Save button -->
        <button class="btn btn-primary btn-sm rounded-0" type="button" id="save-button"><i class="fa fa-save"></i> Save</button>
        <button class="btn btn-default border btn-sm rounded-0" type="reset" form="schedule-form"><i class="fa fa-reset"></i> Cancel</button>
    </div>
</div>
</div>
</div>
</div>
    </div>
    <!-- Event Details Modal -->
    <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0">
                <div class="modal-header rounded-0">
                    <h5 class="modal-title">Schedule Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body rounded-0">
                    <div class="container-fluid">
                        <dl>
                            <dt class="text-muted">Title</dt>
                            <dd id="title" class="fw-bold fs-4"></dd>
                            <dt class="text-muted">Employee</dt>
                            <dd id="description" class=""></dd>
                            <dt class="text-muted">Start</dt>
                            <dd id="start" class=""></dd>
                            <dt class="text-muted">End</dt>
                            <dd id="end" class=""></dd>
                        </dl>
                    </div>
                </div>
                <div class="modal-footer rounded-0">
                    <div class="text-end">
                        <button type="button" class="btn btn-primary btn-sm rounded-0" id="edit" data-id="">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm rounded-0" id="delete" data-id="">Delete</button>
                        <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Event Details Modal -->
  

<?php 
$schedules = $conn->query("SELECT * FROM `schedule_list`");
$sched_res = [];
foreach($schedules->fetch_all(MYSQLI_ASSOC) as $row){
    $row['sdate'] = date("F d, Y h:i A",strtotime($row['start_datetime']));
    $row['edate'] = date("F d, Y h:i A",strtotime($row['end_datetime']));
    $sched_res[$row['id']] = $row;
}
?>
<?php 
if(isset($conn)) $conn->close();
?>
</body>


<script>
    var scheds = $.parseJSON('<?= json_encode($sched_res) ?>')
</script>
<script src="./js/script.js"></script>

<script>
    // Function to save the schedule via AJAX
    function saveSchedule() {
        // Serialize the form data
        var formData = $("#schedule-form").serialize();

        // Send an AJAX POST request to save_schedule.php
        $.ajax({
            type: "POST",
            url: "save_schedule.php",
            data: formData,
            success: function (response) {
                // Handle the response from save_schedule.php as needed
                // For example, you can display a success message
                alert("Schedule saved successfully!");
                
                // Reload the page after a short delay (e.g., 1000 milliseconds or 1 second)
                setTimeout(function () {
                    location.reload();
                }, 50);
            },
            error: function () {
                // Handle errors if the request fails
                alert("Error occurred while saving the schedule.");
            }
        });
    }

    $(document).ready(function () {
        // Handle the click event of the "Save" button
        $("#save-button").click(function () {
            saveSchedule();
        });
    });
    
</script>


</html>