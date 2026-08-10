{{-- ŞABLON METİN — müşteri/avukat onayı ile kesinleştirilmeli. --}}
<p class="alert-soft">
    <strong>Hinweis:</strong> Diese Angaben sind ein Muster und müssen vor der Veröffentlichung
    mit den tatsächlichen Firmendaten (KvK-Nummer, USt-IdNr., vertretungsberechtigte Person)
    vervollständigt und rechtlich geprüft werden.
</p>

<h2>Angaben gemäß § 5 TMG / Art. 3:15d BW</h2>
<table>
    <tr><td>Firma</td><td>{{ setting('firma_unvan', setting('site_adi')) }}</td></tr>
    <tr><td>Inhaber</td><td>{{ setting('yetkili', '—') }}</td></tr>
    <tr><td>Anschrift</td><td>{{ setting('adres', '—') }}</td></tr>
    <tr><td>Telefon</td><td>{{ setting('telefon', '—') }}</td></tr>
    <tr><td>E-Mail</td><td>{{ setting('eposta', '—') }}</td></tr>
    <tr><td>Handelsregister (KvK)</td><td>{{ setting('kvk_no', '—') }}</td></tr>
    <tr><td>USt-IdNr. (BTW)</td><td>{{ setting('btw_no', '—') }}</td></tr>
</table>

<h2>Verantwortlich für den Inhalt</h2>
<p>{{ setting('yetkili', setting('site_adi')) }}, Anschrift wie oben.</p>

<h2>Haftung für Inhalte</h2>
<p>
    Die Inhalte dieser Seiten wurden mit größter Sorgfalt erstellt. Für die Richtigkeit,
    Vollständigkeit und Aktualität der Inhalte können wir jedoch keine Gewähr übernehmen.
    Alle Preisangaben sind Ausgangspreise (&bdquo;ab&ldquo;-Preise) und stellen kein bindendes
    Angebot dar; der verbindliche Preis ergibt sich erst aus dem individuellen Angebot nach
    dem Aufmaß.
</p>

<h2>Haftung für Links</h2>
<p>
    Unser Angebot enthält Links zu externen Websites Dritter, auf deren Inhalte wir keinen
    Einfluss haben. Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter
    verantwortlich.
</p>

<h2>Urheberrecht</h2>
<p>
    Die auf dieser Website veröffentlichten Inhalte, Texte und Bilder unterliegen dem
    Urheberrecht. Eine Vervielfältigung oder Verwendung außerhalb der Grenzen des
    Urheberrechts bedarf der schriftlichen Zustimmung.
</p>

<h2>Streitschlichtung</h2>
<p>
    Zur Teilnahme an einem Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle
    sind wir nicht verpflichtet und nicht bereit.
</p>
