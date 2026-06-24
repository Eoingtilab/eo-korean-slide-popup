(function($){
	function getEditorId($textarea){
		var id = $textarea.attr('id');
		if(!id){
			id = 'eoksp_html_editor_' + Math.floor(Math.random() * 1000000);
			$textarea.attr('id', id);
		}
		return id;
	}

	function removeHtmlEditor($textarea){
		if(!window.wp || !wp.editor){
			return;
		}
		var id = $textarea.attr('id');
		if(!id){
			return;
		}
		try{
			if(typeof tinymce !== 'undefined' && tinymce.get(id)){
				tinymce.get(id).save();
			}
			wp.editor.remove(id);
		}catch(e){}
		$textarea.removeData('eoksp-editor-ready');
	}

	function initHtmlEditors($scope){
		if(!window.wp || !wp.editor || typeof wp.editor.initialize !== 'function'){
			return;
		}

		$scope.find('.eoksp-html-editor').each(function(){
			var $textarea = $(this);
			var id = getEditorId($textarea);

			if(id.indexOf('__INDEX__') !== -1 || $textarea.data('eoksp-editor-ready')){
				return;
			}

			try{
				wp.editor.initialize(id, {
					tinymce: {
						wpautop: true,
						height: 260,
						toolbar1: 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,unlink,undo,redo',
						toolbar2: '',
						menubar: false
					},
					quicktags: true,
					mediaButtons: true
				});
				$textarea.data('eoksp-editor-ready', true);
			}catch(e){
				$textarea.addClass('code');
			}
		});
	}

	function syncHtmlEditors(){
		if(typeof tinymce === 'undefined'){
			return;
		}
		tinymce.triggerSave();
	}

	function bindSlideItem($item){
		$item.find('.eoksp-slide-type').off('change').on('change', function(){
			var type = $(this).val();
			$item.find('.eoksp-type-panel').hide();
			$item.find('.eoksp-type-' + type).show();
			if(type === 'html'){
				initHtmlEditors($item);
			}
		});

		$item.find('.eoksp-remove-slide').off('click').on('click', function(){
			if(window.confirm(EOKSPAdmin.confirmRemove)){
				$item.find('.eoksp-html-editor').each(function(){
					removeHtmlEditor($(this));
				});
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

		initHtmlEditors($item);
	}

	$(function(){
		var $list = $('#eoksp-slides-list');
		bindSlideItem($list.find('[data-slide-item]'));

		$('#eoksp-add-slide').on('click', function(){
			syncHtmlEditors();
			var nextIndex = $list.find('[data-slide-item]').length;
			var template = $('#tmpl-eoksp-slide-item').html().replace(/__INDEX__/g, nextIndex);
			var $item = $(template);
			$list.append($item);
			bindSlideItem($item);
		});

		$('#post').on('submit', function(){
			syncHtmlEditors();
		});
	});
})(jQuery);
