(function() {

    $(document).ready(function() {

        var language = $("html").attr("lang");

        if (language === 'en-US') {
            language = undefined;
        }

        tinymce.init({
            "selector": ".wysiwyg-editor",
            "language": language,
            "plugins": "save image media link table charmap code",
            "content_css": "/css/tinymce/default.min.css",
            "toolbar": "save | undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | charmap code",
            "menubar": false,
            "statusbar": false,
            "relative_urls": false,

            file_browser_callback : function(field_name, url, type, win) {

                var w = window,
                d = document,
                e = d.documentElement,
                g = d.getElementsByTagName('body')[0],
                x = w.innerWidth || e.clientWidth || g.clientWidth,
                y = w.innerHeight|| e.clientHeight|| g.clientHeight;

                var cmsURL = '/vendor/filemanager/index.html?&field_name='+field_name+'&lang='+tinymce.settings.language;

                if (type == 'image') {
                    cmsURL = cmsURL + "&type=images";
                }

                tinyMCE.activeEditor.windowManager.open({
                    file : cmsURL,
                    title : 'Filemanager',
                    width : x * 0.8,
                    height : y * 0.8,
                    resizable : "yes",
                    close_previous : "no"
                });
            }
        });

    });

})();