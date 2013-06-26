<div class="media info">

	<a href="[[~[[+id]]]]">
		<img alt="[[+tv.category]]: [[+tv.name]]" src="[[+tv.image:phpthumbof=`w=140&h=140&zc=1`]]" data-src="holder.js/140x140" class="media-object img-polaroid thumb pull-left">
	</a>

	<div class="media-body pull-left">
		<h2 class="media-heading name">[[+tv.category:cat=`: `]][[+tv.name]]</h2>

		<ul>
			<li class="size">
				<span class="muted">размеры:</span>
				[[+tv.size]]
			</li>
			<li class="color">
				<span class="muted">цвет:</span>
				[[+tv.color:ellipsis=`20`]]
			</li>
			<li class="article">
				<span class="muted">артикул:</span>
				[[+tv.article]]
			</li>
			<li class="cost">
				<span class="badge badge-warning">[[+tv.cost:multiply=`[[$cost-ratio]]`:cat=` [[$cost-currency]]`]]</span>
			</li>
		</ul>

		[[-
		<li>цена: [[+tv.cost]]</li>
		<li>category: [[+tv.category]]</li>
		<li>manufacturer: [[+tv.manufacturer]]</li>
		]]

	</div>

	<button class="buy btn btn-warning pull-right" type="button">Заказать</button>

	<div class="clearfix"></div>

</div>

