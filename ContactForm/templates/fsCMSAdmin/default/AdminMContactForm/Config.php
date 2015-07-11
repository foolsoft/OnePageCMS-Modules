[parent:../AdminPanel/Index.php]

[block-content]
<?php echo fsHtml::Link($myLink.'Index', T('XMLcms_back'), false, array('class' => 'fsCMS-btn admin-btn-back')); ?>
<hr />
<form action='<?php echo fsHtml::Url($myLink.'DoConfig'); ?>' method='post'>
  <p>
    <?php _T('XMLcontact_form_codepage'); ?>:
    <select class='input-small' name='codePage' id='codePage'>
      <option>utf-8</option>
      <option>windows-1251</option>
      <option>koi8-r</option>
      <option>iso-8859-1</option>
    </select>
    <span class='space'></span>
    <?php _T('XMLcontact_form_content_type'); ?>:
    <select class='input-small' name='contentType' id='contentType'>
      <option>text/html</option>
      <option>text/plain</option>
      <option>multipart/mixed</option>
      <option>multipart/alternative</option>
      <option>application/octet-stream</option>
    </select>
  </p>
  <p>
    <?php _T('XMLcms_blocked_ip'); ?>:<br />
    <textarea name="block_ip" class="input-100"><?php echo $tag->settings->block_ip; ?></textarea>
  </p>
  <script type='text/javascript'>
    $('#codePage').val('<?php echo $tag->settings->codePage; ?>');
    $('#contentType').val('<?php echo $tag->settings->contentType; ?>');  
  </script>
  <hr />
  <input class='fsCMS-btn admin-btn-save' type='submit' value='<?php _T('XMLcms_save'); ?>' />     
</form>      
[endblock-content]