[[!prepareFilterForm]]

<div class="search-product">
<form>
	<fieldset>
		<legend>Фильтр</legend>

		<label>Цена</label>
		<div class="row-fluid">
			<div class="span6 input-prepend">
				<span class="add-on">от</span>
				<input class="span7" type="text" id="prependedInput" name="cost_from" value="[[+form_filter.cost_from]]">
			</div>

			<div class="span6 input-prepend">
				<span class="add-on">до</span>
				<input class="span7" type="text" id="prependedInput" name="cost_to" value="[[+form_filter.cost_to]]">
			</div>
		</div>

		<label for="category">Категория</label>
		<div class="row-fluid">
			<select class="span12" name="category">
				[[+form_filter.category]]
			</select>
		</div>

		<label for="manufacturer">Производитель</label>
		<div class="row-fluid">
			<select class="span12" name="manufacturer">
				[[+form_filter.manufacturer]]
			</select>
		</div>

		<label for="sort">Сортировка</label>
		<div class="row-fluid">
			<select class="span12" name="sort">
				[[+form_filter.sort]]
			</select>
		</div>

		<hr>

		<button type="submit" class="btn btn-mini btn-primary"><i class="icon-search icon-white"> </i> Применить</button>
		<a href="[[~[[*id]]]]" class="btn btn-mini pull-right"><i class="icon-remove-circle"> </i> Сбросить</a>
	</fieldset>
</form>
</div>
