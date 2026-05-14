<?php
// backend/agendar.php
// Endpoint para receber o formulário de agendamento,
// salvar no banco de dados e disparar e-mails via PHPMailer.

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

// ─── Autoload (Composer) ──────────────────────────────────────────────────────
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dependências não instaladas. Execute: composer install']);
    exit;
}
require_once $autoload;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as MailException;

// ─── Config (carrega .env) ────────────────────────────────────────────────────
require_once __DIR__ . '/Core/Config.php';

// ─── Aceita apenas POST ───────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método não permitido.']);
    exit;
}

// ─── Coleta e sanitiza os dados ───────────────────────────────────────────────
$nome           = trim(strip_tags($_POST['nome']           ?? ''));
$email          = trim(strip_tags($_POST['email']          ?? ''));
$telefone       = trim(strip_tags($_POST['telefone']       ?? ''));
$data_formatura = trim(strip_tags($_POST['data_formatura'] ?? ''));
$curso          = trim(strip_tags($_POST['curso']          ?? ''));
$mensagem_extra = trim(strip_tags($_POST['mensagem']       ?? ''));

// ─── Validação básica ─────────────────────────────────────────────────────────
$erros = [];
if (empty($nome))           $erros[] = 'O campo Nome é obrigatório.';
if (empty($email))          $erros[] = 'O campo E-mail é obrigatório.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = 'E-mail inválido.';
if (empty($data_formatura)) $erros[] = 'A Data da Formatura é obrigatória.';
if (empty($curso))          $erros[] = 'O campo Curso é obrigatório.';

if (!empty($erros)) {
    http_response_code(422);
    echo json_encode(['sucesso' => false, 'mensagem' => implode(' ', $erros)]);
    exit;
}

// ─── Conecta ao Banco de Dados ────────────────────────────────────────────────
try {
    $cfg = DB_CONFIG;
    $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['name']};charset=utf8mb4";
    $pdo = new PDO($dsn, $cfg['user'], $cfg['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro de conexão com o banco de dados.']);
    exit;
}

// ─── Salva o agendamento ──────────────────────────────────────────────────────
try {
    $stmt = $pdo->prepare(
        "INSERT INTO agendamentos (nome, email, telefone, data_formatura, curso, mensagem)
         VALUES (:nome, :email, :telefone, :data_formatura, :curso, :mensagem)"
    );
    $stmt->execute([
        ':nome'           => $nome,
        ':email'          => $email,
        ':telefone'       => $telefone ?: null,
        ':data_formatura' => $data_formatura,
        ':curso'          => $curso,
        ':mensagem'       => $mensagem_extra ?: null,
    ]);
    $agendamento_id = $pdo->lastInsertId();
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar agendamento. Tente novamente.']);
    exit;
}

// ─── Formata a data para exibição ─────────────────────────────────────────────
$data_obj    = new DateTime($data_formatura);
$data_br     = $data_obj->format('d/m/Y');
$data_longa  = $data_obj->format('d \\d\\e F \\d\\e Y');

// ─── Configurações de E-mail (ajuste aqui) ───────────────────────────────────
// Use as credenciais do seu servidor SMTP (Gmail, Zoho, Mailgun, etc.)
$smtp_host     = getenv('MAIL_HOST')     ?: 'smtp.gmail.com';
$smtp_port     = (int)(getenv('MAIL_PORT')     ?: 587);
$smtp_user     = getenv('MAIL_USER')     ?: 'seuemail@gmail.com';
$smtp_pass     = getenv('MAIL_PASS')     ?: 'sua-senha-de-app';
$smtp_from     = getenv('MAIL_FROM')     ?: $smtp_user;
$smtp_name     = getenv('MAIL_FROM_NAME') ?: 'Diplomas Raúl';

// E-mail do Raúl (receberá o alerta)
$raul_email    = getenv('RAUL_EMAIL')    ?: 'raul@example.com';
$raul_nome     = 'Raúl';

// ─── Função auxiliar para criar instância PHPMailer ──────────────────────────
function criarMailer(
    string $host, int $port, string $user, string $pass, string $from, string $fromName
): PHPMailer {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = $host;
    $mail->SMTPAuth   = true;
    $mail->Username   = $user;
    $mail->Password   = $pass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = $port;
    $mail->CharSet    = 'UTF-8';
    $mail->setFrom($from, $fromName);
    return $mail;
}

