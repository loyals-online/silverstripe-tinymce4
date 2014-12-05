<?php
/**
 * Represents a large text field that contains HTML content.
 * This behaves similarly to {@link Text}, but the template processor won't escape any HTML content within it.
 * 
 * @see HTMLVarchar
 * @see Text
 * @see Varchar
 * 
 * @package framework
 * @subpackage model
 */
class CustomHTMLText extends HTMLText {

	public function scaffoldFormField($title = null, $params = null) { error_log('test');
		return new CustomHtmlEditorField($this->name, $title);
	}

}


