{{-- ŞABLON METİN — GDPR uyumu için avukat onayı gerek. --}}
<p class="alert-soft">
    <strong>Note:</strong> Template text. Before publication it must be adapted to the services
    actually in use (hosting, analytics, fonts, maps) and reviewed legally.
</p>

<h2>1. Controller</h2>
<p>
    The controller for data processing on this website is
    {{ setting('firma_unvan', setting('site_adi')) }}, {{ setting('adres') }},
    e-mail: {{ setting('eposta') }}, phone: {{ setting('telefon') }}.
</p>

<h2>2. What data we process</h2>
<ul>
    <li>
        <strong>Contact form:</strong> name, phone number, e-mail address, subject and message
        — in order to answer your enquiry.
    </li>
    <li>
        <strong>&ldquo;Free measuring&rdquo; request:</strong> additionally postcode, town,
        address and preferred date — in order to arrange and carry out the measuring visit.
    </li>
    <li>
        <strong>Server log files:</strong> IP address, time of access, page requested, browser
        type — technically necessary for operating and securing the website.
    </li>
</ul>

<h2>3. Legal basis</h2>
<p>
    Processing of your form data takes place in order to enter into and perform a contract
    (Art. 6(1)(b) GDPR) or on the basis of your consent (Art. 6(1)(a) GDPR). Processing of log
    files is based on our legitimate interest in the secure operation of the website
    (Art. 6(1)(f) GDPR).
</p>

<h2>4. Retention period</h2>
<p>
    We store your data only for as long as is necessary to deal with your enquiry, and beyond
    that within the statutory retention periods. Enquiries that do not result in an order are
    deleted after twelve months at the latest.
</p>

<h2>5. Disclosure to third parties</h2>
<p>
    Your data is passed on only where this is necessary to perform the contract (for example to
    manufacturers for made-to-measure production, or to fitters) or where we are legally
    obliged to do so. We do not sell your data.
</p>

<h2>6. Cookies and external services</h2>
<p>
    This website uses technically necessary cookies (for the session and to protect forms
    against misuse). <strong>Fonts, icons and all other design files are served exclusively
    from our own server</strong> — no Google Fonts, CDNs or other third parties are involved,
    and your IP address is not passed on to anyone.
    Details can be found in our <a href="{{ route('legal', 'cookies') }}">cookie notice</a>.
</p>

<h2>7. Your rights</h2>
<p>
    You have the right of access, rectification, erasure, restriction of processing, data
    portability and objection. You may withdraw any consent given at any time. To do so, please
    contact {{ setting('eposta') }}.
</p>

<h2>8. Right to complain</h2>
<p>
    You may lodge a complaint with a data protection supervisory authority — in the Netherlands
    the Autoriteit Persoonsgegevens, in Germany the state data protection authority responsible
    for your place of residence.
</p>
