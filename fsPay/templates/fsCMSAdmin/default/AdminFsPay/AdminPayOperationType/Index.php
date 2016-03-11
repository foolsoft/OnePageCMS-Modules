[parent:../../AdminPanel/Index.php]

[block-content]
<?php
$textBack = T('XMLcms_back');
$textAdd = T('XMLfspay_add_operation');
$textDelete = T('XMLcms_delete');
$textEdit = T('XMLcms_edit');
?>
<a class="fsCMS-btn admin-btn-back float-left"
   href="<?php echo fsHtml::Url(URL_ROOT.'AdminPayController/Index'); ?>"
   title="<?php echo $textBack; ?>">
  <?php echo $textBack; ?>
</a>
<a class="fsCMS-btn admin-btn-add float-left" href="<?php echo fsHtml::Url($myLink.'Add'); ?>"
     title="<?php echo $textAdd; ?>">
    <?php echo $textAdd; ?>
</a>
<hr />
<table class="list-table">
  <tr>
    <th>№</th>
    <th><?php _T('XMLcms_text_name'); ?></th>
    <th><?php _T('XMLcms_text_action'); ?></th>
  </tr>
  <?php foreach ($tag->operations as $operation) { ?>
  <tr>
    <td><?php echo $operation['id']; ?></td>
    <td><?php echo $operation['name']; ?></td>
    <td>
      <div class='admin-action-td'>
        <a href='<?php echo $myLink; ?>Edit/key/<?php echo $operation['id']; ?>/'
           title='<?php echo $textEdit; ?>'
           class='admin-btn-small admin-btn-edit'>
        </a>   
        <a href='<?php echo $myLink; ?>Delete/referer/Index/key/<?php echo $operation['id']; ?>/'
           title='<?php echo $textDelete; ?>'
           class='admin-btn-small admin-btn-delete'>
        </a>   
        <div class='clr'></div>
      </div>
    </td>
  </tr>
  <?php } ?>
</table>  
[endblock-content]