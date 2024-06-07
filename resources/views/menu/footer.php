<footer class="footer fancyfooter">

	<div class="container">
		<div class="footer__inner">
			<div class="footer__logo mb-3">
				<a href="<?php view::url('base'); ?>" class="footer__logo__wrapper"
					title="The Moscow Times - Independent News from Russia">
					<?php if (false && isset($_GET["amp"]) == 1): ?>
						<amp-img src="<?php view::url('static'); ?>/img/logo_tmt_amp-1710_2023-1.png" alt="The Moscow Times" layout="responsive" height="126" width="640"></amp-img>
					<?php else: ?>
						<img src="<?php view::url('static'); ?>img/logo_tmt_30_yo.svg" alt="The Moscow Times">
					<?php endif; ?>
				</a>
			</div>
			<div class="footer__main">
				<?php /*
				<div class="footer__form" y-use="Newsletter" data-url="<?php view::route('newsletter'); ?>" id="newsletter">
					<h3 class="header--style-3 footer__form__header"><?php view::lang('Sign up for our weekly newsletter'); ?>
					</h3>
					<div class="footer__form__error" y-name="error" style="display:none"></div>
					<input type="text" placeholder="<?php view::lang('Your email'); ?>" y-name="email" />
					<button class="button button--color-3" y-name="submit"><?php view::lang('Submit'); ?></button>
					<div class="" y-name="done" style="display:none">Thanks for signing up!</div>
				</div>
				*/ ?>
				<div class="footer__menu">
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
							$child = fetch::dom('li', ['class' => 'col' . ($hasChild ? ' has-child' : '')], $link);
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
					$menu = fetch::dom('ul', ['class' => 'row-flex depth-0']);
					// fill it up with the menu tree
					$fill($menu, $tree);
					// render the dom tree and output
					view::raw($menu->render());
					?>
				<?php if (false && isset($_GET["amp"]) == 1): ?>
				<ul class="row-flex depth-0">
					<li class="col has-child">
						<a href="#"><span  class="consent-ccpa consent-eea">Misc</span></a>
							<ul class="depth-1">
								<li class="col">
									<div id="ccpa-consent-ui" class="consent-ccpa">
  									<a on="tap:consent.prompt(consent=SourcePoint)">Do not sell or share my personal information</a>
									</div>
								</li>
								<li class="col">
									<div id="eea-consent-ui" class="consent-eea">
 										<a on="tap:consent.prompt(consent=SourcePoint)">Privacy Settings</a>
									</div>
								</li>
							</ul>
					</li>
				</ul>
				<?php endif; ?>
				</div>
			</div>
			<div class="footer__bottom">
				&copy; The Moscow Times, all rights reserved.
			</div>

		</div>

	</div>
</footer>