// ─── Template HTML do e-mail para o CLIENTE ──────────────────────────────────
function templateCliente(
    string $nome, string $curso, string $data_br, string $agendamento_id
): string {
    return <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Confirmação de Agendamento</title>
</head>
<body style="margin:0;padding:0;background:#0a1128;font-family:Georgia,serif;">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#0a1128;padding:40px 20px;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;">

          <!-- CABEÇALHO -->
          <tr>
            <td style="background:linear-gradient(135deg,#07101f 0%,#0d1f3c 100%);padding:40px 50px;text-align:center;border-bottom:3px solid #e06d24;">
              <div style="font-family:Georgia,serif;font-size:28px;color:#fff;letter-spacing:3px;text-transform:uppercase;">
                DIPLOMAS <span style="color:#e06d24;">RAÚL</span>
              </div>
              <div style="width:50px;height:2px;background:#e06d24;margin:15px auto 0;"></div>
            </td>
          </tr>

          <!-- CORPO -->
          <tr>
            <td style="background:#061326;padding:50px;">
              <p style="color:#e06d24;font-size:14px;text-transform:uppercase;letter-spacing:2px;margin:0 0 15px;">
                Pedido Recebido ✓
              </p>
              <h1 style="color:#fff;font-size:30px;margin:0 0 25px;line-height:1.2;">
                Olá, {$nome}!<br>
                <span style="font-size:20px;color:rgba(255,255,255,0.65);">Recebemos o seu agendamento.</span>
              </h1>

              <p style="color:rgba(255,255,255,0.75);font-size:16px;line-height:1.7;margin:0 0 30px;">
                Ficamos felizes com o seu interesse! O <strong style="color:#e06d24;">Raúl</strong> irá analisar
                os detalhes da sua sessão e entrará em contato em breve para confirmar a data e horário.
              </p>

              <!-- CARD DO RESUMO -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0"
                     style="background:rgba(255,255,255,0.04);border:1px solid rgba(224,109,36,0.3);border-radius:6px;margin:0 0 35px;">
                <tr>
                  <td style="padding:30px 35px;">
                    <p style="color:#4a7ba5;font-size:12px;text-transform:uppercase;letter-spacing:2px;margin:0 0 18px;">
                      Resumo do Pedido #{$agendamento_id}
                    </p>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td style="padding:8px 0;color:rgba(255,255,255,0.5);font-size:14px;width:140px;">Nome</td>
                        <td style="padding:8px 0;color:#fff;font-size:14px;font-weight:bold;">{$nome}</td>
                      </tr>
                      <tr>
                        <td style="padding:8px 0;border-top:1px solid rgba(255,255,255,0.07);color:rgba(255,255,255,0.5);font-size:14px;">Curso</td>
                        <td style="padding:8px 0;border-top:1px solid rgba(255,255,255,0.07);color:#fff;font-size:14px;">{$curso}</td>
                      </tr>
                      <tr>
                        <td style="padding:8px 0;border-top:1px solid rgba(255,255,255,0.07);color:rgba(255,255,255,0.5);font-size:14px;">Data da Formatura</td>
                        <td style="padding:8px 0;border-top:1px solid rgba(255,255,255,0.07);color:#e06d24;font-size:14px;font-weight:bold;">{$data_br}</td>
                      </tr>
                      <tr>
                        <td style="padding:8px 0;border-top:1px solid rgba(255,255,255,0.07);color:rgba(255,255,255,0.5);font-size:14px;">Status</td>
                        <td style="padding:8px 0;border-top:1px solid rgba(255,255,255,0.07);">
                          <span style="background:#e06d24;color:#fff;font-size:12px;padding:4px 12px;border-radius:20px;text-transform:uppercase;letter-spacing:1px;">
                            Pendente de Confirmação
                          </span>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>

              <p style="color:rgba(255,255,255,0.55);font-size:14px;line-height:1.6;margin:0 0 30px;">
                Enquanto isso, sinta-se à vontade para dar uma olhada no nosso portfólio ou entrar em contato
                diretamente pelo WhatsApp caso tenha alguma dúvida.
              </p>

              <!-- BOTÃO WHATSAPP -->
              <table cellpadding="0" cellspacing="0" border="0" style="margin:0 auto;">
                <tr>
                  <td align="center"
                      style="background:#25D366;border-radius:4px;">
                    <a href="https://wa.me/+557587100691"
                       style="display:inline-block;padding:14px 30px;color:#fff;text-decoration:none;font-size:14px;letter-spacing:1px;text-transform:uppercase;">
                      💬 Falar pelo WhatsApp
                    </a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- RODAPÉ -->
          <tr>
            <td style="background:#07101f;padding:25px 50px;text-align:center;border-top:1px solid rgba(255,255,255,0.08);">
              <p style="color:rgba(255,255,255,0.35);font-size:12px;margin:0;font-family:Arial,sans-serif;letter-spacing:1px;">
                © 2025 Diplomas Raúl · Todos os direitos reservados
              </p>
              <p style="color:rgba(255,255,255,0.2);font-size:11px;margin:8px 0 0;font-family:Arial,sans-serif;">
                Você recebeu este e-mail porque solicitou um agendamento no nosso site.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
HTML;
}

