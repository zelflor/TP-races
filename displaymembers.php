<!DOCTYPE html>
<html lang="fr">
<head>
        <meta charset="UTF-8">
        <meta name="keywords" content="Course, Running">
        <meta name="description" content="Devenez adhérents à des courses à pied">
        <meta name="author" content="QUEIROZ Florian">
        <meta name="viewport" content="width=device-width">
        <title>Club de course à pied</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/pages/displayMembers.css">
        <link rel="shortcut icon" href="../media/favicon.png" type="image/x-icon">
</head>
    <body>
         <?php

        include './components/header.php';
        ?>



        <section>
            <div class="div-page-title-container">
                <h3>Liste des Adhérents</h3>
                <div class="div-input-search">
                
                    <input type="text" name="" id="input-text-search" placeholder="Rechercher un adhérents">
                    <button id="btn-submit-search"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M21.0002 21L16.7002 16.7M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg></button>
                </div>
            </div>
          <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Numéro de licence</th>
                    <th>Photo de profile</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Sexe</th>
                    <th>Date de naissance</th>
                    <th>Mail</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><div class="div-avatar"></div></td>
                    <td>John</td>
                    <td>Doe</td>
                    <td>M</td>
                    <td>01/02/2000</td>
                    <td>johndoe@btsciel.lan</td>
                </tr>
            </tbody>
        </table>
        </section>
        <script src="./js/searchs/searchMembers.js"></script>
         <?php

        include './components/footer.php';
        ?>
    </body>
</html>