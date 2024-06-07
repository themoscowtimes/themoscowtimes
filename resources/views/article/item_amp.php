<?php
$bem = fetch::bem('article', $context ?? null, $modifier ?? null);
$archive = $archive ?? false;
$section = fetch::section($item);

$authorsArr = [];
foreach($item['authors'] as $author) {
	$authorsArr[] = $author->title;
}

$sectionsArr = [];
foreach(fetch::config('sections') as $section => $label) {
	if($item->$section == 1) {
		$sectionsArr[] = $label;
	}
}

$sections = implode(', ', $sectionsArr);
$authors = implode(', ', $authorsArr);
?>

<!doctype html>
<html ⚡ data-temlatetype="amp">

<head>
	<meta charset="utf-8">
	<?php 
	$title = $item->title;
	$url = $archive ? fetch::route('archive_article', $item->data()) : fetch::route('article', $item->data());
	view::file('seo/link', ['link' => [
		'canonical' => $url
	]]);
	?>
	<?php if (isset($_GET["canonical"]) == 1): ?>
	<meta http-equiv="refresh" content="1; url=<?php echo($url); ?>">
	<style>
	body>* {
		display: none !important;
	}

	.redirect {
		display: block !important;
		position: absolute;
		top: 0;
		left: 0;
		z-index: 10000;
		height: 100vh;
		width: 100vw;
		font-family: sans-serif;
		font-size: 3rem;
		padding: 4rem;
	}

	.redirect a {
		color: #3263c0;
		font-weight: 600;
	}

	.redirect svg {
		position: absolute;
		margin: auto 0 0 auto;
		right: 4rem;
		width: 6rem;
		height: 6rem;
	}
	</style>
	<?php endif; ?>
	<meta name="viewport" content="width=device-width,minimum-scale=1">
	<style amp-boilerplate>
	body {
		-webkit-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
		-moz-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
		-ms-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
		animation: -amp-start 8s steps(1, end) 0s 1 normal both
	}

	@-webkit-keyframes -amp-start {
		from {
			visibility: hidden
		}

		to {
			visibility: visible
		}
	}

	@-moz-keyframes -amp-start {
		from {
			visibility: hidden
		}

		to {
			visibility: visible
		}
	}

	@-ms-keyframes -amp-start {
		from {
			visibility: hidden
		}

		to {
			visibility: visible
		}
	}

	@-o-keyframes -amp-start {
		from {
			visibility: hidden
		}

		to {
			visibility: visible
		}
	}

	@keyframes -amp-start {
		from {
			visibility: hidden
		}

		to {
			visibility: visible
		}
	}
	</style><noscript>
		<style amp-boilerplate>
		body {
			-webkit-animation: none;
			-moz-animation: none;
			-ms-animation: none;
			animation: none
		}
		</style>
	</noscript>
	<style amp-custom>
	/* fonts */
	/* latin */
	@font-face {
		font-family: 'Merriweather';
		font-style: italic;
		font-weight: 300;
		src: url(https://fonts.gstatic.com/s/merriweather/v30/u-4l0qyriQwlOrhSvowK_l5-eR7lXff4jvzDP3WG.woff2) format('woff2');
		unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
	}

	/* latin */
	@font-face {
		font-family: 'Merriweather';
		font-style: normal;
		font-weight: 300;
		src: url(https://fonts.gstatic.com/s/merriweather/v30/u-4n0qyriQwlOrhSvowK_l521wRZWMf6hPvhPQ.woff2) format('woff2');
		unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
	}

	/* latin */
	@font-face {
		font-family: 'Merriweather';
		font-style: normal;
		font-weight: 400;
		src: url(https://fonts.gstatic.com/s/merriweather/v30/u-440qyriQwlOrhSvowK_l5-fCZMdeX3rg.woff2) format('woff2');
		unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
	}

	/* latin */
	@font-face {
		font-family: 'Merriweather';
		font-style: normal;
		font-weight: 900;
		src: url(https://fonts.gstatic.com/s/merriweather/v30/u-4n0qyriQwlOrhSvowK_l52_wFZWMf6hPvhPQ.woff2) format('woff2');
		unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
	}

	/* latin */
	@font-face {
		font-family: 'Roboto';
		font-style: normal;
		font-weight: 300;
		src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmSU5fBBc4AMP6lQ.woff2) format('woff2');
		unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
	}

	/* latin */
	@font-face {
		font-family: 'Roboto';
		font-style: normal;
		font-weight: 400;
		src: url(https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu4mxKKTU1Kg.woff2) format('woff2');
		unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
	}

	/* latin */
	@font-face {
		font-family: 'Roboto';
		font-style: normal;
		font-weight: 500;
		src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmEU9fBBc4AMP6lQ.woff2) format('woff2');
		unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
	}

	/* latin */
	@font-face {
		font-family: 'Roboto';
		font-style: normal;
		font-weight: 700;
		src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmWUlfBBc4AMP6lQ.woff2) format('woff2');
		unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
	}

	/* latin */
	@font-face {
		font-family: 'Roboto';
		font-style: normal;
		font-weight: 900;
		src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmYUtfBBc4AMP6lQ.woff2) format('woff2');
		unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
	}

	/*! smallest css reset via https://codepen.io/kevinpowell/pen/QWxBgZX */
	*,
	*::before,
	*::after {
		box-sizing: border-box;
	}

	* {
		margin: 0;
		padding: 0;
		font: inherit;
	}

	body {
		min-height: 100vh;
	}

	img,
	amp-img,
	picture,
	figure,
	iframe,
	amp-iframe,
	amp-layout,
	svg,
	amp-youtube,
	video,
	amp-video {
		display: block;
		max-width: 100%;
	}

	.amp-body {
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		padding-bottom: 30px;
	}

	.article-container {
		flex: 1
	}

	html,
	body {
		font-weight: 300;
		line-height: 1.6;
		color: rgb(1, 1, 1);
	}

	a {
		color: rgb(1, 1, 1);
		text-decoration: none;
	}

	.amp-nav,
	.label.article__label,
	.article__header h1,
	.byline,
	.featured-image figcaption,
	.article__block,
	.author-excerpt-default__name,
	.footer .footer__menu {
		font-family: Roboto, sans-serif;
	}

	html,
	.article__block--html,
	.article__content blockquote {
		font-family: Merriweather, serif;
	}

	.amp-nav {
		padding: 12px;
		border-bottom: 1px solid #e8e8e8;
		margin-bottom: 24px;
		display: flex;
		justify-content: space-between;
		position: sticky;
		top: 0;
		background: rgb(254, 254, 254);
		z-index: 5;
	}

	.amp-nav--logo-link {
		display: block
	}

	.amp-nav--logo-img {
		width: 205px;
		height: 40px;
	}

	.amp-nav--extra,
	.amp-nav--extra-link {
		display: flex;
		align-items: center;
		justify-content: center;
		text-align: center;
	}

	.amp-nav--extra-link {
		width: 25px;
		height: 25px;
		/* font-size: .8rem; */
		font-weight: 700;
		margin-left: 8px;
	}

	/* article header style */
	.article {
		margin-bottom: 48px;
	}

	.amp-embed-youtube,
	.article__header {
		margin-bottom: 12px;
	}

	.article__label {
		font-size: .8rem;
		font-weight: 400;
		text-transform: uppercase;
		line-height: 1em;
		display: inline-block;
		margin-bottom: 12px;
		color: rgb(254, 254, 254);
		background-color: rgb(1, 1, 1);
		padding: 4px;
	}

	.label--opinion {
		color: rgb(254, 254, 254);
		background-color: #3263c0;
	}

	.label.article__label.label--partners {
		color: inherit;
		background-color: inherit;
	}

	.article__header h1 {
		font-size: 2rem;
		font-weight: 900;
		line-height: 1em;
		margin-bottom: 12px;
	}

	.article__header h2 {
		font-size: 1.3rem;
		font-weight: 400;
		line-height: 1.2em;
	}

	/* footer style */
	.container {
		padding-left: 12px;
		padding-right: 12px;
	}

	.footer .footer__inner {
		border-top: 1px solid rgb(1, 1, 1);
		padding: 24px 0 12px 0;
	}

	.mb-3 {
		margin-bottom: 24px;
	}

	.byline {
		font-size: .9rem;
		border-top: 1px solid #e8e8e8;
		margin-bottom: 12px;
	}

	.row-flex,
	.byline__details {
		display: flex;
	}

	.row-flex .col {
		width: 100%;
	}

	.byline__details {
		padding: 6px 0;
	}

	.byline__details__column {
		line-height: 1.4em;
	}

	.byline__author__image-wrapper {
		overflow: hidden;
		border-radius: 50%;
		width: 40px;
		height: 40px;
		margin-right: 12px;
	}

	.byline__author,
	.byline__datetime {
		font-weight: 500;
	}

	.byline__author__name {
		color: #3263c0;
	}

	.article__intro {
		font-size: 1.1rem;
		padding-top: 12px;
		margin-bottom: 12px;
		font-weight: 300;
		line-height: 1.6em;
		border-top: 1px solid #e8e8e8;
	}

	.featured-image {
		margin-bottom: 24px;
	}

	.featured-image,
	.article__images {
		min-width: 390px;
		margin-left: -12px;
		margin-right: -12px;
	}

	.featured-image figcaption {
		padding: 8px;
		text-align: left;
		line-height: 1.2em;
		color: #959595;
		font-size: .82rem;
		font-weight: 300;
		display: flex;
	}

	.featured-image__caption {
		margin-bottom: 0.4em;
		margin-right: 0.5em;
		font-weight: 600;
	}

	.featured-image__credits {
		color: rgb(1, 1, 1);
		font-weight: 400;
	}

	.article__content,
	.article__block,
	.article__content p,
	.article__content ul,
	.article__content ol {
		margin-bottom: 24px;
	}

	.article__content p,
	.article__content ul,
	.article__content ol {
		line-height: 1.9em;
	}

	strong,
	b,
	.strong {
		font-weight: 600;
	}

	.article__content a {
		color: #3263c0;
	}

	.article__content ul,
	.article__content ol {
		margin-left: 1em;
	}

	.article__image figcaption {
		display: flex;
		justify-content: space-between;
		line-height: 1.2em;
		color: #959595;
		font-size: .82rem;
		font-weight: 300;
		padding: 8px 0;
	}

	.article__image .article__image__caption,
	.article__images__caption {
		line-height: 1.2em;
		font-weight: 400;
	}

	.article__image .article__image__credits,
	.article__images__credits {
		color: rgb(1, 1, 1);
		font-weight: 600;
	}

	.article__block--header h2 {
		font-weight: 700;
		font-size: 1.5rem;
		line-height: 1.2em;
		margin-bottom: 0.3em;
	}

	.article__images .article__image figcaption {
		padding: 8px;
	}

	.article__related-article {
		border-bottom: 1px solid #b0c4ea;
		padding-top: 24px;
	}

	.article__related-article .related-article__label {
		color: #3263c0;
		padding: 6px 0;
		display: block;
		border-bottom: 1px solid #3263c0;
		font-size: .8rem;
		font-weight: 400;
		text-transform: uppercase;
		line-height: 1em;
	}

	.article__related-article .related-article__inner {
		padding: 12px 0;
	}

	.article__related-article a {
		display: block;
	}

	.article__related-article .related-article__title {
		font-family: 'Roboto';
		font-weight: 700;
		font-size: 1.25rem;
		line-height: 1.2em;
		margin-bottom: 0.2em;
		color: rgb(1, 1, 1);
	}

	.article__related-article .related-article__cta {
		color: #3263c0;
		text-transform: uppercase;
		font-size: .9rem;
		font-weight: 700;
	}

	.article__content blockquote {
		padding-left: 24px;
		padding-right: 24px;
		padding-bottom: 12px;
		padding-top: 18px;
		font-weight: 700;
		font-size: 1.2rem;
		position: relative;
	}

	.article__aside {
		padding: 12px;
		border: 1px solid #e8e8e8;
		border-radius: 4px;
		background-clip: padding-box;
	}

	.article__block--embed p {
		line-height: 1.2;
	}

	.article__link a {
		display: block;
		color: rgb(1, 1, 1);
		background-color: #f5f7fc;
		padding: 12px;
	}

	.article__link .article__link__title {
		font-weight: 600;
		font-size: 1.1rem;
		line-height: 1.3em;
	}

	.article__disclaimer {
		color: #3263c0;
		font-style: italic;
		font-size: .9rem;
		margin-bottom: 12px;
	}

	.author-excerpt-default {
		padding: 12px;
	}

	.author-excerpt-default {
		background-color: #f7f7f7;
		border-bottom: 1px solid #e8e8e8;
		position: relative;
		margin-bottom: 12px;
	}

	.author-excerpt-default__name {
		line-height: 1.4em;
		font-weight: 700;
		display: block;
		margin-bottom: 0.5em;
	}

	.author-excerpt-default__description {
		line-height: 1.7em;
		font-size: .8rem;
		display: block;
	}

	.article__tags {
		display: block;
		font-weight: 500;
		margin-bottom: 12px;
	}

	.article__tags__tag {
		color: #3263c0;
	}

	.footer .footer__main {
		padding-bottom: 36px;
		margin-bottom: 12px;
		border-bottom: 1px solid rgb(1, 1, 1);
	}

	.footer ul,
	.footer ol {
		list-style: none;
	}

	/* two column footer */
	.footer ul.row-flex.depth-0 {
		display: flex;
		flex-wrap: wrap;
	}

	.footer .footer__menu .depth-0>li {
		font-weight: 400;
		margin-bottom: 12px;
		width: 50%;
	}

	.footer .footer__menu .depth-0>li>a {
		font-weight: 700;
	}

	.redirect,
	/* hide socials from footer because icon font is not connected */
	.footer__main li:nth-child(2).col.has-child {
		display: none;
	}

	.footer .footer__bottom {
		font-size: .8rem;
	}

	amp-analytics,
	amp-auto-ads,
	amp-story-auto-ads {
		position: fixed;
		top: 0;
		width: 1px;
		height: 1px;
		overflow: hidden;
		visibility: hidden;
	}

	.consent-eea {
		display: none;
	}

	.consent-ccpa {
		display: none;
	}

	.amp-geo-group-eea .consent-eea {
		display: block;
	}

	.amp-geo-group-ccpa .consent-ccpa {
		display: block;
	}

	.center {
		margin: 0 auto;
		display: flex;
	}

	.slot-bottom {
		margin-bottom: 48px;
	}

	<?php if (isset($_GET["slot-names"])==1): ?>[data-slot]:before {
		content: attr(data-slot);
		font-family: monospace;
		font-size: .8rem;
		text-align: center;
		width: 100%;
		height: 20px;
		line-height: 1.25rem;
		display: block;
		z-index: 1000;
		background-color: rgba(51, 251, 51, .5);
		outline: 1px solid rgb(51, 51, 51);
		position: relative;
		z-index: 11;
	}

	<?php endif;
	?>
	</style>
	<script async src="https://cdn.ampproject.org/v0.js"></script>
	<script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
	<script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>
	<!-- ads starts here -->
	<meta content="amp-ad,amp-iframe" name="amp-consent-blocking">
	<script async custom-element="amp-sticky-ad" src="https://cdn.ampproject.org/v0/amp-sticky-ad-1.0.js"></script>
	<script async custom-element="amp-fx-flying-carpet" src="https://cdn.ampproject.org/v0/amp-fx-flying-carpet-0.1.js">
	</script>
	<script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>
	<script async custom-element="amp-accordion" src="https://cdn.ampproject.org/v0/amp-accordion-0.1.js"></script>
	<script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
	<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
	<script async custom-element="amp-consent" src="https://cdn.ampproject.org/v0/amp-consent-0.1.js"></script>
	<script async custom-element="amp-geo" src="https://cdn.ampproject.org/v0/amp-geo-0.1.js"></script>
	<!--
