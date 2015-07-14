<?php
try {
	require_once("init.php");
	if (empty($_SESSION['user']) || !is_file("pdfs/".$_GET['ISBN'].".pdf")) {
		echo "<script>";
		echo 'window.location = "index.php"';
		echo "</script>";
	}

} catch (Exception $e) {
}

require_once ("headers/head.php");
require_once ("headers/pageHeader.php");
?>
	<script type="text/javascript">
		document.body.style.backgroundImage = "url('images/backgroundImageUserSignIn.jpg')";
	</script>
		<div id="content">
			<div id="readerPdfContainer">
			  <object data="pdfs/<?php echo $_GET['ISBN']?>.pdf" type="application/pdf" width="100%" height="100%">
	  				<p>It appears you don't have a PDF plugin for this browser.
				     No biggie... you can <a href="pdfs/<?php echo $_GET['ISBN']?>.pdf">click here to
				     download the PDF file.</a></p>
			  </object>
 			</div>
		</div>
		

<?php 
	require_once ("footers/footerSignInPage.php");
?>