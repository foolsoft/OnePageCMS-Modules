[parent:../AdminPanel/AddEdit.php]

[block-content]
<?php
$textBack = T('XMLcms_back');
$first_system = '';
?>
<a class="fsCMS-btn admin-btn-back" href="<?php echo fsHtml::Url($myLink.'Index'); ?>" title="<?php echo $textBack; ?>">
  <?php echo $textBack; ?>
</a>
<hr />
<form action='<?php echo fsHtml::Url($myLink.'DoConfig'); ?>' method='post'>
  <h3><?php _T('XMLfspay_main_settings'); ?></h3>
  <p>
    <?php _T('XMLfspay_notify_email'); ?>:<br />
    <input type='text' name='notify_email' class='input-100' value='<?php echo $tag->settings->notify_email; ?>' />
  </p>
  <p>
    <?php _T('XMLfspay_secret'); ?>:<br />
    <input type='text' name='secret' class='input-100' value='<?php echo $tag->settings->secret; ?>' />
  </p>
  <p>
    <?php _T('XMLfspay_can_create_pays'); ?>:
    <input type='checkbox' name='dynamic_create' <?php echo $tag->settings->dynamic_create == 1 ? 'checked' : ''; ?> />
  </p>
  <p>
    <?php _T('XMLfspay_inform_template'); ?>:
    <textarea name="inform_template" class="ckeditor"><?php echo $tag->settings->inform_template; ?></textarea>
  </p>
  <p>
    <?php _T('XMLfspay_ok_template'); ?>:
    <textarea name="ok_template" class="ckeditor"><?php echo $tag->settings->ok_template; ?></textarea>
  </p>
  <h3><?php _T('XMLfspay_system_settings'); ?></h3>
  <p>
    <?php _T('XMLfspay_payment_system'); ?>: 
    <select onchange="fsCMS.Ajax('<?php echo $myLink; ?>AjaxLoadSystemConfig' + URL_SUFFIX, 'POST', 'system='+this.value, 'system-config', true);" name='payments_methods' id='payments_methods'>
    <?php 
        echo payFunctions::GetPaymentsSystems('Admin', $first_system);
    ?>
    </select>
    <div id='system-config'></div>
  </p>
  <hr />
  <input class='fsCMS-btn admin-btn-save' type='submit' value='<?php _T('XMLcms_save'); ?>' />     
</form>
<script type='text/javascript'>
$(document).ready(function() {
    $('#system-config').load('<?php echo $myLink; ?>AjaxLoadSystemConfig' + URL_SUFFIX + '?system=' + $('#payments_methods').val());    
});
</script>      
[endblock-content]