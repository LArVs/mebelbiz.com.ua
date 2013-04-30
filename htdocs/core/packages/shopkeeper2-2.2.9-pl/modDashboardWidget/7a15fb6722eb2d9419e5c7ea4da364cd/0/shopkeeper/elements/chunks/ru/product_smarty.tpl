<div class="product shk-item">
    <div class="product-b">
        <div class="product-descr">
            <a href="{link id="{$item.id}"}">
                {if $item['tv.image'] ne ""}
                    <img class="shk-image" src="{$item['tv.image']}" alt="" height="130" width="130" />
                {else}
                    <img class="shk-image" src="/assets/images/nophoto.jpg" alt="" height="130" width="130" />
                {/if}
            </a>
            <h3>{$item.pagetitle}</h3>
            {$item.introtext}<br />
            <a href="{link id="{$item.id}"}">Подробнее &rsaquo;</a>
            <div style="clear:both;"></div>
            <small>На складе: {$item['tv.inventory']}</small>
        </div>
        <form action="{link id="{field name="id"}"}" method="post">
            <fieldset>
                <input type="hidden" name="shk-id" value="{$item.id}" />
                <input type="hidden" name="shk-name" value="{$item.pagetitle}" />
                <input type="hidden" name="shk-count" value="1" />
                <div class="product-price">
                    <button type="submit" class="shk-but">В корзину</button>
                    <div>Цена: <span class="shk-price">{$item['tv.price']}</span> руб.</div>
                </div>
            </fieldset>
        </form>
    </div>
</div>