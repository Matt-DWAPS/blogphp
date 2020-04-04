<?php $this->title = 'Valider votre Inscription'; ?>
<div style="border: 10px solid #2A374E; padding: 10px">
    <p>Nom d'utilisateur : <?php echo $username; ?></p>
    <p>Adresse mail : <?php echo $email; ?></p>
    <p>Veuillez valider votre inscription en cliquant sur le lien ci-dessous :</p><br/>
    <?php echo "<a href='http://blog-php.webagency-matt.com/index.php?action=userValidationRegistered&email=$email&token=$token'>Valider mon
    inscription</a>"; ?>
</div>

