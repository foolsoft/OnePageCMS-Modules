[parent:../AdminPanel/Index.php]

[block-content]
<?php
$textBack = T('XMLcms_back');
$first_system = '';
?>
<a class="fsCMS-btn admin-btn-back" href="<?php echo fsHtml::Url(URL_ROOT . 'AdminPayController/Index'); ?>" title="<?php echo $textBack; ?>">
    <?php echo $textBack; ?>
</a>
<hr />
<form action='<?php echo fsHtml::Url($myLink . 'Add'); ?>' method='post'>
    <p class="title">
        <?php _T('Плательщик'); ?><br />
        <?php echo fsHtml::Select('contact', $tag->users, false, array('class' => 'input-100')); ?>
    </p>
    <p class="title">
        <?php _T('Тип опeрации'); ?><br />
        <?php echo fsHtml::Select('id_operation', $tag->operations, false, array('class' => 'input-100')); ?>
    </p>
    <p class="title">
        <?php _T('Система оплаты'); ?><br />
        <?php echo fsHtml::Select('system', $tag->systems, false, array('class' => 'input-100')); ?>
    </p>
    <p class="title">
        <?php _T('Сумма'); ?><br />
        <?php echo fsHtml::Number('sum', 0, array('class' => 'input-100', 'min' => '0', 'step' => '0.01')); ?>
    </p>
    <p>
        <?php _T('Комментарий'); ?><br />
        <?php echo fsHtml::Textarea('comment', '', array('class' => 'input-100')); ?>
    </p>
    <input class='fsCMS-btn admin-btn-save' type='submit' value='<?php _T('XMLcms_save'); ?>' />
</form>
[endblock-content]