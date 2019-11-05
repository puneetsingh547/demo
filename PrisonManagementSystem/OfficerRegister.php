<html>
	<head>
		<title>PMS | Register</title>
	</head>
	<body>

		<h2>Rsgister</h2>
		<form method="post" action="register.php">
            <div class="form-inputs">
                <label>Officer ID <span class="required">*</span></label>
                <input class="form-style" type="text" name="OfficerId" placeholder="enter your id..." required/>
            </div>	
            <div class="form-inputs">
                <label>First Name <span class="required">*</span></label>
                <input class="form-style" type="text" name="FirstName" placeholder="enter your first name..." />
            </div>	
            <div class="form-inputs">
                <label>Last Name <span class="required"></span></label>
                <input class="form-style" type="text" name="LastName" placeholder="enter your last name..." />
            </div>
            <div class="form-inputs">
                <label>Address <span class="required">*</span></label>
                <input class="form-style" type="text" name="Address" placeholder="enter your address..." />
            </div>            	
            <div class="form-inputs">
                <label>Phone <span class="required">*</span></label>
                <input class="form-style" type="number" name="Phone" placeholder="enter your phone no..." />
            </div>	
            <div class="form-inputs">
                <label>Age <span class="required">*</span></label>
                <input class="form-style" type="number" name="Age" placeholder="enter your age..." />
            </div>	
            <div class="form-inputs">
                <label>Password <span class="required">*</span></label>
                <input class="form-style" type="password" name="Password" placeholder="enter your password..." />
            </div>	
            <div class="form-inputs">
                <label>Confirm Password <span class="required">*</span></label>
                <input class="form-style" type="password" name="Password" placeholder="enter your password again..." />
            </div>	
            <div class="btn-holder">
                <button class="btn-register" type="submit">login</button>
            </div> 		
		</form>
		
		 
	</body>
	<script src="js/script.js"></script>
</html>
