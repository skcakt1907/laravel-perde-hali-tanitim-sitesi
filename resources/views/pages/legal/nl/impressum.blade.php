{{-- ŞABLON METİN — müşteri/avukat onayı ile kesinleştirilmeli. --}}
<p class="alert-soft">
    <strong>Let op:</strong> deze gegevens zijn een sjabloon en moeten worden aangevuld met de
    werkelijke bedrijfsgegevens (KvK-nummer, BTW-nummer, bevoegd persoon) en juridisch worden
    nagekeken vóór publicatie.
</p>

<h2>Bedrijfsgegevens</h2>
<table>
    <tr><td>Bedrijf</td><td>{{ setting('firma_unvan', setting('site_adi')) }}</td></tr>
    <tr><td>Eigenaar</td><td>{{ setting('yetkili', '—') }}</td></tr>
    <tr><td>Adres</td><td>{{ setting('adres', '—') }}</td></tr>
    <tr><td>Telefoon</td><td>{{ setting('telefon', '—') }}</td></tr>
    <tr><td>E-mail</td><td>{{ setting('eposta', '—') }}</td></tr>
    <tr><td>KvK-nummer</td><td>{{ setting('kvk_no', '—') }}</td></tr>
    <tr><td>BTW-nummer</td><td>{{ setting('btw_no', '—') }}</td></tr>
</table>

<h2>Verantwoordelijk voor de inhoud</h2>
<p>{{ setting('yetkili', setting('site_adi')) }}, adres zoals hierboven vermeld.</p>

<h2>Aansprakelijkheid voor de inhoud</h2>
<p>
    De inhoud van deze pagina&rsquo;s is met de grootst mogelijke zorg samengesteld. Wij kunnen
    echter niet garanderen dat deze juist, volledig en actueel is. Alle vermelde prijzen zijn
    vanafprijzen en vormen geen bindend aanbod; de bindende prijs staat in de individuele
    prijsopgave die u na de inmeetafspraak ontvangt.
</p>

<h2>Aansprakelijkheid voor links</h2>
<p>
    Onze site bevat links naar externe websites van derden waarvan wij de inhoud niet in de hand
    hebben. Voor de inhoud van gelinkte pagina&rsquo;s is altijd de betreffende aanbieder
    verantwoordelijk.
</p>

<h2>Auteursrecht</h2>
<p>
    De op deze website gepubliceerde inhoud, teksten en afbeeldingen zijn auteursrechtelijk
    beschermd. Verveelvoudiging of gebruik buiten de grenzen van het auteursrecht vereist
    schriftelijke toestemming.
</p>

<h2>Geschillenbeslechting</h2>
<p>
    Wij zijn niet verplicht en niet bereid deel te nemen aan een geschillenprocedure bij een
    geschillencommissie voor consumenten.
</p>
