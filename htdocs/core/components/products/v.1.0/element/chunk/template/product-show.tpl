<!-- ---------{ product --------- -->
<div class="row-fluid">
	<div class="span5"><div class="media-body pull-left">
		<h2 class="media-heading name">[[*category:cat=`: `]][[*name]]</h2>

		<ul>
			<li class="size">
				<span class="muted">размеры:</span>
				[[*size]]
			</li>
			<li class="color">
				<span class="muted">цвет:</span>
				[[*color]]
			</li>
			<li class="manufacturer">
				<span class="muted">производитель:</span>
				[[*manufacturer]]
			</li>
			<li class="article">
				<span class="muted">артикул:</span>
				[[*article]]
			</li>
			<li class="cost">
				Старая цена:
				<span class="badge">
					[[*cost:multiply=`[[$cost-ratio]]`:cat=` [[$cost-currency]]`]]
				</span>
				<br />
				Новая цена:
				<span class="badge badge-warning">
					[[*cost:cat=` [[$cost-currency]]`]]
				</span>
			</li>
		</ul>
	</div></div>

	<div class="span7">
		<div class="content">
		[[*content]]
		</div>
	</div>

	<button class="buy btn btn-warning pull-right" type="button">Заказать</button>

</div>
<!-- ---------- product }-------- -->
