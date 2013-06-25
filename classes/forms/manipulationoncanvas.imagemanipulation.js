jQuery(document).ready(function($) {
	$('#canvas').css({'width':canvas_width,'height':canvas_height,'overflow':'hidden','border':'1px solid #CCC','position':'relative'});
	var image = new Image();
    image.onload = function() {
   	 	var uploaded_height = image.height;
   		var uploaded_width = image.width;
   		uploaded_ratio = uploaded_width/uploaded_height;
   		canvas_ratio = canvas_width/canvas_height;
   		if(uploaded_ratio >= canvas_ratio){
   			multiplier = canvas_width/uploaded_width;
   		}else{
   			multiplier = canvas_height/uploaded_height;
   		}
   		w = uploaded_width * multiplier;
   		h = uploaded_height * multiplier;
   		posx = (canvas_width - w)/2;
   		posy = (canvas_height - h)/2;
   		$('#w').val(w);
		$('#h').val(h);
   		$('#x').val(posx);
		$('#y').val(posy);
				
   		$('#resizable').css({'position':'absolute','top':posy,'left':posx,'width':w,'height':h});

   		$('#draggable').draggable({
   			stop:function(){
				$('#x').val(parseInt($(this).css('left')) + posx);
				$('#y').val(parseInt($(this).css('top')) + posy);
   			}
   		});
   		$('#resizable').resizable({
   			aspectRatio:uploaded_ratio,
   			stop:function(){
				$('#w').val($(this).css('width'));
				$('#h').val($(this).css('height'));
   			}
   		});
    }
    image.src = $('#resizable').attr('src');
});