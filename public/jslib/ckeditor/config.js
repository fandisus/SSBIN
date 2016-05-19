/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
        config.filebrowserBrowseUrl = '/jslib/kcfinder/browse.php?type=files';
        config.filebrowserImageBrowseUrl = '/jslib/kcfinder/browse.php?type=images';
        config.filebrowserFlashBrowseUrl = '/jslib/kcfinder/browse.php?type=flash';
        config.filebrowserUploadUrl = '/jslib/kcfinder/upload.php?type=files';
        config.filebrowserImageUploadUrl = '/jslib/kcfinder/upload.php?type=images';
        config.filebrowserFlashUploadUrl = '/jslib/kcfinder/upload.php?type=flash';
        config.extraPlugins = 'codeTag';
        config.allowedContent = true;
};
