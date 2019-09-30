(function($) {
    function get_wordpress_magic360_MainContainer(toolEl) {
        var result = null;
        return result;
    }

    window.InlineShortcodeView_vc_wordpress_magic360_shortcode = window.InlineShortcodeView.extend({
        render: function () {
            var name, tool, toolEl, el, main;
            window.InlineShortcodeView_vc_wordpress_magic360_shortcode.__super__.render.call(this);

            name = 'Magic360';

            el = this.$el;
            vc.frame_window.vc_iframe.addActivity(function() {
                $ = window.frames[0].jQuery || $;
                tool = this[name];
                if (tool) {
                    toolEl = $(el).find('.' + name);
                    if (toolEl.length) {
                        setTimeout(function() {
                            $(toolEl).each(function(index, te) {
                                main = get_wordpress_magic360_MainContainer(te);
                                // tool.stop($(te)[0]);
                                tool.start($(te)[0]);
                                // tool.refresh($(te)[0]);
                                if (main) {
                                    $(main).MagicToolboxGallery();
                                }
                            });
                        }, 500);
                    }
                }
            });

            return this;
        },

        updated: function () {
            window.InlineShortcodeView_vc_wordpress_magic360_shortcode.__super__.updated.call(this);
        },

        parentChanged: function () {
            var name, tool, toolEl, main;
            window.InlineShortcodeView_vc_wordpress_magic360_shortcode.__super__.parentChanged.call(this);
            $ = window.frames[0].jQuery || $;

            name = 'Magic360';

            tool = vc.frame_window[name];

            if (tool) {
                toolEl = $(this.$el).find('.' + name);
                if (toolEl.length) {
                    $(toolEl).each(function(index, te) {
                        main = get_wordpress_magic360_MainContainer(te);
                        if (main) {
                            $(main).MagicToolboxGalleryDestroy();
                        }
                        if (tool.refresh) {
                            tool.refresh($(te)[0]);
                        } else {
                            tool.stop($(te)[0]);
                            tool.start($(te)[0]);
                        }
                        if (main) {
                            $(main).MagicToolboxGallery();
                        }
                    });
                }
            }
        },

        remove: function () {
            var name, tool, toolEl, main;
            $ = window.frames[0].jQuery || $;
            name = 'Magic360';

            tool = vc.frame_window[name];

            if (tool) {
                toolEl = $(this.$el).find('.' + name);
                if (toolEl.length) {
                    $(toolEl).each(function(index, te) {
                        main = get_wordpress_magic360_MainContainer(te);
                        if (main) {
                            $(main).MagicToolboxGalleryDestroy();
                        }
                        tool.stop($(te)[0]);
                    });
                }
            }

            window.InlineShortcodeView_vc_wordpress_magic360_shortcode.__super__.remove.call( this );

            return this;
        }
    });

})(window.jQuery);
