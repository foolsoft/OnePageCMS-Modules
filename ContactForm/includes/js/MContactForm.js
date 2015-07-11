$(document).ready(function() {
  $('.mContactFormAjax').each(function() {
    $this = $(this);
    var resultId = $this.attr('id') + '-ajax-result';
    $this.ajaxForm({
        target: '#' + resultId,
        beforeSubmit: function() {
          fsCMS.WaitAnimation('on', resultId, 16);
        } 
      }
    );
  });
  
  $('.mContactForm input[type=text][data-text!=""], .mContactForm textarea[data-text!=""]').each(function() {
    $(this).val($(this).attr('data-text'));
    $(this).addClass('okField');
    $(this).click(function() {
        if ($(this).val() == $(this).attr('data-text')) {
            $(this).val('');
        } 
    });
    $(this).blur(function() {
        if ($(this).val() == '') {
            $(this).val($(this).attr('data-text'));
        }
    });
  });
  
  $('.mContactForm').submit(function() {
    var inputs = $(this).find('input[type=text][data-text!=""], textarea[data-text!=""]');
    var isError = false;
    for (var i = 0; i < inputs.length; ++i) {
        if ($(inputs[i]).attr('data-text') == $(inputs[i]).val()) {
            $(inputs[i]).addClass('errorField');
            isError = true;
        } else {
            $(inputs[i]).removeClass('errorField');
        }
    }
    return !isError;
  });
  
});