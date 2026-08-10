{{-- ŞABLON METİN — müşteri/avukat onayı ile kesinleştirilmeli. --}}
<p class="alert-soft">
    <strong>Note:</strong> These details are a template and must be completed with the actual
    company data (Chamber of Commerce number, VAT number, authorised representative) and
    reviewed legally before publication.
</p>

<h2>Company details</h2>
<table>
    <tr><td>Company</td><td>{{ setting('firma_unvan', setting('site_adi')) }}</td></tr>
    <tr><td>Owner</td><td>{{ setting('yetkili', '—') }}</td></tr>
    <tr><td>Address</td><td>{{ setting('adres', '—') }}</td></tr>
    <tr><td>Phone</td><td>{{ setting('telefon', '—') }}</td></tr>
    <tr><td>E-mail</td><td>{{ setting('eposta', '—') }}</td></tr>
    <tr><td>Chamber of Commerce (KvK)</td><td>{{ setting('kvk_no', '—') }}</td></tr>
    <tr><td>VAT number (BTW)</td><td>{{ setting('btw_no', '—') }}</td></tr>
</table>

<h2>Responsible for content</h2>
<p>{{ setting('yetkili', setting('site_adi')) }}, address as above.</p>

<h2>Liability for content</h2>
<p>
    The content of these pages has been prepared with the greatest care. We cannot, however,
    guarantee that it is accurate, complete or up to date. All prices shown are starting
    (&ldquo;from&rdquo;) prices and do not constitute a binding offer; the binding price is
    set out in the individual quotation issued after the measuring visit.
</p>

<h2>Liability for links</h2>
<p>
    Our site contains links to external third-party websites over whose content we have no
    control. The respective provider is always responsible for the content of the linked pages.
</p>

<h2>Copyright</h2>
<p>
    The content, texts and images published on this website are protected by copyright.
    Any reproduction or use beyond the limits of copyright law requires written consent.
</p>

<h2>Dispute resolution</h2>
<p>
    We are neither obliged nor willing to take part in dispute resolution proceedings before
    a consumer arbitration board.
</p>
