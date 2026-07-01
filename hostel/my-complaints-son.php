<?php
session_start();
include('includes/config.php');
date_default_timezone_set('Asia/Kolkata');
include('includes/checklogin.php');
check_login();
$aid = $_SESSION['id'];
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>My Complaints</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include('includes/header.php');?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php');?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title" style="margin-top: 20px;">My Complaints (Sơn)</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">All Complaint Details</div>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sno.</th>
                                            <th>Complaint No.</th>
                                            <th>Complaint Type</th>
                                            <th>Reg Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php	
$ret = "SELECT ComplainNumber, complaintType, regDate FROM complaints WHERE userId = ?";
$stmt = $mysqli->prepare($ret);
$stmt->bind_param('i', $aid);
$stmt->execute();
$res = $stmt->get_result();
$cnt = 1;
while($row = $res->fetch_object()) {
?>
                                        <tr>
                                            <td><?php echo $cnt;?></td>
                                            <td><?php echo $row->ComplainNumber;?></td>
                                            <td><?php echo $row->complaintType;?></td>
                                            <td><?php echo $row->regDate;?></td>
                                        </tr>
<?php 
    $cnt = $cnt + 1;
} ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>