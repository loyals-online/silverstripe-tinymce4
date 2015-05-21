<?php

define('TINYMCE4_PATH', dirname(__FILE__));
define('TINYMCE4_DIR', basename(TINYMCE4_PATH));

Object::useCustomClass('HtmlEditorField', 'CustomHtmlEditorField');
Config::inst()->update('HtmlEditorField', 'use_gzip', false);

// Default CMS HTMLEditorConfig
CustomHtmlEditorConfig::get('cms')->setOptions(array(
	'friendly_name' => 'Default CMS',
	'priority' => '50',

	'body_class' => 'typography',
	'document_base_url' => isset($_SERVER['HTTP_HOST']) ? Director::absoluteBaseURL() : null,

	'cleanup_callback' => "sapphiremce_cleanup",

	'use_native_selects' => false,
	'valid_elements' => "@[id|class|style|title],a[id|rel|rev|dir|tabindex|accesskey|type|name|href|target|title"
		. "|class],-strong/-b[class],-em/-i[class],-strike[class],-u[class],#p[id|dir|class|align|style],-ol[class],"
		. "-ul[class],-li[class],br,img[id|dir|longdesc|usemap|class|src|border|alt=|title|width|height|align|data*],"
		. "-sub[class],-sup[class],-blockquote[dir|class],-cite[dir|class|id|title],"
		. "-table[cellspacing|cellpadding|width|height|class|align|summary|dir|id|style],"
		. "-tr[id|dir|class|rowspan|width|height|align|valign|bgcolor|background|bordercolor|style],"
		. "tbody[id|class|style],thead[id|class|style],tfoot[id|class|style],"
		. "#td[id|dir|class|colspan|rowspan|width|height|align|valign|scope|style],"
		. "-th[id|dir|class|colspan|rowspan|width|height|align|valign|scope|style],caption[id|dir|class],"
		. "-div[id|dir|class|align|style],-span[class|align|style],-pre[class|align],address[class|align],"
		. "-h1[id|dir|class|align|style],-h2[id|dir|class|align|style],-h3[id|dir|class|align|style],"
		. "-h4[id|dir|class|align|style],-h5[id|dir|class|align|style],-h6[id|dir|class|align|style],hr[class],"
		. "dd[id|class|title|dir],dl[id|class|title|dir],dt[id|class|title|dir],@[id,style,class]",
	'extended_valid_elements' => "img[class|src|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name"
		. "|usemap|data*],iframe[src|name|width|height|align|frameborder|marginwidth|marginheight|scrolling],"
		. "object[width|height|data|type],param[name|value],map[class|name|id],area[shape|coords|href|target|alt]"
));

CustomHtmlEditorConfig::get('cms')->disablePlugins('contextmenu');

CustomHtmlEditorConfig::get('cms')->enablePlugins('media', 'fullscreen', 'colorpicker', 'importcss');
CustomHtmlEditorConfig::get('cms')->enablePlugins(array(
	'ssbuttons' => '/' . TINYMCE4_DIR . '/thirdparty/tinymce_ssbuttons/editor_plugin_src.js'
));

CustomHtmlEditorConfig::get('cms')->addButtonsToLine(1, 'unlink', 'anchor', '|', 'code', 'fullscreen', '|');
CustomHtmlEditorConfig::get('cms')->insertButtonsBefore('undo', 'menubtn', '|');
CustomHtmlEditorConfig::get('cms')->insertButtonsBefore('bold', 'formatselect', '|');

// Menubar options, remove file (and others)
CustomHtmlEditorConfig::get('cms')->setOption('menubar', 'edit view format table tools');