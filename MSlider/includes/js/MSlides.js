var fsSlider = {
  Slider: {},
  CurrentSlide: {},
  Create: function(selector) {
    $(selector).each(function() {
      var $this = $(this), interval = $this.attr('data-interval');
      var autoStart = $this.attr('data-auto') != 'false';
      if(interval == undefined || interval == null || interval == '' 
         || !$.isNumeric(interval) || parseInt(interval) < 0) {
        interval = 5000;
      }
      fsSlider.CurrentSlide[$this.attr('id')] = 1;
      if($this.find('.fsSlide').length == 1) {
        return;
      }
      if(autoStart) {
        fsSlider.Slider[$this.attr('id')] = setInterval(function() {
          fsSlider.Next($this);  
        }, interval);
      }  
    });
  },
  ChangeZIndex: function($sliderObj, step) {
    //step = 1 (next) or -1 (previous)
    var slidesCount = $sliderObj.find('.fsSlide').length;
    var id = $sliderObj.attr('id');
    $sliderObj.find('.fsSlide').each(function() {
      var $this = $(this);
      if($this.css('z-index') == slidesCount) {
        $this.css('z-index', step == 1 ? 1 : slidesCount - 1);
      } else {
        var zIndex = parseInt($this.css('z-index')) + step;
        if(step == -1 && zIndex == 0) {
          zIndex = slidesCount; 
        } 
        $this.css('z-index', zIndex); 
      }
    }); 
  },
  ChangeCurrentSlide: function(sliderId, step) {
    var slidesCount = $('#' + sliderId).find('.fsSlide').length;
    fsSlider.CurrentSlide[sliderId] += step;
    if(slidesCount < fsSlider.CurrentSlide[sliderId]) {
      fsSlider.CurrentSlide[sliderId] = 1;  
    } else if(1 > fsSlider.CurrentSlide[sliderId]) {
      fsSlider.CurrentSlide[sliderId] = slidesCount;  
    }
  },
  Next: function($sliderObj) {
    var id = $sliderObj.attr('id');
    var animationType = fsSlider.GetAnimation($sliderObj);
    $sliderObj.find('.fsSlide' + fsSlider.CurrentSlide[id]).animate(animationType, function() { 
      fsSlider.ChangeZIndex($sliderObj, 1);
      fsSlider.AfterAnimation($sliderObj, $(this)); 
      fsSlider.ChangeCurrentSlide(id, 1);
    });   
  },
  Back: function($sliderObj) {
    var id = $sliderObj.attr('id'),
        slideTop = $sliderObj.find('.fsSlide' + fsSlider.CurrentSlide[id]);
    fsSlider.ChangeZIndex($sliderObj, -1);
    var zIndex = $(slideTop).css('z-index');
    $(slideTop).css('z-index', $sliderObj.find('.fsSlide').length + 1);
    var animationType = fsSlider.GetAnimation($sliderObj, -1);
    $(slideTop).animate(animationType, function() {
        fsSlider.AfterAnimation($sliderObj, $(this));
        $(slideTop).css('z-index', zIndex);
        fsSlider.ChangeCurrentSlide(id, -1);
    });   
  },
  GetAnimation: function($sliderObj, step) {
    step = step || 1;
    var animation = $sliderObj.attr('data-animation');
    switch(animation) {
      case 'fade': return { opacity: '0' };  
      case 'scroll': return { marginTop: -step * $sliderObj.height() + 'px' };  
      case 'slide': return { marginLeft: -step * $sliderObj.width() + 'px' }; 
      case 'none': default: return {};
    }
  },
  AfterAnimation: function($sliderObj, $slide) {
    var animation = $sliderObj.attr('data-animation');
    switch(animation) {
      case 'fade': $slide.css('opacity',  '1');  
      case 'scroll': $slide.css('margin-top',  '0');
      case 'slide': $slide.css('margin-left',  '0');
      case 'none': default: break;
    }
  }  
}

$(document).ready(function() {
  fsSlider.Create('.fsSlider');
  $('.fsSlider .navigation').click(function() {
    clearInterval(fsSlider.Slider[$(this).parent().attr('id')]);
  });
  $('.fsSlider .navigation-previous').click(function() {
    fsSlider.Back($(this).parent());  
  });
  $('.fsSlider .navigation-next').click(function() {
    fsSlider.Next($(this).parent());  
  });
});