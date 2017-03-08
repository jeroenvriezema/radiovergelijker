<script>
		$(document).ready(function() {
			setInterval(function() {
				$.get( "<?php echo BASE_URL; ?>/refresh.php?type=nowPlaying", function(data) {
					$("#nowPlaying").html(data);
				});
			}, 10000);
		});
	</script>