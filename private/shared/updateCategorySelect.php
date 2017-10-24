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

                    $("#album").append("<option value='"+id+"'>"+menu_name+"</option>");

                }
            }
        });
    }).trigger('change');


});
</script>
