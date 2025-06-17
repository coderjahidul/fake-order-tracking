jQuery(function($) {
    function checkDSR(phone) {
        console.log('Checking DSR for phone:', phone);
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
                    }else{
                        $('#payment .payment_method_cod').hide();
                    }
                }
            }
        });
    }

    // Monitor phone input field change (Woo default field ID)
    $(document).on('keyup', 'input[name="billing_phone"]', function() {
        let phone = $(this).val().replace(/\D/g, ''); // Remove non-numeric characters
        
        // Remove +88 or 880 from beginning
        if (phone.startsWith('88')) {
            phone = phone.substring(2);
        }
    
        // Check if phone number is now 11 digits
        if (phone.length === 11) {
            checkDSR(phone);
        }
    });
});

