[parent:../../AdminPanel/Index.php]

[block-content]
<?php
$textBack = T('XMLcms_back');
?>
<a class="fsCMS-btn admin-btn-back"
   href="<?php echo fsHtml::Url($myLink.'Index'); ?>"
   title="<?php echo $textBack; ?>">
  <?php echo $textBack; ?>
</a>
<hr />
<form action="<?php echo $myLink; ?>DoEdit/key/<?php echo $tag->operation->id; ?>/" method='post'>
  <p class='title'>
    <?php echo T('XMLcms_text_name'); ?>:<br />
    <input class='input-100' maxlength='125' type='text' name='name' value='<?php echo $tag->operation->name; ?>' />
  </p>
  <hr /> 
  <input class='fsCMS-btn admin-btn-save' type='submit' value='<?php echo T('XMLcms_save'); ?>' />   
</form>
[endblock-content]