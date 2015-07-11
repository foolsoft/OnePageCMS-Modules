[parent:../AdminPanel/AddEdit.php]

[block-content]
<?php echo fsHtml::Link($myLink.'Index', T('XMLcms_back'), false, array('class' => 'fsCMS-btn admin-btn-back')); ?>
<hr />
<form action="<?php echo $myLink; ?>DoAdd/referer/Index/" method='post'>
  <p class='title'>
    <?php _T('XMLcms_text_title'); ?>:<br />
    <input onkeyup='fsCMS.Chpu(this.value, "name");' class='input-100' maxlength='125' type='text' name='title' />
  </p>
  <p class='title'>
    <?php _T('XMLcms_text_name'); ?>:<br />
    <input onkeyup='fsCMS.Chpu(this.value, this.id);' id='name' class='input-100' maxlength='50' type='text' name='name' />
  </p>
  <p>
    <?php _T('XMLcontact_form_send_mail_to'); ?>:<br />
    <input class='input-100' maxlength='75' type='text' name='mail' />
  </p>   
  <p>
    <?php _T('XMLcms_text_template'); ?>:
    <textarea class='ckeditor' name='tpl'></textarea>
    <span class='small'>
      <?php _T('XMLcontact_form_footer_5'); ?>:<br />
      {message} - <?php echo T('XMLcms_controller_message'); ?>;<br />
      <?php _T('XMLcontact_form_required_sample'); ?>:<br />
      &lt;input name="<b>required</b>[fieldName]" /><br />
      <?php _T('XMLcontact_form_other_sample'); ?>:<br />
      &lt;input name="<b>other</b>[fieldName]" /><br />
      <?php _T('XMLcontact_form_message_user_sample'); ?>:<br />
      &lt;input name="required[<b>email</b>]" />,
      &lt;input name="other[<b>email</b>]" />
    </span>
  </p>
  <p>
    <?php _T('XMLcontact_form_message_template'); ?>:
    <textarea class='ckeditor' name='message'></textarea>
    <span class='small'>
      <?php _T('XMLcontact_form_footer_1'); ?>:<br />
      {date} - <?php echo T('XMLcontact_form_footer_2'); ?>;<br />
      {time} - <?php echo T('XMLcontact_form_footer_3'); ?>;<br />
      {ip} - <?php echo T('XMLcontact_form_footer_4'); ?>;
    </span>
  </p>
  
  <p class='vspace'>
    <?php _T('XMLcontact_form_need_message_user'); ?>:
    <input type='checkbox' name='mail_user' />
  </p> 
  <p class='vspace'>
    <?php echo T('XMLcms_text_title').' ('.T('XMLcms_text_users').')'; ?>:<br />
    <input class='input-100' maxlength='125' type='text' name='title_user' />
  </p>
  <p class='vspace'>
    <?php _T('XMLcontact_form_message_user_template'); ?>:
    <textarea class='ckeditor' name='message_to_user'></textarea>
    <span class='small'>
      <?php echo T('XMLcontact_form_footer_1'); ?>:<br />
      {date} - <?php echo T('XMLcontact_form_footer_2'); ?>;<br />
      {time} - <?php echo T('XMLcontact_form_footer_3'); ?>;<br />
      {ip} - <?php echo T('XMLcontact_form_footer_4'); ?>;
    </span>
  </p>
  
  <p class='title vspace'>
    <?php _T('XMLcontact_form_message_ajax'); ?>:
    <input type='checkbox' name='ajax' />
  </p> 
  
  <hr /> 
  <input class='fsCMS-btn admin-btn-save' type='submit' value='<?php _T('XMLcms_add'); ?>' />   
</form>
[endblock-content]