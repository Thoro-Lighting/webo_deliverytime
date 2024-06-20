{if !empty($deliveryTimeSum)}
    <div class="row">
        <div class="col-12">
            <span class="font-weight-bold">
                    {l s='Przwidywany czas dostawy to %s dni roboczych' sprintf = ['%s' => $deliveryTimeSum] d='Modules.Webideliverytime.Front'}
            </span>
        </div>
    </div>
{/if}
