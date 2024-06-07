define('yellow.Vue')
.as({
	make: function(constructor){
		var vue;
		return {
			appendTo: function(el){
				var mount = document.createElement('div');
				el.appendChild(mount);
				this.render(mount);
			},
			prependTo: function(el){
				var mount = document.createElement('div');
				el.insertBefore(mount, el.firstChild);
				this.render(mount);
			},
			render: function(mount){
				constructor.el = mount;
				vue = new Vue(constructor);
			}
		}
	}
});
/*
var data = {
	foo: 'bar'
}

base.get('Vue').make({
	template: scope.template('named'),
	data: data,
	computed:{
	},
	methods: {
		clicked: function(){
		}
	}
}).appendTo(scope.element('mountingpoint'));
*/