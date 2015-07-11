[parent:../AdminPanel/Index.php]

[block-content]
<?php
$textDelete = T('XMLcms_delete');
$textEdit = T('XMLcms_edit'); 
$textOpen = T('XMLcms_text_open');
echo fsHtml::Link($myLink.'Add', T('XMLcounter_add'), false, array('class' => 'fsCMS-btn admin-btn-add'));
?>  
<hr />
<table class="list-table">
  <tr>
    <th><?php _T('XMLcms_text_code'); ?></th>
    <th><?php _T('XMLcms_text_link'); ?></th>
    <th><?php _T('XMLcms_text_value'); ?></th>
    <th><?php _T('XMLcms_text_action'); ?></th>
  </tr>
  <?php foreach ($tag->counters as $counter) { ?>
  <tr>
    <td>{% MLinkCounter/Counter | id=<?php echo $counter['id']; ?> %}</td>
    <td>
        <a href='<?php echo URL_ROOT.'MLinkCounter/Open/id/'.$counter['id'].'/'; ?>' title='<?php echo $textOpen; ?>'>
            <?php echo URL_ROOT.'MLinkCounter/Open/id/'.$counter['id'].'/'; ?>
        </a>
    </td>   
    <td><?php echo $counter['count']; ?></td>
    <td>
      <div class='admin-action-td'>
        <a href='<?php echo $myLink; ?>Edit/key/<?php echo $counter['id']; ?>/'
           title='<?php echo $textEdit; ?>'
           class='admin-btn-small admin-btn-edit'
        >
        </a>   
        <a href='<?php echo $myLink; ?>Delete/key/<?php echo $counter['id']; ?>/'
           title='<?php echo $textDelete; ?>'
           class='admin-btn-small admin-btn-delete'
        >
        </a>   
        <div class='clr'></div>
      </div>
    </td>
  </tr>
  <?php } ?>
</table>  
[endblock-content]