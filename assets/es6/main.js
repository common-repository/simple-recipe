(function ($) {
    $(document).ready(function () {
        $('.smrc_ingredients .ingredient').on('click', function () {
            $(this).toggleClass('active');
        });

        $('.smrc_rating_form input').on('change', function () {
            let value = $(this).val();
            for(let i = 1; i <= 5; i++){
                $(this).parent().removeClass('active-' + i);
            }
            $(this).parent().addClass('active-' + value);
        });

        $('.smrc_rating_form').on('submit', function (e) {
            e.preventDefault();
            let rate = $(this).find('input[name=rating]:checked').val();
            let postId = $(this).data('post-id');
            console.log(postId);
            console.log(rate);
            if(typeof rate !== 'undefined' && typeof postId !== 'undefined') {
                $.ajax({
                    url: smrc_url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        action: 'smrc_rate_recipe',
                        rate: rate,
                        post_id: postId,
                        nonce: smrc_nonces.smrc_rate_recipe
                    },
                    success: function () {
                        $('.smrc_rating_form').remove();
                        $.cookie('smrc_rating_' + postId, '1', { expires: 365 });
                    }
                });
            }
        })

        $('.ingredients_toggle').on('click', function () {
            let ingredients = $(this).parent().find('.smrc_ingredients');
           if($(this).hasClass('active')){
               ingredients.toggleClass('active');
               $(this).removeClass('active');
           }
           else {
               $(this).addClass('active');
               if(ingredients.hasClass('active')){
                   ingredients.removeClass('active');
                   $(this).removeClass('active');
               }
           }


        });
    });
})(jQuery);