<!DOCTYPE html>
<html lang="en">
<head>
	  <link rel="icon" type="image/jpg" href="pages/images/logo.png" >
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
    <meta name="author" content="GeeksLabs">
    <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
    <!-- <link rel="shortcut icon" href="img/favicon.png"> -->

    <title>Si Kupat - <?php echo '2020'; ?></title>

    <!-- Bootstrap CSS -->    
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- bootstrap theme -->
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <!--external css-->
    <!-- font icon -->
    <link href="css/elegant-icons-style.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="plugin/jquery-ui.css" />
    <script src="plugin/jquery-1.8.3.js"></script>
    <script src="plugin/jquery-ui.js"></script>
    
    <script>
/*autocomplete muncul setelah user mengetikan minimal1 karakter */
    $(function() {  
        $( "#username" ).autocomplete({
         source: "data.php",  
           minLength:1, 
        });
    });
    
    </script>

    <style>
      body {
        background: url("img/bg.png") no-repeat center fixed;
			  background-size: cover;
      }
    </style>
    
</head>

  <body class="login-img3-body">
    <div class="container" style="margin-top: -50px">
        <form class="login-form" style="border-radius: 20px 20px 20px 20px" action="periksa_login.php" method="post">        
        <div class="login-wrap text-center">
            <img src="pages/images/logo.png" width="50px">
            <p class="login-img" style="font-size: 45px;"><b><i>Si Kupat<i></b><sub style="font-size: 18px; font-weight: bold">2020</sub></p>
            <p style="font-size: 15px; margin-top: -15px; margin-bottom: 15px; color: black"><i>Sistem Informasi Keuangan Cepat</i></p>
            <div class="input-group">
              <span class="input-group-addon"><i class="icon_profile"></i></span>
              
              <input name="username" type="text" id="username" placeholder="Username" class="form-control"/>
            </div>
            <div class="input-group">
                <span class="input-group-addon"><i class="icon_key_alt"></i></span>
                <input name="password" type="password" class="form-control" placeholder="Password">
            </div>
            
            <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
            <hr>
            <p style="font-size: 12px; color: black; margin: -10px 0px -10px 0px;"><i>Copyright 2019 &copy; Dinas Kesehatan Jepara</i></p>
        </div>
      </form>

    </div>


  </body>
</html>
