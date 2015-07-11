function SlideSetting(id) {
  var $div = $('#slide-setting-'+id);
  if($div.css('display') == 'none') {
    $div.slideDown();
  } else {
    $div.slideUp();
  }
}

function DeleteSlide(id) {
  if(!confirm(cmsDictionary.cms_text_sure)) {
    return;
  }
  fsCMS.Ajax(
    URL_ROOT + 'AdminMSlides/AjaxDeleteSlide/key/' + id + '/',
    'POST', false, false, true, false,
    function(answer) {
      if(answer == 'ok') {
        $('#slide' + id).slideUp('slow', function() { $('#slide' + id).remove(); });
      } else if(answer.split(':')[0] == 'error') {
        alert(answer.split(':')[1]);
      } 
    } 
  );
}