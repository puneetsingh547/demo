<html>
	<head>
		<title>PRISONIOR | EDIT</title>
	</head>
	<body>
		<div>
			<h2>Edit Prisoner Detail</h2>
			<form method="post" action="updatePrisonerDetail.php">
				<div>
					<label>First Name<span class="required">*</span></label>
					<input class="form-style" type="text" name="fName" >
				</div>
				<div>
					<label>Last Name</label>
					<input class="form-style" type="text" name="lName" >
				</div>
				<div>
					<label>IPC Section <span class="required">*</span></label>
					<input class="form-style" type="text" name="sectionNo" >
				</div>
				<div>
					<label>Address<span class="required">*</span></label>
					<input class="form-style" type="text" name="address" >
				</div>
				<div>
					<label>Date Of Birth<span class="required">*</span></label>
					<input class="form-style" type="date" name="DOB" required>
					<time datetime="YYYY-MM-DDThh:mm:ssTZD">
				</div>
				<div>
					<label>Date Of In<span class="required">*</span></label>
					<input class="form-style" type="date" name="DOI" >
				</div>
				<div>
					<label>Improisonment Time<span class="required">*</span></label>
					<input class="form-style" type="date" name="iTime" >
				</div>
				<div class="btn-holder">
					<button type="submit" class="btn-register">Register</button>
				</div>								
			</form>
		</div>
	</body>
</html>

