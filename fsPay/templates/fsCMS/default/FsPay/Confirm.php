[parent:../MPages/Index.php]

[block-content]
<table>
  <tr>
    <th align="right">Email:</th>
    <td align="left"><?php echo $tag->input_contact; ?></td>
  </tr>
  <tr>
    <th align="right">Сумма платежа:</th>
    <td align="left"><?php echo $tag->input_sum; ?></td>
  </tr>
  <tr>
    <th align="right">Тип операции:</th>
    <td align="left"><?php echo $tag->input_operation_type; ?></td>
  </tr>
  <tr>
    <th align="right">Комментарий к платежу:</th>
    <td align="left"><?php echo $tag->input_comment; ?></td>
  </tr>
  <tr>                
    <th align="right">Тип оплаты:</th>
    <td align="left"><?php echo $tag->input_pay_type; ?></td>
  </tr>
  <tr>
    <td colspan='2' align="center">
        <input type='button' onclick="window.history.back();" value='Назад' /> 
        <input type='submit' value='Подтвердить и оплатить' />
    </td>
  </tr>
  <tr>
    <td colspan='2' align="center"><?php echo $tag->message; ?></td>
  </tr>
</table>
[endblock-content]