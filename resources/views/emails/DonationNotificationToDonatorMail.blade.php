<!DOCTYPE html>
<html>
<head>
    <title>Association Envol</title>
</head>
<body>
    <p>Lausanne, le {{ date('d.m.y', strtotime($details['created_at'])) }}</p>
    <p>
        L'association Envol vous remercie vivement de votre don. Son comité étudie avec soin les nombreuses
        demandes de bourses qui lui sont adressées. Il répond favorablement aux demandes jugées
        prioritaires et réalisables en soulageant les bénéficiaires des coûts de leur formation pour lesquels ils
        ou elles n'ont trouvé ni aide ni solution.
    </p>
    <p>
        Par votre don, vous contribuez au soutien qu'Envol leur apporte et nous vous en sommes très
        reconnaissants
    </p>

    @if($details['payment_method'] == 'stripe' && $details['interval'] != 'one_time')
        <p style="width:100%">Un espace donateur est en cours de création, pour le moment, <strong>pour annuler votre souscription, veuillez contacter notre <a href="mailto:comptabilite@association-envol.info" target="_blank">comptabilité</a></strong>. Merci</p>
    @endif
    @if($details['payment_method'] == 'paypal' && $details['interval'] != 'one_time')
        <p style="width:100%">Pour annuler votre souscription, veuillez vous rendre sur votre espace <a href="https://paypal.com/signin" target="_blank">Paypal</a></p>
    @endif
        <p style="width:100%">En cas de questions ou problème, vous pouvez sans autre contacter notre <a href="mailto:comptabilite@association-envol.info" target="_blank">comptabilité</a></p>
    <br/>
    <p><i>Ceci est un message automatique, merci de ne pas y répondre.</i></p>

</body>
</html>

