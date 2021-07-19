<?php
if(count(get_included_files()) == 1){
	header('HTTP/1.0 403 Forbidden');
	exit;
}
?>

	<!-- footer -->
	<div class="footer">
		<div class="copyright">
			<p>© 2016 . All rights reserved | Design by <a href="http://w3layouts.com">W3layouts</a></p>
			<p><img src="images/masafat.png" alt=""> Edited & Programmed by <a href="http://masafat.co">MASAFAT.Co</a> </p>
		</div>
	</div>
	<!-- //footer -->
	<script>
	    		const toast = swal.mixin({
		  toast: true,
		  position: 'top-end',
		  showConfirmButton: false,
		  timer: 3000
		});

	</script>
	<script src="js/jarallax.js"></script>
	<script type="text/javascript">
		/* init Jarallax */
		$('.jarallax').jarallax({
			speed: 0.5,
			imgWidth: 1366,
			imgHeight: 768
		})
		
	</script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/js/ion.rangeSlider.min.js"></script>

	<script src="js/ion.rangeSlider.min.js"></script>
	<script src="js/responsiveslides.min.js"></script>
	<script type="text/javascript" src="js/move-top.js"></script>
	<script type="text/javascript" src="js/easing.js"></script>
	
	<!-- here stars scrolling icon -->
	<script type="text/javascript">
		$(document).ready(function() {
			/*
				var defaults = {
				containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear'
				};
			*/

			$().UItoTop({ easingType: 'easeOutQuart' });

			});
	</script>
	<!-- //here ends scrolling icon -->
	<!-- pricing -->
	<script src="js/jquery.flipster.js"></script>
	<script>
	<!--

		$(function(){ $(".flipster").flipster({ style: 'carousel', start: 0 }); });

	-->
	</script>
	<!-- //pricing -->
	<!-- slider -->
	<script type="text/javascript" src="js/jquery.immersive-slider.js"></script>
	<!-- //slider -->
