<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- PAGE HERO -->
<div class="page-hero">
  <div class="wrap">
    <p class="breadcrumb"><a href="<?= site_url('/') ?>">Home</a> › Contact</p>
    <h1>We'd love to hear from you</h1>
    <p>Questions about Goat Banking, the farm, or anything else — reach us via the form or directly.</p>
  </div>
</div>

<section>
  <div class="wrap">
    <div class="contact-grid">
      <div class="contact-info">
        <div class="contact-card">
          <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
          <div>
            <div class="contact-label">Visit us</div>
            <div class="contact-value">Mukono–Kayunga Road<br>Mukono District, Uganda</div>
          </div>
        </div>
        <div class="contact-card">
          <div class="contact-icon"><i class="fas fa-phone"></i></div>
          <div>
            <div class="contact-label">Phone / WhatsApp</div>
            <a href="tel:+256700000000" class="contact-value">+256 700 000 000</a>
          </div>
        </div>
        <div class="contact-card">
          <div class="contact-icon"><i class="fas fa-envelope"></i></div>
          <div>
            <div class="contact-label">Email</div>
            <a href="mailto:hello@mdgoatco.farm" class="contact-value">hello@mdgoatco.farm</a>
          </div>
        </div>
        <div class="contact-card">
          <div class="contact-icon"></div>
          <div>
            <div class="contact-label">Office hours</div>
            <div class="contact-value">Monday – Friday: 8 AM – 5 PM<br>Saturday: 8 AM – 12 noon</div>
          </div>
        </div>
      </div>

      <div class="contact-form-card">
        <h3>Send us a message</h3>
        <?= form_open('contact', ['class' => 'contact-form']) ?>
        <?= csrf_field() ?>
        <div class="field-row">
          <div class="field">
            <label for="name">Your name *</label>
            <input type="text" id="name" name="name" value="<?= esc(old('name')) ?>" placeholder="e.g. Robert Kizito" required>
          </div>
          <div class="field">
            <label for="email">Email address *</label>
            <input type="email" id="email" name="email" value="<?= esc(old('email')) ?>" placeholder="you@example.com" required>
          </div>
        </div>
        <div class="field">
          <label for="subject">Subject *</label>
          <select id="subject" name="subject" required>
            <option value="">Select…</option>
            <option <?= old('subject') === 'Goat Banking enquiry' ? 'selected' : '' ?>>Goat Banking enquiry</option>
            <option <?= old('subject') === 'Goat purchase / sales' ? 'selected' : '' ?>>Goat purchase / sales</option>
            <option <?= old('subject') === 'Farm consultancy' ? 'selected' : '' ?>>Farm consultancy</option>
            <option <?= old('subject') === 'General enquiry' ? 'selected' : '' ?>>General enquiry</option>
            <option <?= old('subject') === 'Other' ? 'selected' : '' ?>>Other</option>
          </select>
        </div>
        <div class="field">
          <label for="message">Message *</label>
          <textarea id="message" name="message" required
            placeholder="Tell us what you'd like to know…"><?= esc(old('message')) ?></textarea>
        </div>
        <?php if (!empty($errors ?? [])): ?>
          <div class="form-errors">
            <?php foreach ($errors as $e): ?><p><?= esc($e) ?></p><?php endforeach ?>
          </div>
        <?php endif ?>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">Send message</button>
        <?= form_close() ?>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>