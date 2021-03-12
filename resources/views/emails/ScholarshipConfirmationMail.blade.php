<!DOCTYPE html>
<html>
<head>
    <title>Association Envol</title>
</head>
<body>
<p>Lausanne, le {{ date('d.m.y', strtotime($details['created_at'])) }}</p>

<div style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 14px;">
    <h4 style="color:#011993;">Confirmation de dépot de demande de bourse.</h4>
    <p>{{ $details['gender'] }} {{ $details['fullName'] }},</p>
    <p>Votre demande de bourse nous est bien parvenue, nous reviendrons vers vous après une première analyse de celle-ci.</p>
    <p>Entre temps, si :</p>
    <p>vous avez des questions nous vous invitons consulter notre <a style="color: #011993; text-decoration: none;font-weight:bold;" href="https://association-envol.info/faq" target="_blank">page des questions fréquentes</a>.</p>
    <p>vous avez des questions particulières entre-temps, vous pouvez nous contacter par le biais de <a style="color: #011993; text-decoration: none;font-weight:bold;" href="https://association-envol.info/contact" target="_blank">notre formulaire de contact</a> ou en nous écrivant directement à <a style="color: #011993; text-decoration: none;font-weight:bold;" href="mailto:secretariat@association-envol.info">secretariat@association-envol.info</a></p>

<p><i>Ceci est un message automatique, merci de ne pas y répondre.</i></p>
</body>
</html>
