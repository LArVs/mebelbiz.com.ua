
<p>Date: [[+date]].</p>

<p>Order number: [[+orderID]].</p>

<ul>

[[+loop]]
    [[+name:is=`Delivery`:then=`
    <li><b>[[+name]]</b> [[+addit_data]]</li>
    `:else=`
    <li><b><a href="[[+link]]">[[+name]]</a></b> ([[+price]]) [[+addit_data]] <b>x [[+count]]</b></li>
    `]]
[[+end_loop]]

</ul>

<br />

Total price: <b>[[+price_total]] [[+currency]]</b>
<br />
