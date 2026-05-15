<?php
// admin.php
// Painel de controle Premium para gerenciar agendamentos e portfólio

session_start();
require_once __DIR__ . '/backend/Core/Config.php';

// Verifica a senha no .env
$env_path = __DIR__ . '/.env';
$admin_pass = 'raul123';
if (file_exists($env_path)) {
    $lines = file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $val) = explode('=', $line, 2);
            if (trim($key) === 'ADMIN_PASS') {
                $admin_pass = trim($val);
            }
        }
    }
}

function getDB() {
    $cfg = DB_CONFIG;
    $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['name']};charset=utf8mb4";
    $pdo = new PDO($dsn, $cfg['user'], $cfg['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

// ─── ENDPOINT AJAX (Atualizar Status e Deletar Foto) ───────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Limpa qualquer saída anterior (previne erros de JSON malformado)
    if (ob_get_level()) ob_end_clean();
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Não autenticado']);
        exit;
    }
    
    if ($_POST['action'] === 'update_status') {
        $id = (int)$_POST['id'];
        $novo_status = $_POST['status'];
        
        if (in_array($novo_status, ['pendente', 'confirmado', 'cancelado'])) {
            try {
                $pdo = getDB();
                $stmt = $pdo->prepare("UPDATE agendamentos SET status = :status WHERE id = :id");
                $stmt->execute([':status' => $novo_status, ':id' => $id]);
                echo json_encode(['sucesso' => true]);
            } catch (Exception $e) {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no banco']);
            }
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Status inválido']);
        }
        exit;
    } 
    
    if ($_POST['action'] === 'delete_foto') {
        $foto_del = basename($_POST['foto']);
        $portfolio_dir = __DIR__ . '/assets/imagem/portfolio/';
        $path_del = $portfolio_dir . $foto_del;
        
        if (file_exists($path_del)) {
            // Tenta deletar o arquivo
            if (unlink($path_del)) {
                echo json_encode(['sucesso' => true]);
            } else {
                // Captura o erro do PHP se o unlink falhar (ex: falta de permissão)
                $error = error_get_last();
                $errorMsg = $error ? $error['message'] : 'Erro desconhecido';
                echo json_encode(['sucesso' => false, 'mensagem' => 'Erro de permissão: ' . $errorMsg]);
            }
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Arquivo não encontrado no servidor.']);
        }
        exit;
    }
    
    // Se a action não for reconhecida
    echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida']);
    exit;
}

// ─── LÓGICA DE LOGIN E LOGOUT ─────────────────────────────────
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Senha incorreta.";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

$logged_in = $_SESSION['admin_logged_in'] ?? false;

// ─── GESTÃO DE PORTFÓLIO (UPLOAD) ─────────────────────────────
$portfolio_dir = __DIR__ . '/assets/imagem/portfolio/';
if (!is_dir($portfolio_dir)) {
    @mkdir($portfolio_dir, 0777, true);
}

if ($logged_in && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Upload de nova foto
    if (isset($_FILES['nova_foto'])) {
        $file = $_FILES['nova_foto'];
        if ($file['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                $new_name = uniqid('foto_') . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $portfolio_dir . $new_name)) {
                    $success = "Foto adicionada ao portfólio com sucesso!";
                } else {
                    $error = "Erro ao salvar a imagem no servidor.";
                }
            } else {
                $error = "Formato inválido. Envie apenas JPG, PNG ou WEBP.";
            }
        } elseif ($file['error'] !== UPLOAD_ERR_NO_FILE) {
            $error = "Erro no upload da imagem.";
        }
    }
}

