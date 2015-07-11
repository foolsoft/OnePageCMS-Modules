[parent:../AdminPanel/Index.php]

[block-content]
<?php
$textDelete = T('XMLcms_delete');
$textEdit = T('XMLcms_edit'); 
echo fsHtml::Link($myLink.'Add', T('XMLslider_add'), false, array('class' => 'fsCMS-btn admin-btn-add'));
?>
<hr />
<table class="list-table">
  <tr>
    <th><?php _T('XMLcms_text_name'); ?></th>
    <th><?php _T('XMLcms_text_template_code'); ?></th>
    <th><?php _T('XMLcms_text_action'); ?></th>
  </tr>
  <?php foreach ($tag->sliders as $slider) { ?>
  <tr>
    <td><?php echo $slider['name']; ?></td>
    <td>{% MSlides/Slider | id=<?php echo $slider['id']; ?> %}</td>
    <td>
      <div class='admin-action-td'>
        <a href='<?php echo $myLink; ?>Edit/key/<?php echo $slider['id']; ?>/'
           title='<?php echo $textEdit; ?>'
           class='admin-btn-small admin-btn-edit'
        ></a>   
        <a href='<?php echo $myLink; ?>Delete/key/<?php echo $slider['id']; ?>/'
           title='<?php echo $textDelete; ?>'
           class='admin-btn-small admin-btn-delete'
        ></a>   
        <div class='clr'></div>
      </div>
    </td>
  </tr>
  <?php } ?>
</table>  
[endblock-content]