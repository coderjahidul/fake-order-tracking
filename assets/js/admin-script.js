jQuery(document).ready(function($) {
    $('.check-dsr-again').on('click', function() {
        const button = $(this);
        const orderId = button.data('order-id');
        button.text('Checking...');
        console.log(orderId);

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'cdc_dsr_refresh',
                order_id: orderId,
                nonce: cdc_ajax_object.nonce
            },
            success: function(response) {
                if(response.success) {
                    let phone = response.data.phone;
                    let total_parcels = response.data.total_parcels;
                    let total_delivered = response.data.total_delivered;
                    let total_cancel = response.data.total_cancel;
                    let message = response.data.message;

                    button.text('Check Again');
                    button.closest('.dsr-metrics').find('.dsr_mobile-number').text(phone);
                    button.closest('.dsr-metrics').find('.dsr_total_parcels').text(total_parcels);
                    button.closest('.dsr-metrics').find('.dsr_total_delivered').text(total_delivered);
                    button.closest('.dsr-metrics').find('.dsr_total_cancel').text(total_cancel);
                    button.closest('.dsr-metrics').find('.dsr_message_text').text(message).css('color', 'green');
                }else{
                    // error message
                    let message = response.data.message;
                    button.closest('.dsr-metrics').find('.dsr_message_text').text(message).css('color', 'red');
                    button.text('Check Again');
                }
            }
        });
    });
});