// ─── Template HTML do e-mail de ALERTA para o Raúl ───────────────────────────
function templateAdmin(
    string $nome, string $email, string $telefone,
    string $curso, string $data_br, string $mensagem_extra, string $agendamento_id
): string {
    $tel_html    = $telefone   ? "<tr><td style='padding:8px 0;border-top:1px solid rgba(255,255,255,0.07);color:rgba(255,255,255,0.5);font-size:14px;width:140px;'>Telefone</td><td style='padding:8px 0;border-top:1px solid rgba(255,255,255,0.07);color:#fff;font-size:14px;'>{$telefone}</td></tr>" : '';
    $msg_html    = $mensagem_extra ? "<tr><td style='padding:8px 0;border-top:1px solid rgba(255,255,255,0.07);color:rgba(255,255,255,0.5);font-size:14px;vertical-align:top;'>Mensagem</td><td style='padding:8px 0;border-top:1px solid rgba(255,255,255,0.07);color:rgba(255,255,255,0.8);font-size:14px;'>{$mensagem_extra}</td></tr>" : '';

    return <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#0a1128;font-family:Georgia,serif;">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#0a1128;padding:40px 20px;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;">
          <tr>
            <td style="background:linear-gradient(135deg,#07101f,#0d1f3c);padding:35px 50px;text-align:center;border-bottom:3px solid #4a7ba5;">
              <div style="font-size:12px;color:#4a7ba5;text-transform:uppercase;letter-spacing:3px;margin-bottom:8px;">
                🔔 Novo Agendamento Recebido
              </div>
              <div style="font-family:Georgia,serif;font-size:22px;color:#fff;letter-spacing:3px;text-transform:uppercase;">
                DIPLOMAS <span style="color:#e06d24;">RAÚL</span>
              </div>
            </td>
          </tr>
          <tr>
            <td style="background:#061326;padding:45px 50px;">
              <p style="color:#fff;font-size:20px;margin:0 0 25px;">
                Raúl, um novo agendamento chegou! <span style="color:#e06d24;">🎓</span>
              </p>
              <table width="100%" cellpadding="0" cellspacing="0" border="0"
                     style="background:rgba(255,255,255,0.04);border:1px solid rgba(74,123,165,0.4);border-radius:6px;margin:0 0 30px;">
                <tr>
                  <td style="padding:30px 35px;">
                    <p style="color:#4a7ba5;font-size:12px;text-transform:uppercase;letter-spacing:2px;margin:0 0 18px;">
                      Pedido #{$agendamento_id}
                    </p>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td style="padding:8px 0;color:rgba(255,255,255,0.5);font-size:14px;width:140px;">Nome</td>
                        <td style="padding:8px 0;color:#fff;font-size:14px;font-weight:bold;">{$nome}</td>
                      </tr>
                      <tr>
                        <td style="padding:8px 0;border-top:1px solid rgba(255,255,255,0.07);color:rgba(255,255,255,0.5);font-size:14px;">E-mail</td>
                        <td style="padding:8px 0;border-top:1px solid rgba(255,255,255,0.07);color:#4a7ba5;font-size:14px;">{$email}</td>
                      </tr>
                      {$tel_html}
                      <tr>
                        <td style="padding:8px 0;border-top:1px solid rgba(255,255,255,0.07);color:rgba(255,255,255,0.5);font-size:14px;">Curso</td>
                        <td style="padding:8px 0;border-top:1px solid rgba(255,255,255,0.07);color:#fff;font-size:14px;">{$curso}</td>
                      </tr>
                      <tr>
                        <td style="padding:8px 0;border-top:1px solid rgba(255,255,255,0.07);color:rgba(255,255,255,0.5);font-size:14px;">Data Formatura</td>
                        <td style="padding:8px 0;border-top:1px solid rgba(255,255,255,0.07);color:#e06d24;font-size:14px;font-weight:bold;">{$data_br}</td>
                      </tr>
                      {$msg_html}
                    </table>
                  </td>
                </tr>
              </table>
              <p style="color:rgba(255,255,255,0.55);font-size:14px;line-height:1.6;margin:0 0 25px;">
                Entre em contato com o cliente o quanto antes para confirmar a sessão e deixá-lo animado!
              </p>
              <!-- Botão responder por e-mail -->
              <table cellpadding="0" cellspacing="0" border="0" style="margin:0 auto;">
                <tr>
                  <td style="background:#e06d24;border-radius:4px;">
                    <a href="mailto:{$email}?subject=Confirmação do seu agendamento - Diplomas Raúl"
                       style="display:inline-block;padding:14px 30px;color:#fff;text-decoration:none;font-size:14px;letter-spacing:1px;text-transform:uppercase;">
                      ✉️ Responder ao Cliente
                    </a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="background:#07101f;padding:20px 50px;text-align:center;border-top:1px solid rgba(255,255,255,0.08);">
              <p style="color:rgba(255,255,255,0.3);font-size:12px;margin:0;font-family:Arial,sans-serif;">
                Alerta automático do sistema · Diplomas Raúl
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
HTML;
}

