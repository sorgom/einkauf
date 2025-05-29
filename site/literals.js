class Literals_de
{
    srv = 'NN';
    pwd  = 'Passwort';
    pwd2 = 'Passwort Wiederholung';
    pwd  = 'Passwort';
    mail = 'E-Mail';
    pwdView = 'Passwort anzeigen';
    pwdHide = 'Passwort verbergen';
    register = 'OK';
    intro =
`Willkommen bei ##SRV.

Lege dein Passwort fest.
Damit werden deine Daten verschlüsselt.
Je komplexer, desto besser.
Das Passwort wird nicht gespeichert.

Gib eine E-Mail-Adresse an, wenn du deinen Zugangs-Link zusätzlich per Mail erhalten möchtest.

Dein Zugangs-Link wird im nächsten Schritt generiert.`;

    explain =
`Passwort
Deine Daten werden verschlüsselt abgelegt.
Nur mit deinem Passwort können sie abgerufen werden.
Das Passwort wird nicht gespeichert.

E-Mail
Gib eine E-Mail-Adresse an, wenn du den Zugangs-Link per Mail erhalten möchtest.
Damit kannst du auch später noch ein Lesezeichen einrichten.
Die Adresse wird nicht gespeichert.`;

    yourLink =
`Voilà.

Hier ist dein Zugangs-Link.
Speichere ihn als Lesezeichen.

Viel Freude mit allem, was zu tun ist ...`;

setSrv(srv)
{
    this.srv = srv;
}
}

const lit = new Literals_de();
