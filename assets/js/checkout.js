jQuery(function($) {
    function checkDSR(phone) {
        console.log('Checking DSR for phone:', phone.length);
        $.ajax({
            type: 'POST',
            url: cdc_ajax_object.ajax_url,
            data: {
                action: 'check_dsr_score',
                phone: phone
            },
            success: function(response) {
                if (response.success && response.data.dsr !== undefined) {
                    const dsr = parseFloat(response.data.dsr);
                    console.log('DSR:', dsr);

                    if (dsr > 50) {
                        $('#payment .payment_method_cod').show();
                    }
                }
            }
        });
    }

    // Monitor phone input field change (Woo default field ID)
    $(document).on('keyup', 'input[name="billing_phone"]', function() {
        const phone = $(this).val();
        console.log('Phone:', phone.length);
        if (phone.length == 11) {
            checkDSR(phone);
        }
    });
});
