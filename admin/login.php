<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';

if (!empty($_SESSION['hb5_admin'])) {
  header('Location: /admin/');
  exit;
}

$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
  // Rate limit login: 8 attempts per 10 minutes per session
  if (!hb5_rate_limit('admin_login', 8, 600)) {
    $error = 'Too many attempts. Please wait and try again.';
  } else {
    hb5_csrf_verify();
    $email = trim((string)($_POST['email'] ?? ''));
    $pass  = (string)($_POST['password'] ?? '');
    $admin = hb5_admin_user();
    if (hash_equals(strtolower($admin['email'] ?? ''), strtolower($email)) && password_verify($pass, (string)($admin['password_hash'] ?? ''))) {
      session_regenerate_id(true);
      $_SESSION['hb5_admin'] = true;
      header('Location: /admin/');
      exit;
    }
    $error = 'Invalid login.';
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-950 text-white grid place-items-center p-4">
  <div class="w-full max-w-md rounded-3xl border border-white/10 bg-white/5 p-8">
    <div class="text-2xl font-semibold">Admin Login</div>
    <p class="mt-2 text-gray-300 text-sm">First run default: <span class="font-mono">admin@example.com</span> / <span class="font-mono">ChangeMe123!</span></p>

    <?php if ($error): ?>
      <div class="mt-4 rounded-2xl border border-red-500/20 bg-red-500/10 p-3 text-red-200"><?= h($error) ?></div>
    <?php endif; ?>

    <form class="mt-6 grid gap-3" method="post">
      <input type="hidden" name="csrf" value="<?= h(hb5_csrf_token()) ?>">
      <label class="text-sm text-gray-300">Email</label>
      <input name="email" type="email" required class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3 outline-none focus:ring-2 focus:ring-indigo-500/50">
      <label class="text-sm text-gray-300">Password</label>
      <input name="password" type="password" required class="rounded-2xl border border-white/10 bg-gray-950/40 px-4 py-3 outline-none focus:ring-2 focus:ring-indigo-500/50">
      <button class="mt-2 rounded-2xl bg-indigo-600 px-5 py-3 font-semibold hover:opacity-90">Sign in</button>
      <a href="/" class="text-center text-sm text-gray-300 hover:text-white">← Back to site</a>
    </form>
  </div>
</body>
</html>
