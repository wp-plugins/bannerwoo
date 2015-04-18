/**
 * Bannerwoo tinymce functions.
 */

(function() {
    tinymce.PluginManager.add('bannerwoo_shortcodes_mce_button', function( editor, url ) {
        editor.addButton( 'bannerwoo_shortcodes_mce_button', {
            title: 'Add Banner Shortcodes',
            text: 'Banner Shortcodes',
            onclick: function() {
                editor.windowManager.open( {
                	
                    title: 'Bannerwoo - Insert banner',
                    body: [
                        {
                            type: 'textbox',
                            name: 'banner_id',
                            label: 'Banner ID',
                        },
                        {
                            type: 'listbox',
                            name: 'alignment',
                            label: 'Alignment',
                            'values': [
                            	{text: 'Left', value: 'left'},
                                {text: 'Center', value: 'center'},
                                {text: 'Right', value: 'right'}
                            ]
                        }
                    ],
                    onsubmit: function( e ) {
                        editor.insertContent( '[banner id="' + e.data.banner_id + '" alignment="' + e.data.alignment + '"]<br /><br />');
                    }
                });
            }
        });
    });
})();