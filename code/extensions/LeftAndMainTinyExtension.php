<?php

class LeftAndMainTinyExtension extends LeftAndMainExtension {
	function init() {
		if(Member::currentUser()) {
			CustomHtmlEditorConfig::set_active(Member::currentUser()->getHtmlEditorConfigForCMS());
		}

		Requirements::block(MCE_ROOT . 'tiny_mce_src.js');
		Requirements::block(FRAMEWORK_DIR ."/javascript/HtmlEditorField.js");

		// Set default values in the config if missing.  These things can't be defined in the config
		// file because insufficient information exists when that is being processed
		$htmlEditorConfig = CustomHtmlEditorConfig::get_active();
		$htmlEditorConfig->setOption('language', i18n::get_tinymce_lang());
		if(!$htmlEditorConfig->getOption('content_css')) {
			$cssFiles = array();
			$cssFiles[] = FRAMEWORK_ADMIN_DIR . '/css/editor.css';

			// Use theme from the site config
			if(class_exists('SiteConfig') && ($config = SiteConfig::current_site_config()) && $config->Theme) {
				$theme = $config->Theme;
			} elseif(Config::inst()->get('SSViewer', 'theme_enabled') && Config::inst()->get('SSViewer', 'theme')) {
				$theme = Config::inst()->get('SSViewer', 'theme');
			} else {
				$theme = false;
			}

			if($theme) $cssFiles[] = THEMES_DIR . "/{$theme}/css/editor.css";
			else if(project()) $cssFiles[] = project() . '/css/editor.css';

			// Remove files that don't exist
			foreach($cssFiles as $k => $cssFile) {
				if(!file_exists(BASE_PATH . '/' . $cssFile)) unset($cssFiles[$k]);
			}

			$htmlEditorConfig->setOption('content_css', implode(',', $cssFiles));
		}

		CustomHTMLEditorField::include_js();

	}
}