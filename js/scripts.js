(function($) {
    $(document).ready(function() {
        zebraCRM.init();
    });

    var zebraCRM = {
        init: function() {
            zebraCRM.zebraCRMFormSubmit();
        },

        zebraCRMFormSubmit: function() {
            var $form = $('#zebracrm-form');

            $form.validate({
                rules: {
                    first_name: {
                        required: true,
                        minlength: 3
                    },
                    last_name: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true
                    }
                },
                submitHandler: function (form) {

                    $.ajax({
                        url: zcrm.ajax_url,
                        type: 'POST',
                        dataType: 'JSON',
                        data: $form.serialize(),
                        success: function(data) {
                            var $container = $('.zebracrm-container');
                            if (data.error) {
                                $container.find('div.msg')
                                    .addClass('failed').show();
                            }
                            else {
                                $(form)[0].reset();
                                $container.find('div.msg')
                                    .addClass('success').show();
                            }

                            $container.find('div.msg > span').text(data.msg);
                            setTimeout(function() {
                                $container.find('div.msg').removeClass('failed');
                                $container.find('div.msg').removeClass('success');

                                $container.find('div.msg').hide();
                            }, 5000)

                        }
                    });

                    return false;
                }
            });
        }
    }
})(jQuery);