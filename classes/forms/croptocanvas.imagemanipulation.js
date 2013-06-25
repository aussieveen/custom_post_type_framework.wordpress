$(document).ready(function(){

	$('#original_image').Jcrop({
		aspectRatio:canvas_width/canvas_height,
		onChange: updateCoords,
		onSelect: updateCoords
	});

	function updateCoords(c) {
		iwidth = parseInt($("#original_image").css("width"));
		iheight = parseInt($("#original_image").css("height"));

		war = image_width/iwidth;
		har = image_height/iheight;

		$('#x').val(c.x * war);
		$('#y').val(c.y * har);
		$('#w').val(c.w * war);
		$('#h').val(c.h * har);		
	}
});