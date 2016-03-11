[parent:../AdminPanel/Index.php]

[block-content]
<?php
$textSettings = T('XMLcms_settings');
$textCacheClear = T('XMLcms_cache_clear');
$textOtype = T('XMLfspay_operation_type');
$textSortId = T('XMLfspay_sort_id');
$textSortDate = T('XMLfspay_sort_date');
$textDelete = T('XMLcms_delete');
$textActivate = T('XMLcms_activate');
$users = $tag->users;
?>
<a class="fsCMS-btn admin-btn-config float-left" href="<?php echo fsHtml::Url($myLink.'Config'); ?>"
   title="<?php echo $textSettings; ?>">
   <?php echo $textSettings; ?>
</a>
<a class="fsCMS-btn float-left" href="<?php echo fsHtml::Url(URL_ROOT.'AdminPayOperationType/Index'); ?>"
 title="<?php echo $$textOtype; ?>">
 <?php echo $textOtype; ?>
</a>
<?php
foreach ($tag->plugins as $plugin) {
?>
<a class="fsCMS-btn float-left" href="<?php echo $plugin['url']; ?>"
 title="<?php echo $plugin['title']; ?>">
 <?php echo $plugin['title']; ?>
</a>
<?php
}
?>
<hr />
<form method="post">
    <table>
        <tr>
            <td align='right'><?php _T('XMLfspay_payment_number'); ?>:</td>
            <td align='left'><input name='search[id]' value='<?php echo $tag->search->id; ?>' size='5'/></td>
            <td align='right'><?php _T('XMLfspay_contacts'); ?></td>
            <td align='left'><input name='search[contact]' value='<?php echo $tag->search->contact; ?>' /></td>
            <td align='right'><?php _T('XMLcms_text_row_on_page'); ?>:</td>
            <td align='left'><input name='search[count]' value='<?php echo $tag->search->count; ?>' size='3' /></td>
            <td align='right'><?php _T('XMLcms_text_first_row'); ?>:</td>
            <td align='left'><input name='search[start]' value='<?php echo $tag->search->start; ?>' size='3' /></td>
            <td align='left'><input type='submit' value='<?php _T('XMLcms_text_search'); ?>' /></td>
        </tr>
    </table>
</form>
<hr />
<table class="list-table">
    <tr>
        <th>
            <a alt='<?php echo $textSortId; ?>' title='<?php echo $textSortId; ?>' href='<?php echo $myLink; ?>SetSort/sort/id/sort_desc/<?php echo (int)(!fsSession::GetInstance('pay_sort_desc')); ?>/'>
            <?php _T('XMLfspay_payment_number'); ?>
            </a>
        </th>
        <th>
            <a alt='<?php echo $textSortDate; ?>' title='<?php echo $textSortDate; ?>' href='<?php echo $myLink; ?>SetSort/sort/date_payed/sort_desc/<?php echo (int)!(fsSession::GetInstance('pay_sort_desc')); ?>/'>
            <?php _T('XMLfspay_date'); ?>
            </a>
        </th>
        <th>
           <?php _T('XMLfspay_payment_type'); ?>
        </th>
        <th>                
           <?php _T('XMLfspay_operation'); ?>
        </th>            
        <th>
            <?php _T('XMLfspay_sum'); ?>
        </th>
        <th>
            <?php _T('XMLfspay_contacts'); ?>
        </th>
        <th>
            <?php _T('XMLfspay_comment'); ?>
        </th>
        <th>  
            <?php _T('XMLfspay_system_message'); ?>
        </th>
        <th>
            <?php _T('XMLcms_text_action'); ?>
        </th>
    </tr>
<?php foreach ($tag->pays as $pay) { ?>
    <tr class="admin-row-active-<?php echo $pay['status']; ?>">
        <td>
           <?php echo $pay['id']; ?>
        </td>
        <td>
           <?php echo $pay['status'] == 0 ? $pay['date_created'] : $pay['date_payed']; ?>
        </td>
        <td>
           <?php echo T(payFunctions::GetName($pay['system'])); ?>
        </td>
        <td>                
           <?php echo $pay['link_id_operation']; ?>
        </td>            
        <td>
            <?php echo $pay['sum']; ?>
        </td>
        <td>
            <?php 
            if(is_numeric($pay['contact']) && isset($users[$pay['contact']])) {
                echo $users[$pay['contact']];
            } else {
                echo $pay['contact']; 
            }
            ?>
        </td>
        <td>
            <?php echo empty($pay['comment']) ? '-' : $pay['comment']; ?>
        </td>
        <td>  
            <?php echo empty($pay['message']) ? '-' : $pay['message']; ?>
        </td>
        <td>
            <div class='admin-action-td'>
            <?php if ($pay['status'] != '2') { ?>
            <a href='<?php echo $myLink.'Activate/key/'.$pay['id'].'/'; ?>'
               title='<?php echo $textActivate; ?>'
               class='admin-btn-small admin-btn-activate'></a>
            <?php } ?>
            <a href='<?php echo $myLink; ?>Delete/key/<?php echo $pay['id']; ?>/'
               title='<?php echo $textDelete; ?>'
               class='admin-btn-small admin-btn-delete'></a>  
            </div>
        </td>
    </tr>        
<?php } ?>
</table>
<hr />
[endblock-content]