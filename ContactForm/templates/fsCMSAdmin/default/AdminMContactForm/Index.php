[parent:../AdminPanel/Index.php]

[block-content]
<?php
$textDelete = T('XMLcms_delete');
$textEdit = T('XMLcms_edit');
?>
<div>
  <?php 
  echo fsHtml::Link($myLink.'Config', T('XMLcms_settings'), false, array('class' => 'fsCMS-btn admin-btn-config float-left'));
  echo fsHtml::Link($myLink.'Add', T('XMLcontact_form_add'), false, array('class' => 'fsCMS-btn float-left'));
  ?>
  <div class="clr"></div>
</div>
<hr />
<table class="list-table">
  <tr>
    <th><?php _T('XMLcms_text_title'); ?></th>
    <th><?php _T('XMLcms_text_code'); ?></th>
    <th><?php _T('XMLcms_text_action'); ?></th>
  </tr>
  <?php foreach ($tag->forms as $form) { ?>
  <tr class='admin-row-active-<?php echo $form['mail'] == '' ? '0' : '1'; ?>'>  
    <td><?php echo $form['title']; ?></td>
    <td>{% MContactForm/Form | name=<?php echo $form['name']; ?> %}</td>
    <td>
      <div class='admin-action-td'>
        <a href='<?php echo $myLink; ?>Edit/key/<?php echo $form['name']; ?>/'
           title='<?php echo $textEdit; ?>'
           class='admin-btn-small admin-btn-edit'
        ></a>   
        <a href='<?php echo $myLink; ?>Delete/key/<?php echo $form['name']; ?>/'
           title='<?php echo $textDelete; ?>'
           class='admin-btn-small admin-btn-delete'
        ></a>   
        <div class='clr'></div>
      </div
    </td>
  </tr>  
  <?php } ?>
</table>
[endblock-content]