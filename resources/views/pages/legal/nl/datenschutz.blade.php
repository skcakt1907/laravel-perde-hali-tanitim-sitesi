{{-- ŞABLON METİN — AVG/GDPR uyumu için avukat onayı gerek. --}}
<p class="alert-soft">
    <strong>Let op:</strong> sjabloontekst. Vóór publicatie moet deze worden aangepast aan de
    daadwerkelijk gebruikte diensten (hosting, statistieken, lettertypen, kaarten) en juridisch
    worden nagekeken.
</p>

<h2>1. Verwerkingsverantwoordelijke</h2>
<p>
    Verwerkingsverantwoordelijke voor de gegevensverwerking op deze website is
    {{ setting('firma_unvan', setting('site_adi')) }}, {{ setting('adres') }},
    e-mail: {{ setting('eposta') }}, telefoon: {{ setting('telefon') }}.
</p>

<h2>2. Welke gegevens wij verwerken</h2>
<ul>
    <li>
        <strong>Contactformulier:</strong> naam, telefoonnummer, e-mailadres, onderwerp en
        bericht — om uw vraag te kunnen beantwoorden.
    </li>
    <li>
        <strong>Aanvraag &bdquo;gratis inmeten&rdquo;:</strong> aanvullend postcode, plaats,
        adres en gewenste datum — om de inmeetafspraak te maken en uit te voeren.
    </li>
    <li>
        <strong>Serverlogbestanden:</strong> IP-adres, tijdstip van het bezoek, opgevraagde
        pagina, browsertype — technisch noodzakelijk voor de werking en de beveiliging van de
        website.
    </li>
</ul>

<h2>3. Grondslag</h2>
<p>
    De verwerking van uw formuliergegevens vindt plaats om een overeenkomst te sluiten en uit te
    voeren (art. 6 lid 1 sub b AVG) of op basis van uw toestemming (art. 6 lid 1 sub a AVG).
    De verwerking van logbestanden is gebaseerd op ons gerechtvaardigd belang bij een veilige
    werking van de website (art. 6 lid 1 sub f AVG).
</p>

<h2>4. Bewaartermijn</h2>
<p>
    Wij bewaren uw gegevens niet langer dan nodig is om uw vraag te behandelen, en daarna binnen
    de wettelijke bewaartermijnen. Aanvragen die niet tot een opdracht leiden, worden uiterlijk
    na twaalf maanden verwijderd.
</p>

<h2>5. Verstrekking aan derden</h2>
<p>
    Uw gegevens worden alleen doorgegeven wanneer dat nodig is voor de uitvoering van de
    overeenkomst (bijvoorbeeld aan fabrikanten voor maatwerkproductie of aan monteurs) of
    wanneer wij daartoe wettelijk verplicht zijn. Wij verkopen uw gegevens niet.
</p>

<h2>6. Cookies en externe diensten</h2>
<p>
    Deze website gebruikt technisch noodzakelijke cookies (voor de sessie en om formulieren
    tegen misbruik te beschermen). <strong>Lettertypen, pictogrammen en alle overige
    ontwerpbestanden worden uitsluitend vanaf onze eigen server geleverd</strong> — er komen
    geen Google Fonts, CDN&rsquo;s of andere derden aan te pas en uw IP-adres wordt aan niemand
    doorgegeven. Meer hierover in onze
    <a href="{{ route('legal', 'cookies') }}">cookieverklaring</a>.
</p>

<h2>7. Uw rechten</h2>
<p>
    U heeft recht op inzage, rectificatie, verwijdering, beperking van de verwerking,
    dataportabiliteit en bezwaar. Gegeven toestemming kunt u op elk moment intrekken. Neem
    hiervoor contact op via {{ setting('eposta') }}.
</p>

<h2>8. Klachtrecht</h2>
<p>
    U kunt een klacht indienen bij een toezichthouder — in Nederland bij de Autoriteit
    Persoonsgegevens, in Duitsland bij de voor uw woonplaats bevoegde deelstaatautoriteit.
</p>
