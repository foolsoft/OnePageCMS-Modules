[parent:../AdminPanel/AddEdit.php]

[block-content]
<?php echo fsHtml::Link($myLink.'Index', T('XMLcms_back'), false, array('class' => 'fsCMS-btn admin-btn-back')); ?>
<hr />
<form action="<?php echo $myLink; ?>DoAdd/referer/Index/" method='post'>
  <p class='title'>
    <?php _T('XMLcms_text_name'); ?>:<br />
    <input id='name' class='input-100' maxlength='50' type='text' name='name' />
  </p>
  <p class='title'>
    <?php _T('XMLslider_width'); ?>:
    <input type="number" min="1" onfocus="this.select()" value="1" name="width">
    <span class="space"></span>
    <?php _T('XMLslider_height'); ?>:
    <input type="number" min="1" onfocus="this.select()" value="1" name="height">
    <span class="space"></span>
    <?php _T('XMLcms_text_template'); ?>:
    <select name='template'>
    <?php foreach($tag->templates as $tpl) { ?>
      <option><?php echo $tpl; ?></option>
    <?php } ?>
    </select>
  </p>
  <p class='title'>
    <?php _T('XMLslider_navigation'); ?>:
    <select name="navigation">
      <option value='1' selected><?php _T('XMLcms_yes'); ?></option>
      <option value='0'><?php _T('XMLcms_no'); ?></option>
    </select>
    <span class="space"></span>
    <?php _T('XMLslider_animation'); ?>:
    <select name="animation">
      <?php foreach($tag->animations as $animation) { ?>
        <option><?php echo $animation; ?></option>
      <?php } ?>
    </select>
    <span class="space"></span>
    <?php _T('XMLslider_unit'); ?>:
    <select name="width_unit">
      <option>px</option>
      <option>%</option>
    </select>
  </p>
  <p class='title'>
    <?php _T('XMLslider_interval'); ?>, <?php _T('XMLslider_seconds'); ?>:
    <input type="number" min="1" onfocus="this.select()" value="1" name="interval">
  </p>
  <hr /> 
  <input class='fsCMS-btn admin-btn-save' type='submit' value='<?php _T('XMLcms_add'); ?>' />   
</form>
[endblock-content]