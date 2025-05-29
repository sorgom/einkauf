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
    explain =
`Passwort
Deine Daten werden verschlüsselt abgelegt.
Nur mit deinem Passwort können sie abgerufen werden.
Das Passwort wird nicht gespeichert.

E-Mail
Gib eine E-Mail-Adresse an, wenn du den Zugangs-Link per Mail erhalten möchtest.
Damit kannst du auch später noch ein Lesezeichen einrichten.
Die Adresse wird nicht gespeichert.
`;
    yourLink =
`Voilà.
Hier ist dein Zugangs-Link.
Speichere ihn als Lesezeichen.
Viel Freude mit allem, was zu tun ist ...

`;

setSrv(srv)
{
    this.srv = srv;
}
}

const lit = new Literals_de();
