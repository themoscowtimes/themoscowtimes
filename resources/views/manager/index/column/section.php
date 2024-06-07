{%
var found = [];
var sections = ['news', 'opinion', 'meanwhile', 'city', 'business','climate','diaspora', 'ukraine_war', 'sponsored', 'russian', 'indepth', 'lecture_series'];
for(var i = 0; i < sections.length; i++) {
	if (item.data[sections[i]]) {
		if(sections[i] == 'indepth') {
			found.push('feature');
		} else {
			found.push(sections[i]);
		}
	}
}
var section = found.join(', ');
%}
{{ section }}

