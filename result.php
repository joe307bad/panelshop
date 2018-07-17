<?php
// header('Content-type: text/xml');
$response = urldecode($_GET['response']);
?>
<html>
<head>

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
	<style>
textarea {
	width: 500px !important;
	height: 200px !important;
}
</style>
	<script>
    hbspt.forms.create({
        portalId: '401673',
        formId: '240d8cbc-49fd-443a-b5bf-36e1a021e27b',
        onFormReady($form, ctx){
            $('textarea[name="message"]').val("<?php echo $response; ?>").change();
        }
    });
</script>
</body>
</html>
