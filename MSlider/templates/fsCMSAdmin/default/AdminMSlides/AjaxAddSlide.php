<div>
  <div data-idx="<?php echo $tag->id; ?>" id="slide<?php echo $tag->id; ?>" class="slide">
    <div>
      <input class="slide-delete" type="button" onclick="DeleteSlide('<?php echo $tag->id; ?>');" value="<?php _T('XMLcms_delete'); ?>" />
      <input class="slide-setting" type="button" onclick="SlideSetting('<?php echo $tag->id; ?>');" value="<?php _T('XMLcms_edit'); ?>" />
      <img class="slide-img" height="<?php echo $tag->height; ?>" width="<?php echo $tag->width.($tag->width_unit == '%' ? '%' : ''); ?>" src="<?php echo $tag->src; ?>" border="2" alt="<?php echo $tag->alt; ?>" title="<?php echo $tag->alt; ?>" />
    </div>
    <div id="slide-setting-<?php echo $tag->id; ?>" class="slide-setting-container title">
      <hr />
      <?php _T('XMLcms_text_title'); ?>:
      <input type="text" name="alt[<?php echo $tag->id; ?>]" value="<?php echo $tag->alt; ?>" />
      <span class="space"></span>
      <?php _T('XMLcms_text_link'); ?>:
      <input type="text" name="href[<?php echo $tag->id; ?>]" value="<?php echo $tag->href; ?>" />
      <span class="space"></span>
      <?php _T('XMLcms_text_order'); ?>:
      <input type="number" min="0" onfocus="this.select()" value="<?php echo $tag->order; ?>" name="order[<?php echo $tag->id; ?>]">
      <span class="space"></span>
      <?php _T('XMLcms_text_content'); ?>:
      <br />
      <textarea id="ckeditor<?php echo $tag->id; ?>" style="resize:none;" class="ckeditor" name="html[<?php echo $tag->id; ?>]"><?php echo $tag->html; ?></textarea>
    </div>
  </div>
</div>