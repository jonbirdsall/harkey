<script>
$(document).ready(function(){

    $("#category").change(function(){
        var category = $(this).val();

        $.ajax({
            url: '/public/admin/albums/getAlbums.php',
            type: 'post',
            data: {category_id:category},
            dataType: 'json',
            success:function(response){

                var len = response.length;

                $("#album").empty();
                for( var i = 0; i<len; i++){
                    var id = response[i]['id'];
                    var menu_name = response[i]['menu_name'];
                    <?php if (!$new) { ?>
                        if (id == image_album) {
                            $("#album").append("<option value='"+id+"' selected>"+menu_name+"</option>");
                        } else {
                            $("#album").append("<option value='"+id+"'>"+menu_name+"</option>");
                        }
                    <?php } else { ?>
                        $("#album").append("<option value='"+id+"'>"+menu_name+"</option>");
                    <?php } ?>
                }
            }
        });
    }).trigger('change');


});
</script>
