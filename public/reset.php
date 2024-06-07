<?php
if(isset($_GET['token']) && $_GET['token'] === 'vQds98zeVrtvKi6w4598w56kv845vwrt3cG45b') {
	opcache_reset();
	echo 'cache cleared';
}