[parent:../AdminPanel/Index.php]

[block-content]
<?php echo fsHtml::Link($myLink.'Index', T('XMLcms_back'), false, array('class' => 'fsCMS-btn admin-btn-back')); ?>
<hr />
<form action="<?php echo $myLink; ?>DoEdit/key/<?php echo  $tag->counter->id; ?>/" method='post'>
  <p class='title'>
    <?php _T('XMLcms_text_link'); ?>:<br />
    <input value="<?php echo $tag->counter->link; ?>" id='link' class='input-100' maxlength='100' type='text' name='link' />
  </p>
  <p class='title'>
    <?php _T('XMLcms_text_value'); ?>:<br />
    <input onkeyup="fsCMS.IsNumeric(this, 0, true, true);" value="<?php echo $tag->counter->count; ?>"  id='count'  class='input-100' maxlength='100' type='text' name='count' />
  </p>
  <hr /> 
  <input class='fsCMS-btn admin-btn-save' type='submit' value='<?php _T('XMLcms_save'); ?>' />   
</form>
[endblock-content]