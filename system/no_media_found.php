<html>
	<head>
	<title><?php echo $lang_error_404_title ?></title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<style>
	body {
		background-color: #242424;
		font-family: "Helvetica","Arial",sans-serif;
	}
	.error-main-content {
		height: 100%;
		width: 500px;
		position: fixed;
		padding: 200px 0px 100px 95px;
	}
	.error-nfbutton {
		font-size: 14px;
		text-transform: uppercase;
		text-decoration: none;
		color: #fff;
		border: 2px solid #fff;
		border-radius: 99px;
		padding: 12px 30px 12px;
		display: inline-block;
		float: left;
		margin-right: 20px;
	}
	.error-nfbutton:hover {
		background-color: rgba(255,255,255,0.05);
	}
	</style>
	</head>
	<body>
		<div class="error-main-content">
		<div style="color:#dc4e41;font-size:48px;line-height:60px;margin-bottom:5px;"><?php echo $lang_error_404_title ?></div>
		<div style="color:#fff;font-size:30px;line-height:40px;margin-bottom:5px;"><?php echo $lang_error_404_subtitle ?></div>
		<div style="border-top:1px solid #505050;margin-top:30px;"></div>
		<div style="color:#fff;font-size:18px;line-height:27px;margin-top:30px;margin-bottom:30px;"><?php echo $lang_error_404_text ?></div>
		<a class="error-nfbutton" href="index.php"><?php echo $lang_error_404_home_button ?></a>
		<a class="error-nfbutton" href="index.php?suggest=true"><?php echo $lang_error_404_suggest_button ?></a>
		</div>
	</body>
</html>