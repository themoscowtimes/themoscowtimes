define('manager.form.element.Article')
.as(function(y){
	
	this.start = function(scope)
	{
		var video = scope.fetch('element-video').fetch('group', 'closest');
		var audio = scope.fetch('element-audio').fetch('group', 'closest');
		var images = scope.fetch('element-images').fetch('group', 'closest');
		var live = scope.fetch('live');
		var intro = scope.fetch('element-intro').fetch('group', 'closest');
		var summary = scope.fetch('element-summary').fetch('group', 'closest');
		var excerptLive = scope.fetch('element-excerpt_live').fetch('group', 'closest');
		var body = scope.fetch('element-body').fetch('group', 'closest');
		var image = scope.fetch('element-image').closest('.card');
		
		scope.fetch('element-type').change(function(){
			video.hide();
			audio.hide();
			live.hide();
			summary.hide();
			excerptLive.hide();
			intro.show();
			// dont gide iamges in block editor
			if(images.fetch('editor', 'closest').length == 0) { 
				images.hide();
			}
			
			body.hide();
			image.hide();
			
			switch(y(this).val()) {
				case 'default':
					image.show();
					body.show();
					break;
				case 'video':
					video.show();
					image.show();
					body.show();
					break;
				case 'podcast':
					image.show();
					audio.show();
					body.show();
					break;
				case 'gallery':
					image.show();
					images.show();
					break;
				case 'live':
					image.show();
					summary.show();
					excerptLive.show();
					intro.hide();
					live.show();
					break;
			}
		}).change();
	}
});