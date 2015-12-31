<?php

class SiteTreeTinyExtension extends DataExtension
{

    public function updateCMSFields(FieldList $fields)
    {
        Requirements::javascript(TINYMCE4_DIR ."/javascript/HtmlEditorField.js");
        Requirements::css(TINYMCE4_DIR ."/css/HtmlEditorField.css");

        if (Member::currentUser()) {
            CustomHtmlEditorConfig::set_active(Member::currentUser()->getHtmlEditorConfigForCMS());
        }

        $fields->replaceField("Content",
            CustomHtmlEditorField::create("Content", _t('SiteTree.HTMLEDITORTITLE', "Content", 'HTML editor title'))->addExtraClass('stacked')
        );
    }
}
