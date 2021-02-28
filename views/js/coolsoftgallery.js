$(document).ready(function(){

  $('.delete_ajax_close').on('click', function() {
    var data = window.location.href;
    var token = data.substr(data.indexOf("token=")+6);
    var srcDelete = $(this).data( "src" );

    var myObj = {};
    myObj["token"] = token;
    myObj["image_delete"] = srcDelete;

   // var json = JSON.stringify(myObj);
   var dataUrl = window.location.origin+'/modules/coolsoftgallery/ajax.php';
    $.ajax({
        url : dataUrl,
        data : myObj,
        method : "post",
        success : function(response) {
            console.log(response);
            window.location.reload();
        },
        error : function(e) {
            console.log(e);
        }
    });
  })

});