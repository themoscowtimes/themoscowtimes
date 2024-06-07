<div class="navigation" y-use="Navigation">

	<?php view::menu('expanded'); ?>

	<nav class="nav-top">
		<div class="menu-trigger" y-name="open"><i class="fa fa-reorder"></i></div>
		<div class="nav-top__logo--xs hidden-sm-up">
			<a href="<?php view::url('base'); ?>" class="site-header__logo " title="The Moscow Times - Independent News from Russia">
				<img src="<?php view::url('static'); ?>img/logo_tmt_30_yo.svg" alt="The Moscow Times" />
			</a>
		</div>
		<?php
		// helper funtion to fill up a dom tree recursively
		$fill = function($parent, $branch = [], $depth = 1) use (& $fill) {
			foreach ($branch as $item) {
				$hasChild = (isset($item->children) && count($item->children));
				// html node for each item
				switch ($item->type) {
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
				if ($item->children && count($item->children) > 0) {
					switch ($depth) {
						case 1:
							$tag = 'ul';
							$attributes = ['class' => ''];
							break;
						case 2:
							$tag = 'ul';
							$attributes = ['class' => ''];
							break;
						default:
							$tag = 'ul';
							$attributes = ['class' => ''];
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
		$menu = fetch::dom('ul', ['class' => 'nav-top__list']);
		// fill it up with the menu tree
		$fill($menu, $tree);
		// render the dom tree and output
		view::raw($menu->render());
		?>

		<div class="nav-top__wrapper">
			<div class="nav-top__extra">
				<a href="https://moscowtimes.ru" class="nav-top__lang-toggle">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 6" width="26" height="18">
						<rect fill="#fff" width="9" height="3" />
						<rect fill="#d52b1e" y="3" width="9" height="3" />
						<rect fill="#0039a6" y="2" width="9" height="2" />
					</svg>
					<span>RU</span>
				</a>
			</div>
			<a href="<?php view::route('search') ?>" title="Search" class="nav-top__search">
				<i class="fa fa-search"></i>
			</a>
			<div class="nav-top__account hidden-sm-up">
				<?php view::file('common/account'); ?>
			</div>
		</div>
	</nav>
</div>