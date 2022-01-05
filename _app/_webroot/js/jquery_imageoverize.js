// JavaScript Document

// required jQuery

(function($) {

  $.fn.extend({
    imageOverize : function () {
      $(this).hover(
        $(this).imageOverActivate,
        $(this).imageOverDeactivate);
      return this;
    },

    imageOverActivate : function(){
      if($(this).length > 1) {
        $.each($(this), function(){
          $(this).imageOverActivate();
        });
      } else {
        var src = $(this).attr('src');
        var filetype = src.match(/\.([^\.]*)$/)[1];
        if(!src.match(/_ov/)) {
          $(this).attr('src', src.replace(/\.([^\.]*)$/, '_ov.' + filetype));
        }
      }
      return this;
    },

    imageOverDeactivate : function(){
      if($(this).length > 1) {
        $.each($(this), function(){
          $(this).imageOverDeactivate();
        });
      } else {
        var src = $(this).attr('src');
        var filetype = src.match(/\.([^\.]*)$/)[1];
        if(src.match(/_ov/)) {
          $(this).attr('src', src.replace(/_ov\.([^\.]*)$/, '.' + filetype));
        }
      }
      return this;
    }
  });

  $(function(){
    $('.image_ov img, img.image_ov, input[type=image].image_ov').imageOverize();
  });

})(jQuery);
