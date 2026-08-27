<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue sur Je Suis Béninois</title>
</head>
<body style="margin:0;padding:0;background:#f5f5f0;font-family:'DM Sans',Arial,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f5f0;padding:32px 0;">
        <tr>
            <td align="center">

                <table width="520" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,.08);">

                    {{-- Bandeau drapeau --}}
                    <tr>
                        <td style="height:8px;background:linear-gradient(90deg,#1B5E20 33%,#FFD700 33%,#FFD700 66%,#C62828 66%);"></td>
                    </tr>

                    {{-- En-tête --}}
                    <tr>
                        <td style="background:#1B5E20;padding:32px 40px;text-align:center;">
                            <div style="font-family:Georgia,serif;font-size:22px;font-weight:700;color:#ffffff;">
                                JE SUIS BÉNINOIS
                            </div>
                            <div style="font-size:11px;color:#FFD700;letter-spacing:2px;text-transform:uppercase;margin-top:4px;">
                                Fierté &amp; Culture
                            </div>
                        </td>
                    </tr>

                    {{-- Corps --}}
                    <tr>
                        <td style="padding:40px;">
                            <p style="font-size:20px;color:#1a1a1a;margin:0 0 16px;font-family:Georgia,serif;">
                                Bienvenue, {{ $name }} !
                            </p>

                            <p style="font-size:14px;color:#555;line-height:1.7;margin:0 0 20px;">
                                Merci de rejoindre <strong>Je Suis Béninois</strong>, la communauté dédiée
                                à la valorisation de la culture, de l'histoire et du patrimoine du Bénin.
                            </p>

                            <p style="font-size:14px;color:#555;line-height:1.7;margin:0 0 28px;">
                                Votre compte est maintenant actif. Vous pouvez dès à présent explorer
                                nos articles, découvrir nos traditions et contribuer à notre patrimoine vivant.
                            </p>

                            <table cellpadding="0" cellspacing="0" style="margin:0 auto;">
                                <tr>
                                    <td style="background:#1B5E20;border-radius:8px;">
                                        <a href="{{ route('home') }}"
                                           style="display:inline-block;padding:13px 28px;color:#ffffff;font-size:14px;font-weight:600;text-decoration:none;">
                                            Découvrir le site
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Pied --}}
                    <tr>
                        <td style="background:#f5f5f0;padding:20px 40px;text-align:center;">
                            <p style="font-size:11px;color:#999;margin:0;">
                                © {{ date('Y') }} Je Suis Béninois — Tous droits réservés.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>