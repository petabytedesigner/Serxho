<?php
declare(strict_types=1);

session_start();

const SITE_URL = 'https://creativeartbyserxho.gt.tc';
const SITE_NAME = 'Creative Art by Serxho';

header('Content-Type: text/html; charset=utf-8');

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    $trap = trim((string)($_POST['website'] ?? ''));

    if (!hash_equals($_SESSION['csrf_token'], $csrf) || $trap !== '') {
        $error = 'Kërkesa nuk është e vlefshme.';
    } else {
        $name = trim((string)($_POST['name'] ?? ''));
        $phone = trim((string)($_POST['phone'] ?? ''));
        $message = trim((string)($_POST['message'] ?? ''));

        if ($name === '' || $phone === '' || $message === '') {
            $error = 'Ju lutem plotësoni emrin, telefonin dhe përshkrimin.';
        } else {
            $lead = [
                'created_at' => gmdate('c'),
                'name' => mb_substr($name, 0, 120),
                'phone' => mb_substr($phone, 0, 80),
                'email' => mb_substr(trim((string)($_POST['email'] ?? '')), 0, 160),
                'service' => mb_substr(trim((string)($_POST['service'] ?? '')), 0, 120),
                'message' => mb_substr($message, 0, 2000),
            ];

            $dir = __DIR__ . '/storage';
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            file_put_contents(
                $dir . '/leads.jsonl',
                json_encode($lead, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL,
                FILE_APPEND | LOCK_EX
            );

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $success = true;
        }
    }
}
?>
<!doctype html>
<html lang="sq">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Creative Art by Serxho | String Art & Punime të Personalizuara</title>
    <meta name="description" content="Creative Art by Serxho krijon punime artistike të personalizuara, string art, portrete dhe dhurata unike të punuara me dorë.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://creativeartbyserxho.gt.tc/">

    <meta property="og:type" content="website">
    <meta property="og:title" content="Creative Art by Serxho">
    <meta property="og:description" content="Punime artistike të personalizuara, string art dhe dhurata unike të punuara me dorë.">
    <meta property="og:url" content="https://creativeartbyserxho.gt.tc/">
    <meta property="og:site_name" content="Creative Art by Serxho">

    <style>
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #fff7ef;
            background:
                radial-gradient(circle at top left, rgba(218,164,65,.22), transparent 34%),
                linear-gradient(135deg, #0f0d0b, #1d1510 55%, #0f0d0b);
            line-height: 1.6;
        }
        a { color: inherit; text-decoration: none; }
        .container { width: min(1120px, calc(100% - 32px)); margin: 0 auto; }
        header {
            position: sticky;
            top: 0;
            z-index: 10;
            background: rgba(15,13,11,.82);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,.12);
        }
        .nav {
            min-height: 74px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
        }
        .mark {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #d9a441, #fff1b9);
            color: #1a1209;
            font-weight: 900;
        }
        .links {
            display: flex;
            gap: 18px;
            color: #d2c0ab;
            font-size: 14px;
        }
        .hero { padding: 86px 0 56px; }
        .hero-grid {
            display: grid;
            grid-template-columns: 1.05fr .95fr;
            gap: 34px;
            align-items: center;
        }
        .badge {
            display: inline-flex;
            padding: 8px 13px;
            border-radius: 999px;
            border: 1px solid rgba(217,164,65,.42);
            color: #ffe0a0;
            background: rgba(217,164,65,.08);
            margin-bottom: 18px;
        }
        h1 {
            margin: 0;
            font-size: clamp(38px, 6vw, 72px);
            line-height: .98;
            letter-spacing: -2px;
        }
        .lead {
            color: #d2c0ab;
            font-size: 18px;
            max-width: 650px;
            margin: 22px 0 30px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 0 22px;
            border-radius: 999px;
            font-weight: 800;
            border: 1px solid rgba(255,255,255,.14);
        }
        .btn-primary {
            background: linear-gradient(135deg, #d9a441, #ffe8ad);
            color: #17100a;
            border: 0;
        }
        .btn-secondary {
            background: rgba(255,255,255,.06);
            margin-left: 10px;
        }
        .preview {
            min-height: 430px;
            border-radius: 28px;
            padding: 26px;
            border: 1px solid rgba(255,255,255,.13);
            background: linear-gradient(135deg, rgba(217,164,65,.16), rgba(255,255,255,.05));
            box-shadow: 0 24px 70px rgba(0,0,0,.36);
        }
        .frame {
            height: 100%;
            min-height: 378px;
            border-radius: 22px;
            border: 1px solid rgba(255,255,255,.16);
            display: grid;
            place-items: center;
            text-align: center;
            background:
                radial-gradient(circle at 35% 30%, rgba(217,164,65,.34), transparent 28%),
                linear-gradient(145deg, #2b2117, #100d0b);
        }
        .frame strong {
            display: block;
            font-size: 34px;
            line-height: 1.1;
        }
        .frame span { color: #d2c0ab; }
        section { padding: 54px 0; }
        h2 {
            font-size: clamp(30px, 4vw, 46px);
            letter-spacing: -1.2px;
            margin: 0 0 24px;
        }
        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }
        .card, .form {
            border: 1px solid rgba(255,255,255,.12);
            background: rgba(255,255,255,.055);
            border-radius: 24px;
            padding: 24px;
        }
        .card h3 { margin: 0 0 10px; }
        .card p { margin: 0; color: #d2c0ab; }
        .contact {
            display: grid;
            grid-template-columns: .85fr 1.15fr;
            gap: 24px;
            align-items: start;
        }
        label {
            display: block;
            margin: 14px 0 7px;
            font-weight: 800;
        }
        input, select, textarea {
            width: 100%;
            min-height: 48px;
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,.16);
            background: rgba(0,0,0,.22);
            color: #fff7ef;
            padding: 12px 14px;
            font: inherit;
            outline: none;
        }
        textarea { min-height: 130px; resize: vertical; }
        .notice {
            padding: 13px 15px;
            border-radius: 16px;
            margin-bottom: 16px;
            border: 1px solid rgba(255,255,255,.12);
        }
        .success { color: #b8f7c1; background: rgba(44,160,72,.12); }
        .error { color: #ffb4a8; background: rgba(255,82,82,.12); }
        .hidden { position: absolute; left: -9999px; }
        footer {
            border-top: 1px solid rgba(255,255,255,.12);
            padding: 28px 0;
            color: #d2c0ab;
            margin-top: 40px;
        }
        @media (max-width: 860px) {
            .hero-grid, .contact, .cards { grid-template-columns: 1fr; }
            .nav { flex-direction: column; align-items: flex-start; padding: 14px 0; }
            .links { flex-wrap: wrap; }
            .btn-secondary { margin-left: 0; margin-top: 10px; }
        }
    </style>
</head>
<body>
<header>
    <div class="container nav">
        <a class="brand" href="/">
            <span class="mark">CA</span>
            <span>Creative Art by Serxho</span>
        </a>
        <nav class="links">
            <a href="#services">Shërbimet</a>
            <a href="#process">Procesi</a>
            <a href="#contact">Kontakt</a>
        </nav>
    </div>
</header>

<main>
    <section class="hero">
        <div class="container hero-grid">
            <div>
                <span class="badge">Punime artistike të personalizuara</span>
                <h1>Art unik i krijuar me dorë për kujtime që mbeten.</h1>
                <p class="lead">
                    Creative Art by Serxho krijon string art, portrete dhe dhurata të personalizuara
                    për njerëz, familje, ambiente dhe raste të veçanta.
                </p>
                <a class="btn btn-primary" href="#contact">Dërgo kërkesë</a>
                <a class="btn btn-secondary" href="#services">Shiko shërbimet</a>
            </div>
            <div class="preview">
                <div class="frame">
                    <div>
                        <strong>String Art<br>Custom Made</strong>
                        <span>Creative Art by Serxho</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="services">
        <div class="container">
            <h2>Çfarë mund të krijojmë</h2>
            <div class="cards">
                <article class="card">
                    <h3>String Art Personal</h3>
                    <p>Punime me fije dhe gozhdë sipas fotos, emrit ose idesë së klientit.</p>
                </article>
                <article class="card">
                    <h3>Dhurata të Personalizuara</h3>
                    <p>Dhurata për ditëlindje, çifte, familje, miq dhe raste të veçanta.</p>
                </article>
                <article class="card">
                    <h3>Dekor për Ambiente</h3>
                    <p>Punime artistike për shtëpi, lokale, zyra ose hapësira kreative.</p>
                </article>
            </div>
        </div>
    </section>

    <section id="process">
        <div class="container">
            <h2>Si funksionon</h2>
            <div class="cards">
                <article class="card"><h3>1. Dërgo idenë</h3><p>Klienti dërgon foton, emrin, madhësinë ose përshkrimin.</p></article>
                <article class="card"><h3>2. Marrëveshje</h3><p>Konfirmohet çmimi, afati dhe mënyra e realizimit.</p></article>
                <article class="card"><h3>3. Realizim</h3><p>Punimi krijohet me kujdes dhe dorëzohet sipas marrëveshjes.</p></article>
            </div>
        </div>
    </section>

    <section id="contact">
        <div class="container contact">
            <div>
                <h2>Dërgo një kërkesë</h2>
                <p class="lead">Plotëso formën dhe do të kontaktohesh për detajet, çmimin dhe kohën e realizimit.</p>
            </div>

            <form class="form" method="post" action="#contact">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

                <div class="hidden">
                    <label>Website</label>
                    <input type="text" name="website" tabindex="-1" autocomplete="off">
                </div>

                <?php if ($success): ?>
                    <div class="notice success">Kërkesa u dërgua me sukses. Do të kontaktohesh së shpejti.</div>
                <?php endif; ?>

                <?php if ($error !== ''): ?>
                    <div class="notice error"><?= e($error) ?></div>
                <?php endif; ?>

                <label for="name">Emri</label>
                <input id="name" name="name" type="text" maxlength="120" required>

                <label for="phone">Telefoni</label>
                <input id="phone" name="phone" type="tel" maxlength="80" required>

                <label for="email">Email</label>
                <input id="email" name="email" type="email" maxlength="160">

                <label for="service">Lloji i punimit</label>
                <select id="service" name="service">
                    <option value="String Art">String Art</option>
                    <option value="Portret i personalizuar">Portret i personalizuar</option>
                    <option value="Dhuratë e personalizuar">Dhuratë e personalizuar</option>
                    <option value="Dekor ambienti">Dekor ambienti</option>
                    <option value="Tjetër">Tjetër</option>
                </select>

                <label for="message">Përshkrimi i idesë</label>
                <textarea id="message" name="message" maxlength="2000" required></textarea>

                <div style="margin-top:18px">
                    <button class="btn btn-primary" type="submit">Dërgo kërkesën</button>
                </div>
            </form>
        </div>
    </section>
</main>

<footer>
    <div class="container">
        © <?= date('Y') ?> Creative Art by Serxho — Punime artistike të personalizuara.
    </div>
</footer>
</body>
</html>
