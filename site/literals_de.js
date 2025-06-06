class Literals
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

const lit = new Literals();

class IntroLiterals
{
heading_overview = 'Übersicht';
heading_edit = 'Text-Eingabe';
heading_list = 'Todo-Liste';
heading_about = `Worum geht's?`;
what_about =
``;
overview_start =
`Wie einfach ist das?
Nutzen wir Todo-Listen zum Einkaufen!
Die Übersicht ist anfangs noch leer.
(1) Klicke auf das Schreiben-Symbol. Damit geht's zur Text-Eingabe ..`;

edit_first_write =
`Schreiben wir 3 Einkauflisten!
- Jede Todo-Liste bginnt mit einem @ am Zeilenanfang.
- Jede Zeile darunter ist ein zu erledigender Punkt.
(2) Eingabe der Todo-Listen für Aldi, REWE und Edeka.
Hierbei kannst du natürlich auch die Spracheingabe deines Smartphones nutzen.
(3) Mit einem Klick auf das Speichern-Symbol geht's wieder zur Übersicht ..`;

edit_first_write_laptop =
`Die Texteingabe kannst du natürlich auch auf einem Laptop machen.
Danach aktualisierst du einfach den Browser auf deinem Smartphone.`;

overview_written =
`Siehe da: Die 3 Todo-Listen stehen in der Übersicht.
(4) Gehen wir zu Aldi ..`;

todo_list_aldi =
`Du siehst den Namen der Todo-Liste als Überschrift
und die zu erledigenden Punkte.
Die Überschrift bleibt immer sichtbar, auch wenn die Liste zu scrollen ist.`;

todo_list_aldi_clicked =
`Aldi lohnt sich (oder war das Lidl?)
(5) Ein Klick auf "Milch" - abgehakt, durchgestrichen
(6) Ein Klick auf "O-Saft" - abgehakt, durchgestrichen
Siehe da: wenn alle Punkte abgehakt sind, dann ist auch die Überschrift abgehakt.
In diesem Fall durchgestrichen: alles bekommen.
(7) zurück zur Übersicht ..`;

overview_after_aldi_go_rewe =
`Siehe da: Aldi ist abgehakt und nach unten gerückt.
(8) Gehen wir zu REWE! Mal sehen, ob wir alles bekommen ..`;


todo_list_rewe = 'Todo-Liste REWE.';

todo_list_rewe_clicked =
`(9) Ein Klick auf "Gurke -,79" - abgehakt, durchgestrichen

Hm, heute nur Eier aus Bodenhaltung im Regal - das müssen wir woanders kaufen.
(10)(11) Zwei Klicks auf "Eier aus Freilandhaltung" - abgehakt, unterstrichen

(12) Ein Klick auf "Kekse" - abgehakt, durchgestrichen

Siehe da: wenn alle Punkte abgehakt sind, dann ist auch die Überschrift abgehakt.
In diesem Fall unterstrichen: nicht alles bekommen.
(13) zurück zur Übersicht ..`;

overview_after_rewe_go_rewe =
`Siehe da: REWE ist abgehakt und nach unten gerückt, unterstrichen.
(14) Gehen wir noch mal zur Todo-Liste REWE ..`;

todo_list_rewe_delete =
`(15) Todo-Liste löschen (und bestätigen).
Damit geht's wieder zur Übersicht.`;

overview_with_not_found =
`Siehe da:
- REWE taucht nicht mehr auf
- dafür eine neue Todo-Liste namens "?"
(16) Schauen wir uns das in der Text-Eingabe an ..`;


edit_not_found =
`Siehe da:
- Die "Eier aus Freilandhalung" sind unter "@ ?" gelistet.
- Du kannst sie mit Copy & Paste in eine neue Todo-Liste verschieben.
- Die Todo-Liste "REWE" ist leer.
`;
}

const intro = new IntroLiterals();
