define('manager.live.Manage')
.use('yellow.View')
.use('manager.Dialog')
.use('manager.Loading')
.use('manager.Message')
.as(function(y, View, Dialog, Loading, Message) {
	
	var _prototype;
	var _id;
	var _scope;
	var _values;
	
	
	this.start = function(scope) 
	{
		_scope = scope;
		
		_prototype = _scope.fetch('prototype');

		freshForm();
		posts();
		
		_scope.fetch('publish').click(function(){
			if(_id) {
				update(_id, values())
			} else {
				create(values());
			}
		})
		
		_scope.fetch('draft').click(function(){
			if(_id) {
				update(_id, values(), true);
			} else {
				create(values(), true);
			}
		})
		
	
		setInterval(function(){
			posts()
		}, 30000)
	}
	
	
	
	var values = function()
	{
		return {
			body: _scope.fetch('form').fetch('element-body').invoke('value'),
		}
	}
	
	
	var freshForm = function()
	{
		_id = null;
		createForm({
			body: [{
				type: 'html', body: ''
			}]
		})
		_scope.fetch('form').fetch('element-body').fetch('block-update').click();
	}
	
	
	
	var createForm = function(values)
	{
		if(_id) {
			_scope.fetch('create').hide();
			_scope.fetch('update').show();
		} else {
			_scope.fetch('create').show();
			_scope.fetch('update').hide();
		}
		// Set current value
		_values = values;
		// Create new block element
		var blocks = _prototype.make();
		// Set value in the blocks element
		blocks.fetch('element').data('value', values.body || '')
		// Add it to the html
		_scope.fetch('form').empty().append(blocks);
		// Show it
		blocks.show();
		// Start it up
		blocks.start();
	}
	
	
	
	var unsaved = function()
	{
		if(JSON.stringify(values()) == JSON.stringify(_values)) {
			return false;
		} else {
			return true;
		}
	}

	
	var create = function(data, draft) {
		if(draft) {
			data.status = 'draft'
		} else {
			data.status = 'live'
		}
		Loading.show();
		y.ajax(_scope.data('url').create,{
			method: 'post',
			data: data,
			dataType: 'json'
		}).done(function(result){
			posts();
			freshForm();
		}).always(function(){
			Loading.hide()
		})
	}
	
	
	var update = function(id, data, draft) {
		if(draft) {
			data.status = 'draft'
		} else {
			data.status = 'live'
		}
		Loading.show();
		y.ajax(_scope.data('url').update.replace('{{id}}', id),{
			method: 'post',
			data: data,
			dataType: 'json'
		}).done(function(result){
			posts();
			freshForm();
		}).always(function(){
			Loading.hide()
		})
	}
	
	
	var posts = function()
	{
		y.ajax(_scope.data('url').items,{
			dataType: 'json'
		}).done(function(data){
			if(y.isArray(data)) {
				_scope.fetch('posts').empty();
				_scope.fetch('drafts').empty();
				for(var i = 0; i < data.length; i++) {
					if(data[i].status == 'draft') {
						_scope.fetch('drafts').append(post(data[i]))
					} else {
						_scope.fetch('posts').append(post(data[i]))
					}
				}
			}
		})
	}
	
	
	var post = function(data)
	{
		var item = View.make(_scope.template('post'),{}, {
			time: function(ts) {
	
				return new Date(ts * 1000).toJSON().substring(0,19).replace('T',' ');
			}
		}).element(data);	
		
		if(y.isArray(data.body)) {
			for(var i = 0; i < data.body.length; i++) {
				var block = data.body[i];
				
				if(block.type == 'html' && block.body) {
					item.fetch('body').append(y(block.body))
				} else if(block.type == 'article' && block.article) {
					item.fetch('body').append(View.make(_scope.template('block_article')).element({
						article: block.article
					}))
				} else if(block.type == 'image' && block.image) {
					item.fetch('body').append(View.make(_scope.template('block_image')).element({
						image:block.image
					}))
				} else if(block.type == 'link' && block.link) {
					item.fetch('body').append(View.make(_scope.template('block_link')).element({
						link: block.link
					}))
				} else if(block.type == 'embed' && block.embed) {
					//item.fetch('body').append(y(block.embed))
					item.fetch('body').append(y('<div></div>').text(block.embed))

				}
			}
		}
		
		item.fetch('update').click(function() {
			if(unsaved()) {
				Dialog.confirm('Discard changes?', 'You have unsaved changes in your current post. Discard these changes?', function(){
					_id = data.id;
					createForm({
						body: data.body
					});
				})
			} else {
				_id = data.id;
				createForm({
					body: data.body
				});
			}
		})
		
		item.fetch('delete').click(function() {
			Dialog.confirm('Delete post?', 'Do you want to delete this post?', function(){
				Loading.show();
				y.ajax(_scope.data('url').delete.replace('{{id}}', data.id),{
					method: 'post',
					dataType: 'json'
				}).done(function(result){
					
					if(_id == data.id) {
						// deleted the one that was editing
						_id = null;
						freshForm();
					}
				}).always(function(){
					posts();
					Loading.hide()
				})
			})
		})
		
		return item;
	}
});
