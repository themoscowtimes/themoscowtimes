<?php
$text = isset($item) && $item ? $item->title :'';
?>
<div class="social">
	<a href="https://www.facebook.com/sharer/sharer.php?u=<?php view::route('article', $item->data()); ?>" class="social__icon social__icon--facebook" target="_blank" title="<?php view::lang('Share on Facebook'); ?>"><i class="fa fa-brands fa-facebook"></i></a>
	<a href="https://twitter.com/intent/tweet/?url=<?php view::route('article', $item->data()); ?>&text=<?php view::attr($text); ?>" class="social__icon social__icon--x-twitter" target="_blank" title="<?php view::lang('Share on Twitter'); ?>"><i class="fa fa-brands fa-x-twitter"></i></a>
	<a href="https://telegram.me/share/url?url=<?php view::route('article', $item->data()); ?>" class="social__icon social__icon--telegram" target="_blank" title="<?php view::lang('Share on Telegram'); ?>"><i class="fa fa-paper-plane"></i></a>
	<a href="https://wa.me/?text=<?php view::route('article', $item->data()); ?>" class="social__icon social__icon--whatsapp"><i class="fa fa-whatsapp" target="_blank" title="<?php view::lang('Share on WhatsApp'); ?>"></i></a>
	<a href="mailto:?subject=Shared: <?php view::attr($text); ?>&body=An article from The Moscow Times: <?php view::route('article', $item->data()); ?>" class="social__icon social__icon--email"><i class="fa fa-envelope" target="_blank" title="<?php view::lang('Share with email'); ?>"></i></a>
	<a href="https://flipboard.com" data-flip-widget="shareflip" class="social__icon social__icon--flipboard" title="<?php view::lang('Share on Flipboard'); ?>"><img src="<?php view::url('static'); ?>img/flipboard_mrrw.png" /></a>
	<a href="<?php view::route('article', $item->data()); ?>/pdf" class="social__icon social__icon--pdf"><i class="fa fa-file-pdf-o" target="_blank" title="Download as PDF"></i></a>
</div>
