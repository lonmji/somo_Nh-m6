<?php
session_start();
include('includes/config.php');
date_default_timezone_set('Asia/Kolkata');
include('includes/checklogin.php');
check_login();
$aid=$_SESSION['id'];

if(isset($_POST['submit']))
{
// Posted Values
$acceswardent=$_POST['acceswardent'];
$accesmember=$_POST['accesmember'];
$redproblem=$_POST['redproblem'];
$Room=$_POST['Room'];
$Mess=$_POST['Mess'];
$hstelsor=$_POST['hstelsor'];
$overall=$_POST['overall'];
$feedback=$_POST['feedback'];

// Query for insertion data into database
$query="insert into feedback(AccessibilityWarden,AccessibilityMember,RedressalProblem,Room,Mess,HostelSurroundings,OverallRating,FeedbackMessage,userId) values(?,?,?,?,?,?,?,?,?)";
$stmt = $mysqli->prepare($query);
$rc=$stmt->bind_param('ssssssssi',$acceswardent,$accesmember,$redproblem,$Room,$Mess,$hstelsor,$overall,$feedback,$aid);
$stmt->execute();

echo "<script>alert('Feedback registered successfully');</script>";
echo "<script type='text/javascript'> document.location = 'feedback.php'; </script>";
}
?>

<!doctype html>
<html lang="en" class="no-js">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<title>Feedback Registration</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
	<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
	<style>
		.panel-custom {
			border: none;
			border-radius: 8px;
			box-shadow: 0 4px 12px rgba(0,0,0,0.1);
		}
		.panel-custom .panel-heading {
			background-color: #3e454c !important;
			border-color: #3e454c !important;
			font-weight: bold;
			border-top-left-radius: 8px;
			border-top-right-radius: 8px;
		}
		.radio-group {
			background: #f9f9f9;
			padding: 10px 15px;
			border-radius: 6px;
			border: 1px solid #e3e3e3;
			display: inline-block;
			width: 100%;
		}
		.radio-inline {
			font-weight: 500;
			margin-right: 15px !important;
		}
		.radio-inline input[type="radio"] {
			margin-top: 3px;
		}
		.form-group label {
			font-weight: 600;
			color: #444;
		}
		.table-custom th {
			background-color: #f5f5f5;
			width: 35%;
		}
		.alert-info-custom {
			background-color: #eef7ff;
			color: #0066cc;
			border: 1px solid #bce0ff;
			padding: 20px;
			border-radius: 8px;
			font-size: 15px;
		}
	</style>
