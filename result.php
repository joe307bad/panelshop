<?php
// header('Content-type: text/xml');
$response = urldecode($_GET['response']);
?>
<html>
<head>
<link rel="stylesheet" id="divi-fonts-css" href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;subset=latin,latin-ext" type="text/css" media="all">
<link rel="stylesheet" type="text/css" href="/new-configurator/otherstyles.css">
<link rel="stylesheet" type="text/css" href="/new-configurator/styles.css">
<style>
form {
    margin-top: 20px !important;
}

textarea {
min-height:200px;
}
</style>
</head>
<body>
	<script src="http://code.jquery.com/jquery-2.2.4.min.js"
		integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
		crossorigin="anonymous"></script>
	<!--[if lte IE 8]>
<script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2-legacy.js"></script>
<![endif]-->
	<script charset="utf-8" type="text/javascript"
		src="//js.hsforms.net/forms/v2.js"></script>
	<!--[if lte IE 8]>
	<script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2-legacy.js"></script>
	<![endif]-->
	<script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2.js"></script>
	<script>
	  hbspt.forms.create({
		portalId: "401673",
		formId: "6fc67009-76dd-4ea4-aaa5-70c9c9042a62",
		css: "",
        onFormReady($form, ctx){
            $('textarea[name="message"]').val("<?php echo $response; ?>").change();
        }
	});
	</script>
</body>
</html>
