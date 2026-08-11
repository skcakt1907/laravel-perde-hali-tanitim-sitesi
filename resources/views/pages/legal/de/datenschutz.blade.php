{{-- ŞABLON METİN — DSGVO/AVG uyumu için avukat onayı gerek. --}}
<p class="alert-soft">
    <strong>Hinweis:</strong> Mustertext. Vor Veröffentlichung an die tatsächlich eingesetzten
    Dienste (Hosting, Analyse, Schriftarten, Karten) anzupassen und rechtlich zu prüfen.
</p>

<h2>1. Verantwortlicher</h2>
<p>
    Verantwortlich für die Datenverarbeitung auf dieser Website ist
    {{ setting('firma_unvan', setting('site_adi')) }}, {{ setting('adres') }},
    E-Mail: {{ setting('eposta') }}, Telefon: {{ setting('telefon') }}.
</p>

<h2>2. Welche Daten wir verarbeiten</h2>
<ul>
    <li>
        <strong>Kontaktformular:</strong> Name, Telefonnummer, E-Mail-Adresse, Betreff und
        Nachrichtentext — um Ihre Anfrage zu beantworten.
    </li>
    <li>
        <strong>Anfrage &bdquo;Kostenloses Aufmaß&ldquo;:</strong> zusätzlich Postleitzahl, Ort,
        Adresse sowie Wunschtermin — um den Aufmaßtermin zu vereinbaren und durchzuführen.
    </li>
    <li>
        <strong>Server-Logdateien:</strong> IP-Adresse, Zeitpunkt des Zugriffs, aufgerufene
        Seite, Browsertyp — technisch erforderlich für Betrieb und Sicherheit.
    </li>
</ul>

<h2>3. Rechtsgrundlagen</h2>
<p>
    Die Verarbeitung Ihrer Formulardaten erfolgt zur Anbahnung und Erfüllung eines Vertrags
    (Art. 6 Abs. 1 lit. b DSGVO) bzw. auf Grundlage Ihrer Einwilligung
    (Art. 6 Abs. 1 lit. a DSGVO). Die Verarbeitung von Logdateien beruht auf unserem
    berechtigten Interesse am sicheren Betrieb der Website (Art. 6 Abs. 1 lit. f DSGVO).
</p>

<h2>4. Speicherdauer</h2>
<p>
    Wir speichern Ihre Daten nur so lange, wie es für die Bearbeitung Ihrer Anfrage
    erforderlich ist, sowie darüber hinaus im Rahmen gesetzlicher Aufbewahrungsfristen.
    Anfragen ohne Auftragserteilung löschen wir spätestens nach zwölf Monaten.
</p>

<h2>5. Weitergabe an Dritte</h2>
<p>
    Eine Weitergabe Ihrer Daten erfolgt nur, soweit dies zur Vertragserfüllung erforderlich
    ist (z. B. an Hersteller für die Maßanfertigung oder an Monteure) oder wir gesetzlich
    dazu verpflichtet sind. Ein Verkauf Ihrer Daten findet nicht statt.
</p>

<h2>6. Cookies und externe Dienste</h2>
<p>
    Diese Website setzt technisch notwendige Cookies ein (z. B. für die Sitzung und den
    Schutz von Formularen vor Missbrauch). <strong>Schriftarten, Symbole und alle weiteren
    Gestaltungsdateien werden ausschließlich von unserem eigenen Server geladen</strong> —
    es findet keine Einbindung von Google Fonts, CDNs oder anderen Drittanbietern statt und
    Ihre IP-Adresse wird dabei an niemanden weitergegeben. Details finden Sie in unseren
    <a href="{{ route('legal', 'cookies') }}">Cookie-Hinweisen</a>.
</p>

<h2>7. Ihre Rechte</h2>
<p>
    Sie haben das Recht auf Auskunft, Berichtigung, Löschung, Einschränkung der Verarbeitung,
    Datenübertragbarkeit sowie Widerspruch. Eine erteilte Einwilligung können Sie jederzeit
    widerrufen. Wenden Sie sich dazu an {{ setting('eposta') }}.
</p>

<h2>8. Beschwerderecht</h2>
<p>
    Sie können sich bei einer Datenschutz-Aufsichtsbehörde beschweren — in den Niederlanden
    bei der Autoriteit Persoonsgegevens, in Deutschland bei der für Ihren Wohnort
    zuständigen Landesdatenschutzbehörde.
</p>
