define('article.Newsletter')
.use('yellow.View')
.as(function(y, View){

	this.start = function(scope)
	{
		// get content
		var content = scope.fetch('article-content', 'closest');
		
		// move this out of content
		scope.insertAfter(content);
		
		if(
			content.text().replace(/\s+/g, ' ').split(' ').length > 100 
			&& content.find('p').length > 2
		) {
			View.make(scope.template('newsletter')).element()
			.insertBefore(content.find('p').last());
		}
	}
});