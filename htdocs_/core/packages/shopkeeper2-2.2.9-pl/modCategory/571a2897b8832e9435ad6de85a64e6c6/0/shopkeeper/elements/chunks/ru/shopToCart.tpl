<div class="product-tocart shk-item">
<form action="[[~[[*id]]? &scheme=`abs`]]" method="post">
  <input type="hidden" name="shk-id" value="[[*id]]" />
  <input type="hidden" name="shk-name" value="[[*pagetitle]]" />
  <input type="hidden" name="shk-count" value="1" />
  <div align="right">
    Цена: <span class="shk-price" id="stuff_[[*id]]_price">[[*price]]</span> руб.
    <button type="submit" name="shk-submit" class="shk-but">В корзину</button>
  </div>
</form>
</div>
