<?php
/* ATTENTION!
Creating MSlide template is hard and specific process, 
be sure that you use all $tag values. 
*/
?>
<div class="fsSlider"
     data-auto="true"
     data-id="<?php echo $tag->id; ?>"
     data-interval="<?php echo $tag->interval; ?>"
     data-animation="<?php echo $tag->animation; ?>"
     id="fsSlider<?php echo $tag->id; ?>"
     style="width:<?php echo $tag->width.$tag->width_unit; ?>;height:<?php echo $tag->height; ?>px">
    <a name="fsSlider<?php echo $tag->id; ?>"></a> 
    <?php if($tag->navigation == '1') {
      $offsetTop = $tag->height / 2 - 25;
      $offsetLeft = $tag->width - 20 - 45;
    ?> 
      <div style="margin:<?php echo $offsetTop; ?>px 0 0 20px;" class="navigation navigation-previous"></div>
      <div style="margin:<?php echo $offsetTop; ?>px 0 0 <?php echo $offsetLeft; ?>px;" class="navigation navigation-next"></div>
    <?php } ?>
    <ul class="fsSlides" style="width:<?php echo $tag->width.$tag->width_unit; ?>;height:<?php echo $tag->height; ?>px;">
    <?php $idx = 1; foreach($slides as $slide) { ?>
      <li style="width:<?php echo $tag->width.$tag->width_unit; ?>;z-index:<?php echo ($tag->count - $idx + 1); ?>;" id="fsSlide<?php echo $tag->id; ?>-<?php echo $idx; ?>" class="fsSlide<?php echo $idx++; ?> fsSlide">
        <a href="<?php echo $slide['href'] == '' ? '#fsSlider'.$tag->id : $slide['href']; ?>" title="<?php echo $slide['alt']; ?>" class="fsSlideLink">
          <img class="fsSlideImage" src="<?php echo $tag->path.$slide['image']; ?>" border="0" 
               width="<?php echo $tag->width.$tag->width_unit; ?>" 
               height="<?php echo $tag->height; ?>" 
               alt="<?php echo $slide['alt']; ?>" title="<?php echo $slide['alt']; ?>" />
          <div class="fsSlideHtml"><?php echo $slide['html']; ?></div>
        </a>
      </li>
    <?php } ?>
    </ul>
</div>