</head>
<body>
	<?php include('includes/header.php');?>
	<div class="ts-main-content">
		<?php include('includes/sidebar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<h2 class="page-title" style="margin-top: 20px;">Ý kiến phản hồi (Feedback)</h2>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-primary panel-custom">
									<div class="panel-heading">Thông tin khảo sát</div>
									<div class="panel-body" style="padding: 30px Gold;">
										<?php
										$uid=$_SESSION['login'];
										$stmt=$mysqli->prepare("SELECT emailid FROM registration WHERE emailid=? || regno=? ");
										$stmt->bind_param('ss',$uid,$uid);
										$stmt->execute();
										$stmt -> bind_result($email);
										$rs=$stmt->fetch();
										$stmt->close();
										if($rs) {  
											$ret=$mysqli->prepare("SELECT id FROM feedback WHERE userId=? ");
											$ret->bind_param('i',$aid);
											$ret->execute();
											$ret->bind_result($count);
											$ret->fetch();
											$ret->close();

											if($count>0) {
												$ret="SELECT * FROM feedback WHERE userId=? ";
												$stmt= $mysqli->prepare($ret);
												$stmt->bind_param('i',$aid);
												$stmt->execute();
												$res=$stmt->get_result();
												while($row=$res->fetch_object()) { 
										?>
											<div class="table-responsive">
												<table class="table table-bordered table-custom" style="margin-bottom: 30px;">
													<thead>
														<tr>
															<th colspan="2" class="text-center text-primary" style="background: #eef7ff;"><h4>Chi tiết phản hồi của bạn</h4></th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<th>Ngày gửi phản hồi</th>
															<td><?php echo $row->postinDate;?></td>
														</tr>
														<tr>
															<th>Khả năng tiếp cận Quản lý (Warden)</th>
															<td><span class="label label-info"><?php echo $row->AccessibilityWarden;?></span></td>
														</tr>
														<tr>
															<th>Khả năng tiếp cận Ban quản lý ký túc xá</th>
															<td><span class="label label-info"><?php echo $row->AccessibilityMember;?></span></td>
														</tr>
														<tr>
															<th>Giải quyết các vấn đề phát sinh</th>
															<td><span class="label label-info"><?php echo $row->RedressalProblem;?></span></td>
														</tr>
														<tr>
															<th>Chất lượng phòng ở</th>
															<td><span class="label label-info"><?php echo $row->Room;?></span></td>
														</tr>
														<tr>
															<th>Chất lượng nhà ăn (Mess)</th>
															<td><span class="label label-info"><?php echo $row->Mess;?></span></td>
														</tr>
														<tr>
															<th>Môi trường xung quanh khuôn viên</th>
															<td><span class="label label-info"><?php echo $row->HostelSurroundings;?></span></td>
														</tr>
														<tr>
															<th>Đánh giá tổng thể</th>
															<td><span class="label label-success"><?php echo $row->OverallRating;?></span></td>
														</tr>
														<tr>
															<th>Nội dung góp ý thêm</th>
															<td><?php echo isset($row->FeedbackMessage) && !empty($row->FeedbackMessage) ? $row->FeedbackMessage : "<em>Không có ý kiến khác</em>";?></td>
														</tr>
													</tbody>
												</table>
											</div>
										<?php 
												}
											} else {
										?>
											<form method="post" action="" name="complaint" class="form-horizontal">
												
												<div class="form-group">
													<label class="col-sm-4 control-label">Khả năng tiếp cận Quản lý (Warden) <span class="text-danger">*</span></label>
													<div class="col-sm-8">
														<div class="radio-group">
															<label class="radio-inline"><input type="radio" name="acceswardent" value="Excellent" required> Xuất sắc</label>
															<label class="radio-inline"><input type="radio" name="acceswardent" value="Very Good" required> Rất tốt</label>
															<label class="radio-inline"><input type="radio" name="acceswardent" value="Good" required> Tốt</label>
															<label class="radio-inline"><input type="radio" name="acceswardent" value="Average" required> Trung bình</label>
															<label class="radio-inline"><input type="radio" name="acceswardent" value="Below Average" required> Kém</label>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-4 control-label">Khả năng tiếp cận Ban quản lý KTX <span class="text-danger">*</span></label>
													<div class="col-sm-8">
														<div class="radio-group">
															<label class="radio-inline"><input type="radio" name="accesmember" value="Excellent" required> Xuất sắc</label>
															<label class="radio-inline"><input type="radio" name="accesmember" value="Very Good" required> Rất tốt</label>
															<label class="radio-inline"><input type="radio" name="accesmember" value="Good" required> Tốt</label>
															<label class="radio-inline"><input type="radio" name="accesmember" value="Average" required> Trung bình</label>
															<label class="radio-inline"><input type="radio" name="accesmember" value="Below Average" required> Kém</label>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-4 control-label">Giải quyết các vấn đề phát sinh <span class="text-danger">*</span></label>
													<div class="col-sm-8">
														<div class="radio-group">
															<label class="radio-inline"><input type="radio" name="redproblem" value="Excellent" required> Xuất sắc</label>
															<label class="radio-inline"><input type="radio" name="redproblem" value="Very Good" required> Rất tốt</label>
															<label class="radio-inline"><input type="radio" name="redproblem" value="Good" required> Tốt</label>
															<label class="radio-inline"><input type="radio" name="redproblem" value="Average" required> Trung bình</label>
															<label class="radio-inline"><input type="radio" name="redproblem" value="Below Average" required> Kém</label>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-4 control-label">Chất lượng phòng ở <span class="text-danger">*</span></label>
													<div class="col-sm-8">
														<div class="radio-group">
															<label class="radio-inline"><input type="radio" name="Room" value="Excellent" required> Xuất sắc</label>
															<label class="radio-inline"><input type="radio" name="Room" value="Very Good" required> Rất tốt</label>
															<label class="radio-inline"><input type="radio" name="Room" value="Good" required> Tốt</label>
															<label class="radio-inline"><input type="radio" name="Room" value="Average" required> Trung bình</label>
															<label class="radio-inline"><input type="radio" name="Room" value="Below Average" required> Kém</label>
														</div>
													</div>
												</div>					

												<div class="form-group">
													<label class="col-sm-4 control-label">Chất lượng nhà ăn (Mess) <span class="text-danger">*</span></label>
													<div class="col-sm-8">
														<div class="radio-group">
															<label class="radio-inline"><input type="radio" name="Mess" value="Excellent" required> Xuất sắc</label>
															<label class="radio-inline"><input type="radio" name="Mess" value="Very Good" required> Rất tốt</label>
															<label class="radio-inline"><input type="radio" name="Mess" value="Good" required> Tốt</label>
															<label class="radio-inline"><input type="radio" name="Mess" value="Average" required> Trung bình</label>
															<label class="radio-inline"><input type="radio" name="Mess" value="Below Average" required> Kém</label>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-4 control-label">Môi trường khuôn viên xung quanh <span class="text-danger">*</span></label>
													<div class="col-sm-8">
														<div class="radio-group">
															<label class="radio-inline"><input type="radio" name="hstelsor" value="Excellent" required> Xuất sắc</label>
															<label class="radio-inline"><input type="radio" name="hstelsor" value="Very Good" required> Rất tốt</label>
															<label class="radio-inline"><input type="radio" name="hstelsor" value="Good" required> Tốt</label>
															<label class="radio-inline"><input type="radio" name="hstelsor" value="Average" required> Trung bình</label>
															<label class="radio-inline"><input type="radio" name="hstelsor" value="Below Average" required> Kém</label>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-4 control-label">Đánh giá tổng thể <span class="text-danger">*</span></label>
													<div class="col-sm-8">
														<div class="radio-group">
															<label class="radio-inline"><input type="radio" name="overall" value="Excellent" required> Xuất sắc</label>
															<label class="radio-inline"><input type="radio" name="overall" value="Very Good" required> Rất tốt</label>
															<label class="radio-inline"><input type="radio" name="overall" value="Good" required> Tốt</label>
															<label class="radio-inline"><input type="radio" name="overall" value="Average" required> Trung bình</label>
															<label class="radio-inline"><input type="radio" name="overall" value="Below Average" required> Kém</label>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-4 control-label">Ý kiến đóng góp khác (nếu có)</label>
													<div class="col-sm-8">
														<textarea name="feedback" id="feedback" class="form-control" rows="4" placeholder="Nhập ý kiến của bạn tại đây..."></textarea>
													</div>
												</div>

												<div class="row" style="margin-top: 20px;">
													<div class="col-sm-8 col-sm-offset-4">
														<button type="submit" name="submit" class="btn btn-primary btn-lg" style="padding: 8px 30px;">Gửi đánh giá</button>
													</div>
												</div>
											</form>
										<?php 
											}
										} else { 
										?>
											<div class="alert-info-custom text-center">
												<i class="fa fa-info-circle"></i> Bạn chưa đủ điều kiện đánh giá. Sau khi đăng ký ký túc xá thành công, bạn mới có thể thực hiện chức năng này.
											</div>
										<?php } ?>
									</div>
								</div>
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
