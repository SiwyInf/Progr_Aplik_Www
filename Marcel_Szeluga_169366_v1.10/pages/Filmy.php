<?php
// Połączenie z bazą danych
include('cfg.php');

try {
    // Przygotowanie i wykonanie zapytania do bazy danych
    $stmt = $pdo->prepare("SELECT tytul, url_video FROM filmy WHERE status = 1");
    $stmt->execute();
    $filmy = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Błąd połączenia z bazą danych: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Language" content="pl">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filmy o Wędkarstwie</title>
    <link rel="stylesheet" href="css/style_css.css">
</head>
<body>
    <header>
        <h1 class="wed">Filmy o wędkarstwie</h1>
    </header>

    <div class="container">
        <?php if (!empty($filmy)): ?>
            <?php foreach ($filmy as $film): ?>
                <div class="product">
                    <h2><?php echo htmlspecialchars($film['tytul']); ?></h2>
                    <iframe 
                        width="560" 
                        height="315" 
                        src="https://www.youtube.com/embed/<?php echo htmlspecialchars($film['url_video']); ?>" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Brak dostępnych filmów.</p>
        <?php endif; ?>
    </div>
</body>
</html>
