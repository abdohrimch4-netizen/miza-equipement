<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmation de commande</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f9f9f9; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
        <div style="text-align: center; border-bottom: 2px solid #e8340a; padding-bottom: 20px; mb-4">
            <h1 style="color: #e8340a; margin: 0;">MIZA Équipement</h1>
            <p style="margin: 5px 0 0; color: #666;">Merci pour votre commande !</p>
        </div>

        <div style="padding: 20px 0;">
            <p>Bonjour {{ $order->nom_client }},</p>
            <p>Nous avons bien reçu votre commande <strong>#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong> et nous vous en remercions.</p>
            
            <h3 style="color: #e8340a;">Détails de la livraison</h3>
            <ul style="list-style: none; padding: 0;">
                <li><strong>Nom :</strong> {{ $order->nom_client }}</li>
                <li><strong>Téléphone :</strong> {{ $order->telephone }}</li>
                <li><strong>Adresse :</strong> {{ $order->adresse }}, {{ $order->ville }}</li>
            </ul>

            <h3 style="color: #e8340a;">Récapitulatif de la commande</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f4f4f4;">
                        <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Produit</th>
                        <th style="padding: 10px; border: 1px solid #ddd; text-align: center;">Qté</th>
                        <th style="padding: 10px; border: 1px solid #ddd; text-align: right;">Prix</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $item->name ?? ($item->product->name ?? 'Produit') }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">{{ $item->quantity }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: right;">{{ number_format(($item->price ?? 0) * $item->quantity, 2) }} MAD</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" style="padding: 10px; text-align: right;">Total :</th>
                        <th style="padding: 10px; text-align: right; color: #e8340a;">{{ number_format($order->total, 2) }} MAD</th>
                    </tr>
                </tfoot>
            </table>

            <p style="margin-top: 20px;">Notre équipe vous contactera très prochainement pour confirmer la date de livraison.</p>
        </div>

        <div style="text-align: center; border-top: 1px solid #ddd; padding-top: 20px; font-size: 12px; color: #999;">
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
            <p>© {{ date('Y') }} MIZA Équipement</p>
        </div>
    </div>
</body>
</html>
