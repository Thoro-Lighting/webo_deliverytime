$(() => {
    $('.js-delivery-time-item').on('change', function () {
        const $updateField = $('.js-send-delivery-time-update');

        if ($updateField.val() != 1) {
            $updateField.val(1);
        }

    });
});
