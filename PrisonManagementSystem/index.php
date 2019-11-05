<html>
	<head>
		<title>PMS | Home</title>
	</head>
	<body>
		<h1>Log-in</h1>
			<form method="post" action="officerLogin.php">
                    <div class="form-inputs">
                         <label>Officer ID <span class="required">*</span></label>
                         <input class="form-style" type="text" name="OfficerId" placeholder="enter your id..." />
                    </div>
                    <div class="form-inputs">
                          <label>Password <span class="required">*</span></label>
                          <input class="form-style" type="text" name="OfficerPassword" placeholder="enter your Password..." />
                    </div>
                    <div class="btn-holder">
                           <button class="btn-register" type="submit">login</button>
                    </div>                          
               </form>
               <p>not yet registerd<a href="OfficerRegister.php">Register Now</a></p>		
	</body>
</html>
