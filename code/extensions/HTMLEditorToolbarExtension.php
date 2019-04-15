<?php

class HTMLEditorToolbarExtension extends Extension {

	public function updateLinkForm($form) {
		if($optionSet = $form->Fields()->dataFieldByName('LinkType')) {
			$options = $optionSet->getSource();

			// add Phone
			$options['telephone'] = _t('TinyMCE4.TelephoneNumber','Telephone number');

			$optionSet->setSource($options);

			// insert phone TextField
			$form->Fields()->insertBefore(new TextField('telephone', _t('TinyMCE4.TelephoneNumber','Telephone number')), 'TargetBlank');

		}
	}

}