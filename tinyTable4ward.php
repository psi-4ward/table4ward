<?php

if(TL_MODE == 'BE') {
	$this->import('BackendUser','User');
	$this->language	 = $this->User->language;
}

/**
 * This is the tinyMCE (rich text editor) configuration file. Please visit
 * http://tinymce.moxiecode.com for more information.
 */
if ($GLOBALS['TL_CONFIG']['useRTE']): ?>
<script src="<?php echo $this->base; ?>assets/tinymce/tiny_mce_gzip.js"></script>
<script>
tinyMCE_GZ.init({
  plugins : "advimage,autosave,directionality,emotions,inlinepopups,paste,save,searchreplace,style,tabfocus,template,typolinks,xhtmlxtras",
  themes : "advanced",
  languages : "<?php echo $this->language; ?>",
  disk_cache : false,
  debug : false
});
</script>

<script type="text/javascript">
TableWizard4ward.tinyMCEInit = function(textareaID) {

	tinyMCE.init({
	  mode : "none",
	  height : "400",
	  width : "600",
	  language : "<?php echo $this->language; ?>",
	  elements : textareaID,
	  remove_linebreaks : false,
	  force_hex_style_colors : true,
	  fix_list_elements : true,
	  fix_table_elements : true,
	  theme_advanced_font_sizes : "9px,10px,11px,12px,13px,14px,15px,16px,17px,18px,19px,20px,21px,22px,23px,24px",
	  doctype : '<!DOCTYPE html>',
	  element_format : 'html',
	  document_base_url : "<?php echo $this->base; ?>",
	  entities : "160,nbsp,60,lt,62,gt,173,shy",
	  cleanup_on_startup : true,
	  save_enablewhendirty : true,
	  save_on_tinymce_forms : true,
	  file_browser_callback : "TinyCallback.fileBrowser",
	  advimage_update_dimensions_onchange : false,
	  template_external_list_url : "<?php echo TL_PATH; ?>/assets/tinymce/plugins/typolinks/typotemplates.php",
	  plugins : "advimage,directionality,inlinepopups,paste,save,searchreplace,style,tabfocus,template,typolinks,xhtmlxtras",
	  content_css : "<?php echo TL_PATH; ?>/system/themes/tinymce.css,<?php echo (TL_PATH ? TL_PATH .'/' : ''). $this->uploadPath; ?>/tinymce.css",
	  event_elements : "a,div,h1,h2,h3,h4,h5,h6,img,p,span",
	  extended_valid_elements: "q[cite|class|title],article,section,hgroup,figure,figcaption",
	  tabfocus_elements : ":prev,:next",
	  theme : "advanced",
	  theme_advanced_resizing : true,
	  theme_advanced_resize_horizontal : false,
	  theme_advanced_toolbar_location : "top",
	  theme_advanced_toolbar_align : "left",
	  theme_advanced_statusbar_location : "bottom",
	  theme_advanced_source_editor_width : "700",
	  theme_advanced_blockformats : "div,p,address,pre,h1,h2,h3,h4,h5,h6",
	  theme_advanced_buttons1 : "save,cancel,separator,typolinks,unlink,separator,image,typobox,separator,undo,redo,separator,removeformat,separator,code,separator,charmap,separator,bullist,numlist,indent,outdent",
	  theme_advanced_buttons2 : "formatselect,fontsizeselect,styleselect,separator,bold,italic,underline,separator,justifyleft,justifycenter,justifyright,justifyfull",
	  theme_advanced_buttons3 : "",

	  
	  save_onsavecallback: function(){
		  this.tableWizard4ward.tinyMCEsave();
	  },
	  save_oncancelcallback: function(){
		  this.tableWizard4ward.tinyMCEcancle();
	  } 
	  
	});

	tinyMCE.execCommand('mceAddControl', false, textareaID);
};

</script>
<?php endif; ?>