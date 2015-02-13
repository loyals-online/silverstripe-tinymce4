silverstripe-tinymce4
=====================

Replaces the default Silverstripe HTMLEditor (TinyMCE v3) with TinyMCE v4.

![Screenshop](http://mediaweb.nl/assets/public/silverstripe-tinymce4.png)

## Usage
- HtmlEditorConfig is replaced with CustomHtmlEditorConfig
- HtmlEditorField is replaced with CustomHtmlEditorField

**When using this module, beware: using HTMLEditorField will probably break the admin. Replace all occurences with CustomHTMLEditorField.**

### There are two use cases:
1. Using automatic scaffolding (scaffoldFormField):
```
	private static $db = array(
		'CustomContent' => 'CustomHTMLText'
	)
```
2. Using updateCMSFields or getCMSFields:
```
	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->insertAfter(
			CustomHTMLEditorField::create('CustomContent', 'Custom Content')->addExtraClass('stacked'),
			'Content'
		);

		return $fields;
	}
```

Note: Be sure to allow access to 'thirdparty/tinymce/tiny_mce_gzip.php'; a .htaccess file is added, some extra configuration is required when using Nginx.