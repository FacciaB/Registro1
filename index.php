<?php
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

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $pass = $_POST['password'];

        if (in_array($username, $users) && $pass === $password) {
            $_SESSION['username'] = $username;
            header("Location: quiz.php");
            exit;
        } elseif ($username === $admin_user && $pass === $admin_password) {
            $_SESSION['admin'] = true;
            header("Location: admin.php");
            exit;
        } else {
            echo "Credenziali non valide!";
        }
    } elseif (isset($_POST['submit_quiz'])) {
        $total_score = 0;
        $answers = [
            "q1" => "a", "q2" => "b", "q3" => "c", "q4" => "d", "q5" => "a",
            "q6" => "b", "q7" => "c", "q8" => "d", "q9" => "a", "q10" => "b"
        ];

        foreach ($answers as $question => $correct_answer) {
            if (isset($_POST[$question]) && $_POST[$question] === $correct_answer) {
                $total_score += 10;
            } else {
                $total_score -= 10;
            }
        }

        $_SESSION['score'] = $total_score;
        header("Location: result.php");
        exit;
    }
}
?>

<!-- login.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="post">
        <label>Nome Utente:</label>
        <input type="text" name="username" required>
        <br>
        <label>Password:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit" name="login">Accedi</button>
    </form>
</body>
</html>

<!-- quiz.php -->
<?php if (!isset($_SESSION['username'])) { header("Location: login.php"); exit; } ?>
<!DOCTYPE html>
<html>
<head>
    <title>Quiz</title>
</head>
<body>
    <h1>Quiz su Elettricità e Magnetismo</h1>
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
</body>
</html>

<!-- result.php -->
<?php if (!isset($_SESSION['score'])) { header("Location: login.php"); exit; } ?>
<!DOCTYPE html>
<html>
<head>
    <title>Risultati</title>
</head>
<body>
    <h1>Risultati</h1>
    <p>Punteggio totale: <?php echo $_SESSION['score']; ?>/100</p>
    <a href="login.php">Logout</a>
</body>
</html>

<!-- admin.php -->
<?php if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; } ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Risultati ricevuti:</p>
    <!-- Qui si potrebbero salvare i risultati in un database -->
    <a href="login.php">Logout</a>
</body>
</html>
