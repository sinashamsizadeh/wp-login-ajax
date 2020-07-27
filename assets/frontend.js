(function ($) {
    $(document).ready(function () {
        // Show the login dialog box on click
        $('a#show_login').on('click', function (e) {
            e.preventDefault();
            $('body').prepend('<div class="login_overlay"></div>');
            $('.wp-login-ajax-login-wrap').fadeIn(500);
            $('div.login_overlay, .wp-login-ajax-login-wrap a.close').on('click', function () {
                $('div.login_overlay').remove();
                $('.wp-login-ajax-login-wrap').hide();
            });
        });

        // Perform AJAX login on form submit
        $('.wp-login-ajax-login-wrap').on('submit', function (e) {
            var $status = $('.wp-login-ajax-login-wrap .status');
            e.preventDefault();

            $status.removeClass('wrong');
            $status.fadeIn('fast');
            $status.addClass('loading').text('Please wait...');

            $.ajax({
                url: wp_login_ajax_localize.ajax.url,
                type: 'POST',
                dataType: 'json',
                data: {
                    nonce: wp_login_ajax_localize.ajax.nonce,
                    action: 'live_login', //calls wp_ajax_nopriv_live_login
                    username: $('.wp-login-ajax-login-wrap #user_login').val(),
                    password: $('.wp-login-ajax-login-wrap #user_pass').val(),
                },
                success: function (data) {
                    $status.removeClass('loading').empty();
                    if (data.status == true) {
                        $status.addClass('success').text(wp_login_ajax_localize.translation.login.success);
                        setTimeout(window.location.replace($('.wp-login-ajax-login-wrap').attr('action')), 1000);
                    } else if (data.status == false) {
                        $status.addClass('wrong').text(wp_login_ajax_localize.translation.login.fail);
                    }
                },

            });
        });
    });
})(jQuery);