
<div class="row">
    <div class="col-md-6">
        <h2 class="hvr-underline-from-left" id="six"><b>Contact</b></h2><br /><br />
        <section class="contenu">
            <form id="contact" method="post" action="contact.php" enctype="multipart/form-data" onsubmit="return VerificationFormulaire(this)" >
            <input type="text" id="nom" name="nom" placeholder="Nom et prénom" tabindex="1" />
            <input type="text" id="email" name="mail" placeholder="Email" tabindex="2" >
            <input type="text" id="objet" name="sujet" placeholder="Sujet"/><br />
            <input type="tel" id="tel" name="tel" placeholder="GSM" tabindex="3" /><br />
            <textarea id="message" name="message" placeholder="Message" tabindex="4" cols="20" rows="5"></textarea><br />
            <input name="fichier" id="fichier" type="file" /><br />
            <input type="hidden" name="MAX_FILE_SIZE" value="2000000"> <!--Code pour limiter la taille du fichier-->
            <br />
            <input type="submit" id="envoi_message" name="envoi_message" value="Envoyer"/>
            </form>
        </section>
    </div>
    <div class="col-md-6">
        <div id="map-container"><br /><br /><br /><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2537.03078050523!2d4.674555365200231!3d50.51499409059397!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c183ed82b3884d%3A0xa11ab32fb4eaa7e6!2sMazy+Culture!5e0!3m2!1sfr!2sbe!4v1509698857524" width="400" height="300" frameborder="0" style="border:0" allowfullscreen></iframe></div>
        <br /><p><b>Renseignements/Réservations : </b>0478/46.00.65 ou info@mazyculture.org<br />
        <b>Adresse :</b> Rue de l'Usine 9a - 5032 Mazy<br /></p>
    </div>
</div>




<?php
//============== L'ajout de pièce jointe à un message a été réalisé grâce à https://openclassrooms.com/courses/e-mail-envoyer-un-e-mail-en-php

if (isset($_POST["envoi_message"])) { //****** Le formulaire d'envoi de message a été validé


//====Une fois la ligne ci-dessus enlevée, enlever les commentaires au début de la ligne ci-dessous pour écrire l'adresse du destinataire (vous) en dur dans le programme.

$mail_destinataire = "mandy2044@msn.com"; //=====Mail du destinataire final du message envoyé

//=====Vérification de l'existence d'une pièce jointe.
if ($_FILES['fichier']['name']<>"") $ispiece = true; else $ispiece = false;

//=====Vérification de l'adresse de destination.
if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail_destinataire)) $passage_ligne = "\r\n"; else $passage_ligne = "\n";

//=====Récupération du mail, du nom de l'expéditeur et du sujet.
$mail = $_POST["mail"];
$nom = $_POST["nom"];
$sujet = stripslashes($_POST["sujet"]);
$gsm = stripslashes($_POST["tel"]);

//=====Déclaration des messages au format texte et au format HTML.

$msg_body = "<p><font color='#666666'>La personne ci-dessus vous a contacté(e) à partir de votre site. Si vous désirez lui répondre, il vous suffit de répondre à ce message. Voici le contenu de son message :</font></em></p><br />";

$msg_body .= "<font color='#666666'><em><p>Mail de l'expéditeur : </font><strong>".$mail_expediteur."</strong><br />";
$msg_body .= "<font color='#666666'>Nom de l'expéditeur : </font><strong>".$nom."</strong><br />";
$msg_body .= "<font color='#666666'>GSM : </font><strong>".$gsm."</strong></p>";


$message_tape = htmlspecialchars($_POST["message"], ENT_QUOTES);
$retourligne   = array("\r\n", "\n", "\r");
$remplace = '<br />';
$msg_body .= "<p>".str_replace($retourligne, $remplace, $message_tape)."</p>";
$msg_body = stripslashes($msg_body);

$message_html = "<html><head></head><body>".$msg_body."</body></html>";

//=====Transfert de la pièce jointe sur le serveur.
if ($ispiece) { //===traitement de la pièce jointe seulement si le champ du formulaire a été renseigné
    $uploaddir = './'; //===Chemin du dossier de votre serveur web dans lequel sera transféré la pièce jointe avant d'être traitée
    $upload_file = $uploaddir . $_FILES['fichier']['name'];
    if (move_uploaded_file($_FILES['fichier']['tmp_name'], $upload_file)) {
        $ext = explode(".", basename($_FILES['fichier']['name']));
        switch($ext[1]) {
            default:
            $attach_type =  "application/octet-stream";
        break;
            case "gz":
            $attach_type =  "application/x-gzip";
        break;
            case "tgz":
            $attach_type =  "application/x-gzip";
        break;
            case "zip":
            $attach_type =  "application/zip";
        break;
            case "pdf":
            $attach_type =  "application/pdf";
        break;
            case "png":
            $attach_type =  "image/png";
        break;
            case "gif":
            $attach_type =  "image/gif";
        break;
            case "jpg":
            case"jpeg":
            $attach_type =  "image/jpeg";
        break;
            case "txt":
            $attach_type =  "text/plain";
        break;
            case "htm":
            $attach_type =  "text/html";
        break;
            case "html":
            $attach_type =  "text/html";
        break;
        }
        $attach_name = $_FILES["fichier"]["name"];
    }
    //=====Lecture et mise en forme de la pièce jointe.
    if (file_exists($upload_file)) {
        $fichier = fopen($upload_file, "r");
        $attachement = fread($fichier, filesize($upload_file));
        $attachement = chunk_split(base64_encode($attachement));
        fclose($fichier);
    }
}
//=====Création de la boundary.
$boundary = "-----=".md5(rand());
$boundary_alt = "-----=".md5(rand());

//=====Création du header de l'e-mail.
$header = "From: ".$nom." <".$mail.">".$passage_ligne;
$header.= "Reply-To: ".$nom." <".$mail.">".$passage_ligne;
$header .= "MIME-Version: 1.0".$passage_ligne;
$header.= "Content-Type: multipart/mixed;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;

//=====Création du message.
$message = $passage_ligne."--".$boundary.$passage_ligne;
$message.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary_alt\"".$passage_ligne;
$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
//=====Ajout du message au format texte.
$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_txt.$passage_ligne;
//==========

$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;

//=====Ajout du message au format HTML.
$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_html.$passage_ligne;
//==========

//=====On ferme la boundary alternative.
$message.= $passage_ligne."--".$boundary_alt."--".$passage_ligne;
//==========
$message.= $passage_ligne."--".$boundary.$passage_ligne;

//=====Ajout de la pièce jointe.
if ($ispiece) { //===Ajout de la pièce jointe seulement si le champ du formulaire a été renseigné
    $message.= "Content-Type: ".$attach_type."; name=\"".$attach_name."\"".$passage_ligne;
    $message.= "Content-Transfer-Encoding: base64".$passage_ligne;
    $message.= "Content-Disposition: attachment; filename=\"".$attach_name."\"".$passage_ligne;
    $message.= $passage_ligne.$attachement.$passage_ligne.$passage_ligne;
    $message.= $passage_ligne."--".$boundary."--".$passage_ligne;
}
//=====Envoi de l'e-mail.
mail($mail_destinataire,$sujet,$message,$header);
//==========
echo "<script>alert('Le message a bien été expédié.')</script>";
header("Location:http://localhost/mathieu/FRANCAIS/");
}
?>

<!-- Main Content End -->
