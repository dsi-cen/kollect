<section class="container-fluid blanc">
	<header class="row">
		<div class="col-md-12 col-lg-12 mt-3">
			<h1 class="h2 text-center">Gestion des photos</h1>
		</div>
	</header>
	<div class="row mt-2">
		<div class="col-md-12 col-lg-12">
			<div id="elfinder"></div>
		</div>
	</div>
</section>
<script>
$(document).ready(function() {
	$('#elfinder').elfinder({
		url : 'lib/elfinder/connector.php',
		lang: 'fr',
		height : '600'
	});
});
</script>