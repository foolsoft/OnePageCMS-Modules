[parent:../AdminPanel/AddEdit.php]

[block-content]
<?php
$textAddSlide = T('XMLslide_add');
echo fsHtml::Link($myLink.'Index', T('XMLcms_back'), false, array('class' => 'fsCMS-btn admin-btn-back'));
?>
<hr />
<form action="<?php echo $myLink; ?>DoEdit/key/<?php echo $tag->slider->id; ?>/" method='post'>
  <p class='title'>
    <?php echo T('XMLcms_text_name'); ?>:<br />
    <input id='name' class='input-100' maxlength='50' type='text' name='name' value='<?php echo $tag->slider->name; ?>' />
  </p>
  <p class='title'>
    <?php _T('XMLslider_width'); ?>:
    <input type="number" min="1" onfocus="this.select()" value="<?php echo $tag->slider->width; ?>" name="width">
    <span class="space"></span>
    <?php _T('XMLslider_height'); ?>:
    <input type="number" min="1" onfocus="this.select()" value="<?php echo $tag->slider->height; ?>" name="height">
    <span class="space"></span>
    <?php _T('XMLcms_text_template'); ?>:
    <select name='template'>
    <?php foreach($tag->templates as $tpl) { ?>
      <option <?php echo ($tpl == $tag->slider->template ? 'selected' : ''); ?>><?php echo $tpl; ?></option>
    <?php } ?>
    </select>
  </p>
  <p class='title'>
    <?php _T('XMLslider_navigation'); ?>:
    <select name="navigation">
      <option value='1' <?php echo ('1' == $tag->slider->navigation ? 'selected' : ''); ?>><?php _T('XMLcms_yes'); ?></option>
      <option value='0' <?php echo ('0' == $tag->slider->navigation ? 'selected' : ''); ?>><?php _T('XMLcms_no'); ?></option>
    </select>
    <span class="space"></span>
    <?php _T('XMLslider_animation'); ?>:
    <select name="animation">
      <?php foreach($tag->animations as $animation) { ?>
        <option <?php echo $animation == $tag->slider->animation ? 'selected' : ''; ?>><?php echo $animation; ?></option>
      <?php } ?>
    </select>
    <span class="space"></span>
    <?php _T('XMLslider_unit'); ?>:
    <select name="width_unit">
      <option>px</option>
      <option <?php echo $tag->width_unit == '%' ? 'selected' : ''; ?>>%</option>
    </select>
  </p>
  <p class='title'>
    <?php _T('XMLslider_interval'); ?>, <?php _T('XMLslider_seconds'); ?>:
    <input type="number" min="1" onfocus="this.select()" value="<?php echo $tag->slider->interval; ?>" name="interval">
  </p>
  <hr />
  <b><?php _T('XMLslides'); ?>:</b> 
  <a id="add-slide" href="javascript:" title="<?php echo $textAddSlide; ?>"><?php echo $textAddSlide; ?></a>
  
  <div id="slider-slides"><?php echo $tag->slides; ?></div>
  
  <hr />
  <input class='fsCMS-btn admin-btn-save' type='submit' value='<?php _T('XMLcms_save'); ?>' />   
</form>
<script type="text/javascript">
$(document).ready(function() {
  $.ajax_upload($('#add-slide'), {
    action: '<?php echo $myLink; ?>/AjaxAddSlide/key/<?php echo $tag->slider->id; ?>/',
    name: 'slide',
    onSubmit: function(file, ext) {
      fsCMS.WaitAnimation('on');
      this.disable();
    },
    onComplete: function(file, response) { 
      fsCMS.WaitAnimation('off');
      var temp = response.split(':');
      this.enable();
      if(temp[0] == 'error') {
        alert(temp[1]);
        return;
      }
      $('#slider-slides').append(response);
      var e = $('.ckeditor');
      for (var i = 0; i < e.length; ++i) { 
        if (CKEDITOR.instances[$(e[i]).attr('id')]) {
           CKEDITOR.instances[$(e[i]).attr('id')].destroy(true);
        }
        CKEDITOR.replace($(e[i]).attr('id'));
      }
    }
  });
});
</script>
[endblock-content]