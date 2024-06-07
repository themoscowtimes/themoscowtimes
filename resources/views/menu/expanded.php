
<div class="nav-expanded" style="display: none;" y-name="expanded">
	<div class="nav-overlay"></div>
	<div class="nav-container" y-name="container">
		<div class="container">
			<div class="nav-container__inner">
				<div class="nav-expanded__header">
					<div class="nav-expanded__close" y-name="close">&times;</div>
				</div>
				<nav class="">
					<?php
					// helper funtion to fill up a dom tree recursively
					$fill = function($parent, $branch = [], $depth = 1) use (& $fill) {
						foreach ($branch as $item) {
							$hasChild = (isset($item->children) && count($item->children));
							// html node for each item
							switch($item->type) {
								case 'default' :
									$link = fetch::link($item->link, $item->title);
									break;
								case 'html' :
									$link = $item->html;
									break;
							}
							$child = fetch::dom('li', ['class' => $hasChild ? 'has-child' : ''], $link);
							// add it to the parent
							$parent->append($child);
							// now for the children of each item
							if($item->children && count($item->children) > 0) {
								switch($depth) {
									case 1:
										$tag = 'ul';
										$attributes = ['class' => 'depth-1'];
										break;
									case 2:
										$tag = 'ul';
										$attributes = ['class' => 'depth-2'];
										break;
									default:
										$tag = 'ul';
										$attributes = ['class' => 'depth-3'];
										break;
								}
								// create a wrapper for these children
								$wrapper = fetch::dom($tag, $attributes);
								// append the wrapper to the child
								$child->append($wrapper);
								// fill up the wrapper with the children
								$fill($wrapper, $item->children, $depth + 1);
							}
						}
					};
					// root node for the menu
					$menu = fetch::dom('ul', ['class' => 'depth-0']);
					// fill it up with the menu tree
					$fill($menu, $tree);
					// render the dom tree and output
					view::raw($menu->render());
					?>
				</nav>
			</div>
		</div>
	</div>
</div>