// Lê fotos atuais
$portfolio_fotos = [];
if ($logged_in) {
    $portfolio_fotos = glob($portfolio_dir . '*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
    if ($portfolio_fotos === false) $portfolio_fotos = [];
}

// ─── BUSCA DE DADOS (DASHBOARD) ───────────────────────────────
$agendamentos = [];
$stats = ['total' => 0, 'pendente' => 0, 'confirmado' => 0, 'cancelado' => 0];

if ($logged_in) {
    try {
        $pdo = getDB();
        $stmt = $pdo->query("SELECT * FROM agendamentos ORDER BY id DESC");
        $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($agendamentos as $ag) {
            $stats['total']++;
            $st = $ag['status'] ?? 'pendente';
            if (isset($stats[$st])) $stats[$st]++;
        }
    } catch (PDOException $e) {
        if (empty($error)) $error = "Erro ao conectar ao banco de dados.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel Administrativo | Diplomas Raúl</title>
  <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
  <link href="https://fonts.googleapis.com/css2?family=IMFellFrenchCanon&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      background: radial-gradient(circle at top, rgba(10, 17, 40, 0.9), #040a15 80%);
      min-height: 100vh;
      color: #fff;
      font-family: 'Outfit', sans-serif;
      margin: 0;
      display: flex;
      overflow-x: hidden;
    }

    /* Tela de Login */
    .login-container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      width: 100%;
    }
    .login-box {
      width: 100%;
      max-width: 400px;
      background: rgba(10, 17, 40, 0.65);
      backdrop-filter: blur(16px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 50px 40px;
      box-shadow: 0 30px 80px rgba(0, 0, 0, 0.8);
      text-align: center;
    }
    .login-box img { width: 80px; margin-bottom: 20px; }
    .login-box h2 {
      font-family: 'IMFellFrenchCanon', serif; font-size: 2.2rem; margin-bottom: 30px; color: #e06d24;
    }
    .login-box input {
      width: 100%; padding: 14px 15px; margin-bottom: 20px; background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: #fff; font-family: 'Outfit', sans-serif; box-sizing: border-box;
    }
    .login-box button {
      width: 100%; padding: 14px; background: linear-gradient(135deg, #e06d24, #c55a1b); border: none; border-radius: 8px; color: #fff; font-weight: 600; cursor: pointer;
    }

    .error-msg { background: rgba(255, 74, 74, 0.1); color: #ff4a4a; padding: 10px; border-radius: 6px; margin-bottom: 20px; }
    .success-msg { background: rgba(37, 211, 102, 0.1); color: #25D366; padding: 10px; border-radius: 6px; margin-bottom: 20px; }

    /* Layout Premium Dashboard */
    .sidebar {
      width: 260px; background: rgba(6, 19, 38, 0.8); backdrop-filter: blur(20px); border-right: 1px solid rgba(255, 255, 255, 0.05);
      padding: 30px 20px; display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 10;
    }
    .brand { display: flex; align-items: center; gap: 15px; margin-bottom: 50px; text-decoration: none; }
    .brand img { width: 45px; }
    .brand-text { font-family: 'IMFellFrenchCanon', serif; font-size: 1.5rem; color: #fff; line-height: 1.1; }
    .brand-text span { color: #e06d24; display: block; }
    
    .nav-item {
      display: flex; align-items: center; gap: 12px; padding: 15px; color: rgba(255,255,255,0.7);
      text-decoration: none; border-radius: 10px; margin-bottom: 10px; font-weight: 500; cursor: pointer; transition: 0.3s;
    }
    .nav-item.active, .nav-item:hover { background: rgba(224, 109, 36, 0.15); color: #e06d24; }
    .nav-bottom { margin-top: auto; }
    
    .main-content { flex: 1; margin-left: 260px; padding: 40px 60px; }
    
    .tab-content { display: none; }
    .tab-content.active { display: block; animation: fadeIn 0.4s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* Header */
    .header-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
    .header-top h1 { font-family: 'IMFellFrenchCanon', serif; font-size: 2.8rem; margin: 0; color: #fff; }
    .user-profile { display: flex; align-items: center; gap: 15px; }
    .user-avatar { width: 45px; height: 45px; background: #e06d24; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }

    /* Cards Estatísticas */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px; margin-bottom: 50px; }
    .stat-card { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
    .stat-card span { color: rgba(255,255,255,0.5); font-size: 0.9rem; text-transform: uppercase; }
    .stat-card strong { display: block; margin-top: 10px; font-size: 2.5rem; color: #fff; font-family: 'IMFellFrenchCanon', serif; }

    /* Tabela Premium */
    .table-wrapper { background: rgba(10, 17, 40, 0.65); backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 20px 25px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.05); }
    th { color: rgba(255,255,255,0.4); text-transform: uppercase; font-size: 0.8rem; background: rgba(0,0,0,0.2); }
    tr:hover td { background: rgba(255,255,255,0.02); }

    /* Select Status */
    .status-select {
      appearance: none; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.2); color: #fff; padding: 8px 30px 8px 15px; border-radius: 20px; font-weight: 600; cursor: pointer;
    }
    .status-select[data-status="pendente"] { background-color: rgba(255, 193, 7, 0.15); border-color: rgba(255, 193, 7, 0.4); color: #ffc107; }
    .status-select[data-status="confirmado"] { background-color: rgba(37, 211, 102, 0.15); border-color: rgba(37, 211, 102, 0.4); color: #25D366; }
    .status-select[data-status="cancelado"] { background-color: rgba(255, 74, 74, 0.15); border-color: rgba(255, 74, 74, 0.4); color: #ff4a4a; }
    .status-select option { background: #07101f; color: #fff; }

    /* Botoes Auxiliares */
    .btn-action { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.05); color: #fff; border: 1px solid rgba(255,255,255,0.1); }
    .btn-action.whatsapp { background: rgba(37,211,102,0.1); border-color: rgba(37,211,102,0.3); color: #25D366; }
    .btn-action.whatsapp:hover { background: #25D366; color: #fff; }
    .btn-action.del { background: rgba(255,74,74,0.1); border-color: rgba(255,74,74,0.3); color: #ff4a4a; cursor: pointer; }
    .btn-action.del:hover { background: #ff4a4a; color: #fff; }

    /* Galeria Portfólio Admin */
    .upload-box {
      background: rgba(255,255,255,0.03); border: 2px dashed rgba(255,255,255,0.2); border-radius: 16px; padding: 40px; text-align: center; margin-bottom: 40px;
    }
    .upload-box input[type="file"] { display: none; }
    .upload-btn-label {
      display: inline-block; padding: 12px 24px; background: #e06d24; color: #fff; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s;
    }
    .upload-btn-label:hover { background: #c55a1b; }
    
    .portfolio-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
    .portfolio-card { position: relative; border-radius: 12px; overflow: hidden; aspect-ratio: 4/5; border: 1px solid rgba(255,255,255,0.1); transition: all 0.3s ease; }
    .portfolio-card img { width: 100%; height: 100%; object-fit: cover; }
    .portfolio-card .overlay {
      position: absolute; inset: 0; background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; opacity: 0; transition: 0.3s;
    }
    .portfolio-card:hover .overlay { opacity: 1; }

    #toast { position: fixed; bottom: 30px; right: 30px; padding: 15px 25px; border-radius: 8px; transform: translateY(100px); opacity: 0; transition: 0.4s; z-index: 9999; }
    #toast.show { transform: translateY(0); opacity: 1; }
  </style>
</head>
<body>

  <div class="cursor-dot" data-cursor-dot></div>
  <div class="cursor-outline" data-cursor-outline></div>

  <div class="page-transition active"></div>

<?php if (!$logged_in): ?>
  <div class="login-container">
    <div class="login-box">
      <img src="assets/imagem/logo.png" alt="Diplomas Raúl">
      <h2>Painel Executivo</h2>
      <?php if ($error): ?><div class="error-msg"><?= $error ?></div><?php endif; ?>
      <form method="POST">
        <input type="password" name="password" placeholder="Senha de Acesso" required autofocus>
        <button type="submit">Entrar no Sistema</button>
      </form>
    </div>
  </div>
<?php else: ?>
  
  <aside class="sidebar">
    <a href="index.php" class="brand" target="_blank">
      <img src="assets/imagem/logo.png" alt="Logo">
      <div class="brand-text">DIPLOMAS <span>RAÚL</span></div>
    </a>
    <div class="nav-item active" data-tab="dashboard">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
      Dashboard
    </div>
    <div class="nav-item" data-tab="portfolio">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
      Meu Portfólio
    </div>
    <a href="index.php" class="nav-item" target="_blank">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
      Ver Site
    </a>
    <div class="nav-bottom">
      <a href="?logout=1" class="nav-item" style="color: #ff4a4a;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        Sair
      </a>
    </div>
  </aside>

  <main class="main-content">
    <?php if ($error): ?><div class="error-msg"><?= $error ?></div><?php endif; ?>
    <?php if ($success): ?><div class="success-msg"><?= $success ?></div><?php endif; ?>

    <div id="dashboard" class="tab-content active">
      <div class="header-top">
        <h1>Visão Geral</h1>
        <div class="user-profile">
          <div style="text-align: right;">
            <div style="font-weight: 600; font-size: 1.1rem;">Raúl</div>
            <div style="font-size: 0.85rem; color: rgba(255,255,255,0.5);">Administrador</div>
          </div>
          <div class="user-avatar">R</div>
        </div>
      </div>

      <div class="stats-grid">
        <div class="stat-card"><span>Total de Pedidos</span><strong><?= $stats['total'] ?></strong></div>
        <div class="stat-card" style="border-bottom: 3px solid #ffc107;"><span>Aguardando Retorno</span><strong><?= $stats['pendente'] ?></strong></div>
        <div class="stat-card" style="border-bottom: 3px solid #25D366;"><span>Confirmados</span><strong><?= $stats['confirmado'] ?></strong></div>
        <div class="stat-card" style="border-bottom: 3px solid #ff4a4a;"><span>Cancelados</span><strong><?= $stats['cancelado'] ?></strong></div>
      </div>

      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>ID</th><th>Cliente</th><th>Data e Curso</th><th>Status</th><th style="text-align:center;">Ação</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($agendamentos)): ?>
              <tr><td colspan="5" style="text-align: center; color: rgba(255,255,255,0.4);">Nenhum agendamento.</td></tr>
            <?php else: foreach ($agendamentos as $ag): 
                $data_form = date('d/m/Y', strtotime($ag['data_formatura']));
                $status = $ag['status'] ?? 'pendente';
                $tel = preg_replace('/[^0-9]/', '', $ag['telefone'] ?? '');
                if (strlen($tel) >= 10 && substr($tel, 0, 2) !== '55') $tel = '55' . $tel;
                $wa_link = "https://wa.me/{$tel}?text=" . urlencode("Olá {$ag['nome']}! Aqui é o Raúl. Recebi seu agendamento para o curso de {$ag['curso']}.");
            ?>
              <tr>
                <td style="color: rgba(255,255,255,0.4);">#<?= str_pad($ag['id'], 4, '0', STR_PAD_LEFT) ?></td>
                <td><strong><?= htmlspecialchars($ag['nome']) ?></strong><br><span style="color:rgba(255,255,255,0.5);font-size:0.85rem;"><?= htmlspecialchars($ag['email']) ?></span></td>
                <td><strong style="color:#e06d24;"><?= $data_form ?></strong><br><span style="color:rgba(255,255,255,0.5);font-size:0.85rem;"><?= htmlspecialchars($ag['curso']) ?></span></td>
                <td>
                  <select class="status-select" data-id="<?= $ag['id'] ?>" data-status="<?= $status ?>">
                    <option value="pendente" <?= $status === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                    <option value="confirmado" <?= $status === 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
                    <option value="cancelado" <?= $status === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                  </select>
                </td>
                <td style="text-align:center;">
                  <?php if ($tel): ?>
                    <a href="<?= $wa_link ?>" target="_blank" class="btn-action whatsapp" title="WhatsApp">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    </a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div id="portfolio" class="tab-content">
      <div class="header-top">
        <h1>Meu Portfólio</h1>
      </div>
      
      <div class="upload-box">
        <form action="admin.php" method="POST" enctype="multipart/form-data" id="uploadForm">
          <p style="color: rgba(255,255,255,0.6); margin-bottom: 20px;">Adicione novas fotos ao site clicando no botão abaixo.</p>
          <label class="upload-btn-label">
            Escolher Imagem
            <input type="file" name="nova_foto" accept="image/*" onchange="document.getElementById('uploadForm').submit()">
          </label>
        </form>
      </div>

      <div class="portfolio-grid">
        <?php if(empty($portfolio_fotos)): ?>
          <p style="color: rgba(255,255,255,0.5);">Nenhuma foto no portfólio ainda.</p>
        <?php else: foreach($portfolio_fotos as $foto): $basename = basename($foto); ?>
          <div class="portfolio-card">
            <img src="assets/imagem/portfolio/<?= $basename ?>" alt="Foto">
            <div class="overlay">
              <button type="button" class="btn-action del btn-delete-foto" data-foto="<?= htmlspecialchars($basename, ENT_QUOTES) ?>" title="Excluir Foto">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
              </button>
            </div>
          </div>
        <?php endforeach; endif; ?>
      </div>
    </div>

  </main>

  <div id="toast">Notificação</div>
  <script>
    // Tab Navigation
    document.querySelectorAll('.sidebar .nav-item[data-tab]').forEach(tab => {
      tab.addEventListener('click', () => {
        document.querySelectorAll('.sidebar .nav-item[data-tab]').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        document.getElementById(tab.getAttribute('data-tab')).classList.add('active');
        
        // Remove alertas antigas se mudar de tab
        document.querySelectorAll('.success-msg, .error-msg').forEach(el => el.remove());
      });
    });

    // Se houve sucesso/erro no upload, manter na aba portfólio
    <?php if (isset($_FILES['nova_foto'])): ?>
      document.querySelector('.nav-item[data-tab="portfolio"]').click();
    <?php endif; ?>

    // AJAX para atualizar status
    document.querySelectorAll('.status-select').forEach(select => {
      select.addEventListener('change', async function() {
        const id = this.getAttribute('data-id');
        const newStatus = this.value;
        this.setAttribute('data-status', newStatus);

        const formData = new URLSearchParams();
        formData.append('action', 'update_status');
        formData.append('id', id);
        formData.append('status', newStatus);

        try {
          const res = await fetch('admin.php', { method: 'POST', body: formData });
          const data = await res.json();
          if (data.sucesso) showToast('Status atualizado com sucesso!', false);
          else showToast('Erro: ' + data.mensagem, true);
        } catch (e) {
          showToast('Erro de conexão', true);
        }
      });
    });

    // AJAX para deletar foto
    document.body.addEventListener('click', async function(e) {
      const btn = e.target.closest('.btn-delete-foto');
      if (btn) {
        e.preventDefault();
        
        if(!confirm('Tem certeza que deseja excluir esta foto?')) return;

        const foto = btn.getAttribute('data-foto');
        console.log('Iniciando deleção para a foto: ' + foto);
        
        const formData = new FormData();
        formData.append('action', 'delete_foto');
        formData.append('foto', foto);

        try {
          const res = await fetch('admin.php', { 
              method: 'POST', 
              body: formData
          });
          
          const textResponse = await res.text();
          console.log('Resposta bruta do servidor:', textResponse);
          
          try {
              const data = JSON.parse(textResponse);
              if (data.sucesso) {
                showToast('Foto removida com sucesso!', false);
                const card = btn.closest('.portfolio-card');
                card.style.opacity = '0';
                card.style.transform = 'scale(0.8)';
                setTimeout(() => card.remove(), 300);
              } else {
                showToast('Erro: ' + data.mensagem, true);
              }
          } catch(e) {
              console.error("Erro ao analisar JSON. Resposta original:", textResponse);
              showToast('Erro interno no servidor. Verifique o console.', true);
          }
        } catch (err) {
          console.error("Erro no Fetch:", err);
          showToast('Falha na comunicação com o servidor.', true);
        }
      }
    });

    function showToast(msg, isError) {
      const toast = document.getElementById('toast');
      toast.textContent = msg;
      toast.style.background = isError ? '#ff4a4a' : '#25D366';
      toast.classList.add('show');
      setTimeout(() => toast.classList.remove('show'), 3000);
    }
  </script>
<?php endif; ?>

  <script src="assets/js/script.js?v=<?= time() ?>"></script>
</body>
</html>