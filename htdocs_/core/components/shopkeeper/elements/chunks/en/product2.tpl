<div class="product shk-item">
  <div class="product-b">
    <h3>[[+pagetitle]]</h3>
    <form action="[[~[[*id]]]]" method="post">
    <input type="hidden" name="shk-id" value="[[+id]]" />
      <div class="product-descr">
        <img class="shk-image" src="[[+tv.image]]" alt="" height="130" width="130" />
        <div>Price:<br /> <span class="shk-price">$[[+tv.price:num_format]]</span></div>
        <br />
        <div>[[+tv.param1]]</div>
        <br />
        <div>Color:<br />[[+tv.param2]]</div>
      </div>
      <div align="center">
         <input type="text" name="shk-count" value="1" size="2" maxlength="3" />
         &nbsp;
         <button type="submit" name="shk-submit" class="shk-but" style="float:none;">Add to cart</button>
      </div>
    </form>
  </div>
</div>