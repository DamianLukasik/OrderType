require(['jquery'], function ($) {
    $(document).ready(function () {
        $('body').on('change', 'input[name="order_type"]', function () {
            sendChoicebyAjax($(this).val());
        });
        sendChoicebyAjax($('input[name=order_type]').first().val());
        function sendChoicebyAjax(val) {
            $.ajax({
                url: '/damian_ordertype/index/savechoice',
                type: 'POST',
                data: {
                    order_type_id: val
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        console.log('The customer\'s selection has been saved.');
                    } else {
                        console.error('An error occurred while saving the customer\'s selection.');
                        console.error(response.error);
                    }
                },
                error: function () {
                    console.error('An error occurred while sending the request.');
                }
            });
        }
    });
});