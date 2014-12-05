silverstripe-tinymce4
=====================

*WARNING: EXPERIMENTAL, WIP, Readme will be updated.*

## Usage
- HtmlEditorConfig is replaced with CustomHtmlEditorConfig
- HtmlEditorField is replaces with CustomHtmlEditorField

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