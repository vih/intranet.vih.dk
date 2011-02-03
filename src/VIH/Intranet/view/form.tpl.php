<form method="post" action="<?php e($context->url()); ?>">
<?php foreach ($descriptors as $field) : ?>
<p>
  <label for="<?php e($field['name']); ?>"><?php if(!empty($field['description'])): e($field['description']); else: e($field['name']); endif; ?></label>
  <input type="text" name="<?php e($field['name']); ?>" value="<?php e($field['default']); ?>">
</p>
<?php endforeach; ?>

<p>
  <input type="submit" value="Gem" />
</p>
</form>
