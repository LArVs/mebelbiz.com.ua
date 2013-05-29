<div class="product shk-item">
  <div class="product-b">
    <div class="product-descr">
      <a href="[[~[[+id]]? &scheme=`abs`]]"><img class="shk-image" src="[[+tv.image]]" alt="" height="130" width="130" /></a>
      <h3>[[+pagetitle]]</h3>
      [[+introtext]]<br />
      <a href="[[~[[+id]]? &scheme=`abs`]]">More &rsaquo;</a>
      <div style="clear:both;"></div>
      <small>In stock: [[+tv.inventory]]</small>
    </div>
    <form action="[[~[[*id]]? &scheme=`abs`]]" method="post">
      <fieldset>
        <input type="hidden" name="shk-id" value="[[+id]]" />
        <input type="hidden" name="shk-name" value="[[+pagetitle]]" />
        <input type="hidden" name="shk-count" value="1" size="2" maxlength="3" />
        <div class="product-price">
          <button type="submit" class="shk-but">Add to cart</button>
          <div>Price: <span class="shk-price">$[[+tv.price:num_format]]</span></div>
        </div>
      </fieldset>
    </form>
  </div>
</div>