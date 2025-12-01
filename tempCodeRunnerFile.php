<?php
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'pglife';

$uploadDir = __DIR__ . '/uploads'; // directory to store uploaded files
$uploadWebPath = 'uploads';        // path used in HTML <img src="...">
//////////////////////

// create upload dir if missing
if (!is_dir($uploadDir)) {
  @mkdir($uploadDir, 0755, true);
}

// connect to DB
$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($mysqli->connect_errno) {
  header('Content-Type: application/json');
  echo json_encode(['error' => 'DB connection failed: ' . $mysqli->connect_error]);
  exit;
}
$mysqli->set_charset('utf8mb4');

// helper: save message row
function save_message_db($mysqli, $sender, $text = null, $image_path = null)
{
  $sql = "INSERT INTO messages (`sender`,`text`,`image_path`) VALUES (?,?,?)";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param('sss', $sender, $text, $image_path);
  $ok = $stmt->execute();
  $id = $mysqli->insert_id;
  $stmt->close();
  return $ok ? $id : false;
}

// helper: get bot reply from bot_responses table by keyword
function get_bot_reply($mysqli, $user_text)
{
  $text = strtolower(trim($user_text ?? ''));

  // exact keyword match
  $sql = "SELECT reply FROM bot_responses WHERE keyword = ? LIMIT 1";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param('s', $text);
  $stmt->execute();
  $stmt->bind_result($reply);
  if ($stmt->fetch()) {
    $stmt->close();
    return $reply;
  }
  $stmt->close();

  // optional: fallback: try partial match (first matching row)
  $sql = "SELECT reply FROM bot_responses WHERE ? LIKE CONCAT('%', keyword, '%') LIMIT 1";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param('s', $text);
  $stmt->execute();
  $stmt->bind_result($reply2);
  if ($stmt->fetch()) {
    $stmt->close();
    return $reply2;
  }
  $stmt->close();

  // default fallback
  return "Replying soon...";
}

// allowed image types & size
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
$max_file_size = 5 * 1024 * 1024; // 5MB

