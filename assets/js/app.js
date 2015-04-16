(function($) {
  $(document).ready(function(){
  	$("select").imagepicker();
  });

          //___Start Insert into Editor Panel___//

        $(document).on('click', '.action-insert-shortcode', function () {
            var $presets = $('.tx-feature-value').val();
               if($presets.length != 0){
                wp.media.editor.insert('[xpert-feature post_id="' + $presets + '" ][/xpert-feature]');
                $('#myModal').modal('hide');
               }
        });




})(jQuery);