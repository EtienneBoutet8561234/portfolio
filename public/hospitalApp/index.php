<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrée du Code Utilisateur</title>
</head>
<body>
    <h1>Entrer Votre Code à 3 Lettres</h1>
    <form action="/tryLoginWithCode.php" method="post">
        <label for="userCode">Code:</label>
        <input type="text" id="userCode" name="userCode" maxlength="3" pattern="[A-Za-z]{3}" title="Trois lettres uniquement, svp" required>
        <button type="submit">Soumettre</button>
    </form>
</body>
</html>
