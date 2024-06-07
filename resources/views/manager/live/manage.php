<?php view::extend('template/main'); ?>


<?php view::start('main') ?>
	<div class="content-header" y-name="header-fixed">
		<div class="content-header-1">
			<h2 class="float-left mr-3"><?php view::text($title) ?></h2>
			<a class="btn btn-primary" href="<?php view::action('article', 'update', $id) ?>"><?php view::lang('title.update', 'article') ?></a>
		</div>
	</div>


	<div
		class="row mt-5 ml-2 mr-2"
		y-use="manager.live.Manage"
		data-url="<?php view::attr(json_encode([
			'items' => fetch::action('live', 'posts', $id),
			'create' => fetch::action('live', 'createpost', $id),
			'update' => fetch::action('live', 'updatepost', '{{id}}'),
			'delete' => fetch::action('live', 'deletepost', '{{id}}')
		])) ?>"
	>
		<div y-name="prototype" style="display:none">
			<?php view::file('form/element/dynamic', ['element' => ['body', 'blocks', 'label' => false, 'types' => ['html', 'image' => ['position' => false], 'article' => ['position' => false], 'embed', 'link' => ['position' => false]]]]) ?>
		</div>

		<script type="text/html" y-name="post">
			<div class="card mb-1">
				<small class="card-header">
					{{ time|time }} by {{ username }}
					<span y-name="delete" class="text-secondary clickable float-right"><i class="icon icon-sm">delete</i></span>
					<span y-name="update" class="text-secondary clickable float-right"><i class="icon icon-sm">edit</i></span>
				</small>
				<div class="card-body" y-name="body"></div>
			</div>
		</script>


		<script type="text/html" y-name="block_article">
			<div class="mb-1">
				<div>Article: {{ article.title }}</div>
			</div>
		</script>

		<script type="text/html" y-name="block_link">
			<div class="mb-1">
				{% if link.title || link.url %}
					{% if typeof link.blank != 'undefined' && link.blank == 1 %}<i class="icon">open_in_new</i> {% endif %}
					{% if typeof link.nofollow != 'undefined' && link.nofollow == 1 %}<i class="icon">block</i> {% endif %}
					{{ link.title }} <i class="icon">forward</i> {{ link.url }}
				{% endif %}
			</div>
		</script>

		<script type="text/html" y-name="block_image">
			<div class="mb-1">
				{% if image && image.id && image.id != 0 %}
					<img style="width: 100px" alt="image" src="<?php view::action('image','serve','{{ image.id }}', 'preset=article_640') ?>" />
				{% endif %}
			</div>
		</script>



		<div class="col">
			<div y-name="create">
				<h3>Create new post</h3>
			</div>

			<div y-name="update" style="display:none">
				<h3>Update post</h3>
			</div>

			<div y-name="form" style=""></div>
			<div y-name="publish" class="btn btn-primary">Save and publish</div>
			<div y-name="draft" class="btn btn-secondary">Save as draft</div>
		</div>


		<div class="col">
			<nav>
				<div class="nav nav-tabs" id="nav-tab" role="tablist">
					<a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#posts" role="tab" aria-controls="nav-home" aria-selected="true">Posts</a>
					<a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#drafts" role="tab" aria-controls="nav-home" aria-selected="false">Drafts</a>
				</div>
			</nav>

			<div class="tab-content mt-3">
				<div class="tab-pane active" id="posts">
					<div y-name="posts"></div>
				</div>
				<div class="tab-pane" id="drafts">
					<div y-name="drafts"></div>
				</div>
			</div>

		</div>
	</div>
<?php view::end() ?>