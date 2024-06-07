<?php if (false && isset($_GET["amp"]) == 1): ?>
	<?php
	 if (!function_exists('GetAttributeFromTag')) {
		//Get $element from a $contentString and parse an $attribute value to $AttributeValue
		function GetAttributeFromTag($contentString,$element,$attribute){
			libxml_use_internal_errors(true);
			$dom = new DOMDocument();
			$dom->loadHTML($contentString);
			$AttributeValue = 'hui';
			//detect twitter embed
			if(str_contains($element,'[href^="https://twitter.com/"]')){
				$re =	'/<a href="https:\/\/twitter\.com\/(\w+){1,15}\/status\/(\d+)|\?ref_src=/';
				preg_match($re, $contentString, $matches);
				//$matches[1] is for twitter username
				$AttributeValue = $matches[2];
				echo('//platform.twitter.com/embed/Tweet.html?id='.$AttributeValue);
			//detect instagram embed
			} elseif(str_contains($contentString,'data-instgrm-permalink="https://www.instagram.com/')){
				foreach ($dom->getElementsByTagName($element) as $a) {
					echo '//instagram.com'.parse_url($a->getAttribute($attribute), PHP_URL_PATH).'embed?hui=';
				}
			} else {
				foreach ($dom->getElementsByTagName($element) as $a) {
					echo $a->getAttribute($attribute);
				}
			}
			return $AttributeValue;
			}
		}
	?>
	<?php if (strpos($block['embed'], 'data-telegram-post=') !== false or strpos($block['embed'], 'src="https://www.youtube.com/embed/') !== false or strpos($block['embed'], 'class="twitter-tweet') !== false or strpos($block['embed'], 'src="https://www.facebook.com/plugins/post.php') !== false or strpos($block['embed'], 'src="https://www.facebook.com/plugins/video.php') !== false or strpos($block['embed'], 'data-instgrm-permalink="https://www.instagram.com/') !== false): ?>
		<!-- telegram embed -->
		<?php if (strpos($block['embed'], 'data-telegram-post=') !== false): ?>
			<amp-iframe width="1000" height="400" title="telegram embed" layout="fixed" sandbox="allow-scripts allow-same-origin allow-popups" allowfullscreen frameborder="0" src="//t.me/<?php
				GetAttributeFromTag($block['embed'],'script','data-telegram-post');
			?>?embed=1"></amp-iframe>
		<!-- facebook post/video embed -->
		<?php elseif (strpos($block['embed'], 'src="https://www.facebook.com/plugins/') !== false): ?>
			<amp-iframe width="1000" height="400" title="facebook embed" layout="fixed" sandbox="allow-scripts allow-same-origin allow-popups" frameborder="0" src="<?php
				GetAttributeFromTag($block['embed'],'iframe','src');
			?>"></amp-iframe>
		<!-- instagram embed -->
		<?php elseif (strpos($block['embed'], 'data-instgrm-permalink="https://www.instagram.com/') !== false): ?>
			<amp-iframe width="1000" height="400" title="instagram embed" layout="fixed" sandbox="allow-scripts allow-same-origin allow-popups" frameborder="0" src="<?php
				GetAttributeFromTag($block['embed'],'a','href');
			?>"></amp-iframe>
		<!-- youtube embed -->
		<?php elseif (strpos($block['embed'], 'src="https://www.youtube.com/embed/') !== false): ?>
			<amp-iframe width="1000" height="400" title="youtube embed" layout="fixed" sandbox="allow-scripts allow-same-origin allow-popups" allowfullscreen frameborder="0" src="<?php
				GetAttributeFromTag($block['embed'],'iframe','src');
			?>"></amp-iframe>
		<!-- twitter embed -->
		<?php elseif (strpos($block['embed'], 'class="twitter-tweet') !== false): ?>
			<amp-iframe width="1000" height="400" title="telegram embed" layout="fixed" sandbox="allow-scripts allow-same-origin allow-popups" allowfullscreen frameborder="0" src="<?php
				GetAttributeFromTag($block['embed'],'[href^="https://twitter.com/"]','href');
			?>"></amp-iframe>
		<?php endif; ?>
		<?php else: ?>
			<p>Some external media is not supported on this page, <a href="?amp=1&canonical=1" data-link="0">proceed to themoscowtimes.com to view it</a>.</p>
	<?php endif; ?>
	<?php else: ?>
<div class="article__embed" y-use="Embed">
	<?php view::raw($block['embed']) ?>
</div>
<?php endif; ?>