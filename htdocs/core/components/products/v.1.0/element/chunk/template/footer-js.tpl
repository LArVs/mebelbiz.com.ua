<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.js"></script>

<script type="text/javascript">
$(function() {
	$('.carousel').carousel();
	$('.buy.btn').hover( function() {
		$(this).parent().toggleClass('buy-this');
	});
});
</script>
