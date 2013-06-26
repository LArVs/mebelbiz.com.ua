<!DOCTYPE html>

<html>
  <head>
	<meta charset="utf-8">
	<base href="[[++site_url]]">
	<title>[[++site_name]] - [[*pagetitle]]</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="assets/bootstrap/css/bootstrap.css" rel="stylesheet">
	<link href="assets/template/main.css" rel="stylesheet">
	<link href="assets/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="assets/bootstrap/js/html5shiv.js"></script>
	<![endif]-->

	<script src="assets/template/holder.js"></script>

</head>

<body>

<div class="masthead navbar navbar-fixed-top navbar-inverse"><div class="navbar-inner"><div class="container">
	<a class="brand" href="/">[[++site_name]]</a>
	[[Wayfinder? &startId=`0` &level=`1` &outerClass=`nav pull-right`]]
</div></div></div>

<!-- ---------{ container ------- -->
<div class="container main">

	<div id="myCarousel" class="carousel slide">
		<ol class="carousel-indicators">
			<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
			<li data-target="#myCarousel" data-slide-to="1"></li>
			<li data-target="#myCarousel" data-slide-to="2"></li>
		</ol>
		<!-- Carousel items -->
		<div class="carousel-inner">
			<div class="active item">
				<img data-src="holder.js/550x330">
			</div>
			<div class="item">
				<img data-src="holder.js/550x330">
			</div>
			<div class="item">
				<img data-src="holder.js/550x330">
			</div>
		</div>
		<!-- Carousel nav -->
		<a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
		<a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
	</div>

<nav>
	<div class="b-nav-bc">
		[[BreadCrumb?
		&showHidden=`0`
		&showContainer=`1`
		&showUnPub=`0`
		&showHomeCrumb=`1`
			&linkCrumbTpl=`@CODE:<li><a href="[[+link]]" title="[[+longtitle]]">[[+pagetitle:default=`[[+menutitle]]`]]</a></li>`
			&currentCrumbTpl=`@CODE:<li>[[+pagetitle:default=`[[+menutitle]]`]]</li>`
			&containerTpl=`@CODE:<ul class="breadcrumb">[[+crumbs]]</ul>`
		]]
	</div>
</nav>


<div class="row-fluid">
	<div class="span12">
		<div class="content">
		[[*content]]
		</div>
	</div>
</div>

</div>
<!-- ---------- container }------ -->

<footer class="footer"><div class="container">
	<div class="row">
		<span class="span6 pull-left">&copy; [[++site_name]]</span>
		<span class="span6 pull-right text-right"><small><i class="icon-time"></i>&nbsp;Total time: [^t^]</small></span>
	</div>
</div></footer>

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

</body>

</html>

