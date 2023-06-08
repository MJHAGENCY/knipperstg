(function ($) {
    $(document).ready(function () {
        function ssClickHandler(e,clickedBy) {
            var $button = this != document ? $(this) : $(clickedBy),
                projectId = parseInt($button.data('pid')),
                networkId = parseInt($button.data('nid')),
                postId = parseInt($button.data('post-id')),
				additionalObjectCode = $button.attr('data-plugin-code'),
                data = {},
                url = $button.data('url');

            if ($button.hasClass('trigger-popup')) {
                return;
            }

            data.action = 'social-sharing-share';
            data.project_id = projectId;
            data.network_id = networkId;
            data.post_id = isNaN(postId) ? null : postId;
            data.nonce = SSBS_NONCE_FRONTEND;

			if (additionalObjectCode == 'mbs') {
				var additionalObjectItemId = parseInt($button.attr('data-plugin-item-id'))
				,	additionalObjectItemType = $button.attr('data-plugin-item-code');
				if(!isNaN(additionalObjectItemId)) {
					data.additional_object_code = additionalObjectCode;
					data.additional_object_item_id = additionalObjectItemId;
					data.additional_object_item_type = additionalObjectItemType;
				}
			}

         if (additionalObjectCode == 'gg') {
            var additionalObjectItemId = parseInt($button.closest('[data-attachment-id]').attr('data-attachment-id'))
				,	additionalObjectItemType = data.post_id;
				if(!isNaN(additionalObjectItemId)) {
					data.additional_object_code = additionalObjectCode;
					data.additional_object_item_id = additionalObjectItemId;
					data.additional_object_item_type = additionalObjectItemType;
				}
         }

            $.post(url, data).done(function () {
                $button.find('.counter').text(function (index, text) {
                    if (isNaN(text)) {
                        return text;
                    }

                    return parseInt(text) + 1;
                });
            });

            /** e.preventDefault(); **/
        };

        $(document.body).on('click', '.supsystic-social-sharing a.social-sharing-button', ssClickHandler);
        $(document).on('ssSocialClick', ssClickHandler);
    });
}(jQuery));
// в /plugins/gallery-by-supsystic/src/GridGallery/Galleries/assets/js/frontend.js в стр.1605 добавить $(document).trigger('ssSocialClick', this);
