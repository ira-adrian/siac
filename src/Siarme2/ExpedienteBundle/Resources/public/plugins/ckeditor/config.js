/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */


CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	//config.extraPlugins = 'bootstrapVisibility';
	// Define changes to default configuration here. For example:
	//config.extraPlugins = 'bootstrapVisibility';
	//config.fontSize_defaultLabel = '14px';
	// config.font_defaultLabel = 'Arial';
    // config.fontSize_defaultLabel = '14'; 
   //  config.contentsCss = "body {font-family: Arial; font-size: 14px;}";
	config.extraPlugins = 'autogrow,texttransform';
	 config.language = 'es';
	// config.uiColor = '#AADC6E';
	//config.scayt_sLang = 'es_ES';
		config.toolbarGroups = [
		{ name: 'document', groups: [ 'save', 'source','mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'insert', groups: [ 'insert' ] },
	//	'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'texttransform','list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
	//	'/',
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	];

	config.removeButtons = 'Maximize,Preview,Unlink,Templates,Form,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Find,Replace,Checkbox,Print,NewPage,Superscript,Subscript,Language,CreateDiv,Link,Anchor,Flash,Smiley,Iframe,ShowBlocks,About';

};

