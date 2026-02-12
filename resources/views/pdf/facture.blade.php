<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture #{{ $facture->idfacture }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; }
        .header { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .logo { width: 150px; max-height: 80px; object-fit: contain; }
        .company-info { text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
        .total { font-size: 20px; font-weight: bold; text-align: right; margin-top: 30px; }
        .footer { margin-top: 50px; font-size: 12px; text-align: center; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            @if($facture->custom_logo)
                <img src="{{ public_path('storage/' . $facture->custom_logo) }}" class="logo">
            
            @elseif(isset($pro) && $pro && $pro->logo)
                <img src="{{ public_path('storage/' . $pro->logo) }}" class="logo">
            
            @else
                <img src="{{ public_path('img/logo.webp') }}" class="logo" alt="Hunimalis">
            @endif
        </div>

        <div class="company-info">
            @if(isset($pro) && $pro)
                <h2>{{ $facture->custom_emetteur ?? $pro->libelleetablissement }}</h2>
                <p>
                    {{ $pro->adresse ?? '' }}<br>
                    {{ $pro->cp ?? '' }} {{ $pro->ville ?? '' }}<br>
                    SIRET: {{ $pro->siret ?? 'Non renseigné' }}
                </p>
            @else
                <h2>Hunimalis Shop</h2>
                <p>
                    Service Client<br>
                    contact@hunimalis.com<br>
                    SIRET: 000 000 000 00000
                </p>
            @endif
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Facturé à :</strong><br>
        {{ $facture->client->personne->prenom }} {{ $facture->client->personne->nom }}<br>
        {{ $facture->client->personne->mail }}<br>
        {{ $facture->client->adresse ?? '' }} 
        {{ $facture->client->cp ?? '' }} {{ $facture->client->ville ?? '' }}
    </div>

    <div>
        <strong>Détails :</strong><br>
        Facture n° : #{{ $facture->idfacture }}<br>
        Date : {{ \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y H:i') }}
    </div>

    <table>
        <thead>
            <tr style="background: #f9f9f9;">
                <th>Description (Nature)</th>
                <th style="text-align: right;">Total HT</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $facture->nature ?? 'Commande Hunimalis / Prestation' }}</td>
                <td style="text-align: right;">{{ number_format($facture->total, 2) }} €</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        Total à payer : {{ number_format($facture->total, 2) }} €
    </div>

    <div class="footer">
        Document généré par Hunimalis - Merci de votre confiance.
    </div>
</body>
</html>