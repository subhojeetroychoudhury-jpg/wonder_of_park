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
$spots = [
  ['title' => 'Aurora Lake Walk', 'tag' => 'Sunset serenity', 'icon' => '🌙'],
  ['title' => 'Wildflower Trail', 'tag' => 'Bloom season', 'icon' => '🌼'],
  ['title' => 'Skyline Zipline', 'tag' => 'Big thrills', 'icon' => '🛝'],
  ['title' => 'Stargazer Garden', 'tag' => 'Night lights', 'icon' => '✨'],
];
$idx = (int)(date('z') % count($spots));
$spot = $spots[$idx];
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Wonder of Park</title>
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
        <a href="#about" class="nav__link">About</a>
        <a href="#activities" class="nav__link">Activities</a>
        <a href="#gallery" class="nav__link">Gallery</a>
        <a href="#plan" class="nav__link">Plan a Visit</a>
        <a href="#faq" class="nav__link">FAQ</a>
      </nav>

      <div class="nav__cta">
        <a class="btn btn--primary" href="/wonder-of-park-php/subscription.php">Get Updates</a>
      </div>
    </div>
  </header>

  <main class="wrap">
    <?php if ($flash): ?>
      <div class="toast" role="status"><?= pr_h($flash) ?></div>
    <?php endif; ?>

    <section class="hero">
      <div class="hero__left">
        <div class="pill"><span class="pill__icon"><?= pr_h($spot['icon']) ?></span> Today’s Spotlight: <b><?= pr_h($spot['title']) ?></b></div>
        <h1 class="hero__title">A park built for wonder.</h1>
        <p class="hero__text">Walk the trails, try family games, and unwind in nature-inspired spaces—crafted with love, designed for smiles.</p>
        <div class="hero__actions">
          <a class="btn btn--primary" href="#plan">Plan Your Visit</a>
          <a class="btn" href="#gallery">See the Views</a>
        </div>

        <div class="hero__stats">
          <div class="stat"><div class="stat__num">12</div><div class="stat__label">Gardens</div></div>
          <div class="stat"><div class="stat__num">24</div><div class="stat__label">Activities</div></div>
          <div class="stat"><div class="stat__num">5★</div><div class="stat__label">Family Rated</div></div>
        </div>
      </div>

      <div class="hero__right" aria-hidden="true">
        <div class="scene">
          <div class="cloud c1"></div>
          <div class="cloud c2"></div>
          <div class="cloud c3"></div>
          <div class="sun"></div>
          <div class="hill h1"></div>
          <div class="hill h2"></div>
          <div class="tree t1"></div>
          <div class="tree t2"></div>
          <div class="tree t3"></div>
          <div class="path"></div>
          <div class="spark"></div>
          <div class="spark s2"></div>
        </div>
      </div>
    </section>

    <section id="about" class="section">
      <div class="section__head">
        <h2>Why Wonder of Park?</h2>
        <p>We blend playful design with calm nature. Everything here is made to feel welcoming—solo or with family.</p>
      </div>

      <div class="cards">
        <article class="card">
          <div class="card__icon">🌿</div>
          <h3>Nature-first zones</h3>
          <p>Trails, gardens, and relaxing corners that make your day lighter.</p>
        </article>
        <article class="card">
          <div class="card__icon">🎈</div>
          <h3>Play for all ages</h3>
          <p>Family games, safe play areas, and friendly challenges.</p>
        </article>
        <article class="card">
          <div class="card__icon">💛</div>
          <h3>Warm community vibe</h3>
          <p>Events, workshops, and surprises throughout the week.</p>
        </article>
      </div>
    </section>

    <section id="activities" class="section section--alt">
      <div class="section__head">
        <h2>Activities</h2>
        <p>Pick a mood—adventure, calm, or celebration. (All descriptions are sample content; you can edit later.)</p>
      </div>

      <div class="activityGrid">
        <?php
        $activities = [
          ['title'=>'Lake Walk', 'desc'=>'Gentle loop paths by the water.', 'meta'=>'30–45 min', 'tone'=>'blue'],
          ['title'=>'Wildflower Trail', 'desc'=>'Colorful blooms and photo spots.', 'meta'=>'1–2 hours', 'tone'=>'green'],
          ['title'=>'Zipline Fun', 'desc'=>'Fast thrills with safety-first setup.', 'meta'=>'Ages 10+', 'tone'=>'orange'],
          ['title'=>'Family Mini-Golf', 'desc'=>'Chill competition with clever holes.', 'meta'=>'All ages', 'tone'=>'pink'],
          ['title'=>'Stargazer Garden', 'desc'=>'Evening lights + calm benches.', 'meta'=>'After 7 PM', 'tone'=>'violet'],
          ['title'=>'Craft & Chill', 'desc'=>'Weekend workshops for kids & adults.', 'meta'=>'Sat/Sun', 'tone'=>'teal'],
        ];
        foreach ($activities as $a):
        ?>
          <article class="activity activity--<?= pr_h($a['tone']) ?>">
            <h3><?= pr_h($a['title']) ?></h3>
            <p class="muted"><?= pr_h($a['desc']) ?></p>
            <div class="activity__meta"><?= pr_h($a['meta']) ?></div>
            <a class="link" href="#plan">Add to plan →</a>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section id="gallery" class="section">
      <div class="section__head">
        <h2>Gallery</h2>
        <p>Attractive placeholder visuals (no extra pages required).</p>
      </div>

      <div class="gallery">
        <?php
        $tiles = [
          ['label'=>'Aurora Lake','c1'=>'#6ee7ff','c2'=>'#ffcf5c'],
          ['label'=>'Wildflower Field','c1'=>'#34d399','c2'=>'#fbbf24'],
          ['label'=>'Sky Walk','c1'=>'#a78bfa','c2'=>'#60a5fa'],
          ['label'=>'Forest Trails','c1'=>'#22c55e','c2'=>'#16a34a'],
          ['label'=>'Sunset Path','c1'=>'#fb7185','c2'=>'#fbbf24'],
          ['label'=>'Star Garden','c1'=>'#60a5fa','c2'=>'#f472b6'],
        ];
        for ($i=0;$i<count($tiles);$i++):
          $t=$tiles[$i];
          $svg = "<svg xmlns='http://www.w3.org/2000/svg' width='900' height='600'>
            <defs>
              <linearGradient id='g' x1='0' y1='0' x2='1' y2='1'>
                <stop offset='0' stop-color='".$t['c1']."'/>
                <stop offset='1' stop-color='".$t['c2']."'/>
              </linearGradient>
              <filter id='blur' x='-20%' y='-20%' width='140%' height='140%'>
                <feGaussianBlur stdDeviation='18'/>
              </filter>
            </defs>
            <rect width='900' height='600' rx='34' fill='url(#g)'/>
            <circle cx='210' cy='150' r='90' fill='rgba(255,255,255,.35)' filter='url(#blur)'/>
            <circle cx='710' cy='210' r='110' fill='rgba(255,255,255,.20)' filter='url(#blur)'/>
            <path d='M0 420 C160 340, 320 510, 480 420 C610 350, 760 500, 900 410 L900 600 L0 600 Z' fill='rgba(0,0,0,.18)'/>
            <path d='M0 450 C170 390, 320 540, 480 450 C620 410, 760 520, 900 460 L900 600 L0 600 Z' fill='rgba(255,255,255,.14)'/>
            <text x='50' y='540' font-family='system-ui,Segoe UI,Arial' font-size='38' fill='rgba(255,255,255,.92)' font-weight='700'>".htmlspecialchars($t['label'],ENT_QUOTES)."</text>
          </svg>";
          $src = 'data:image/svg+xml;charset=utf-8,' . rawurlencode($svg);
        ?>
          <figure class="shot" style="--d: <?= (int)$i ?>">
            <img src="<?= $src ?>" alt="<?= pr_h($t['label']) ?>" />
            <figcaption><?= pr_h($t['label']) ?></figcaption>
          </figure>
        <?php endfor; ?>
      </div>
    </section>

    <section id="plan" class="section section--alt">
      <div class="section__head">
        <h2>Plan a Visit</h2>
        <p>Simple visit planning form (stored locally). No database needed.</p>
      </div>

      <div class="planGrid">
        <div class="card card--soft">
          <h3 style="margin-top:0">Quick Booking</h3>
          <form method="POST" class="form">
            <div class="formRow">
              <label>Name</label>
              <input name="visit_name" type="text" required placeholder="Your name" />
            </div>
            <div class="formRow">
              <label>Email</label>
              <input name="visit_email" type="email" required placeholder="you@example.com" />
            </div>
            <div class="formRow">
              <label>Choose time</label>
              <select name="visit_time" required>
                <option value="Morning">Morning</option>
                <option value="Afternoon">Afternoon</option>
                <option value="Evening">Evening</option>
              </select>
            </div>
            <div class="formRow">
              <label>Notes (optional)</label>
              <textarea name="visit_notes" rows="3" placeholder="Anything we should know?"></textarea>
            </div>
            <button class="btn btn--primary" type="submit" name="visit_submit">Book Request</button>
          </form>

          <?php
          if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['visit_submit'])) {
            $name = trim((string)($_POST['visit_name'] ?? ''));
            $email = trim((string)($_POST['visit_email'] ?? ''));
            $time = trim((string)($_POST['visit_time'] ?? ''));
            $notes = trim((string)($_POST['visit_notes'] ?? ''));

            $errors = [];
            if ($name === '') $errors[] = 'Name required.';
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
            if (!in_array($time, ['Morning','Afternoon','Evening'], true)) $errors[] = 'Pick a valid time.';

            if (!$errors) {
              $file = $uploadDir . '/visit_requests.csv';
              $isNew = !file_exists($file);
              $fh = fopen($file, 'a');
              if ($fh) {
                if ($isNew) fputcsv($fh, ['ts','name','email','time','notes']);
                fputcsv($fh, [date('c'), $name, $email, $time, $notes]);
                fclose($fh);
              }
              $_SESSION['flash'] = 'Booking request saved! We will contact you soon.';
              header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
              exit;
            } else {
              $flash = implode(' ', $errors);
              echo '<div class="toast" role="status">' . pr_h($flash) . '</div>';
            }
          }
          ?>
        </div>

        <div class="card card--soft">
          <h3 style="margin-top:0">Today’s Suggested Loop</h3>
          <div class="steps">
            <div class="step"><div class="step__n">1</div><div><b>Lake Walk</b><div class="muted">Slow pace + photo spots</div></div></div>
            <div class="step"><div class="step__n">2</div><div><b>Wildflower Trail</b><div class="muted">Color bloom moments</div></div></div>
            <div class="step"><div class="step__n">3</div><div><b>Family Mini-Golf</b><div class="muted">Fun finish for everyone</div></div></div>
          </div>
          <div class="hr"></div>
          <div class="muted">Tip: Visit at golden hour for the best views.</div>
        </div>
      </div>
    </section>

    <section class="section" id="newsletter">
      <div class="section__head">
        <h2>Get Wonder Updates</h2>
        <p>Newsletter form—saved locally to `storage/newsletter_submissions.csv`.</p>
      </div>

      <div class="subscribe">
        <div class="subscribe__left">
          <h3 style="margin-top:0">Join the wonder</h3>
          <p class="muted">Seasonal events, weekend activities, and secret view spots.</p>

          <ul class="checklist">
            <li>Weekly highlight</li>
            <li>Early event access</li>
            <li>Family-friendly schedules</li>
          </ul>
        </div>

        <div class="subscribe__right">
          <form method="POST" class="form">
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

            <button class="btn btn--primary" type="submit" name="newsletter_submit">Subscribe</button>
          </form>

          <div class="muted" style="margin-top:10px; font-size:12px">
            No database needed. Submissions are stored on your server.
          </div>
        </div>
      </div>
    </section>

    <section class="section section--alt" id="faq">
      <div class="section__head">
        <h2>FAQ</h2>
        <p>Quick answers. Clean, simple, and welcoming.</p>
      </div>

      <div class="faq">
        <?php
        $faqs = [
          ['q'=>'Is this a real park?','a'=>'This is a demo website theme. but the park is real.'],
          ['q'=>'where it located?','a'=>'this is located at rohini.'],
          ['q'=>'what the fee or cost each person','a'=>'the fee or cost will be updated soon (5/21/2026).'],
          ['q'=>'is the there food court?','a'=>'yes the food are there you can locate the food court in wonder of park map.'],
          ['q'=>'any helpline contact?','a'=>'yes this is our helpline contact number 91+9910793546.'],
          
          
          
        ];
        foreach ($faqs as $f):
        ?>
          <details class="faqItem">
            <summary><?= pr_h($f['q']) ?></summary>
            <div class="faqItem__a"><?= pr_h($f['a']) ?></div>
          </details>
        <?php endforeach; ?>
      </div>
    </section>

    <footer class="footer">
      <div class="footer__top">
        <div>
          <div class="footer__brand">Wonder of Park</div>
          <div class="muted">Explore • Play • Breathe</div>
        </div>
        <div class="footer__links">
          <a href="#about">About</a>
          <a href="#activities">Activities</a>
          <a href="#gallery">Gallery</a>
          <a href="#plan">Plan</a>
          <a href="#newsletter">Updates</a>
        </div>
      </div>
      <div class="footer__bottom muted">© <?= date('Y') ?> Wonder of Park. Demo PHP website.</div>
    </footer>
  </main>
</body>
</html>

