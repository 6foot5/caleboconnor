(function($) {
    function getName(id, obj) {
        for (var key in obj.value) {
            if (obj.value[key] === id) {
                return key;
            }
        }
        return null;
    }

    window.vc_wordpress_magic360_admin = vc.shortcode_view.extend({
        render: function () {
            window.vc_wordpress_magic360_admin.__super__.render.call(this);

            this.customRender();
            return this;
        },

        customRender: function() {
            var element = this.$el,
                title = $.trim(this.model.getParam('title')),
                shortcode = $.trim(this.model.getParam('shortcode')),
                wrapper = $(element.find('.wpb_element_wrapper')),
                elementTitle = wrapper.find('.wpb_element_title'),
                content = $('<div class="vc_wordpress_magic360_params">'), paramElement,
                shortcodeName = getName(shortcode, this.params.shortcode);

            if (title !== '') {
                paramElement = $('<p>');
                paramElement.html('<b>Title:</b> ' + title);
                content.append(paramElement);
            }

            paramElement = $('<p>');
            if (shortcode !== 'empty') {
                paramElement.html('<b>Shortcode name:</b> ' + shortcodeName + '<br/><b>Shortcode ID:</b> ' + shortcode);
            } else {
                paramElement.html('The block is empty and you will see nothing.<br/>Please choose some shortcode or create shortcode in Magic 360 constructor.');
            }
            content.append(paramElement);

            wrapper.html('');
            wrapper.append(elementTitle);
            wrapper.append(content);
        },
        ready: function (e) {
            window.vc_wordpress_magic360_admin.__super__.ready.call(this, e);
            return this;
        },
        changeShortcodeParams: function (model) {
            window.vc_wordpress_magic360_admin.__super__.changeShortcodeParams.call(this, model);
            this.customRender(this.$el, model.getParam('title'), model.getParam('shortcode'));
        },
        changeShortcodeParent: function (model) {
            window.vc_wordpress_magic360_admin.__super__.changeShortcodeParent.call(this, model);
        },
        deleteShortcode: function (e) {
            window.vc_wordpress_magic360_admin.__super__.deleteShortcode.call(this, e);
        },
        editElement: function (e) {
            window.vc_wordpress_magic360_admin.__super__.editElement.call(this, e);
        },
        clone: function (e) {
            window.Vvc_wordpress_magic360_admin.__super__.clone.call(this, e);
        }
    });
})(window.jQuery);