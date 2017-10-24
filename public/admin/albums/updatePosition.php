<script>
$(document).ready(function(){

    $("#category").change(function(){

        var category = $(this).val();

        $.ajax({
            url: '/public/admin/albums/countAlbums.php',
            type: 'post',
            data: {category_id:category},
            dataType: 'text',
            success:function(response){
                response = parseInt(response);
                var new_form = <?= $new ? 'true' : 'false'; ?>;
                var album_count;
                if (response == 0 || new_form == true){
                    album_count = response + 1;
                } else {
                    album_count = response;
                }
                var position = $("#position");
                position.empty();
                for( var i = 1; i <= album_count; i++){

                    if (i == album_count) {
                        position.append("<option value='"+i+"' selected>"+i+"</option>");
                    } else {
                        position.append("<option value='"+i+"'>"+i+"</option>");
                    }
                }
                <?php if (!$new) { ?>
                    var existing_id = <?= $album['category_id'] ?? '1'; ?>;
                    var existing_position = <?= $album['position'] ?? '1'; ?>;
                    if (category == existing_id) {
                        position.val(existing_position);
                    } else {
                        position.val(album_count);
                    }
                <?php } else { ?>
                    position.val(album_count);
                <?php } ?>
            }
        });
    }).trigger('change');

});
</script>
