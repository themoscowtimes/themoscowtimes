

<?php view::block('properties', '') ?>


<?php /*
---
Artikel
---
<title><?php view::block('head.title', '') ?></title>
<meta name="keywords" content="%% string of keywords / tags %% | %%section title%%">
<meta name="news_keywords" content="%% string of keywords / tags %% | %%section title%%"/>
<meta name="description" content="%%description%%">
<meta name="thumbnail" content="thumbnail article  | fallback thumb if not set"/>

<meta name="author" content="%%Auteur%%"/>

<meta property="og:type" content="article">
<meta property="og:url" content="%%ABSOLUTE URL%%">
<meta property="og:title" content="%%Title%%">
<meta property="og:description" content=" %%description&&">
<meta property="og:image" content="%%SRC%%"/>
<meta property="og:image:width" content="1200"/>
<meta property="og:image:height" content="%%height%%"/>

<meta property="article:author" content="%%AUTHOR%%"/> <!--- An array of Facebook profile URLs or IDs of the authors for this article -->
<meta property="article:content_tier" content="free" /> // ik weet niet zeker of we dit moete gebruiken / opties zijn "free, locked, or metered"
// <meta property="article:article:expiration_time" content="%%DateTime%%"/>
<meta property="article:modified_time" content="%%DateTime%%"/>
<meta property="article:article:published_time" content="%%DateTime%%"/>
<meta property="article:publisher" content="https://www.facebook.com/MoscowTimes"/>
<meta property="article:section" content="%%SECTION%%"/>
<meta property="article:tag" content="%% string of keywords / tags %%"/>

<link rel="canonical" href="%%url%%">

------------------------------------------------
SECTIE pagina
------------------------------------------------
<title><?php view::block('head.title', '') ?></title>

<meta name="keywords" content="%% string of keywords / tags %% | %%section title%%">
<meta name="news_keywords" content="%% string of keywords / tags %% | %%section title%%"/>
<meta name="description" content="%%description%%">
<meta name="thumbnail" content="thumbnail article  | fallback thumb if not set"/>

<meta property="og:url" content="%%ABSOLUTE URL%%">
<meta property="og:title" content="%%Title%%">
<meta property="og:description" content=" %%description&&">
<meta property="og:image" content="%%SRc logo van TMTC%%"/>

<link rel="canonical" href="%%url%%">

<link rel="alternate" type="application/rss+xml" title="Opinie - NRC" href="/index/opinie/rss/">



------------------------------------------------
Json LD ARTICLE
------------------------------------------------

<script type="application/ld+json" data-json-ld-for-pagemetadata>
    {
		"@context": "http://schema.org/",
        "@type": "NewsArticle",
        "printEdition": "Text issue code",

		"dateCreated": "2018-12-19T13:10:41+02:00",
		"datePublished": "2018-12-19T13:10:41+02:00",
		"dateModified": "2018-12-19T13:10:41+02:00",
		"expires": "2018-12-19T13:10:41+02:00", // enkel indien een artikel een einddatum heeft
		"name": "%%Title%%",
		"headline": "%%Title%%",
		"description": "%%DESCRIPTION%%",
		"keywords": ["%%KEYWORD%%", "%%KEYWORD%%"],
		"articleSection": "%%sectie%%",
		"isAccessibleForFree": true,

		"mainEntityOfPage": "%%URL%%",
		"url": "%%URL%%",

		 "creator": ["%% naam auteur %%"],
		 "author": {
            "@type": "Person",
            "name": "%% naam auteur %%",
            "description": "%% description %%",
			"image": "%%afbeelding%%",
			"url": "%%overzichtspagina artikelen%%",
			"sameAs" : ["http://www.twitter.com/your-profile"]
        },

		"thumbnailUrl": "%% link to preview img size 320px",
        "image": {
            "height": 1280,
            "@type": "ImageObject",
            "url": "",
            "width": 1000
        },

		 "audio": {
            "@type": "AudioObject",
			"embedUrl": "SRC of the embed"
        },

		 "video": {
            "@type": "VideoObject",
			"thumbnail": "foo-fighters-interview-thumb.jpg",
			"thumbnailUrl": "name of video VERPLICHT",
			"embedUrl": "SRC of the embed",
			"description": "description of video VERPLICHT",
			"name": "name of video VERPLICHT",
			"uploadDate": "name of video VERPLICHT"
        },

        "publisher": {
            "@type": "Organization",
            "name": "The MOscow Times",
            "logo": {
                "height": 100,
                "@type": "ImageObject",
                "url": "",
                "width": 100
            }
        },
		"inLanguage": {
            "@type": "Language",
            "name": "English",
            "alternateName": "en"
        },
		"sponsor": { //MT+ artikelen
            "@type": "Organization",
            "name": "%%adverteerder%%",
			"url": "http://www.example.com/",
            logo": {
				"height": 100,
				"@type": "ImageObject",
				"url": "",
				"width": 100
			}
        }
    }
</script>


------------------------------------------------
Json LD section page
------------------------------------------------


<script type="application/ld+json" data-json-ld-for-pagemetadata>
{
	"@context": "http://schema.org/",
	"@type": "CollectionPage",
	"name": "%%SECTION TITLE %% | %%Articles by: naam%",
	"headline": "%%SECTION TITLE %% | %%Articles by: naam%",

	//IF is autor summary page
	"about": {
		"@type": "Person",
		"@id": "%%url-naar pagina van persoon%%",
		"name": "",
		"description": "%%tekst bij persoonn%%"
	},
	"publisher": {
		"@type": "Organization",
		"name": "The Moscow Times",
		"logo": {
			"height": 100,
			"@type": "ImageObject",
			"url": "",
			"width": 100
		}
	},
	"inLanguage": {
		"@type": "Language",
		"name": "English",
		"alternateName": "en"
	},
	"mainEntity": {
		"url": "%%URL%%",
		"@type": "ItemList",
		"@context": "http://schema.org",
		"itemListElement": [{
			"@type": "ListItem",
			"position": 0,
			"url": ""
			}, {
			"@type": "ListItem",
			"position": 1,
			"url": ""
			}, {
			"@type": "ListItem",
			"position": 2,
			"url": ""
			}]
		}
}
</script>

------------------------------------------------
Json LD Home page zoeken en pages
------------------------------------------------

<script type="application/ld+json">
{
	"@context": "http://schema.org",
	"@type": "WebSite",
	"url": "https://themoscowtimes.com/",
	"potentialAction": {
		"@type": "SearchAction",
		"target": "https://themoscowtimes.com/{search_term_string}",
		"query-input": "required name=search_term_string"
	}
}
</script>


------------------------------------------------
Json LD Home page zoeken en pages
------------------------------------------------

<script type="application/ld+json">
{
	"@context": "http://schema.org",
	"@type": "WebSite",
	"url": "https://themoscowtimes.com/",
	"potentialAction": {
		"@type": "SearchAction",
		"target": "https://themoscowtimes.com/{search_term_string}",
		"query-input": "required name=search_term_string"
	}
}
</script>

 */ ?>