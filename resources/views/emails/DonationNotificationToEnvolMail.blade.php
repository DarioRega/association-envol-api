<!DOCTYPE html>
<html>
<head>
    <title>Association Envol</title>
</head>
<body>
    <p>Lausanne, le {{ date('d.m.y', strtotime($details['created_at'])) }}</p>

    <p>Vous avez reçu une donation effectuée via le site internet.</p>
    <p>Informations conernant le donateur: </p>
    <div style="display:flex; justify-content:start;align-items: start">
        <p style="flex:none">Nom et prénom : </p>
        <p style="flex: 1 1 0;margin-left:5px;">{{ $details['full_name'] }}</p>
    </div>
    <div style="display:flex; justify-content:start;align-items: start">
        <p style="flex:none">Email : </p>
        <p style="flex: 1 1 0;margin-left:5px;">{{ $details['email'] }}</p>
    </div>
    <div style="display:flex; justify-content:start;align-items: start">
        <p style="flex:none">Entreprise de référence : </p>
        <p style="flex: 1 1 0;margin-left:5px;">{{ $details['company_name'] ? $details['company_name']  : '-' }}</p>
    </div>
    <div style="display:flex; justify-content:start;align-items: start">
        <p style="flex:none">Commentaire : </p>
        <p style="flex: 1 1 0;margin-left:5px;">{{ $details['commentary'] ? $details['commentary']  : '-'}}</p>
    </div>

    <div style="display:flex; justify-content:start;align-items: start">
        <p style="flex:none">Montant : </p>
        <p style="flex: 1 1 0;margin-left:5px;">{{ $details['amount'] / 100 }} CHF</p>
    </div>

    <div style="display:flex; justify-content:start;align-items: start">
        <p style="flex:none">Récurrence : </p>
        <p style="flex: 1 1 0;margin-left:5px;">{{ $details['selectedInterval']['name'] }}</p>
    </div>

    <div style="display:flex; justify-content:start;align-items: start">
        <p style="flex:none">Plateforme choisie: </p>
        <p style="flex: 1 1 0;margin-left:5px;">{{ $details['payment_method'] }}</p>
    </div>

    <p>Vous pourrez trouver plus de détails sur le site internet de la plateforme utilisée :</p>
    @if($details['payment_method'] == 'stripe')
    <a style="maring-top:25px;display:block;" href="https://dashboard.stripe.com/payments" target="_blank">Stripe</a>
    @else
    <a style="maring-top:25px;display:block;" href="https://www.paypal.com/mep/dashboard" target="_blank">Paypal</a>
    @endif
    <br/>

    <p><i>Ceci est un message automatique, merci de ne pas y répondre.</i></p>

</body>
</html>

