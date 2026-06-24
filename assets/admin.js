(function($){
    function bindSlideItem($item){
        $item.find('.eoksp-slide-type').off('change').on('change', function(){
            var type = $(this).val();
            $item.find('.eoksp-type-panel').hide();
            $item.find('.eoksp-type-' + type).show();
        });

        $item.find('.eoksp-remove-slide').off('click').on('click', function(){
            if(window.confirm(EOKSPAdmin.confirmRemove)){
                $item.remove();
            }
        });

        $item.find('.eoksp-select-image').off('click').on('click', function(e){
            e.preventDefault();
            var $wrap = $(this).closest('.eoksp-type-image');
            var frame = wp.media({
                title: EOKSPAdmin.chooseImage,
                button: { text: EOKSPAdmin.useImage },
                multiple: false,
                library: { type: 'image' }
            });
            frame.on('select', function(){
                var attachment = frame.state().get('selection').first().toJSON();
                $wrap.find('.eoksp-image-id').val(attachment.id || '');
                $wrap.find('.eoksp-image-url').val(attachment.url || '');
                $wrap.find('.eoksp-image-preview').attr('src', attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url || '');
                $wrap.find('.eoksp-image-preview-wrap').addClass('has-image');
            });
            frame.open();
        });

        $item.find('.eoksp-remove-image').off('click').on('click', function(e){
            e.preventDefault();
            var $wrap = $(this).closest('.eoksp-type-image');
            $wrap.find('.eoksp-image-id, .eoksp-image-url').val('');
            $wrap.find('.eoksp-image-preview').attr('src', '');
            $wrap.find('.eoksp-image-preview-wrap').removeClass('has-image');
        });
    }

    $(function(){
        var $list = $('#eoksp-slides-list');
        bindSlideItem($list.find('[data-slide-item]'));

        $('#eoksp-add-slide').on('click', function(){
            var nextIndex = $list.find('[data-slide-item]').length;
            var template = $('#tmpl-eoksp-slide-item').html().replace(/__INDEX__/g, nextIndex);
            var $item = $(template);
            $list.append($item);
            bindSlideItem($item);
        });
    });
})(jQuery);
