<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  header('Location: /contact.php');
  exit;
}

hb5_csrf_verify();

// Honeypot
if (!empty($_POST['company'] ?? '')) {
  header('Location: /contact.php?sent=1');
  exit;
}

// Rate limit: 6 submissions per 10 minutes per session
if (!hb5_rate_limit('contact', 6, 600)) {
  http_response_code(429);
  exit('Too many requests. Please try again later.');
}

$name = trim((string)($_POST['name'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

if ($name === '' || $email === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header('Location: /contact.php');
  exit;
}

$lead = [
  'id' => bin2hex(random_bytes(8)),
  'ts' => gmdate('c'),
  'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
  'name' => $name,
  'email' => $email,
  'message' => $message,
  'user_agent' => substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 250),
];

hb5_add_lead($lead);

// Optional: email notify (disabled by default)
// $to = $site['site']['email'] ?? '';
// if ($to) {
//   @mail($to, 'New website enquiry', "From: $name <$email>\n\n$message");
// }

header('Location: /contact.php?sent=1');
exit;
?>