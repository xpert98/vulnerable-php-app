<?php
require_once 'includes/auth.php';
require_once 'includes/layout.php';
render_header('About', 'about');
?>
<div class="card">
  <h1 style="margin-top:0">What is Tech Wire?</h1>
  <p>Tech Wire is a practitioner-run community for sharing practical engineering knowledge — the guides, templates, and honest answers you wish someone had handed you on day one.</p>
  <div class="grid grid-3">
    <div><strong>8,412</strong><div class="muted small">members</div></div>
    <div><strong>1,204</strong><div class="muted small">guides shared</div></div>
    <div><strong>96</strong><div class="muted small">online now</div></div>
  </div>
  <p><a class="btn btn-primary" href="register.php">Join free</a> <a class="btn btn-ghost" href="members.php">Meet members</a></p>
</div>

<div class="grid grid-2" style="margin-top:16px">
  <div class="card" id="guidelines">
    <h2>Guidelines</h2>
    <ul>
      <li>Be kind and assume good intent.</li>
      <li>No spam, no hate, no pasted credentials or secrets.</li>
      <li>Credit sources when you upload files.</li>
      <li>Mark spoilers and keep titles honest.</li>
    </ul>
  </div>
  <div class="card" id="safety">
    <h2>Safety</h2>
    <ul>
      <li>Don’t post personal contact info publicly — use your wall wisely.</li>
      <li>Report abuse to a moderator via the contact form below.</li>
      <li>Only share files you have the right to distribute.</li>
    </ul>
  </div>
</div>

<div class="card" id="contact" style="margin-top:16px">
  <h2>Contact</h2>
  <p class="muted">Moderators reply within a day during the semester.</p>
  <form method="GET" action="about.php" class="stack" onsubmit="return false;">
    <label for="msg">Message</label>
    <textarea id="msg" rows="3" placeholder="How can we help? (demo form — doesn’t send anywhere)"></textarea>
    <p><button class="btn btn-secondary" type="submit">Send (demo)</button></p>
  </form>
  <p class="muted small">Tip: this demo build is intentionally vulnerable for security training. Don’t reuse real passwords here.</p>
</div>
<?php render_footer(); ?>
