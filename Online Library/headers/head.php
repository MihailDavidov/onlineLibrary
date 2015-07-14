<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
<link rel="stylesheet" href="styles/style.css">
<script src="js/jquery-1.11.3.min.js"></script>
<script>
 		function changeStatus(element,status,isbn) {
 	 		<?php 
 	 			if (empty($_SESSION['user'])) {
 	 				echo "window.location = 'signin.php';";
 	 			}
 	 		?>
 			var triger = $(element).closest("div.statusButton").find("span.progressTriger");
 			var indicator = $(element).closest("div.statusButton").find("span.progressIndicator");
 			triger.hide();
 			indicator.show();
 			var posting = $.post("ajax.php", {"action": "changeBookStatus", "ISBN": isbn,"status": status} );
			 
			posting.done(function(data) {
				var obj = jQuery.parseJSON(data);
				if (obj.result == true) {
					
	 				if (status == 'read') {
		 				triger.text('Read');
		 			} else if (status == 'reading') {
		 				triger.text('Currently Reading');
		 			} else {
		 				triger.text('Want To Read');
		 			}
				}
				indicator.hide(0);
	 			triger.show(0);
			});
 		}


 		$( document ).ready(function() {
 	 		$("input.star").click(function() {
 	 	 		<?php 
 	 	 	 			if (empty($_SESSION['user'])) {
 	 	 	 				echo "window.location = 'signin.php';";
 	 	 	 			}
 	 	 	 	?>
 	 	 		var rating = $(this).attr('id').substr($(this).attr('id').length - 1);
 	 	 		var isbn = $(this).parent().attr('id');
 	 	 		
 	 	 		var posting = $.post("ajax.php", {"action": "rateBook", "ISBN": isbn,"rating": rating} );
 				 
 				posting.done(function(data) {
 					var obj = jQuery.parseJSON(data);
 					if (obj.result == true) {
 						$('form#'+isbn+' .star').prop("disabled", true);
 					}

 				});
 	 		});
 		});
 		
 </script>
</head>
<body>