<script async custom-element="amp-video-docking" src="https://cdn.ampproject.org/v0/amp-video-docking-0.1.js"></script>
<script async custom-element="amp-video-iframe" src="https://cdn.ampproject.org/v0/amp-video-iframe-0.1.js"></script>
-->
	<?php if($item->type == 'video'): ?>
	<?php if (isset($_GET["amp"]) == 1): ?>
	<script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>
	<?php endif; ?>
	<?php endif; ?>
</head>

<body class="amp-body">
	<amp-geo layout="nodisplay">
		<script type="application/json">
		{
			"ISOCountryGroups": {
				"eea": ["preset-eea", "unknown"],
				"ccpa": ["preset-us-ca", "us-va", "us-co", "us-ct"]
			}
		}
		</script>
	</amp-geo>
	<amp-consent id="consent" layout="nodisplay">
		<script type="application/json">
		{
			"consentRequired": false,
			"checkConsentHref": false,
			"consentInstanceId": "sourcepoint",
			"promptUISrc": "https://cdn.privacy-mgmt.com/amp/unified/index.html?authId=CLIENT_ID&source_url=SOURCE_URL",
			"clientConfig": {
				"accountId": 1638,
				"propertyHref": "https://amp.themoscowtimes.com",
				"stageCampaign": false
			},
			"geoOverride": {
				"eea": {
					"consentRequired": "remote",
					"checkConsentHref": "https://cdn.privacy-mgmt.com/wrapper/tcfv2/v1/amp-v2?authId=CLIENT_ID",
					"postPromptUI": "eea-consent-ui",
					"clientConfig": {
						"privacyManagerId": 426503,
						"pmTab": "purposes"
					}
				},
				"ccpa": {
					"consentRequired": "remote",
					"checkConsentHref": "https://cdn.privacy-mgmt.com/wrapper/ccpa/amp-v2?authId=CLIENT_ID",
					"postPromptUI": "ccpa-consent-ui",
					"clientConfig": {
						"isCCPA": true,
						"privacyManagerId": "6022b85a892e762cb08504d0"
					}
				}
			}
		}
		</script>
	</amp-consent>
	<amp-iframe layout="fixed-height" width="auto" title="User Sync" data-block-on-consent height="1"
		sandbox="allow-scripts allow-same-origin" frameborder="0"
		src="https://static.s2s.t13.io/generic/load-cookie.html?source=amp" style="display: block; margin: 0">
		<amp-img layout="fill" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="
			placeholder></amp-img>
	</amp-iframe>
	<?php if (isset($_GET["debug"]) == 1): ?>
	<!-- debug is for test campaign only -->
	<amp-sticky-ad hidden="hidden" layout="nodisplay">
		<amp-ad data-enable-refresh="30" data-multi-size="320x50,300x50" data-slot="/15188745/Triple13/Sticky" height="50"
			rtc-config='{
    "vendors": {
      "t13": {
        "TAG_ID":"ef8c5365-3892-4989-bc05-6509bb7ab8f4",
      	"ACCOUNT_ID": "9a3c6c23-453a-4cba-b419-30b908f39a50"
      }
    },
    "urls": [
      "https://api.floors.dev/sgw/v1/amp/floors?k=QBNaieBwWeBYMRGsreCNdCsuWLTtmZ&slot=/15188745/Triple13/Sticky"
    ]}' style="width:320px;height:50px;" type="doubleclick" width="320"></amp-ad>
	</amp-sticky-ad>
	<?php else: ?>
	<amp-sticky-ad layout="nodisplay">
		<amp-ad data-block-on-consent width="320" height="50" layout="fixed" data-multi-size="300x50,320x50"
			data-multi-size-validation="false" type="doubleclick"
			data-slot="/15188745,21704504769/FS-Themoscowtimes-AMP/themoscowtimes_AMP_Sticky" data-enable-refresh="30"
			data-lazy-fetch="false" rtc-config='{
     "vendors": {
        "t13": {
          "TAG_ID": "60e6d8dd-b140-4fd6-afb6-df3b50ff32e8",
          "ACCOUNT_ID": "9a3c6c23-453a-4cba-b419-30b908f39a50"
        },
        "aps": {
          "PUB_ID": "600",
          "PUB_UUID": "16268e26-dabe-4bf4-a28f-b8f4ee192ed3",
          "PARAMS": {
            "amp": "1"
          }
        },
        "criteo": {
          "NETWORK_ID": "4905",
          "ZONE_ID": "1382490",
          "PUBLISHER_SUB_ID": "FS-themoscowtimes-themoscowtimes_AMP_Sticky"
        },
        "medianet": {
          "CID": "8CU8ZT2C4"
        }
      }
   }'></amp-ad>
	</amp-sticky-ad>
	<?php endif; ?>
	<?php if (isset($_GET["debug"]) == 1): ?>
	<!-- debug is for test campaign only -->
	<amp-ad class="center" data-block-on-consent width="336" height="280" layout="fixed" data-multi-size="300x250,250x250"
		data-multi-size-validation="false" type="doubleclick" data-slot="/15188745/FS-TestPage-AMP/AMP-1"
		data-enable-refresh="30" data-lazy-fetch="true" data-loading-strategy="1.25" rtc-config='{
    	"vendors": {
        "t13": {
          "TAG_ID":"2fe8c37f-b827-43b3-82a9-07b7ae277845",
          "ACCOUNT_ID": "9a3c6c23-453a-4cba-b419-30b908f39a50"
        }
      }
    }'></amp-ad>
	<?php else: ?>
	<amp-ad class="center" data-block-on-consent width="336" height="280" layout="fixed"
		data-multi-size="300x250,250x250,336x280,320x100" data-multi-size-validation="false" type="doubleclick"
		data-slot="/15188745,21704504769/FS-Themoscowtimes-AMP/themoscowtimes_AMP_1" data-enable-refresh="30"
		data-lazy-fetch="true" data-loading-strategy="1.25" rtc-config='{
   "vendors": {
      "t13": {
        "TAG_ID": "fa96dd9f-6287-483e-ab11-248b7c015ad7",
        "ACCOUNT_ID": "9a3c6c23-453a-4cba-b419-30b908f39a50"
      },
      "aps": {
        "PUB_ID": "600",
        "PUB_UUID": "16268e26-dabe-4bf4-a28f-b8f4ee192ed3",
        "PARAMS": {
          "amp": "1"
        }
      },
      "criteo": {
        "NETWORK_ID": "4905",
        "ZONE_ID": "1382491",
        "PUBLISHER_SUB_ID": "FS-Themoscowtimes-themoscowtimes_AMP_1"
      },
      "medianet": {
        "CID": "8CU8ZT2C4"
      }
    }
 }'></amp-ad>
	<?php endif; ?>
	<nav class="amp-nav">
		<!--
		<div class="menu-trigger" y-name="open"><i class="fa fa-reorder"></i></div>
		-->
		<a href="/" class="amp-nav--logo-link" title="The Moscow Times - Independent News from Russia">
			<amp-img src="<?php view::url('static'); ?>/img/logo_tmt_amp-1710_2023-1.png" alt="The Moscow Times"
				layout="responsive" height="126" width="640" class="amp-nav--logo-img">
		</a>

		<div class="amp-nav--extra">
			<a href="https://moscowtimes.ru" class="amp-nav--extra-link">RU</a>
			<a href="/search" title="Search" class="amp-nav--extra-link">
				<amp-layout layout="fixed" width="22" height="22">
					<svg viewBox="0 0 512 512" style="fill:rgb(1,1,1)">
						<path
							d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
						</svg>
				</amp-layout>
			</a>
			<a href="/account" class="amp-nav--extra-link">
				<amp-layout layout="fixed" width="25" height="25">
					<svg viewBox="0 0 512 512" style="fill:rgb(1,1,1)">
						<path
							d="M406.5 399.6C387.4 352.9 341.5 320 288 320H224c-53.5 0-99.4 32.9-118.5 79.6C69.9 362.2 48 311.7 48 256C48 141.1 141.1 48 256 48s208 93.1 208 208c0 55.7-21.9 106.2-57.5 143.6zm-40.1 32.7C334.4 452.4 296.6 464 256 464s-78.4-11.6-110.5-31.7c7.3-36.7 39.7-64.3 78.5-64.3h64c38.8 0 71.2 27.6 78.5 64.3zM256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-272a40 40 0 1 1 0-80 40 40 0 1 1 0 80zm-88-40a88 88 0 1 0 176 0 88 88 0 1 0 -176 0z" />
						</svg>
				</amp-layout>
			</a>
		</div>
	</nav>

	<div class="container--full">
		<div class="preheader_advert py-2">
			<!-- Tag ID: themoscowtimes.com_header -->
			<!--
		<div align="center" data-freestar-ad="__336x280 __970x250"
			id="themoscowtimes.com_header_<?php view::text($item->id); ?>">
		</div>
		<script data-cfasync="false" type="text/javascript">
		freestar.config.enabled_slots.push({
			placementName: "themoscowtimes.com_header",
			slotId: "themoscowtimes.com_header_<?php view::text($item->id); ?>"
		});
		</script>
	-->
		</div>
	</div>
	<?php /* view::banner('article_top'); */ ?>
	<amp-analytics type="gtag" data-credentials="include">
		<script type="application/json">
		{
			"vars": {
				"gtag_id": "G-7PDWRZPVQJ",
				"config": {
					"G-7PDWRZPVQJ": {
						"groups": "default"
					}
				}
			}
		}
		</script>
	</amp-analytics>

	<?php view::manager('article', $item->id); ?>

	<div class="lazy-loaded article-container" id="article-id-<?php view::text($item->id); ?>"
		data-page-id="<?php view::text($item->id); ?>" data-next-id="<?php view::text($next_item_id); ?>"
		data-article-url="<?php view::route('article', $item->data()); ?>"
		data-article-title="<?php view::text($item->title);?>">

		<div class="gtm-section gtm-type" data-section="<?php view::attr($section) ?>"
			data-type="<?php view::attr($item->type) ?>">
			<!-- Google Tag Manager places Streamads based on these classes -->
		</div>

		<?php if($item->type == 'gallery'): ?>
		<?php view::file('article/gallery', ['item' => $item, 'archive' => $archive, 'next_item_id' => $next_item_id]); ?>
		<?php else: ?>
		<div class="container">
			<?php if($item->type == 'video'): ?>
			<?php
				$regex = '/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)/';
				$video = false;
				if(preg_match($regex, $item->video, $matches)) {
					$video = $matches[1];
					$embed  = 'https://www.youtube.com/embed/' . $matches[1];
					$poster = [
						'https://img.youtube.com/vi/' . $matches[1] . '/maxresdefault.jpg',
						'https://img.youtube.com/vi/' . $matches[1] . '/hqdefault.jpg',
						'https://img.youtube.com/vi/' . $matches[1] . '/default.jpg',
					];
				}
				?>

			<?php if ($video): ?>
			<?php if (isset($_GET["amp"]) == 1): ?>
			<amp-youtube width="480" height="270" class="amp-embed-youtube" layout="responsive"
				data-videoid="<?php view::attr($video) ?>">
			</amp-youtube>
			<?php else: ?>
			<figure class="videoheader" data-video="<?php view::attr($video) ?>">
				<div class="videoplayer__aspect"></div>
				<div class="videoplayer">
					<iframe
						src="https://www.youtube.com/embed/<?php view::attr($video) ?>?autoplay=1&loop=1&rel=0&wmode=transparent"
						allowfullscreen="" wmode="Opaque" width="100%" height="100%" frameborder="0"></iframe>
				</div>
			</figure>
			<?php endif; ?>
			<?php endif; ?>
			<?php endif; ?>


			<div class="row-flex gutter-2">
				<div class="col">
					<?php if ($item->sponsored): ?>
					<?php view::file('article/sponsor/mt_plus', ['item' => $item]); ?>
					<?php endif; ?>
					<article class="article article--<?php view::attr($section) ?>">
						<header class="article__header ">
							<?php if ($item->opinion || $item->analysis): ?>
							<?php view::file('common/label', ['item' => $item, 'context' => $bem->block()]) ?>
							<?php endif; ?>
							<?php if (isset($item->partners) && is_array($item->partners) && count($item->partners) > 0 ): ?>
							<?php foreach($item->partners as $partner): ?>
							<span class="label article__label label--partner">
								Partner Content
							</span>
							<a class="label article__label label--partners"
								href="<?php view::route('partner', ['slug' => $partner->slug]) ?>"
								title="<?php view::attr($partner->title) ?>">
								<?php view::text($partner->title); ?>
							</a>
							<?php endforeach; ?>
							<?php endif; ?>
							<h1><a href="<?php view::route('article', $item->data()); ?>"><?php view::text($item->title) ?></a>
							</h1>
							<h2><?php view::text($item->subtitle) ?></h2>
						</header>

						<div class="article__byline byline <?php echo ($item->opinion)?'byline--opinion':'' ?> ">
							<div class="row-flex">
								<div class="col">
									<div class="byline__details">

										<?php if (! $archive): ?>
										<?php foreach ($item->authors as $author): ?>
										<?php if ($author->image): ?>
										<a href="<?php view::route('author', ['slug' => $author->slug]) ?>"
											title="<?php view::attr($author->title) ?>" class="byline__author__image-wrapper" data-hui>
											<amp-img src="<?php view::src($author->image, '120') ?>" layout="fixed"
												class="byline__author__image" height="40" width="40"></amp-img>
										</a>
										<?php endif; ?>
										<?php endforeach; ?>
										<?php endif; ?>

										<div class="byline__details__column">
											<div class="byline__author">
												<?php
												$authorTags = [];
												foreach ($item->authors as $author) {
													if (! $archive){
													  $authorTags[] = '<a href="' . fetch::route('author', ['slug' => $author->slug]) . '" class="byline__author__name" title="' . fetch::attr($author->title) . '">' . fetch::text($author->title) . '</a>';
													} else {
													   $authorTags[] = '<span class="byline__author__name">' . fetch::text($author->title) . '</a>';
													}
												}

												$authorHtml = '';
												$separator = $item->type == 'podcast' ? '' : 'By ';
												while($authorTag = array_shift($authorTags)) {
													$authorHtml .= $separator . $authorTag;
													if(count($authorTags) > 1) {
														$separator = ', ';
													} else {
														$separator = ' and ';
													}
												}
												view::raw($authorHtml);
												?>
											</div>


											<?php if (strtotime($item->updated) > (strtotime($item->time_publication) + (10 * 60))): ?>
											Updated: <time class="byline__datetime timeago"
												datetime="<?php view::text(date('c', strtotime($item->updated))); ?>">
												<?php view::date($item->updated); ?>
											</time>

											<?php else: ?>
											<time class="byline__datetime timeago"
												datetime="<?php view::text(date('c', strtotime($item->time_publication))); ?>">
												<?php view::date($item->time_publication); ?>
											</time>
											<?php endif; ?>
										</div>
									</div>
								</div>
								<!--
							<div class="col-auto">
								<div class="byline__social">
									<?php //view::file('common/social', ['item'=>$item])
									?>
								</div>
							</div>
							-->
							</div>
						</div>

						<?php if ($item->intro!=''): ?>
						<div class="article__intro">
							<?php view::raw(nl2br(strip_tags($item->intro))); ?>
						</div>
						<?php endif; ?>



						<?php if($item->type === 'podcast'): ?>
						<?php if (isset($_GET["amp"]) == 1): ?>
						<amp-iframe width="1000" height="200" title="Netflix House of Cards branding: The Stack" layout="fixed"
							sandbox="allow-scripts allow-same-origin allow-popups" allowfullscreen frameborder="0" src="<?php 
							preg_match('/src="([^\"]+)"/', $item->audio, $match);
							echo $match[1];
							?>">
							<amp-img layout="fill" src="<?php view::url('static'); ?>img/amp-podcast-placeholder.webp" placeholder>
							</amp-img>
						</amp-iframe>
						<?php else: ?>
						<div class="mb-3">
							<?php view::raw($item->audio); ?>
						</div>
						<?php endif; ?>
						<?php elseif ($item->image && $item->video == ''): ?>
						<figure class="article__featured-image featured-image">
							<amp-img src="<?php view::src($item->image, 'article_1360', $archive) ?>" layout="responsive" height="220"
								width="390"></amp-img>
							<?php if ($item->caption!='' || $item->credits!=''): ?>
							<figcaption class="">
								<span class="article__featured-image__caption featured-image__caption">
									<?php view::text($item->caption) ?>
								</span>
								<span class="article__featured-image__credits featured-image__credits">
									<?php view::text($item->credits) ?>
								</span>
							</figcaption>
							<?php endif; ?>
						</figure>
						<?php endif; ?>


						<div class="article__content-container">
							<div class="article__content">
								<?php if (is_array($item->body)): ?>
								<?php foreach ($item->body as $index => $block): ?>
								<div data-id="article-block-type"
									class="article__block article__block--<?php view::attr($block['type']); ?> article__block--<?php echo $block['position'] ?? 'column' ?> ">
									<?php view::file('article/block/' . $block['type'], ['block' => $block]) ?>
									<?php if ($index == 1): ?>
									<?php view::banner('article_body_amp') ?>
									<?php endif; ?>
									<?php if ($index == 2 && $block['type'] == 'html'): ?>
									<?php if (substr_count($block['body'], '<p>') >=2): ?>
									<amp-ad data-block-on-consent width="336" height="280" layout="fixed"
										data-multi-size="250x250,300x250,320x100,336x280" data-multi-size-validation="false"
										type="doubleclick" data-slot="/15188745,21704504769/FS-Themoscowtimes-AMP/themoscowtimes_AMP_3"
										data-enable-refresh="30" data-lazy-fetch="true" data-loading-strategy="1.25" rtc-config='{
											"vendors": {
													"t13": {
														"TAG_ID": "890e3b66-8fc7-4296-9239-22d5afd5f260",
														"ACCOUNT_ID": "9a3c6c23-453a-4cba-b419-30b908f39a50"
													},
													"aps": {
														"PUB_ID": "600",
														"PUB_UUID": "16268e26-dabe-4bf4-a28f-b8f4ee192ed3",
														"PARAMS": {
															"amp": "1"
														}
													},
													"criteo": {
														"NETWORK_ID": "4905",
														"ZONE_ID": "1382491",
														"PUBLISHER_SUB_ID": "FS-themoscowtimes-themoscowtimes_AMP_3"
													},
													"medianet": {
														"CID": "8CU8ZT2C4"
													}
												}
										}'>
									</amp-ad>
									<?php endif; ?>
									<?php endif; ?>
									<?php if ($index == 3 && $block['type'] == 'html'): ?>
									<?php if (substr_count($block['body'], '<p>') >=2): ?>
										<amp-ad
											data-block-on-consent
											width="336"
											height="279"
											layout="fixed"
											data-multi-size="336x280,320x100,300x250,250x250"
											data-multi-size-validation="false"
											type="doubleclick"
											data-slot="/15188745,21704504769/FS-Themoscowtimes-AMP/themoscowtimes_AMP_4"
											data-enable-refresh="30"
											data-lazy-fetch="true"
											data-loading-strategy="1.25"
											rtc-config='{
											"vendors": {
													"t13": {
														"TAG_ID": "c2b51696-46f1-4aad-a351-b636c270a92f",
														"ACCOUNT_ID": "9a3c6c23-453a-4cba-b419-30b908f39a50"
													},
													"aps": {
														"PUB_ID": "600",
														"PUB_UUID": "16268e26-dabe-4bf4-a28f-b8f4ee192ed3",
														"PARAMS": {
															"amp": "1"
														}
													},
													"criteo": {
														"NETWORK_ID": "4905",
														"ZONE_ID": "1382491",
														"PUBLISHER_SUB_ID": "FS-themoscowtimes-themoscowtimes_AMP_4"
													},
													"medianet": {
														"CID": "8CU8ZT2C4"
													}
												}
										}'
										>
										</amp-ad>
									<?php endif; ?>
									<?php endif; ?>
									<?php if ($index == 4 && $block['type'] == 'html'): ?>
									<?php if (substr_count($block['body'], '<p>') >=2): ?>
										<amp-ad
											data-block-on-consent
											width="336"
											height="280"
											layout="fixed"
											data-multi-size="336x280,300x250,250x250,320x100"
											data-multi-size-validation="false"
											type="doubleclick"
											data-slot="/15188745,21704504769/FS-Themoscowtimes-AMP/themoscowtimes_AMP_5"
											data-enable-refresh="30"
											data-lazy-fetch="true"
											data-loading-strategy="1.25"
											rtc-config='{
											"vendors": {
													"t13": {
														"TAG_ID": "b6d28278-ec91-4fbd-8014-b9ef24b7c496",
														"ACCOUNT_ID": "9a3c6c23-453a-4cba-b419-30b908f39a50"
													},
													"aps": {
														"PUB_ID": "600",
														"PUB_UUID": "16268e26-dabe-4bf4-a28f-b8f4ee192ed3",
														"PARAMS": {
															"amp": "1"
														}
													},
													"criteo": {
														"NETWORK_ID": "4905",
														"ZONE_ID": "1382491",
														"PUBLISHER_SUB_ID": "FS-themoscowtimes-themoscowtimes_AMP_5"
													},
													"medianet": {
														"CID": "8CU8ZT2C4"
													}
												}
										}'
										>
										</amp-ad>
									<?php endif; ?>
									<?php endif; ?>
								</div>
								<?php endforeach; ?>
								<?php endif; ?>
							</div>
							<?php if ($item->opinion): ?>
							<div class="article__disclaimer">
								The views expressed in opinion pieces do not necessarily reflect the position of The Moscow Times.
							</div>
							<?php endif; ?>

							<?php if (!$archive && $item->opinion): ?>
							<?php foreach ($item->authors as $author): ?>
							<?php if (($author->body != '') ||($author->twitter!='')): ?>
							<div class="hidden-sm-up">
								<a class="" href="<?php view::route('author', ['slug' => $author->slug]) ?>"
									title="<?php view::attr($author->title) ?>">
									<?php view::file('author/excerpt/default', ['item' => $author, 'context'=>'article']); ?>
								</a>
							</div>
							<?php endif; ?>

							<?php endforeach; ?>
							<?php endif; ?>

							<div class="article__bottom"></div>

							<?php /* view::file('article/block/newsletter'); */ ?>

							<?php if(count($item->tags)>0): ?>
							<div class="article__tags">
								Read more about:
								<?php $glue = ''; ?>
								<?php foreach ($item->tags as $tag): ?>
								<?php echo $glue; ?><?php view::file('common/tag', ['item' => $tag, 'context'=>'article__tags']) ?><?php $glue = ', '; ?>
								<?php endforeach; ?>
							</div>
							<?php endif; ?>

							<div class="hidden-md-up">
								<?php if ($item->business): ?>
								<?php 
								/*
								view::file('article/sponsor')
								*/
							?>
								<?php endif; ?>
							</div>

							<?php 
						//view::file('common/social', ['item'=>$item]) 
						?>
						</div>
					</article>
					<?php if (isset($_GET["debug"]) == 1): ?>
					<!-- debug is for test campaign only -->
					<div class="center slot-bottom">
						<amp-ad data-block-on-consent width="336" class="center" height="280" layout="fixed"
							data-multi-size="300x250,250x250" data-multi-size-validation="false" type="doubleclick"
							data-slot="/15188745/FS-TestPage-AMP/AMP-2" data-enable-refresh="30" data-lazy-fetch="true"
							data-loading-strategy="1.25" rtc-config='{
			"vendors": {
				"t13": {
					"TAG_ID":"e2bcd13f-1929-45ff-8e1c-b5d3604e75fe",
					"ACCOUNT_ID": "9a3c6c23-453a-4cba-b419-30b908f39a50"
				}
			}
			}'></amp-ad>
					</div>
					<?php else: ?>
					<div class="center slot-bottom">
						<amp-ad class="center" data-block-on-consent width="336" height="280" layout="fixed"
							data-multi-size="250x250,300x250,320x100,336x280" data-multi-size-validation="false" type="doubleclick"
							data-slot="/15188745,21704504769/FS-Themoscowtimes-AMP/themoscowtimes_AMP_2" data-enable-refresh="30"
							data-lazy-fetch="true" data-loading-strategy="1.25" rtc-config='{
			"vendors": {
					"t13": {
						"TAG_ID": "ff2462b9-8156-42f8-a2dd-100f04b25956",
						"ACCOUNT_ID": "9a3c6c23-453a-4cba-b419-30b908f39a50"
					},
					"aps": {
						"PUB_ID": "600",
						"PUB_UUID": "16268e26-dabe-4bf4-a28f-b8f4ee192ed3",
						"PARAMS": {
							"amp": "1"
						}
					},
					"criteo": {
						"NETWORK_ID": "4905",
						"ZONE_ID": "1382491",
						"PUBLISHER_SUB_ID": "FS-themoscowtimes-themoscowtimes_AMP_2"
					},
					"medianet": {
						"CID": "8CU8ZT2C4"
					}
				}
			}'></amp-ad>
					</div>
					<?php endif; ?>
				</div>
				<div class="col-auto hidden-sm-down">
					<!-- no sidebar in AMP -->
				</div>
			</div>
		</div>

		<?php endif; ?>

		<?php
	/*
	<div class="container container--full py-3 mb-4" style="background-color: #efefef;">
		<div class="align-center" style="line-height: 0">
			<div style="max-width: 984px; margin: 0 auto;">
				<!-- Tag ID: themoscowtimes.com_billboard_bott -->
				<!--
				<div align="center" data-freestar-ad="__336x280 __970x250"
					id="themoscowtimes.com_billboard_bott_<?php view::text($item->id); ?>">
	</div>
	<script data-cfasync="false" type="text/javascript">
	freestar.config.enabled_slots.push({
		placementName: "themoscowtimes.com_billboard_bott",
		slotId: "themoscowtimes.com_billboard_bott_<?php view::text($item->id); ?>"
	});
	</script>
	-->
	</div>
	</div>
	</div>
	*/
	?>
	<!--
	<?php
	/*
	<div class="container">
		<section class="cluster">

			<div class="cluster__header">
				<h2 class="cluster__label header--style-3">
					<?php if ($item->type == 'gallery'): ?>
					<a href="<?php view::route('gallery') ?>"
						title="<?php view::lang('More galleries') ?>"><?php view::lang('More image galleries') ?></a>
					<?php elseif ($item->type == 'video'): ?>
					<a href="<?php view::route('video') ?>"
						title="<?php view::lang('More videos') ?>"><?php view::lang('More videos') ?></a>
					<?php elseif ($item->type == 'podcast'): ?>
					<a href="<?php view::route('podcasts') ?>"
						title="<?php view::lang('More podcasts') ?>"><?php view::lang('More podcasts') ?></a>
					<?php else: ?>
					<?php view::lang('Read more') ?>
					<?php endif; ?>
				</h2>
			</div>

			<div class="row-flex">
				<?php foreach ($related as $relatedItem): ?>
				<div class="col-3 col-6-sm">
					<?php view::file('article/excerpt/default', ['item' => $relatedItem]); ?>
				</div>
				<?php endforeach; ?>
			</div>
		</section>
	</div>
	*/
	?>
-->
	</div>
	<?php if (isset($_GET["canonical"]) == 1): ?>
	<div class="redirect">
		<svg width="40px" height="40px" viewBox="0 0 40 40">
			<path opacity="0.2" fill="rgb(1,1,1)"
				d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946 s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634 c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z" />
			<path fill="rgb(1,1,1)"
				d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0 C22.32,8.481,24.301,9.057,26.013,10.047z">
				<animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 20 20" to="360 20 20"
					dur="0.7s" repeatCount="indefinite" />
			</path>
		</svg>
		<p>Redirecting you to article:<br>
			<a href="<?php echo($url); ?>"><?php echo($title); ?></a></p>
	</div>
	<?php endif; ?>
	<?php view::menu('footer_amp') ?>
</body>

</html>