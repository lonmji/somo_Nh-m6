<?php
session_start();
include('includes/config.php');
date_default_timezone_set('Asia/Kolkata');
include('includes/checklogin.php');
check_login();
$aid = $_SESSION['id'];

if (isset($_POST['submit'])) {
    $complainttype = $_POST['ctype'];
    $complaintdetails = $_POST['cdetails'];
    $imgfile = $_FILES["image"]["name"];
    $cnumber = mt_rand(100000000, 999999999);

    if ($imgfile != '') {
        $extension = substr($imgfile, strlen($imgfile) - 4, strlen($imgfile));
        $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif", '.pdf');
        
        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
        } else {
            $imgnewfile = md5($imgfile . time()) . $extension;
            move_uploaded_file($_FILES["image"]["tmp_name"], "comnplaintdoc/" . $imgnewfile);

            $query = "insert into complaints(ComplainNumber,userId,complaintType,complaintDetails,complaintDoc) values(?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('iisss', $cnumber, $aid, $complainttype, $complaintdetails, $imgnewfile);
            $stmt->execute();

            echo "<script>alert('Complaint registered. Complaint number is: $cnumber');</script>";
            echo "<script type='text/javascript'> document.location = 'my-complaints-son.php'; </script>";
        }
    } else {
        $imgnewfile = "";
        $query = "insert into complaints(ComplainNumber,userId,complaintType,complaintDetails,complaintDoc) values(?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('iisss', $cnumber, $aid, $complainttype, $complaintdetails, $imgnewfile);
        $stmt->execute();

        echo "<script>alert('Complaint registered. Complaint number is: $cnumber');</script>";
        echo "<script type='text/javascript'> document.location = 'my-complaints-son.php'; </script>";
    }
}
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Complaint Registration</title>
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
                        <h2 class="page-title" style="margin-top: 20px;">Register Complaint (Sơn)</h2>
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Complaint Type</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" required="required" name="ctype">
                                                <option value="">Select Complaint Type</option>
                                                <option value="Food Related">Food Related</option>
                                                <option value="Room Related">Room Related</option>
                                                <option value="Fee Related">Fee Related</option>
                                                <option value="Electrical">Electrical</option>
                                                <option value="Plumbing">Plumbing</option>
                                                <option value="Discipline">Discipline</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Explain Complaint</label>
                                        <div class="col-sm-8">
                                            <textarea name="cdetails" class="form-control" required="required" rows="5"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">File (if any)</label>
                                        <div class="col-sm-8">
                                            <input type="file" name="image" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-sm-offset-4">
                                        <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>