// ─── Disparo dos e-mails ──────────────────────────────────────────────────────
$erros_mail = [];

// 1) E-mail para o cliente
try {
    $mail = criarMailer($smtp_host, $smtp_port, $smtp_user, $smtp_pass, $smtp_from, $smtp_name);
    $mail->addAddress($email, $nome);
    $mail->isHTML(true);
    $mail->Subject = "✅ Raúl Diplomas — Agendamento #{$agendamento_id} recebido!";
    $mail->Body    = templateCliente($nome, $curso, $data_br, (string)$agendamento_id);
    $mail->AltBody = "Olá, {$nome}! Recebemos seu agendamento para o curso {$curso} com formatura em {$data_br}. "
                   . "O Raúl entrará em contato em breve. Pedido #{$agendamento_id}";
    $mail->send();
} catch (MailException $e) {
    $erros_mail[] = 'cliente';
}

// 2) E-mail de alerta para o Raúl
try {
    $mailAdmin = criarMailer($smtp_host, $smtp_port, $smtp_user, $smtp_pass, $smtp_from, $smtp_name);
    $mailAdmin->addAddress($raul_email, $raul_nome);
    $mailAdmin->isHTML(true);
    $mailAdmin->Subject = "🔔 Novo Agendamento #{$agendamento_id} — {$nome}";
    $mailAdmin->Body    = templateAdmin(
        $nome, $email, $telefone, $curso, $data_br, $mensagem_extra, (string)$agendamento_id
    );
    $mailAdmin->AltBody = "Novo agendamento de {$nome} ({$email}) para {$curso} em {$data_br}.";
    $mailAdmin->send();
} catch (MailException $e) {
    $erros_mail[] = 'admin';
}

// ─── Resposta final ───────────────────────────────────────────────────────────
http_response_code(200);
echo json_encode([
    'sucesso'        => true,
    'mensagem'       => "Agendamento confirmado! Um e-mail de confirmação foi enviado para {$email}.",
    'agendamento_id' => $agendamento_id,
    'erros_mail'     => $erros_mail, // vazio = tudo certo; caso contrário, lista quais falharam
]);
