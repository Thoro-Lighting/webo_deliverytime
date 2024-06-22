<div class="row mt-4">
    <div class="col-md-12">
        <h2>{l s='Czas dostawy' d='Modules.WeboDeliverytime.Admin'}</h2>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label class="form-control-label" for="delivery_time_on_stock">
                {l s='Czas dostawy w dniach kiedy produkt jest na stanie' d='Modules.WeboDeliverytime.Admin'}
            </label>
            <input type="text"
                   id="delivery_time_on_stock"
                   name="delivery_time_on_stock"
                   class="form-control js-delivery-time-item"
                   {if !empty($delivery_time_on_stock)}
                       value="{$delivery_time_on_stock}"
                   {/if}
            >
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-control-label" for="delivery_time_out_of_stock">
                {l s='Czas dostawy w dniach kiedy produkt jest wyprzedany' d='Modules.WeboDeliverytime.Admin'}
            </label>
            <input type="text"
                   id="delivery_time_out_of_stock"
                   name="delivery_time_out_of_stock"
                   class="form-control js-delivery-time-item"
                   {if !empty($delivery_time_out_of_stock)}
                       value="{$delivery_time_out_of_stock}"
                   {/if}
            >
        </div>
    </div>

    <input type="hidden" name="weboDeliveryTimeUpdateAfter" class="d-none js-send-delivery-time-update" value="0">
</div>
