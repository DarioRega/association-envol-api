<!DOCTYPE html>
<html>
<head>
    <title>Association Envol</title>
</head>
<body>
<p>Lausanne, le {{ date('d.m.y', strtotime($details['created_at'])) }}</p>

<p>Message envoyé par : {{ $details['gender'] }} {{ $details['fullName'] }}</p>

<p>Sujet : {{$details['subject']}}</p>
<div style="display:flex; justify-content:start;align-items: start">
    <p style="flex:none">Message : </p>
    <p style="flex: 1 1 0;margin-left:5px;">{{ $details['message'] }}</p>
</div>
<p><i>Ceci est un message automatique, merci de ne pas y répondre.</i></p>
</body>
</html>

