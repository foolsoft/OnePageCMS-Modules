[parent:../MPages/Index.php]

[block-content]
<?php if ($tag->ok) { ?>
    <div class="center">
        <h3>Платеж принят! Спасибо за обращение!</h3>
        <a title="<?php _T('Перейти в личный кабинет'); ?>" href="<?php echo fsHtml::Url(URL_ROOT.'user/auth'); ?>"><?php _T('Перейти в личный кабинет'); ?></a>
    </div>
<?php } else { ?>
    <h3 class="center">Доступ закрыт!</h3>
<?php } ?>
[endblock-content]