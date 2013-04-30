
<p>You made ​​an order ([[+date]]) in the online store &laquo;[[++site_name]]&raquo;.</p>

<p>Order number: [[+orderID]].</p>

<p>Order changed the status &quot;[[+status]]&quot;.</p>

<b>Composition:</b>

<ul>

[[+loop]]
  <li>[[+s]]<b><a href="[[+link]]" target="_blank">[[+name]]</a></b> ([[+price]])[[+addit_data]] <b> x [[+count]] items</b>[[+/s]]</li>
[[+end_loop]]

</ul>

<br />

Total price: <b>[[+price_total]]</b> [[+currency]]

<br /><br />

[[+order_changed_txt]]

[[+order_notpossible_txt]]

<p>
  <b>Contact Information:</b><br />
  [[+contacts]]
</p>

[[+plugin]]

