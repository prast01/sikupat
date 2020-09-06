<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>E-Kohort</title>
        <meta name="description" content="Custom Login Form Styling with CSS3" />
        <meta name="keywords" content="css3, login, form, custom, input, submit, button, html5, placeholder" />
        <meta name="author" content="Codrops" />
        <link rel="shortcut icon" href="../favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="css/style.css" />
		<script src="js/modernizr.custom.63321.js"></script>
		<!--[if lte IE 7]><style>.main{display:none;} .support-note .note-ie{display:block;}</style><![endif]-->
		<style>	
			body {
				background: #7f9b4e url(images/bg2.jpg) no-repeat center top;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				background-size: cover;
			}
			.container > header h1,
			.container > header h2 {
				color: #fff;
				text-shadow: 0 1px 1px rgba(0,0,0,0.7);
			}
		</style>
    </head>
    <link rel="stylesheet" href="../plugin/jquery-ui.css" />
    <script src="../plugin/jquery-1.8.3.js"></script>
    <script src="../plugin/jquery-ui.js"></script>
    
    <script>
/*autocomplete muncul setelah user mengetikan minimal1 karakter */
    $(function() {  
        $( "#username" ).autocomplete({
         source: "data_username_nakes.php",  
           minLength:1, 
        });
    });
    
    </script>
    
    <body>
        <div class="container">
		
			<!-- Codrops top bar -->
			
			<header>
			
				<h1>E- <strong>KOHORT</strong></h1>
				<h2>Dinas Kesehatan Kabupaten Jepara</h2>
				

				<div class="support-note">
					<span class="note-ie">Sorry, only modern browsers.</span>
				</div>
				
			</header>
			
			<section class="main">
				 <form class="form-4" action="periksa_login.php" method="post">
				    <h1>Login or Register</h1>
				    <p>
				        <label for="login">Username</label>
				        <input name="username" type="text" id="username" placeholder="Username" class="form-control" required/>
				    </p>
				    <p>
				        <label for="password">Password</label>
				        <input name="password" type="password" class="form-control" placeholder="NO KTP" required> 
				    </p>

				    <p>
				        <input type="submit" name="submit" value="Continue">
				    </p>       
				</form>​
			</section>
			
        </div>
    </body>
</html>