
(function() {
    tinymce.create('tinymce.plugins.prelinuxcode', {
        init : function(ed, url) {
            ed.addButton('prelinuxcode', {
                title : 'SoS Pre Linux Code',
                image : url + '/youtube.png',
                icons : false,
                onclick : function() {
                    var code = '[linux-code]' + ed.selection.getContent() +'[/linux-code]' ;
                    tinyMCE.activeEditor.execCommand( "mceInsertContent", false, code);
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname : "Add Linux Code Syntax",
                author : 'Sophy SEM',
                authorurl : 'http://sophysem.com',
                infourl : 'http://sophysem.com',
                version : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('prelinuxcode', tinymce.plugins.prelinuxcode);
})();