<?php /* Expects: $importAction (route), $importHint (string, column list) */ ?>
<details class="import-widget">
  <summary class="btn btn-outline btn-sm">📤 Import</summary>
  <div class="import-panel">
    <?= form_open_multipart($importAction, ['class' => 'import-form']) ?>
    <?= csrf_field() ?>
    <input type="file" name="file" accept=".csv,.xls,.xlsx" required>
    <button type="submit" class="btn btn-primary btn-sm">Upload</button>
    <?= form_close() ?>
    <?php if (! empty($importHint)): ?>
    <p class="import-hint">Columns: <?= esc($importHint) ?></p>
    <?php endif ?>
  </div>
</details>
