<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

function pr_h($s){
  return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$uploadDir = __DIR__ . '/storage';
if (!is_dir($uploadDir)) {
  @mkdir($uploadDir, 0777, true);
}

$newsletterOk = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newsletter_submit'])) {
  $name = trim((string)($_POST['name'] ?? ''));
  $email = trim((string)($_POST['email'] ?? ''));
  $interest = trim((string)($_POST['interest'] ?? ''));

  $errors = [];
  if ($name === '') $errors[] = 'Name is required.';
  if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';

  if (!$errors) {
    $row = [
      'ts' => date('c'),
      'name' => $name,
      'email' => $email,
      'interest' => $interest,
    ];

    $file = $uploadDir . '/newsletter_submissions.csv';
    $isNew = !file_exists($file);

    $fh = fopen($file, 'a');
    if ($fh) {
      if ($isNew) {
        fputcsv($fh, ['ts','name','email','interest']);
      }
      fputcsv($fh, [$row['ts'],$row['name'],$row['email'],$row['interest']]);
      fclose($fh);
    }

    $newsletterOk = true;
    $_SESSION['flash'] = 'Thanks ' . pr_h($name) . '! You are subscribed to Wonder Updates.';
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
  } else {
    $flash = implode(' ', $errors);
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Wonder of Park - Subscription</title>
  <link rel="stylesheet" href="/wonder-of-park-php/assets/style.css" />
  <script defer src="/wonder-of-park-php/assets/app.js"></script>
</head>
<body>
  <header class="nav">
    <div class="nav__inner">
      <div class="brand">
        <div class="brand__mark" aria-hidden="true"></div>
        <div>
          <div class="brand__name">Wonder of Park</div>
          <div class="brand__sub">Explore • Play • Breathe</div>
        </div>
      </div>

      <nav class="nav__links" aria-label="Primary">
        <a href="/wonder-of-park-php/index.php#about" class="nav__link">About</a>
        <a href="/wonder-of-park-php/index.php#activities" class="nav__link">Activities</a>
        <a href="/wonder-of-park-php/index.php#gallery" class="nav__link">Gallery</a>
        <a href="/wonder-of-park-php/index.php#plan" class="nav__link">Plan a Visit</a>
        <a href="/wonder-of-park-php/index.php#faq" class="nav__link">FAQ</a>
      </nav>

      <div class="nav__cta">
        <a class="btn btn--primary" href="/wonder-of-park-php/subscription.php">Subscribe</a>
      </div>
    </div>
  </header>

  <main class="wrap">
    <?php if ($flash): ?>
      <div class="toast" role="status"><?= pr_h($flash) ?></div>
    <?php endif; ?>

    <section class="section" id="subscription">
      <div class="section__head">
        <h2>Subscription - Wonder Updates</h2>
        <p>Join for seasonal events, weekend activities, and early access. Submissions are stored locally on your server (no database).</p>
      </div>

      <div class="subscribe">
        <div class="subscribe__left">
          <h3 style="margin-top:0">Get the wonder</h3>
          <p class="muted">Everything you need to plan a great visit—delivered with a smile.</p>

          <ul class="checklist">
            <li>Weekly highlight</li>
            <li>Early event access</li>
            <li>Family-friendly schedules</li>
          </ul>

          <div class="hr"></div>
          <div class="muted" style="font-size:13px; line-height:1.7">
            Where it’s saved:
            <code style="font-size:12px; color: var(--text)">wonder-of-park-php/storage/newsletter_submissions.csv</code>
          </div>
        </div>

        <div class="subscribe__right">
          <form method="POST" class="form">
            <input type="hidden" name="newsletter_submit" value="1" />

            <div class="formRow">
              <label>Your name</label>
              <input name="name" type="text" required placeholder="e.g. Aanya" />
            </div>

            <div class="formRow">
              <label>Email</label>
              <input name="email" type="email" required placeholder="you@example.com" />
            </div>

            <div class="formRow">
              <label>What do you like?</label>
              <select name="interest">
                <option value="Trails">Trails</option>
                <option value="Play">Play</option>
                <option value="Gardens">Gardens</option>
                <option value="Events">Events</option>
              </select>
            </div>

            <button class="btn btn--primary" type="submit" style="margin-top:4px">Subscribe</button>
          </form>

          <div class="muted" style="margin-top:10px; font-size:12px">
            No database needed. Submissions are stored on your server.
          </div>
        </div>
      </div>
    </section>

    <footer class="footer">
      <div class="footer__top">
        <div>
          <div class="footer__brand">Wonder of Park</div>
          <div class="muted">Explore • Play • Breathe</div>
        </div>
        <div class="footer__links">
          <a href="/wonder-of-park-php/index.php#about">About</a>
          <a href="/wonder-of-park-php/index.php#activities">Activities</a>
          <a href="/wonder-of-park-php/index.php#gallery">Gallery</a>
          <a href="/wonder-of-park-php/index.php#plan">Plan</a>
          <a href="/wonder-of-park-php/subscription.php">Updates</a>
        </div>
      </div>
      <div class="footer__bottom muted">© <?= date('Y') ?> Wonder of Park. Demo PHP website.</div>
    </footer>
  </main>
</body>
</html>

