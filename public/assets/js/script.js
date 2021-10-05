$(document).ready(function(){
    main_url=location.protocol+'//'+location.host;
    $('#image').on('change', function(){ //on file input change
        if (window.File && window.FileReader && window.FileList && window.Blob) //check File API supported browser
        {
            $('#thumb-output').html(''); //clear html of output element
            var data = $(this)[0].files; //this file data

            $.each(data, function(index, file){ //loop though each file
                if(/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)){ //check supported file type
                    var fRead = new FileReader(); //new filereader
                    fRead.onload = (function(file){ //trigger function on successful read
                        return function(e) {
                            var img = $('<img/>').addClass('thumb').attr('src', e.target.result); //create image element
                            $('#thumb-output').append(img); //append image to output element
                        };
                    })(file);
                    fRead.readAsDataURL(file); //URL representing the file's data.
                }
            });

        }else{
            alert("Your browser doesn't support File API!"); //if File API is absent
        }
    });


    //for product page
    $('.product_main_category').change(function(){
        $categry_id=$(this).val();

        $('.dynamic_sub_category').html('');

        $.ajax({
            beforeSend: function( xhr ) {
                $(".main_loader").show();                    
            },
            url: main_url+'/admin/get_sub_categories',
            type: "get",                
            dataType:'json',
            data:{
                'id' :$categry_id
            },                
            success: function(dataResult){                               
                $.each(dataResult,function(i,data){                       
                    $('.dynamic_sub_category').append("<option value='"+data.id+"'>"+data.name+"</option>");
                });
            }
        });        
    });

    $('.product_main_category').change();
});