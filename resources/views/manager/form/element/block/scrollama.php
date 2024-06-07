<?php view::file('form/element/dynamic', ['element' => ['scrollama', 'textarea', 'label' => 'Graph Embed']]) ?>

<?php view::file('form/element/dynamic', ['element' => ['steps', 'text', 'label' => 'Number of Steps']]) ?>

<script type="text/html" y-name="render">

<div>
{{ scrollama }}
{{ steps }}
</div>

</script>