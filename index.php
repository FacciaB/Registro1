<?php
session_start();

// Lista di utenti e password
$users = [
    "attiglio.dematteis", "briciola.dematteis", "curt.dematteis", "flaffy.dematteis",
    "grigetta.benua", "kendy.dematteis", "lampo.benua", "leonard.lollo.luna.benua",
    "luna.dematteis", "pandino.dematteis", "papero.benua", "sparcky.benua",
    "stellina.dematteis", "sunny.benua", "tartaruga.benua", "tina.dematteis", "yucky.benua"
];

$password = "cfp.2024";
$admin_user = "admin";
$admin_password = "password";

// Risposte corrette
$answers = [
    "q1" => "a", "q2" => "b", "q3" => "c", "q4" => "d", "q5" => "a",
    "q6" => "b", "q7" => "c", "q8" => "d", "q9" => "a", "q10" => "b"
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Login
        $username = $_POST['username'];
        $pass = $_POST['password'];

        if (in_array($username, $users) && $pass === $password) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'user';
        } elseif ($username === $admin_user && $pass === $admin_password) {
            $_SESSION['role'] = 'admin';
        } else {
            $error = "Credenziali non valide!";
        }
    } elseif (isset($_POST['submit_quiz'])) {
        // Valutazione quiz
        $total_score = 0;

        foreach ($answers as $question => $correct_answer) {
            if (isset($_POST[$question]) && $_POST[$question] === $correct_answer) {
                $total_score += 10;
            } else {
                $total_score -= 10;
            }
        }

        $_SESSION['score'] = $total_score;
        $_SESSION['completed'] = true;
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Elettricità e Magnetismo</title>
</head>
<body>
    <?php if (!isset($_SESSION['role'])): ?>
        <!-- Login -->
        <h1>Login</h1>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="post">
            <label>Nome Utente:</label>
            <input type="text" name="username" required>
            <br>
            <label>Password:</label>
            <input type="password" name="password" required>
            <br>
            <button type="submit" name="login">Accedi</button>
        </form>
    <?php elseif ($_SESSION['role'] === 'user' && !isset($_SESSION['completed'])): ?>
        <!-- Quiz -->
        <h1>Benvenuto, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <h2>Quiz su Elettricità e Magnetismo</h2>
        <form method="post">
            <?php
            $questions = [
                "q1" => "Qual è l'unità di misura della corrente elettrica?",
                "q2" => "Che cos'è un conduttore?",
                "q3" => "Quale materiale è un isolante?",
                "q4" => "Qual è la carica dell'elettrone?",
                "q5" => "Che cos'è la legge di Ohm?",
                "q6" => "Quale strumento misura la resistenza elettrica?",
                "q7" => "Qual è la funzione di un trasformatore?",
                "q8" => "Che cos'è il campo magnetico?",
                "q9" => "Che cos'è l'induzione elettromagnetica?",
                "q10" => "Qual è la relazione tra tensione e corrente?"
            ];

            $options = [
                "q1" => ["a) Ampere", "b) Volt", "c) Ohm", "d) Watt"],
                "q2" => ["a) Materiale che trasmette corrente", "b) Materiale che blocca corrente", "c) Materiale magnetico", "d) Nessuna delle precedenti"],
                "q3" => ["a) Rame", "b) Alluminio", "c) Plastica", "d) Ferro"],
                "q4" => ["a) Positiva", "b) Negativa", "c) Neutra", "d) Nessuna delle precedenti"],
                "q5" => ["a) V = IR", "b) P = IV", "c) Q = CV", "d) R = V/I"],
                "q6" => ["a) Voltmetro", "b) Ohmetro", "c) Amperometro", "d) Galvanometro"],
                "q7" => ["a) Convertire tensione", "b) Misurare corrente", "c) Generare calore", "d) Conservare energia"],
                "q8" => ["a) Campo elettrico", "b) Area di forza magnetica", "c) Misura della potenza", "d) Legge di Faraday"],
                "q9" => ["a) Creazione di corrente da un campo magnetico", "b) Misurazione di resistenza", "c) Generazione di calore", "d) Nessuna delle precedenti"],
                "q10" => ["a) Direttamente proporzionale", "b) Inversamente proporzionale", "c) Non correlate", "d) Dipendono dalla resistenza"]
            ];

            foreach ($questions as $key => $question) {
                echo "<p>$question</p>";
                foreach ($options[$key] as $opt) {
                    echo "<label><input type='radio' name='$key' value='" . strtolower($opt[0]) . "'> $opt</label><br>";
                }
            }
            ?>
            <button type="submit" name="submit_quiz">Invia</button>
        </form>
    <?php elseif ($_SESSION['role'] === 'user' && isset($_SESSION['completed'])): ?>
        <!-- Risultati -->
        <h1>Risultati</h1>
        <p>Punteggio totale: <?php echo $_SESSION['score']; ?>/100</p>
        <a href="index.php?logout=1">Logout</a>
    <?php elseif ($_SESSION['role'] === 'admin'): ?>
        <!-- Admin -->
        <h1>Admin Dashboard</h1>
        <p>Qui potresti visualizzare e gestire i risultati degli utenti (implementazione futura).</p>
        <a href="index.php?logout=1">Logout</a>
    <?php endif; ?>
</body>
</html>
