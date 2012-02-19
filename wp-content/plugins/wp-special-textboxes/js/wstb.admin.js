(function($){
  $(document).ready(function() {
    $("#tabs").tabs();
  
    $('#js_shadow_color, #js_textShadow_color').ColorPicker({
        onSubmit: function(hsb, hex, rgb, el){
          $(el).val(hex);
          $(el).ColorPickerHide();
        },
        onBeforeShow: function(){
          $(this).ColorPickerSetColor(this.value);
        }
      }).bind('keyup', function(){
      $(this).ColorPickerSetColor(this.value);
    });
    return false;
  });
})(jQuery)