[parent:../AdminPanel/Index.php]

[block-content]
<?php echo fsHtml::Link($myLink.'Index', T('XMLcms_back'), false, array('class' => 'fsCMS-btn admin-btn-back')); ?>
<hr />
<form action="<?php echo $myLink; ?>DoAdd/referer/Index/" method='post'>
  <p class='title'>
    <?php _T('XMLcms_text_link'); ?>:<br />
    <input id='link' class='input-100' maxlength='100' type='text' name='link' />
  </p>
  <p class='title'>
    <?php _T('XMLcounter_start_value'); ?>:<br />
    <input onkeyup="fsCMS.IsNumeric(this, 0, true, true);" value='0' id='count' class='input-100' maxlength='100' type='text' name='count' />
  </p>
  <hr /> 
  <input class='fsCMS-btn admin-btn-save' type='submit' value='<?php _T('XMLcms_add'); ?>' />   
</form>
[endblock-content]