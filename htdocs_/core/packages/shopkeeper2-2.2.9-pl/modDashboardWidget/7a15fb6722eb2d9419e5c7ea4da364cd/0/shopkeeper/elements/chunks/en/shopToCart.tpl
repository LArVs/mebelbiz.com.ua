<div class="product-tocart shk-item">
<form action="[[~[[*id]]? &scheme=`abs`]]" method="post">
  <input type="hidden" name="shk-id" value="[[*id]]" />
  <input type="hidden" name="shk-name" value="[[*pagetitle]]" />
  <input type="hidden" name="shk-count" value="1" size="2" maxlength="3" />
  <div align="right">
    Price: <span class="shk-price" id="stuff_[[*id]]_price">$[[*price]]</span>
    <button type="submit" name="shk-submit" class="shk-but">Add to cart</button>
  </div>
</form>
</div>
