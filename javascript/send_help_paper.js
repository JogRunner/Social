//获得表单数据
function get_paper_info()
{
    var paper_json ={  content_text:$('#content_text').val(),
                        help_location:$('#hidden-school-value').val(),
                        help_last_time:$('#dg').val(),
                    };
    return paper_json;
}

//提交表单
$.fn.UploadFormWithImg = function(validate_form, o){
    
    var file = null;
    $('#upload_picture').change(function()
    {
        file = this.files['0'];
    });

    this.click(function(){

        if(false == validate_form())
        {
            return false;
        }

        var paper_info = get_paper_info();

        console.log(file);
        //$('#error').html(file.type);
        if(file && file.size && file.size > o.mixsize){
            o.error('大小超过限制');
            //file.value='';
        }else if(o.type && file &&  o.type.indexOf(file.type) < 0){
            o.error('格式不正确');
            //ile.value='';
        }else{
            var URL = URL || webkitURL;
            var blob = URL.createObjectURL(file);
            //o.before(blob);
            _compress(blob, file, paper_info);
        }

    });


    function _compress(blob, file, paper_info){
        var img = new Image();

        if(null == file){
            _ajax(null, null, paper_info);
        }else{

            img.src = blob;
            img.onload = function(){
                var canvas = document.createElement('canvas');
                var ctx = canvas.getContext('2d');
                if(!o.width && !o.height && o.quality == 1){
                    var w = this.width;
                    var h = this.height;
                }else{
                    var w = o.width || this.width;
                    var h = o.height || w/this.width*this.height;
                }
                $(canvas).attr({width : w, height : h});
                ctx.drawImage(this, 0, 0, w, h);
                var base64 = canvas.toDataURL(file.type, (o.quality || 0.8)*1 );
                if( navigator.userAgent.match(/iphone/i) ) {
                    var mpImg = new MegaPixImage(img);
                    mpImg.render(canvas, { maxWidth: w, maxHeight: h, quality: o.quality || 0.8, orientation: 6 });
                    base64 = canvas.toDataURL(file.type, o.quality || 0.8 );
                }

                // 修复android
                if( navigator.userAgent.match(/Android/i) ) {
                    var encoder = new JPEGEncoder();
                    base64 = encoder.encode(ctx.getImageData(0,0,w,h), o.quality * 100 || 80 );
                    //alert(base64);
                }

                _ajax(base64,file.type, paper_info);
            };

        }

        
    }

    function _ajax(base64,type,paper_info){
        $.post(o.url,{base64:base64,type:type,paper_info:paper_info},function(res){
            var res = eval('(' + res + ')');
            if(res.status == 1){
                o.error(res.msg);
            }else{
                o.success(res.msg);
            }
            console.log(res);
        });

    }

    return true;
};
