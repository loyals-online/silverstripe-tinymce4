<?php
/**
 * A TinyMCE-powered WYSIWYG HTML editor field with image and link insertion and tracking capabilities. Editor fields
 * are created from <textarea> tags, which are then converted with JavaScript.
 *
 * @package forms
 * @subpackage fields-formattedinput
 */
class CustomHtmlEditorField extends TextareaField {

	/**
	 * @config
	 * @var Boolean Use TinyMCE's GZIP compressor
	 */
	private static $use_gzip = true;

	/**
	 * @config
	 * @var Integer Default insertion width for Images and Media
	 */
	private static $insert_width = 600;

	/**
	 * @config
	 * @var bool Should we check the valid_elements (& extended_valid_elements) rules from HtmlEditorConfig server side?
	 */
	private static $sanitise_server_side = false;

	protected $rows = 30;

	/**
	 * @deprecated since version 4.0
	 */
	public static function include_js() {
		Deprecation::notice('4.0', 'Use CustomHtmlEditorConfig::require_js() instead');
		CustomHtmlEditorConfig::require_js();
	}


	protected $editorConfig = null;

	/**
	 * Creates a new HTMLEditorField.
	 * @see TextareaField::__construct()
	 *
	 * @param string $name The internal field name, passed to forms.
	 * @param string $title The human-readable field label.
	 * @param mixed $value The value of the field.
	 * @param string $config HTMLEditorConfig identifier to be used. Default to the active one.
	 */	
	public function __construct($name, $title = null, $value = '', $config = null) {
		parent::__construct($name, $title, $value);

		$this->editorConfig = $config ? $config : CustomHtmlEditorConfig::get_active_identifier();
	}

	public function getAttributes() {
		return array_merge(
			parent::getAttributes(),
			array(
				'tinymce'     => 'true',
				'style'       => 'width: 97%; height: ' . ($this->rows * 16) . 'px', // prevents horizontal scrollbars
				'value'       => null,
				'data-config' => $this->editorConfig
			)
		);
	}

	public function saveInto(DataObjectInterface $record) {
		if($record->hasField($this->name) && $record->escapeTypeForField($this->name) != 'xml') {
			throw new Exception (
				'HtmlEditorField->saveInto(): This field should save into a HTMLText or HTMLVarchar field.'
			);
		}

		$htmlValue = Injector::inst()->create('HTMLValue', $this->value);

		// Sanitise if requested
		if($this->config()->sanitise_server_side) {
			$santiser = Injector::inst()->create('HtmlEditorSanitiser', CustomHtmlEditorConfig::get_active());
			$santiser->sanitise($htmlValue);
		}

		// Resample images and add default attributes
		if($images = $htmlValue->getElementsByTagName('img')) foreach($images as $img) {
			// strip any ?r=n data from the src attribute
			$img->setAttribute('src', preg_replace('/([^\?]*)\?r=[0-9]+$/i', '$1', $img->getAttribute('src')));

			// Resample the images if the width & height have changed.
			if($image = File::find(urldecode(Director::makeRelative($img->getAttribute('src'))))){
				$width  = (int)$img->getAttribute('width');
				$height = (int)$img->getAttribute('height');

				if($width && $height && ($width != $image->getWidth() || $height != $image->getHeight())) {
					//Make sure that the resized image actually returns an image:
					$resized=$image->ResizedImage($width, $height);
					if($resized) $img->setAttribute('src', $resized->getRelativePath());
				}
			}

			// Add default empty title & alt attributes.
			if(!$img->getAttribute('alt')) $img->setAttribute('alt', '');
			if(!$img->getAttribute('title')) $img->setAttribute('title', '');

			// Use this extension point to manipulate images inserted using TinyMCE, e.g. add a CSS class, change default title
			// $image is the image, $img is the DOM model
			$this->extend('processImage', $image, $img);
		}

		// optionally manipulate the HTML after a TinyMCE edit and prior to a save
		$this->extend('processHTML', $htmlValue);

		// Store into record
		$record->{$this->name} = $htmlValue->getContent();
	}

	/**
	 * @return HtmlEditorField_Readonly
	 */
	public function performReadonlyTransformation() {
		$field = $this->castedCopy('HtmlEditorField_Readonly');
		$field->dontEscape = true;

		return $field;
	}

	public function performDisabledTransformation() {
		return $this->performReadonlyTransformation();
	}
}
