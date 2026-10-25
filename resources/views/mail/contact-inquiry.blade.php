<p>Nouveau message depuis le site PAH Immo.</p>

<p>
    <strong>Nom :</strong> {{ $lastName }}<br>
    <strong>Prénom :</strong> {{ $firstName }}<br>
    <strong>E-mail :</strong> {{ $email }}<br>
    <strong>Téléphone :</strong> {{ $phone }}
</p>

<p><strong>Message :</strong></p>
<p>{!! nl2br(e($body)) !!}</p>
