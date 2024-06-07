define('yellow.String')
.as({
	escape: function(string)
	{
		var map = {
			'&': '&amp;',
			'<': '&lt;',
			'>': '&gt;',
			'"': '&quot;',
			"'": '&#39;',
			'/': '&#x2F;'
		};
		return String(string).replace(/[&<>"'\/]/g, function (s) {
			return map[s];
		});
	}
});