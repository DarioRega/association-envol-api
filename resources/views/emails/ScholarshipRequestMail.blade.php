<!DOCTYPE html>
<html>
<head>
    <title>Association Envol</title>
</head>
<body>
<p>Lausanne, le {{ date('d.m.y', strtotime($details['created_at'])) }}</p>

<p>Vous avez reçu une nouvelle demande de bourse par le formulaire en ligne d'Envol. Vous trouverez ci-dessous les informations nécessaires ainsi que les remarques du demandeur.</p>
<p>Genre: {{ $details['gender'] }}</p>
<p>Nom : {{ $details['fullName'] }}</p>
<p>Age du demandeur : {{ $details['age']}} ({{$details['birthdate']}})</p>
<p>Email : {{$details['email']}}</p>
<div style="display:flex; justify-content:start;align-items: start">
    <p style="flex:none">Remarques particulières : </p>
    <p style="flex: 1 1 0;margin-left:5px;">{{ $details['remarks'] }}</p>
</div>
<br>
<p><i>Ceci est un message automatique, merci de ne pas y répondre.</i></p>
</body>
</html>