// handle AJAX requests
$action = $_POST['action'] ?? null;
if ($action === 'send_message') {
  $text = $_POST['text'] ?? '';
  $saved_image_path = null;

  // handle uploaded file
  if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['image'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
      header('Content-Type: application/json');
      echo json_encode(['status' => 'error', 'message' => 'Upload error']);
      exit;
    }
    if ($file['size'] > $max_file_size) {
      header('Content-Type: application/json');
      echo json_encode(['status' => 'error', 'message' => 'File too large']);
      exit;
    }
    $mime = mime_content_type($file['tmp_name']);
    if (!in_array($mime, $allowed_types)) {
      header('Content-Type: application/json');
      echo json_encode(['status' => 'error', 'message' => 'Invalid file type']);
      exit;
    }
    // safe unique filename
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION) ?: 'bin';
    $base = bin2hex(random_bytes(8));
    $fname = $base . '.' . $ext;
    $dest = rtrim($uploadDir, '/') . '/' . $fname;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
      header('Content-Type: application/json');
      echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file']);
      exit;
    }
    $saved_image_path = $uploadWebPath . '/' . $fname;
  }

  // save user message
  save_message_db($mysqli, 'user', $text ?: null, $saved_image_path);

  // fetch bot reply from DB
  $reply = get_bot_reply($mysqli, $text);
  // save bot reply
  save_message_db($mysqli, 'bot', $reply, null);

  header('Content-Type: application/json');
  echo json_encode(['status' => 'ok']);
  exit;
} elseif ($action === 'fetch_messages') {
  $rows = [];
  $res = $mysqli->query("SELECT id, sender, text, image_path, created_at FROM messages ORDER BY id ASC");
  while ($r = $res->fetch_assoc()) {
    $rows[] = $r;
  }
  header('Content-Type: application/json');
  echo json_encode(['messages' => $rows]);
  exit;
} elseif ($action === 'clear_messages') {
  $mysqli->query("TRUNCATE TABLE messages");
  // remove uploaded files
  $files = glob($uploadDir . '/*');
  foreach ($files as $f) {
    if (is_file($f)) @unlink($f);
  }
  header('Content-Type: application/json');
  echo json_encode(['status' => 'cleared']);
  exit;
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Chat with DB bot replies</title>
  <style>
    :root {
      --bg: #e5ddd5;
      --my: #dcf8c6;
      --other: #fff;
      --accent: #128C7E
    }

    html,
    body {
      height: 100%;
      margin: 0
    }

    body {
      font-family: Arial, Helvetica, sans-serif;
      background: var(--bg);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px
    }

    .chat-shell {
      width: 90vw;
      max-width: 360px;
      height: 75vh;
      max-height: 680px;
      background: #f0f0f0;
      border-radius: 10px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
      display: flex;
      flex-direction: column;
      overflow: hidden
    }

    .header {
      background: linear-gradient(90deg, #075E54, #128C7E);
      color: #fff;
      padding: 12px;
      display: flex;
      align-items: center;
      justify-content: space-between
    }

    .header-left {
      display: flex;
      align-items: center;
      gap: 10px
    }

    .chat-logo {
      width: 40px;
      height: 40px;
      border-radius: 8px;
      object-fit: cover;
      background: #fff;
      padding: 4px
    }

    .title {
      font-weight: 600
    }

    .meta {
      font-size: 12px;
      color: rgba(255, 255, 255, 0.9)
    }

    .header-controls {
      display: flex;
      align-items: center;
      gap: 8px
    }

    .home-btn {
      color: white;
      background: transparent;
      padding: 8px 10px;
      border-radius: 8px;
      text-decoration: none;
      font-size: 14px;
      border: 1px solid rgba(255, 255, 255, 0.18)
    }

    .clear {
      color: #fff;
      background: transparent;
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 6px 10px;
      border-radius: 6px;
      cursor: pointer
    }

    .messages {
      flex: 1;
      padding: 12px;
      overflow: auto;
      background: linear-gradient(180deg, #eaf7f5 0%, rgba(255, 255, 255, 0)100%)
    }

    .msg {
      max-width: 76%;
      margin-bottom: 10px;
      padding: 10px 12px;
      border-radius: 10px;
      word-wrap: break-word;
      box-shadow: 0 1px 0 rgba(0, 0, 0, 0.06)
    }

    .msg.user {
      margin-left: auto;
      background: var(--my)
    }

    .msg.bot {
      margin-right: auto;
      background: var(--other)
    }

    .time {
      font-size: 11px;
      color: #666;
      margin-top: 6px;
      text-align: right
    }

    .msg img,
    .msg iframe {
      max-width: 220px;
      display: block;
      margin-top: 8px;
      border-radius: 8px
    }

    .composer {
      display: flex;
      padding: 10px;
      background: #fff;
      align-items: center;
      gap: 8px;
      border-top: 1px solid #eee;
      box-sizing: border-box
    }

    .composer input[type="text"] {
      flex: 1;
      padding: 10px 12px;
      border-radius: 20px;
      border: 1px solid #ddd;
      outline: none;
      font-size: 14px;
      box-sizing: border-box
    }

    .composer button {
      background: var(--accent);
      border: none;
      color: #fff;
      padding: 10px 14px;
      border-radius: 20px;
      cursor: pointer
    }

    .file-label {
      background: #fff;
      border: 1px solid #ddd;
      padding: 8px 10px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      display: inline-flex;
      align-items: center
    }

    .composer input[type="file"] {
      display: none
    }

    /* preview styles */
    #filePreview {
      display: none;
      max-width: 100%;
      padding: 8px 12px 0 12px;
      box-sizing: border-box;
    }

    .preview-wrap {
      display: flex;
      align-items: center;
      gap: 8px;
      border-radius: 8px;
      padding: 6px;
      background: #fff;
      border: 1px solid #eee
    }

    .preview-thumb {
      max-width: 80px;
      max-height: 80px;
      border-radius: 8px;
      object-fit: cover;
      border: 1px solid #ddd
    }

    .preview-info {
      font-size: 13px;
      color: #333
    }

    .preview-remove {
      background: transparent;
      border: 1px solid #ddd;
      padding: 6px;
      border-radius: 6px;
      cursor: pointer
    }

    @media (max-width:360px) {
      .msg img {
        max-width: 160px
      }
    }
  </style>
</head>

<body>
  <div class="chat-shell">
    <div class="header">
      <div class="header-left">
        <img src="img/logo3.png" alt="Logo" class="chat-logo" onerror="this.style.display='none'">
        <div>
          <div class="title">Chat Bot</div>
          <div class="meta">Online</div>
        </div>
      </div>
      <div class="header-controls">
        <a href="index.php" class="home-btn">Home</a>
        <button class="clear" id="btnClear">Clear</button>
      </div>
    </div>

    <div class="messages" id="messages"></div>

    <!-- PREVIEW: moved above composer so send button always stays visible -->
    <div id="filePreview"></div>

    <form id="chatForm" class="composer" enctype="multipart/form-data">
      <input type="text" id="inputMsg" name="text" placeholder="Type a message" autocomplete="off" />

      <!-- file label + hidden input -->
      <div style="display:flex;align-items:center;gap:8px;">
        <label class="file-label" for="fileInput">📎</label>
        <input type="file" id="fileInput" name="image" accept="image/*,.pdf">
      </div>

      <button type="submit" id="btnSend">Send</button>
    </form>
  </div>

  <script>
    const messagesEl = document.getElementById('messages');
    const chatForm = document.getElementById('chatForm');
    const btnClear = document.getElementById('btnClear');
    const fileInput = document.getElementById('fileInput');
    const filePreview = document.getElementById('filePreview');

    function escapeHtml(s) {
      return (s || '').toString().replace(/[&<>"']/g, function(c) {
        return {
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          '"': '&quot;',
          "'": "&#39;"
        } [c];
      });
    }

    function renderMessages(list) {
      messagesEl.innerHTML = '';
      list.forEach(m => {
        const div = document.createElement('div');
        div.className = 'msg ' + (m.sender === 'user' ? 'user' : 'bot');
        let inner = '<div>' + escapeHtml(m.text || '') + '</div>';
        if (m.image_path) {
          // if image or pdf path: attempt to show image/iframe
          const lower = m.image_path.toLowerCase();
          if (lower.endsWith('.pdf')) {
            inner += '<iframe src="' + escapeHtml(m.image_path) + '" width="220" height="140" style="border:0;border-radius:8px;margin-top:8px"></iframe>';
          } else {
            inner += '<img src="' + escapeHtml(m.image_path) + '" alt="uploaded image">';
          }
        }
        inner += '<div class="time">' + (m.created_at || '') + '</div>';
        div.innerHTML = inner;
        messagesEl.appendChild(div);
      });
      messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    async function fetchMessages() {
      const fd = new FormData();
      fd.append('action', 'fetch_messages');
      const res = await fetch('', {
        method: 'POST',
        body: fd
      });
      const data = await res.json();
      if (data.messages) renderMessages(data.messages);
    }

    // show preview when file selected
    fileInput.addEventListener('change', () => {
      const file = fileInput.files[0];
      filePreview.innerHTML = '';
      if (!file) {
        filePreview.style.display = 'none';
        return;
      }

      const wrap = document.createElement('div');
      wrap.className = 'preview-wrap';

      // if image -> show thumbnail
      if (file.type.startsWith('image/')) {
        const img = document.createElement('img');
        img.className = 'preview-thumb';
        img.src = URL.createObjectURL(file);
        img.onload = () => URL.revokeObjectURL(img.src);
        wrap.appendChild(img);

        const info = document.createElement('div');
        info.className = 'preview-info';
        info.innerHTML = '<div>' + escapeHtml(file.name) + '</div><div style="font-size:12px;color:#666">' + Math.round(file.size / 1024) + ' KB</div>';
        wrap.appendChild(info);
      }
      // if PDF -> show name + small iframe preview (browser dependent)
      else if (file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf')) {
        const info = document.createElement('div');
        info.className = 'preview-info';
        info.innerHTML = '<div>📄 ' + escapeHtml(file.name) + '</div><div style="font-size:12px;color:#666">' + Math.round(file.size / 1024) + ' KB</div>';
        wrap.appendChild(info);

        try {
          const iframe = document.createElement('iframe');
          iframe.style.width = '120px';
          iframe.style.height = '80px';
          iframe.style.border = '1px solid #ddd';
          iframe.src = URL.createObjectURL(file);
          wrap.appendChild(iframe);
          iframe.onload = () => URL.revokeObjectURL(iframe.src);
        } catch (e) {
          // no inline preview possible
        }
      }
      // other types
      else {
        const info = document.createElement('div');
        info.className = 'preview-info';
        info.textContent = file.name;
        wrap.appendChild(info);
      }

      // remove button
      const remove = document.createElement('button');
      remove.type = 'button';
      remove.className = 'preview-remove';
      remove.textContent = 'Remove';
      remove.addEventListener('click', () => {
        fileInput.value = '';
        filePreview.innerHTML = '';
        filePreview.style.display = 'none';
      });
      wrap.appendChild(remove);

      filePreview.appendChild(wrap);
      filePreview.style.display = 'block';
    });

    chatForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const fd = new FormData(chatForm);
      fd.append('action', 'send_message');
      // ensure either text or image is present
      if (!fd.get('text') && !fileInput.files.length) {
        return alert('Type a message or attach an image');
      }
      const res = await fetch('', {
        method: 'POST',
        body: fd
      });
      const data = await res.json();
      if (data.status === 'ok') {
        chatForm.reset();
        filePreview.innerHTML = '';
        filePreview.style.display = 'none';
        fetchMessages();
      } else {
        alert(data.message || 'Error sending message');
      }
    });

    btnClear.addEventListener('click', async () => {
      if (!confirm('Clear conversation?')) return;
      const fd = new FormData();
      fd.append('action', 'clear_messages');
      await fetch('', {
        method: 'POST',
        body: fd
      });
      fetchMessages();
    });

    // initial load + polling
    fetchMessages();
    setInterval(fetchMessages, 2500);
  </script>
</body>

</html>