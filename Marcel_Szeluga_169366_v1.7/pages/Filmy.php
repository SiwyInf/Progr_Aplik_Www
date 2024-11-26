<?php
include('cfg.php');  // Połączenie z bazą danych

// Pobieranie danych filmów z bazy danych
try {
    $stmt = $pdo->prepare("SELECT tytul, url_video FROM filmy WHERE status = 1");
    $stmt->execute();
    $filmy = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Błąd: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Language" content="pl">
    <title>Filmy</title>
    <link rel="stylesheet" type="text/css" href="css/style_css.css">
</head>
<body>
    <header>
        <h1 class="wed">Filmy o wędkarstwie</h1>
        <nav>
        </nav>
    </header>

    <div class="container">
        <?php foreach ($filmy as $film): ?>
            <div class="product">
                <h2><?php echo htmlspecialchars($film['tytul']); ?></h2>
                <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo htmlspecialchars($film['url_video']); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
