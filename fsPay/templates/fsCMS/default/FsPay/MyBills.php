[parent:../MPages/Index.php]

[block-content]
<div class="row title">
    <div class="col-sm-1"><?php _T('Номер'); ?></div>
    <div class="col-sm-2"><?php _T('Дата'); ?></div>
    <div class="col-sm-2"><?php _T('Сумма'); ?></div>
    <div class="col-sm-3"><?php _T('Услуга'); ?></div>
    <div class="col-sm-2"><?php _T('Комментарий'); ?></div>
    <div class="col-sm-2"><?php _T('XMLcms_text_action'); ?></div>
</div>
<?php foreach($tag->bills as $bill) { ?>
<div class="margin-top row row-paystatus-<?php echo $bill['status']; ?>">
    <div class="col-sm-1"><?php echo $bill['id']; ?></div>
    <div class="col-sm-2"><?php echo $bill['date_created']; ?></div>
    <div class="col-sm-2"><?php echo $bill['sum']; ?> &#8381;</div>
    <div class="col-sm-3"><?php echo $bill['link_id_operation']; ?></div>
    <div class="col-sm-2"><?php echo $bill['comment']; ?></div>
    <div class="col-sm-2"><?php
        if($bill['status'] == 2) {
            _T('Оплачен');
        } else if($bill['status'] == 1) {
            _T('Ожидает подтверждения');
        } else {
            echo fsHtml::Link(URL_ROOT.'fsPay'.$bill['system'].'/Action?payment='.$bill['id'], T('Оплатить'), '', array('class' => 'btn btn-default'));
        } 
    ?></div>
</div>
<?php } ?>
[endblock-content]