<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * MC Gordijnen — başlangıç içeriği.
 *
 * Metinler Almanca (site ana dili) + Türkçe olarak girilir.
 * Görseller public/img/demo altındaki üretilmiş yer tutuculardır;
 * müşterinin gerçek fotoğrafları geldiğinde panelden değiştirilir.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // GÖRELİ yol: alan adı değişse bile görseller çalışır (bkz. media() yardımcısı)
        $img = fn (string $name) => 'img/demo/' . $name . '.jpg';

        /* ---------------- Yönetici ---------------- */
        $admin = User::updateOrCreate(['email' => 'admin@ornek-perde.nl'], [
            'name'     => 'MC Gordijnen Yönetici',
            'password' => Hash::make('admin123'),
            'phone'    => '+31 6 84 10 46 48',
        ]);
        // role mass-assign edilemez (yetki yükseltme önlemi); sunucu tarafında forceFill ile.
        $admin->forceFill(['role' => 'admin'])->save();

        /* ---------------- Ayarlar ---------------- */
        $ayarlar = [
            'site_adi' => 'MC Gordijnen',

            'site_aciklama' => 'Maßgefertigte Gardinen, Plissees, Rollos, Jalousien und Teppiche. '
                . 'Kostenloses Aufmaß und Beratung bei Ihnen zu Hause, fachgerechte Montage aus einer Hand.',
            'site_aciklama_nl' => 'Gordijnen, plissé- en rolgordijnen, jaloezieën en tapijten op maat. '
                . 'Gratis inmeten en advies bij u thuis, vakkundige montage door één team.',
            'site_aciklama_en' => 'Made-to-measure curtains, pleated blinds, roller blinds, venetian '
                . 'blinds and rugs. Free measuring and advice at your home, expert fitting from one team.',
            'site_aciklama_tr' => 'Ölçüye özel fon perde, plise, stor, jaluzi ve halı. '
                . 'Evinizde ücretsiz ölçü ve danışmanlık, tek elden ustaca montaj.',

            'telefon'   => '+31 6 84 10 46 48',
            'whatsapp'  => '31684104648',
            'eposta'    => 'info@ornek-perde.nl',
            // Adres, KvK ve BTW müşteriden alınacak — boş kaldıkça sitede o bloklar gösterilmez.
            'adres'     => '',
            'instagram' => 'https://www.instagram.com/mc_gordijnen/',
            'facebook'  => '',

            'calisma_saatleri'    => "Mo–Fr 09:00–18:00\nSa 10:00–16:00 (nach Absprache)",
            'calisma_saatleri_nl' => "ma–vr 09:00–18:00\nza 10:00–16:00 (op afspraak)",
            'calisma_saatleri_en' => "Mon–Fri 09:00–18:00\nSat 10:00–16:00 (by appointment)",
            'calisma_saatleri_tr' => "Pzt–Cum 09:00–18:00\nCmt 10:00–16:00 (randevu ile)",

            'hero_gorsel' => 'img/demo/hero.jpg',
            'hero_baslik' => "Maßgefertigte Fensterdekoration\nfür Ihr Zuhause",
            'hero_metin'  => 'Von Plissees und Rollos bis zu Vorhängen und handverlesenen Teppichen: '
                . 'Wir messen kostenlos bei Ihnen aus, beraten Sie in Ruhe und montieren fachgerecht.',
            'hero_baslik_nl' => "Raamdecoratie op maat\nvoor uw woning",
            'hero_metin_nl'  => 'Van plissé- en rolgordijnen tot overgordijnen en zorgvuldig uitgezochte tapijten: '
                . 'wij meten gratis bij u thuis in, nemen de tijd voor advies en monteren alles vakkundig.',
            'hero_baslik_en' => "Made-to-measure window dressing\nfor your home",
            'hero_metin_en'  => 'From pleated and roller blinds to curtains and hand-picked rugs: '
                . 'we measure at your home free of charge, take the time to advise you and fit everything properly.',
            'hero_baslik_tr' => "Eviniz için\nölçüye özel pencere dekorasyonu",
            'hero_metin_tr'  => 'Plise ve stordan fon perdeye, özenle seçilmiş halılara kadar: '
                . 'Ücretsiz yerinde ölçü alıyor, acele etmeden danışmanlık veriyor ve ustaca monte ediyoruz.',

            'istatistik_yil'      => '15',
            'istatistik_pencere'  => '12.000',
            'istatistik_musteri'  => '3.400',
            'istatistik_bolge'    => '100',

            'hakkimizda_gorsel' => 'img/demo/about.jpg',
            'hakkimizda_baslik' => 'Ein Familienbetrieb für Fensterdekoration — mit Maßband und Musterbuch bei Ihnen vor Ort',
            'hakkimizda_metin'  => "MC Gordijnen ist ein Familienbetrieb für Gardinen, Sonnenschutz und Teppiche. "
                . "Wir kommen zu Ihnen nach Hause, messen jedes Fenster selbst aus und bringen Stoffmuster mit — "
                . "denn eine Farbe wirkt im eigenen Wohnzimmer immer anders als im Laden.\n\n"
                . "Wir arbeiten ohne Zwischenhändler: Beratung, Aufmaß, Fertigung und Montage laufen über uns. "
                . "So bleiben die Wege kurz, die Termine verlässlich und der Preis nachvollziehbar. "
                . "Ob eine einzelne Dachschräge oder die komplette Wohnung, ob Privathaushalt, Büro oder Ferienwohnung — "
                . "Sie bekommen dieselbe Sorgfalt.\n\n"
                . "Neben Fensterdekoration führen wir Teppiche und Läufer, auch als Maßanfertigung mit "
                . "eingefasster Kante in der Größe, die Ihr Raum braucht.",
            'hakkimizda_maddeler' => "Kostenloses Aufmaß, keine Anfahrtskosten\nMaßanfertigung auf den Millimeter\n"
                . "Montage durch eigene Monteure\nStoffmuster zum Vergleich vor Ort\n"
                . "Feste Preise im schriftlichen Angebot\nBeratung auf Deutsch, Niederländisch, Englisch und Türkisch",
            'hakkimizda_baslik_nl' => 'Een familiebedrijf voor raamdecoratie — bij u langs met rolmaat en stalenboek',
            'hakkimizda_metin_nl'  => "MC Gordijnen is een familiebedrijf voor gordijnen, zonwering en tapijten. "
                . "Wij komen bij u thuis, meten elk raam zelf op en nemen stofstalen mee — "
                . "want een kleur werkt in uw eigen woonkamer altijd anders dan in de winkel.\n\n"
                . "Wij werken zonder tussenhandel: advies, inmeten, productie en montage lopen via ons. "
                . "Zo blijven de lijnen kort, de afspraken betrouwbaar en de prijs navolgbaar. "
                . "Of het om één dakraam gaat of om de hele woning, om een particulier huis, een kantoor of "
                . "een vakantiewoning — u krijgt dezelfde zorg.\n\n"
                . "Naast raamdecoratie leveren wij tapijten en lopers, ook als maatwerk met een omgezoomde "
                . "rand in precies de maat die uw ruimte nodig heeft.",
            'hakkimizda_maddeler_nl' => "Gratis inmeten, geen voorrijkosten\nMaatwerk tot op de millimeter\n"
                . "Montage door onze eigen monteurs\nStofstalen om ter plaatse te vergelijken\n"
                . "Vaste prijzen in een schriftelijke prijsopgave\nAdvies in het Duits, Nederlands, Engels en Turks",
            'hakkimizda_baslik_en' => 'A family business for window dressing — at your door with tape measure and sample book',
            'hakkimizda_metin_en'  => "MC Gordijnen is a family business for curtains, sun protection and rugs. "
                . "We come to your home, measure every window ourselves and bring fabric samples with us — "
                . "because a colour always looks different in your own living room than it does in a shop.\n\n"
                . "We work without middlemen: advice, measuring, production and fitting all run through us. "
                . "That keeps the process short, the appointments reliable and the price easy to follow. "
                . "Whether it is a single sloping roof window or a whole home, a private house, an office or a "
                . "holiday let — you get the same care.\n\n"
                . "Alongside window dressing we also supply rugs and runners, including made to measure with a "
                . "bound edge in exactly the size your room needs.",
            'hakkimizda_maddeler_en' => "Free measuring, no call-out charge\nMade to measure to the millimetre\n"
                . "Fitted by our own fitters\nFabric samples to compare at your home\n"
                . "Fixed prices in a written quotation\nAdvice in German, Dutch, English and Turkish",
            'hakkimizda_baslik_tr' => 'Pencere dekorasyonunda aile işletmesi — mezura ve numune kitabıyla kapınızda',
            'hakkimizda_metin_tr'  => "MC Gordijnen; perde, güneşlik ve halı işi yapan bir aile işletmesidir. "
                . "Evinize geliyor, her pencereyi kendimiz ölçüyor ve kumaş numunelerini yanımızda getiriyoruz — "
                . "çünkü bir renk, kendi salonunuzda dükkândakinden her zaman farklı görünür.\n\n"
                . "Aracısız çalışıyoruz: danışmanlık, ölçü, üretim ve montaj bizden geçiyor. "
                . "Böylece süreç kısalıyor, randevular güvenilir oluyor ve fiyat anlaşılır kalıyor. "
                . "Tek bir çatı eğimi de olsa, evin tamamı da olsa; konut, ofis ya da tatil evi — "
                . "aynı özeni görüyorsunuz.\n\n"
                . "Pencere dekorasyonunun yanında halı ve yol halısı da bulunduruyoruz; odanızın istediği ölçüde, "
                . "kenarı overloklu ölçüye özel üretim dahil.",
            'hakkimizda_maddeler_tr' => "Ücretsiz ölçü, yol ücreti yok\nMilimetrik ölçüye özel üretim\n"
                . "Kendi montaj ekibimiz\nYerinde karşılaştırmalı kumaş numuneleri\n"
                . "Yazılı teklifte sabit fiyat\nAlmanca, Hollandaca, İngilizce ve Türkçe danışmanlık",

            // Künye/yasal alanlar — müşteriden gelecek
            'firma_unvan' => 'MC Gordijnen',
            'yetkili'     => '',
            'kvk_no'      => '',
            'btw_no'      => '',
            'harita_embed' => '',
        ];
        foreach ($ayarlar as $k => $v) {
            Setting::updateOrCreate(['anahtar' => $k], ['deger' => $v]);
        }
        Setting::flush();

        /* ---------------- Kategoriler ---------------- */
        $catData = [
            [
                'slug' => 'gardinen', 'icon' => 'bi-columns-gap', 'image' => $img('kat-gardinen'),
                'name_nl' => 'Gordijnen & Overgordijnen',
                'description_nl' => 'Overgordijnen, zijpanelen en vitrage met plooiband, ringen of golfrail — van lichte vitrage tot zware verduisterende stof.',
                'name_en' => 'Curtains & Drapes',
                'description_en' => 'Curtains, side panels and voile with pleat tape, eyelets or wave heading — from light voiles to heavy blackout fabrics.',
                'name' => 'Gardinen & Vorhänge', 'name_tr' => 'Fon Perde & Tül',
                'description' => 'Vorhänge, Schals und Tüll in Faltenband, Ösen oder Wellenform — vom leichten Store bis zum schweren Verdunkelungsstoff.',
                'description_tr' => 'Pileli, kuş gözlü ya da dalga formunda fon perde, yan perde ve tül — hafif tülden ağır karartma kumaşına.',
            ],
            [
                'slug' => 'plissees', 'icon' => 'bi-layers', 'image' => $img('kat-plissee'),
                'name_nl' => 'Plisségordijnen',
                'description_nl' => 'De allrounder voor dakramen en lastige vormen: van boven en van onder verstelbaar, ook als isolerend duette-plissé.',
                'name_en' => 'Pleated Blinds',
                'description_en' => 'The all-rounder for roof windows and awkward shapes: adjustable from the top and the bottom, also available as an insulating honeycomb pleated blind.',
                'name' => 'Plissees', 'name_tr' => 'Plise Perde',
                'description' => 'Der Allrounder für Dachfenster und schwierige Formen: von oben und unten verstellbar, auch als Wabenplissee mit Isolierwirkung.',
                'description_tr' => 'Çatı pencereleri ve zor formlar için ideal: alttan ve üstten ayarlanabilir, yalıtım sağlayan petek plise seçeneğiyle.',
            ],
            [
                'slug' => 'rollos', 'icon' => 'bi-window-sidebar', 'image' => $img('kat-rollo'),
                'name_nl' => 'Rolgordijnen',
                'description_nl' => 'Strakke lijnen, veel stofkeuze: rolgordijnen met zijtrek of kettingbediening, duo-rolgordijnen en volledige verduistering voor de slaapkamer.',
                'name_en' => 'Roller Blinds',
                'description_en' => 'Clean lines, a wide choice of fabrics: side-pull and chain-operated roller blinds, double roller blinds and full blackout for the bedroom.',
                'name' => 'Rollos', 'name_tr' => 'Stor Perde',
                'description' => 'Klare Linien, viel Stoffauswahl: Seitenzug- und Kettenzugrollos, Doppelrollos und komplette Verdunkelung fürs Schlafzimmer.',
                'description_tr' => 'Net çizgiler, geniş kumaş seçeneği: zincir mekanizmalı storlar, zebra (çift) storlar ve yatak odası için tam karartma.',
            ],
            [
                'slug' => 'jalousien', 'icon' => 'bi-list', 'image' => $img('kat-jalousien'),
                'name_nl' => 'Jaloezieën',
                'description_nl' => 'Licht doseren in plaats van buitensluiten: houten, bamboe en aluminium lamellen van 25 tot 50 mm, traploos kantelbaar.',
                'name_en' => 'Venetian Blinds',
                'description_en' => 'Control the light instead of shutting it out: wooden, bamboo and aluminium slats from 25 to 50 mm, tilting through any angle.',
                'name' => 'Jalousien', 'name_tr' => 'Jaluzi',
                'description' => 'Licht dosieren statt aussperren: Holz-, Bambus- und Aluminiumlamellen in 25 bis 50 mm, stufenlos kippbar.',
                'description_tr' => 'Işığı kesmek yerine ayarlamak: 25–50 mm ahşap, bambu ve alüminyum lameller, kademesiz açı ayarı.',
            ],
            [
                'slug' => 'lamellenvorhaenge', 'icon' => 'bi-distribute-vertical', 'image' => $img('kat-lamellen'),
                'name_nl' => 'Verticale lamellen',
                'description_nl' => 'Voor brede raampartijen en terrasdeuren: verticale lamellen die kunnen draaien en volledig naar de zijkant schuiven.',
                'name_en' => 'Vertical Blinds',
                'description_en' => 'For wide window fronts and patio doors: vertical slats that rotate and slide completely to one side.',
                'name' => 'Lamellenvorhänge', 'name_tr' => 'Dikey Lamelli Perde',
                'description' => 'Für breite Fensterfronten und Terrassentüren: vertikale Lamellen, die sich drehen und komplett zur Seite schieben lassen.',
                'description_tr' => 'Geniş pencere cepheleri ve teras kapıları için: dönebilen ve tamamen yana toplanabilen dikey lameller.',
            ],
            [
                'slug' => 'teppiche', 'icon' => 'bi-grid-3x3', 'image' => $img('kat-teppiche'),
                'name_nl' => 'Tapijten & Lopers',
                'description_nl' => 'Wol, kelim en laagpolig — van standaardmaten tot tapijt op maat met omgezoomde rand voor trap en hal.',
                'name_en' => 'Rugs & Runners',
                'description_en' => 'Wool, kilim and short pile — from standard sizes to made-to-measure rugs with a bound edge for stairs and hallways.',
                'name' => 'Teppiche & Läufer', 'name_tr' => 'Halı & Yol Halısı',
                'description' => 'Wolle, Kelim und Kurzflor — von der Standardgröße bis zum Maßteppich mit eingefasster Kante für Treppe und Flur.',
                'description_tr' => 'Yün, kilim ve kısa hav — standart ölçüden merdiven ve koridor için kenarı overloklu ölçüye özel halıya.',
            ],
        ];

        $cats = [];
        foreach ($catData as $i => $row) {
            $cats[$row['slug']] = Category::updateOrCreate(
                ['slug' => $row['slug']],
                $row + ['sira' => $i + 1, 'durum' => true]
            );
        }

        /* ---------------- Hizmetler ---------------- */
        $services = [
            [
                'slug' => 'kostenloses-aufmass', 'icon' => 'bi-rulers',
                'title_nl' => 'Gratis inmeten & advies',
                'summary_nl' => 'Wij komen naar u toe, meten elk raam nauwkeurig op en nemen stofstalen mee — gratis en zonder verplichtingen.',
                'content_nl' => "De afspraak duurt, afhankelijk van het aantal ramen, 30 tot 60 minuten. Wij meten breedte, "
                    . "hoogte, diepte van de dagkant en de vensterbank, controleren de montageondergrond en bepalen of "
                    . "montage aan de wand of aan het plafond beter is.\n\n"
                    . "Daarnaast nemen wij stalenboeken mee: u ziet de stoffen in het licht van uw eigen ruimte, kunt "
                    . "de doorzichtigheid vergelijken en kleuren naast wand, vloer en meubels houden.\n\n"
                    . "Aan het einde van de afspraak weet u welke oplossing bij welk raam past. De schriftelijke "
                    . "prijsopgave ontvangt u meestal binnen twee werkdagen. Inmeten, voorrijden en advies zijn gratis, "
                    . "ook als u daarna een andere keuze maakt.",
                'title_en' => "Free measuring & advice",
                'summary_en' => "We come to you, measure every window precisely and bring fabric samples — free of charge and without obligation.",
                'content_en' => "Depending on the number of windows the visit takes 30 to 60 minutes. We measure width, height, recess depth and window sill, check the mounting surface and work out whether wall or ceiling fitting makes more sense.\n\nWe also bring sample books: you see the fabrics in the light of your own room, can compare how transparent they are and hold colours against your walls, floor and furniture.\n\nBy the end of the visit you know which solution suits which window. You normally receive the written quotation within two working days. Measuring, travel and advice are free, even if you decide differently afterwards.",
                'title' => 'Kostenloses Aufmaß & Beratung',
                'title_tr' => 'Ücretsiz ölçü & danışmanlık',
                'summary' => 'Wir kommen zu Ihnen, messen jedes Fenster exakt aus und bringen Stoffmuster mit — kostenlos und unverbindlich.',
                'summary_tr' => 'Adresinize geliyor, her pencereyi milimetrik ölçüyor ve kumaş numuneleri getiriyoruz — ücretsiz ve yükümlülüksüz.',
                'content' => "Der Termin dauert je nach Anzahl der Fenster 30 bis 60 Minuten. Wir messen Breite, Höhe, "
                    . "Nischentiefe und Fensterbank, prüfen den Montagegrund und klären, ob Wand- oder Deckenmontage sinnvoller ist.\n\n"
                    . "Dazu bringen wir Musterbücher mit: Sie sehen die Stoffe im Licht Ihres eigenen Raums, "
                    . "können Transparenzen vergleichen und Farben neben Wand, Boden und Möbeln halten.\n\n"
                    . "Am Ende des Termins wissen Sie, welche Lösung zu welchem Fenster passt. Das schriftliche Angebot "
                    . "erhalten Sie in der Regel innerhalb von zwei Werktagen. Aufmaß, Anfahrt und Beratung sind kostenlos, "
                    . "auch wenn Sie sich danach anders entscheiden.",
                'content_tr' => "Randevu, pencere sayısına göre 30–60 dakika sürer. Genişlik, yükseklik, niş derinliği ve "
                    . "pencere denizliğini ölçüyor, montaj zeminini kontrol ediyor ve duvara mı tavana mı montajın daha doğru olduğunu belirliyoruz.\n\n"
                    . "Yanımızda numune kitapları getiriyoruz: kumaşları kendi odanızın ışığında görüyor, geçirgenlikleri "
                    . "karşılaştırıyor ve renkleri duvar, zemin ve mobilyanın yanında tutabiliyorsunuz.\n\n"
                    . "Randevu sonunda hangi pencereye hangi çözümün uyduğunu biliyorsunuz. Yazılı teklifi genellikle "
                    . "iki iş günü içinde alıyorsunuz. Ölçü, yol ve danışmanlık ücretsizdir — sonrasında başka bir karar verseniz bile.",
            ],
            [
                'slug' => 'massanfertigung', 'icon' => 'bi-scissors',
                'title_nl' => 'Maatwerk',
                'summary_nl' => 'Elk raam is anders. Uw raamdecoratie wordt op basis van onze maten tot op de millimeter gemaakt.',
                'content_nl' => "Confectiematen passen zelden echt: oude panden staan niet haaks, dakschuintes lopen toe, "
                    . "dagkanten zijn boven en onder verschillend breed. Daarom maken wij alles op maat.\n\n"
                    . "Bij gordijnen kiest u de stof, de plooisoort (plooiband, ringen, golfrail) en de zoomafwerking. "
                    . "Bij plissé-, rolgordijnen en jaloezieën bepaalt u de kleur, de lichtdoorlatendheid, de "
                    . "bedieningszijde en de montagewijze. Tapijten snijden wij op uw ruimtemaat en de rand zomen wij om.\n\n"
                    . "De productietijd is doorgaans twee tot vier weken, afhankelijk van de stof.",
                'title_en' => "Made to measure",
                'summary_en' => "Every window is different. Your window dressing is made to our measurements, down to the millimetre.",
                'content_en' => "Off-the-shelf sizes rarely really fit: older buildings are out of square, roof slopes taper, recesses are wider at the top than the bottom. That is why we make everything to measure.\n\nFor curtains you choose the fabric, the heading (pleat tape, eyelets, wave) and the hem. For pleated, roller and venetian blinds you decide the colour, how much light comes through, the operating side and the type of fitting. Rugs are cut to your room size and the edge is bound.\n\nProduction usually takes two to four weeks, depending on the fabric.",
                'title' => 'Maßanfertigung',
                'title_tr' => 'Ölçüye özel üretim',
                'summary' => 'Jedes Fenster ist anders. Ihre Dekoration wird nach unseren Maßen auf den Millimeter gefertigt.',
                'summary_tr' => 'Her pencere farklıdır. Dekorasyonunuz aldığımız ölçülere göre milimetre hassasiyetinde üretilir.',
                'content' => "Konfektionsware passt selten wirklich: Altbauten sind schief, Dachschrägen laufen zusammen, "
                    . "Nischen sind oben und unten unterschiedlich breit. Deshalb fertigen wir nach Maß.\n\n"
                    . "Bei Vorhängen wählen Sie Stoff, Faltenart (Faltenband, Ösen, Wellenband) und Saumausführung. "
                    . "Bei Plissees, Rollos und Jalousien bestimmen Sie Farbe, Lichtdurchlässigkeit, Bedienseite und Montageart. "
                    . "Teppiche schneiden wir auf Ihr Raummaß und fassen die Kante ein.\n\n"
                    . "Die Fertigungszeit liegt üblicherweise bei zwei bis vier Wochen, abhängig vom Stoff.",
                'content_tr' => "Hazır ürün nadiren gerçekten oturur: eski binalar şaşıdır, çatı eğimleri daralır, "
                    . "nişler üstte ve altta farklı genişliktedir. Bu yüzden ölçüye özel üretiyoruz.\n\n"
                    . "Fon perdede kumaşı, pile tipini (pile şeridi, kuş gözü, dalga bandı) ve etek dikişini siz seçiyorsunuz. "
                    . "Plise, stor ve jaluzide rengi, ışık geçirgenliğini, kumanda yönünü ve montaj biçimini belirliyorsunuz. "
                    . "Halıları oda ölçünüze göre kesip kenarını overlokluyoruz.\n\n"
                    . "Üretim süresi kumaşa bağlı olarak genellikle iki ile dört hafta arasındadır.",
            ],
            [
                'slug' => 'montage', 'icon' => 'bi-tools',
                'title_nl' => 'Levering & montage',
                'summary_nl' => 'Eigen monteurs, afgesproken tijden, een schone werkplek — inclusief fijnafstelling ter plaatse.',
                'content_nl' => "De montage wordt gedaan door onze eigen monteurs, niet door steeds wisselende "
                    . "onderaannemers. U krijgt een vaste afspraak met een tijdvak.\n\n"
                    . "Wij nemen gereedschap, montagemateriaal en een stofzuiger mee, beschermen vloer en meubels en "
                    . "voeren verpakking en boorstof weer af. Na de montage stellen wij trekkoorden, kettinglengtes en "
                    . "de plooival bij en laten wij u de bediening zien.\n\n"
                    . "Oude gordijnrails en rolgordijnen demonteren en voeren wij op verzoek meteen af.",
                'title_en' => "Delivery & fitting",
                'summary_en' => "Our own fitters, agreed appointments, a clean workplace — including fine adjustment on site.",
                'content_en' => "Fitting is done by our own fitters, not by changing subcontractors. You get a fixed appointment with a time slot.\n\nWe bring tools, fixings and a vacuum cleaner, protect your floor and furniture and take the packaging and drilling dust away with us. After fitting we adjust cords, chain lengths and the hang of the pleats and show you how everything works.\n\nOn request we also remove and dispose of your old curtain tracks and blinds at the same time.",
                'title' => 'Lieferung & Montage',
                'title_tr' => 'Teslimat & montaj',
                'summary' => 'Eigene Monteure, vereinbarte Termine, sauberer Arbeitsplatz — inklusive Feinjustierung vor Ort.',
                'summary_tr' => 'Kendi montaj ekibimiz, kararlaştırılan randevular, temiz çalışma — yerinde ince ayar dahil.',
                'content' => "Montiert wird von unseren eigenen Monteuren, nicht von wechselnden Subunternehmern. "
                    . "Sie bekommen einen festen Termin mit Zeitfenster.\n\n"
                    . "Wir bringen Werkzeug, Befestigungsmaterial und Sauger mit, schützen Boden und Möbel und nehmen "
                    . "Verpackung sowie Bohrstaub wieder mit. Nach der Montage justieren wir Zugschnüre, Kettenlängen "
                    . "und Faltenwurf und zeigen Ihnen die Bedienung.\n\n"
                    . "Alte Vorhangschienen und Rollos demontieren und entsorgen wir auf Wunsch gleich mit.",
                'content_tr' => "Montajı, değişen taşeronlar değil kendi ekibimiz yapar. Zaman aralığı belirtilen sabit bir randevu alırsınız.\n\n"
                    . "Alet, montaj malzemesi ve süpürge getiriyor; zemini ve mobilyayı koruyor; ambalaj ile matkap tozunu "
                    . "geri götürüyoruz. Montajdan sonra çekme kordonlarını, zincir boylarını ve pile dökümünü ayarlıyor, "
                    . "kullanımı size gösteriyoruz.\n\n"
                    . "İsterseniz eski perde raylarını ve storları sökme ve bertaraf işini de aynı gün yapıyoruz.",
            ],
            [
                'slug' => 'sonnen-und-hitzeschutz', 'icon' => 'bi-brightness-high',
                'title_nl' => 'Zon-, warmte- & inkijkwering',
                'summary_nl' => 'Warme zolderkamers, spiegeling op beeldschermen, inkijk vanaf de straat — voor elk daarvan is er een eigen oplossing.',
                'content_nl' => "Warmte houdt u het beste buiten voordat die door het glas komt: duette-plissés met een "
                    . "reflecterende achterzijde en lichte rolgordijnen met warmtewerende coating verlagen de "
                    . "kamertemperatuur merkbaar.\n\n"
                    . "Tegen spiegeling op de werkplek zijn jaloezieën of verticale lamellen in het voordeel, omdat zij "
                    . "het licht naar boven richten in plaats van het te blokkeren.\n\n"
                    . "Voor inkijkwering zonder donkerte zijn halftransparante stoffen en duo-rolgordijnen geschikt: "
                    . "'s overdag inkijkwering bij daglicht, 's avonds volledig dicht.",
                'title_en' => "Sun, heat & privacy protection",
                'summary_en' => "Overheated loft rooms, glare on screens, being overlooked from the street — there is a different solution for each.",
                'content_en' => "Heat is best kept out before it comes through the glass: honeycomb pleated blinds with a reflective backing and light-coloured roller blinds with a heat-protection coating noticeably reduce the room temperature.\n\nAgainst glare at a desk, venetian or vertical blinds have the advantage because they direct light upwards instead of blocking it.\n\nFor privacy without darkness, semi-transparent fabrics and double roller blinds work well: screening in daylight during the day, closed in the evening.",
                'title' => 'Sonnen-, Hitze- & Sichtschutz',
                'title_tr' => 'Güneş, ısı ve mahremiyet koruması',
                'summary' => 'Aufgeheizte Dachzimmer, blendende Bildschirme, Einblick von der Straße — dafür gibt es je eine passende Lösung.',
                'summary_tr' => 'Isınan çatı odaları, ekranı yakan parlama, sokaktan içeriyi görme — her biri için ayrı bir çözüm var.',
                'content' => "Wärme hält man am besten draußen, bevor sie durchs Glas kommt: Wabenplissees mit "
                    . "reflektierender Rückseite und helle Rollos mit Hitzeschutzbeschichtung senken die Raumtemperatur deutlich.\n\n"
                    . "Gegen Blendung am Arbeitsplatz sind Jalousien oder Lamellenvorhänge im Vorteil, weil sie Licht "
                    . "nach oben lenken, statt es zu blockieren.\n\n"
                    . "Für Sichtschutz ohne Dunkelheit eignen sich halbtransparente Stoffe und Doppelrollos: tagsüber "
                    . "Blickschutz bei Tageslicht, abends geschlossen.",
                'content_tr' => "Isıyı en iyi, camdan içeri girmeden dışarıda tutmak gerekir: arkası yansıtıcı petek pliseler "
                    . "ve ısı koruma kaplamalı açık renk storlar oda sıcaklığını belirgin biçimde düşürür.\n\n"
                    . "Çalışma alanındaki parlamaya karşı jaluzi ve dikey lamelli perdeler avantajlıdır; çünkü ışığı "
                    . "engellemek yerine yukarı yönlendirirler.\n\n"
                    . "Karanlık olmadan mahremiyet için yarı şeffaf kumaşlar ve zebra storlar uygundur: gündüz gün ışığında "
                    . "görüş engeli, akşam tam kapanma.",
            ],
            [
                'slug' => 'reinigung-und-aenderung', 'icon' => 'bi-arrow-repeat',
                'title_nl' => 'Vermaken & reinigen',
                'summary_nl' => 'Verhuisd of gordijn te lang? Wij korten in, veranderen de ophanging en reinigen uw stoffen.',
                'content_nl' => "Na een verhuizing passen gordijnen bijna nooit op de nieuwe ramen. Vaak is het de moeite "
                    . "waard bestaande stoffen in te korten of naar een andere ophanging om te werken in plaats van nieuw "
                    . "te kopen.\n\n"
                    . "Wij korten gordijnen in breedte en hoogte in, vervangen plooiband door ringen of golfrail en "
                    . "vervangen defecte rolgordijnmechanieken en jaloezieladders.\n\n"
                    . "Ook het afhalen, het vakkundig reinigen en het weer ophangen nemen wij op ons — u hoeft niets te "
                    . "sjouwen.",
                'title_en' => "Alterations & cleaning",
                'summary_en' => "Moved house or curtains too long? We shorten them, change the heading and clean your fabrics.",
                'content_en' => "After a move, curtains almost never fit the new windows. It is often worth shortening existing fabrics or reworking them onto a different heading rather than buying new.\n\nWe shorten curtains in width and height, swap pleat tape for eyelets or wave heading and replace faulty roller blind mechanisms and venetian blind ladders.\n\nWe also take them down, clean them properly and hang them back up — you do not have to carry anything.",
                'title' => 'Änderung & Reinigung',
                'title_tr' => 'Tadilat & temizlik',
                'summary' => 'Umgezogen oder Vorhang zu lang? Wir kürzen, ändern die Aufhängung und reinigen Ihre Stoffe.',
                'summary_tr' => 'Taşındınız ya da perde uzun mu geldi? Kısaltıyor, askı sistemini değiştiriyor ve kumaşlarınızı temizliyoruz.',
                'content' => "Nach einem Umzug passen Vorhänge fast nie auf die neuen Fenster. Häufig lohnt es sich, "
                    . "vorhandene Stoffe zu kürzen oder auf eine andere Aufhängung umzuarbeiten, statt neu zu kaufen.\n\n"
                    . "Wir kürzen Vorhänge in Breite und Höhe, tauschen Faltenband gegen Ösen oder Wellenband, "
                    . "ersetzen defekte Rollo-Mechaniken und Jalousie-Leitern.\n\n"
                    . "Dazu übernehmen wir das Abhängen, die fachgerechte Reinigung und das Wiederaufhängen — Sie müssen "
                    . "nichts schleppen.",
                'content_tr' => "Taşınma sonrası perdeler yeni pencerelere neredeyse hiç uymaz. Sıklıkla yenisini almak yerine "
                    . "mevcut kumaşı kısaltmak ya da başka bir askı sistemine dönüştürmek daha mantıklıdır.\n\n"
                    . "Fon perdeleri hem enden hem boydan kısaltıyor, pile şeridini kuş gözü veya dalga bandıyla değiştiriyor, "
                    . "arızalı stor mekanizmalarını ve jaluzi merdivenlerini yeniliyoruz.\n\n"
                    . "Sökme, uygun yöntemle temizleme ve tekrar takma işini de biz yapıyoruz — hiçbir şey taşımanız gerekmiyor.",
            ],
            [
                'slug' => 'objekte-und-gewerbe', 'icon' => 'bi-building',
                'title_nl' => 'Kantoor, praktijk & projecten',
                'summary_nl' => 'Meerdere ruimtes, één aanspreekpunt: lichtwering, akoestiek en brandvertragende stoffen voor bedrijfsruimtes.',
                'content_nl' => "Voor kantoren, praktijken, vakantiewoningen en horeca plannen wij complete ruimtes: één "
                    . "consequent beeld over alle ramen, plus de technische eisen.\n\n"
                    . "Op verzoek leveren wij brandvertragende stoffen volgens norm, akoestisch werkende gordijnen voor "
                    . "galmende ruimtes en lichtwering volgens de arbo-richtlijnen.\n\n"
                    . "De montage kunnen wij buiten uw openingstijden uitvoeren, zodat uw bedrijf door kan blijven lopen.",
                'title_en' => "Office, practice & commercial",
                'summary_en' => "Several rooms, one contact: glare protection, acoustics and flame-retardant fabrics for commercial spaces.",
                'content_en' => "For offices, medical practices, holiday lets and hospitality we plan complete spaces: a consistent look across all windows, plus the technical requirements.\n\nOn request we supply flame-retardant fabrics to standard, acoustically effective curtains for echoing rooms and glare protection in line with workplace guidelines.\n\nWe can carry out the fitting outside your opening hours so that your business keeps running.",
                'title' => 'Büro, Praxis & Objekt',
                'title_tr' => 'Ofis, klinik & proje işleri',
                'summary' => 'Mehrere Räume, ein Ansprechpartner: Blendschutz, Akustik und Brandschutzstoffe für Gewerbeflächen.',
                'summary_tr' => 'Birden çok mekân, tek muhatap: ticari alanlar için parlama önleme, akustik ve yanmaz kumaşlar.',
                'content' => "Für Büros, Praxen, Ferienwohnungen und Gastronomie planen wir komplette Flächen: "
                    . "einheitliche Optik über alle Fenster, dazu die technischen Anforderungen.\n\n"
                    . "Auf Wunsch liefern wir schwer entflammbare Stoffe nach Norm, akustisch wirksame Vorhänge für "
                    . "hallende Räume sowie Blendschutz nach Arbeitsplatzrichtlinien.\n\n"
                    . "Montage können wir außerhalb Ihrer Öffnungszeiten durchführen, damit der Betrieb weiterläuft.",
                'content_tr' => "Ofis, klinik, tatil evi ve restoranlar için alanın tamamını planlıyoruz: tüm pencerelerde "
                    . "tek bir görünüm ve buna ek olarak teknik gereklilikler.\n\n"
                    . "İstek üzerine norma uygun güç tutuşur kumaşlar, yankılı mekânlar için akustik etkili perdeler ve "
                    . "çalışma alanı yönergelerine uygun parlama koruması sağlıyoruz.\n\n"
                    . "İşletmeniz aksamasın diye montajı çalışma saatleriniz dışında yapabiliyoruz.",
            ],
        ];
        foreach ($services as $i => $row) {
            Service::updateOrCreate(['slug' => $row['slug']], $row + ['sira' => $i + 1, 'durum' => true]);
        }

        /* ---------------- Ürünler ---------------- */
        /* Özellik tablosu terim sözlüğü — Almanca anahtar/değerin karşılıkları.
           Yeni bir özellik yazarsanız karşılığını buraya ekleyin; eşleşme yoksa
           Almancası olduğu gibi kalır (site kırılmaz, sadece o satır çevrilmez). */
        $attrTerms = [
            'nl' => [
                // anahtarlar
                'Material' => 'Materiaal',
                'Lichtdurchlässigkeit' => 'Lichtdoorlatendheid',
                'Aufhängung' => 'Ophanging',
                'Montage' => 'Montage',
                'Pflege' => 'Onderhoud',
                'Extra' => 'Extra',
                'Bedienung' => 'Bediening',
                'Sonderformen' => 'Bijzondere vormen',
                'Lamellenbreite' => 'Lamelbreedte',
                'Hinweis' => 'Let op',
                'Florhöhe' => 'Poolhoogte',
                'Größen' => 'Maten',
                'Breiten' => 'Breedtes',
                'Länge' => 'Lengte',
                // değerler
                '70% Polyester, 30% Leinen' => '70% polyester, 30% linnen',
                '100% Polyester' => '100% polyester',
                '100% Polyester, 3-lagig' => '100% polyester, 3-laags',
                '100% Polyester, gestreift' => '100% polyester, gestreept',
                '100% Schurwolle' => '100% scheerwol',
                'Polyester, plissiert 20 mm' => 'Polyester, plissé 20 mm',
                'Polyester mit Verdunkelungsbeschichtung' => 'Polyester met verduisterende coating',
                'Polyester' => 'Polyester',
                'Wabenstruktur, Rückseite reflektierend' => 'Honingraatstructuur, reflecterende achterzijde',
                'Echtholz (Basswood), lackiert' => 'Echt hout (basswood), gelakt',
                'Aluminium' => 'Aluminium',
                'Wolle-Baumwoll-Mischung' => 'Wol-katoenmengsel',
                'Polypropylen-Wolle-Mischung' => 'Polypropyleen-wolmengsel',
                'halbtransparent' => 'halftransparant',
                'blickdicht' => 'niet doorschijnend',
                'verdunkelnd' => 'verduisterend',
                'transparent' => 'transparant',
                'lichtdurchlässig' => 'lichtdoorlatend',
                'halbtransparent bis verdunkelnd' => 'halftransparant tot verduisterend',
                'stufenlos regelbar' => 'traploos regelbaar',
                'Faltenband oder Wellenband' => 'plooiband of golfband',
                'Ösen, Faltenband, Wellenband' => 'ringen, plooiband, golfband',
                'Schiene oder Stange' => 'rails of roede',
                'Wand oder Decke' => 'wand of plafond',
                'Wand, Decke oder Klemmträger' => 'wand, plafond of klemsteunen',
                'Wand, Decke, Nische oder Klemmträger' => 'wand, plafond, dagkant of klemsteunen',
                'Bohren oder Klemmträger' => 'boren of klemsteunen',
                'Wand, Decke oder Nische' => 'wand, plafond of dagkant',
                '30° Feinwäsche' => 'fijne was op 30°',
                'thermisch und schalldämpfend' => 'isolerend en geluiddempend',
                'Hitze- und Kälteschutz' => 'warmte- en koudewering',
                'Griffbedienung oder Kordel' => 'greep- of koordbediening',
                'Griff, Kordel oder Motor' => 'greep, koord of motor',
                'Kettenzug links oder rechts' => 'ketting links of rechts',
                'Kettenzug oder Motor' => 'kettingbediening of motor',
                'Kettenzug' => 'kettingbediening',
                'Wendestab und Zugschnur' => 'kantelstok en trekkoord',
                'Kette und Schnur oder Motor' => 'ketting en koord of motor',
                'Dachschräge, Trapez, Dreieck' => 'dakschuinte, trapezium, driehoek',
                'auch mit seitlicher Führungsschiene' => 'ook met zijgeleiding leverbaar',
                'nicht für Feuchträume' => 'niet geschikt voor vochtige ruimtes',
                'für Bad und Küche geeignet' => 'geschikt voor bad en keuken',
                'auch für schräge Fensterfronten' => 'ook voor schuine raampartijen',
                'für Fußbodenheizung geeignet' => 'geschikt voor vloerverwarming',
                'schalldämpfend' => 'geluiddempend',
                'auch für Treppenstufen' => 'ook voor traptreden',
                'flach gewebt' => 'vlakgeweven',
                'ca. 12 mm' => 'ca. 12 mm',
                'ca. 8 mm' => 'ca. 8 mm',
                'saugen, professionell reinigen' => 'stofzuigen, professioneel laten reinigen',
                'Standardmaße und Maßanfertigung' => 'standaardmaten en maatwerk',
                'nach Maß, Kante eingefasst' => 'op maat, rand omgezoomd',
                '25 mm' => '25 mm',
                '50 mm' => '50 mm',
                '89 oder 127 mm' => '89 of 127 mm',
                '120×180, 160×230, 200×290 cm' => '120×180, 160×230, 200×290 cm',
                '67, 80, 100 cm' => '67, 80, 100 cm',
            ],
            'en' => [
                // anahtarlar
                'Material' => 'Material',
                'Lichtdurchlässigkeit' => 'Light transmission',
                'Aufhängung' => 'Heading',
                'Montage' => 'Fitting',
                'Pflege' => 'Care',
                'Extra' => 'Extra',
                'Bedienung' => 'Operation',
                'Sonderformen' => 'Special shapes',
                'Lamellenbreite' => 'Slat width',
                'Hinweis' => 'Note',
                'Florhöhe' => 'Pile height',
                'Größen' => 'Sizes',
                'Breiten' => 'Widths',
                'Länge' => 'Length',
                // değerler
                '70% Polyester, 30% Leinen' => '70% polyester, 30% linen',
                '100% Polyester' => '100% polyester',
                '100% Polyester, 3-lagig' => '100% polyester, 3 layers',
                '100% Polyester, gestreift' => '100% polyester, striped',
                '100% Schurwolle' => '100% pure new wool',
                'Polyester, plissiert 20 mm' => 'Polyester, 20 mm pleats',
                'Polyester mit Verdunkelungsbeschichtung' => 'Polyester with blackout coating',
                'Polyester' => 'Polyester',
                'Wabenstruktur, Rückseite reflektierend' => 'Honeycomb structure, reflective backing',
                'Echtholz (Basswood), lackiert' => 'Solid wood (basswood), lacquered',
                'Aluminium' => 'Aluminium',
                'Wolle-Baumwoll-Mischung' => 'Wool and cotton blend',
                'Polypropylen-Wolle-Mischung' => 'Polypropylene and wool blend',
                'halbtransparent' => 'semi-transparent',
                'blickdicht' => 'opaque',
                'verdunkelnd' => 'blackout',
                'transparent' => 'transparent',
                'lichtdurchlässig' => 'light-filtering',
                'halbtransparent bis verdunkelnd' => 'semi-transparent to blackout',
                'stufenlos regelbar' => 'infinitely adjustable',
                'Faltenband oder Wellenband' => 'pleat tape or wave heading',
                'Ösen, Faltenband, Wellenband' => 'eyelets, pleat tape or wave heading',
                'Schiene oder Stange' => 'track or pole',
                'Wand oder Decke' => 'wall or ceiling',
                'Wand, Decke oder Klemmträger' => 'wall, ceiling or clamp brackets',
                'Wand, Decke, Nische oder Klemmträger' => 'wall, ceiling, recess or clamp brackets',
                'Bohren oder Klemmträger' => 'drilling or clamp brackets',
                'Wand, Decke oder Nische' => 'wall, ceiling or recess',
                '30° Feinwäsche' => 'delicate wash at 30°',
                'thermisch und schalldämpfend' => 'thermal and sound-absorbing',
                'Hitze- und Kälteschutz' => 'heat and cold protection',
                'Griffbedienung oder Kordel' => 'handle or cord',
                'Griff, Kordel oder Motor' => 'handle, cord or motor',
                'Kettenzug links oder rechts' => 'chain on the left or right',
                'Kettenzug oder Motor' => 'chain or motor',
                'Kettenzug' => 'chain operation',
                'Wendestab und Zugschnur' => 'tilt wand and pull cord',
                'Kette und Schnur oder Motor' => 'chain and cord, or motor',
                'Dachschräge, Trapez, Dreieck' => 'sloping, trapezoid and triangular windows',
                'auch mit seitlicher Führungsschiene' => 'also available with side guide rails',
                'nicht für Feuchträume' => 'not suitable for damp rooms',
                'für Bad und Küche geeignet' => 'suitable for bathrooms and kitchens',
                'auch für schräge Fensterfronten' => 'also for angled window fronts',
                'für Fußbodenheizung geeignet' => 'suitable for underfloor heating',
                'schalldämpfend' => 'sound-absorbing',
                'auch für Treppenstufen' => 'also for stair treads',
                'flach gewebt' => 'flat woven',
                'ca. 12 mm' => 'approx. 12 mm',
                'ca. 8 mm' => 'approx. 8 mm',
                'saugen, professionell reinigen' => 'vacuum, clean professionally',
                'Standardmaße und Maßanfertigung' => 'standard sizes and made to measure',
                'nach Maß, Kante eingefasst' => 'made to measure, edge bound',
                '25 mm' => '25 mm',
                '50 mm' => '50 mm',
                '89 oder 127 mm' => '89 or 127 mm',
                '120×180, 160×230, 200×290 cm' => '120×180, 160×230, 200×290 cm',
                '67, 80, 100 cm' => '67, 80, 100 cm',
            ],
            'tr' => [
                'Material' => 'Malzeme',
                'Lichtdurchlässigkeit' => 'Işık geçirgenliği',
                'Aufhängung' => 'Askı sistemi',
                'Montage' => 'Montaj',
                'Pflege' => 'Bakım',
                'Extra' => 'Ek özellik',
                'Bedienung' => 'Kumanda',
                'Sonderformen' => 'Özel formlar',
                'Lamellenbreite' => 'Lamel genişliği',
                'Hinweis' => 'Not',
                'Florhöhe' => 'Hav yüksekliği',
                'Größen' => 'Ölçüler',
                'Breiten' => 'Genişlikler',
                'Länge' => 'Boy',
                '70% Polyester, 30% Leinen' => '%70 polyester, %30 keten',
                '100% Polyester' => '%100 polyester',
                '100% Polyester, 3-lagig' => '%100 polyester, 3 katmanlı',
                '100% Polyester, gestreift' => '%100 polyester, çizgili',
                '100% Schurwolle' => '%100 saf yün',
                'Polyester, plissiert 20 mm' => 'Polyester, 20 mm plise',
                'Polyester mit Verdunkelungsbeschichtung' => 'Karartma kaplamalı polyester',
                'Polyester' => 'Polyester',
                'Wabenstruktur, Rückseite reflektierend' => 'Petek yapı, arkası yansıtıcı',
                'Echtholz (Basswood), lackiert' => 'Gerçek ahşap (ıhlamur), lakeli',
                'Aluminium' => 'Alüminyum',
                'Wolle-Baumwoll-Mischung' => 'Yün-pamuk karışımı',
                'Polypropylen-Wolle-Mischung' => 'Polipropilen-yün karışımı',
                'halbtransparent' => 'yarı şeffaf',
                'blickdicht' => 'ışık geçirmez',
                'verdunkelnd' => 'karartma',
                'transparent' => 'şeffaf',
                'lichtdurchlässig' => 'ışık geçirgen',
                'halbtransparent bis verdunkelnd' => 'yarı şeffaftan karartmaya',
                'stufenlos regelbar' => 'kademesiz ayarlanabilir',
                'Faltenband oder Wellenband' => 'pile şeridi veya dalga bandı',
                'Ösen, Faltenband, Wellenband' => 'kuş gözü, pile şeridi, dalga bandı',
                'Schiene oder Stange' => 'ray veya boru',
                'Wand oder Decke' => 'duvar veya tavan',
                'Wand, Decke oder Klemmträger' => 'duvar, tavan veya kıskaçlı aparat',
                'Wand, Decke, Nische oder Klemmträger' => 'duvar, tavan, niş veya kıskaçlı aparat',
                'Bohren oder Klemmträger' => 'vidalı veya kıskaçlı aparat',
                'Wand, Decke oder Nische' => 'duvar, tavan veya niş',
                '30° Feinwäsche' => '30° hassas yıkama',
                'thermisch und schalldämpfend' => 'ısı yalıtımlı ve ses yumuşatıcı',
                'Hitze- und Kälteschutz' => 'ısı ve soğuk yalıtımı',
                'Griffbedienung oder Kordel' => 'tutamak veya kordon',
                'Griff, Kordel oder Motor' => 'tutamak, kordon veya motor',
                'Kettenzug links oder rechts' => 'zincir sağda veya solda',
                'Kettenzug oder Motor' => 'zincir veya motor',
                'Kettenzug' => 'zincir mekanizma',
                'Wendestab und Zugschnur' => 'çevirme çubuğu ve çekme kordonu',
                'Kette und Schnur oder Motor' => 'zincir ve kordon veya motor',
                'Dachschräge, Trapez, Dreieck' => 'çatı eğimi, trapez, üçgen',
                'auch mit seitlicher Führungsschiene' => 'yan kılavuz raylı seçenek de var',
                'nicht für Feuchträume' => 'ıslak hacimler için uygun değil',
                'für Bad und Küche geeignet' => 'banyo ve mutfağa uygun',
                'auch für schräge Fensterfronten' => 'eğimli pencere cepheleri için de',
                'für Fußbodenheizung geeignet' => 'yerden ısıtmaya uygun',
                'schalldämpfend' => 'ses yumuşatıcı',
                'auch für Treppenstufen' => 'merdiven basamakları için de',
                'flach gewebt' => 'düz dokuma',
                'ca. 12 mm' => 'yaklaşık 12 mm',
                'ca. 8 mm' => 'yaklaşık 8 mm',
                'saugen, professionell reinigen' => 'süpürün, profesyonel temizletin',
                'Standardmaße und Maßanfertigung' => 'standart ölçüler ve ölçüye özel',
                'nach Maß, Kante eingefasst' => 'ölçüye özel, kenarı overloklu',
                '25 mm' => '25 mm',
                '50 mm' => '50 mm',
                '89 oder 127 mm' => '89 veya 127 mm',
                '120×180, 160×230, 200×290 cm' => '120×180, 160×230, 200×290 cm',
                '67, 80, 100 cm' => '67, 80, 100 cm',
            ],
        ];

        /** Almanca özellik dizisini hedef dile çevirir; karşılığı olmayan aynen kalır. */
        $ceviriAttr = function (array $attrs, string $locale) use ($attrTerms): array {
            $sozluk = $attrTerms[$locale] ?? [];
            $out = [];

            foreach ($attrs as $k => $v) {
                $out[$sozluk[$k] ?? $k] = $sozluk[$v] ?? $v;
            }

            return $out;
        };

        /* Ürünlerin Hollandacası — slug'a göre [ad, kısa açıklama] */
        $productNl = [
            'vorhang-leinenoptik-creme' => ['Gordijn linnenlook, crème',
                'Mooie val in een licht gestructureerde stof in warm crème — de klassieker voor de woonkamer.'],
            'vorhang-blickdicht-greige' => ['Gordijn niet doorschijnend, greige',
                'Dichte decoratiestof in greige: houdt inkijk buiten, maar laat nog daglicht door.'],
            'verdunkelungsvorhang-anthrazit' => ['Verduisterend gordijn, antraciet',
                'Drielaagse verduisterende stof voor de slaapkamer — donker, zwaar en geluiddempend.'],
            'store-tuell-goldschimmer' => ['Vitrage met goudglans',
                'Fijne vitrage met een subtiele gouddraad — filtert het licht zacht zonder de kamer te verduisteren.'],
            'plissee-weiss-lichtdurchlaessig' => ['Plisségordijn wit, lichtdoorlatend',
                'Van boven en van onder verstelbaar — het antwoord voor dakramen en badkamers.'],
            'wabenplissee-sand-thermo' => ['Duette-plissé zand, isolerend',
                'Dubbele honingraatstructuur met luchtkussen: houdt in de zomer de warmte buiten en in de winter binnen.'],
            'seitenzugrollo-creme' => ['Rolgordijn met kettingbediening, crème',
                'Een eenvoudig rolgordijn met kettingbediening in warm crème — de voordeligste maatwerkoplossing.'],
            'verdunkelungsrollo-anthrazit' => ['Verduisterend rolgordijn, antraciet',
                'Gecoate stof die vrijwel al het licht buitensluit — voor slaap- en kinderkamers.'],
            'doppelrollo-greige' => ['Duo-rolgordijn, greige',
                'Twee lagen met transparante en dichte banen: regel de lichtinval in elke stand.'],
            'holzjalousie-50mm-natur' => ['Houten jaloezie 50 mm, naturel',
                'Echt houten lamellen met ladderband — warm, hoogwaardig en traploos kantelbaar.'],
            'aluminiumjalousie-25mm' => ['Aluminium jaloezie 25 mm',
                'Smalle aluminium lamellen: slank, geschikt voor vochtige ruimtes en ideaal tegen spiegeling op beeldschermen.'],
            'lamellenvorhang-127mm-weiss' => ['Verticale lamellen 127 mm, wit',
                'Voor brede raampartijen en terrasdeuren: draaiende lamellen die volledig naar de zijkant schuiven.'],
            'kelim-teppich-terra' => ['Kelimtapijt, terracotta',
                'Vlakgeweven kelim in terracotta, antraciet en goud — slijtvast en geschikt voor vloerverwarming.'],
            'wollteppich-natur-kurzflor' => ['Wollen tapijt naturel, laagpolig',
                'Handgeweven scheerwol in natuurtinten: warm onder de voet en dempt contactgeluid.'],
            'laeufer-vintage-mass' => ['Vintage loper, op maat',
                'Hal en trap op maat: uw eigen lengte, omgezoomde rand, gedempte vintagelook.'],
        ];

        /* Ürünlerin İngilizcesi — slug'a göre [ad, kısa açıklama] */
        $productEn = [
            'vorhang-leinenoptik-creme' => ['Linen-look Curtain, Cream',
                'Soft drape in a lightly textured fabric in warm cream — the living-room classic.'],
            'vorhang-blickdicht-greige' => ['Opaque Curtain, Greige',
                'Dense decorative fabric in greige: keeps prying eyes out while still letting daylight in.'],
            'verdunkelungsvorhang-anthrazit' => ['Blackout Curtain, Anthracite',
                'Three-layer blackout fabric for the bedroom — dark, heavy and sound-absorbing.'],
            'store-tuell-goldschimmer' => ['Voile with Gold Shimmer',
                'Fine voile with a subtle gold thread — filters light softly without darkening the room.'],
            'plissee-weiss-lichtdurchlaessig' => ['Pleated Blind, White, Light-filtering',
                'Adjustable from the top and the bottom — the answer for roof windows and bathrooms.'],
            'wabenplissee-sand-thermo' => ['Honeycomb Pleated Blind, Sand, Thermal',
                'Double honeycomb structure with an air cushion: keeps heat out in summer and warmth in during winter.'],
            'seitenzugrollo-creme' => ['Chain-operated Roller Blind, Cream',
                'A simple chain-operated roller blind in warm cream — the most affordable made-to-measure option.'],
            'verdunkelungsrollo-anthrazit' => ['Blackout Roller Blind, Anthracite',
                'Coated fabric that shuts out almost all light — for bedrooms and children rooms.'],
            'doppelrollo-greige' => ['Double Roller Blind, Greige',
                'Two layers with transparent and opaque stripes: adjust the amount of light through any position.'],
            'holzjalousie-50mm-natur' => ['Wooden Venetian Blind 50 mm, Natural',
                'Real wood slats with ladder tape — warm, high quality and tilting through any angle.'],
            'aluminiumjalousie-25mm' => ['Aluminium Venetian Blind 25 mm',
                'Narrow aluminium slats: slim, suitable for damp rooms and ideal against screen glare.'],
            'lamellenvorhang-127mm-weiss' => ['Vertical Blind 127 mm, White',
                'For wide window fronts and patio doors: rotating slats that slide fully to one side.'],
            'kelim-teppich-terra' => ['Kilim Rug, Terracotta',
                'Flat-woven kilim in terracotta, anthracite and gold — hard-wearing and suitable for underfloor heating.'],
            'wollteppich-natur-kurzflor' => ['Wool Rug, Natural, Short Pile',
                'Hand-woven pure wool in natural tones: warm underfoot and absorbs impact noise.'],
            'laeufer-vintage-mass' => ['Vintage Runner, Made to Measure',
                'Hallways and stairs to measure: your chosen length, bound edge, muted vintage look.'],
        ];

        $products = [
            // Gardinen & Vorhänge
            ['gardinen', 'p-vorhang-creme', 'vorhang-leinenoptik-creme', true, 34.90, 'm²',
                'Vorhang Leinenoptik Creme', 'Keten Görünümlü Fon Perde — Krem',
                'Weicher Fallschwung, leicht strukturierter Stoff in warmem Creme — der Klassiker fürs Wohnzimmer.',
                'Yumuşak döküm, hafif dokulu kumaş, sıcak krem ton — salon için klasik tercih.',
                ['Material' => '70% Polyester, 30% Leinen', 'Lichtdurchlässigkeit' => 'halbtransparent', 'Aufhängung' => 'Faltenband oder Wellenband', 'Montage' => 'Schiene oder Stange', 'Pflege' => '30° Feinwäsche'],
            ],
            ['gardinen', 'p-vorhang-greige', 'vorhang-blickdicht-greige', true, 39.90, 'm²',
                'Vorhang blickdicht Greige', 'Işık Geçirmez Fon Perde — Greige',
                'Dichter Dekostoff in Greige: hält Blicke draußen, lässt aber noch Tageslicht durch.',
                'Greige tonunda yoğun dekor kumaşı: dışarıdan görüşü keser, gün ışığını yine de içeri alır.',
                ['Material' => '100% Polyester', 'Lichtdurchlässigkeit' => 'blickdicht', 'Aufhängung' => 'Ösen, Faltenband, Wellenband', 'Montage' => 'Schiene oder Stange', 'Pflege' => '30° Feinwäsche'],
            ],
            ['gardinen', 'p-vorhang-anthrazit', 'verdunkelungsvorhang-anthrazit', false, 46.90, 'm²',
                'Verdunkelungsvorhang Anthrazit', 'Karartma Fon Perde — Antrasit',
                'Dreilagiger Verdunkelungsstoff fürs Schlafzimmer — dunkel, schwer und schalldämpfend.',
                'Yatak odası için üç katmanlı karartma kumaşı — karanlık, ağır ve sesi yumuşatan.',
                ['Material' => '100% Polyester, 3-lagig', 'Lichtdurchlässigkeit' => 'verdunkelnd', 'Extra' => 'thermisch und schalldämpfend', 'Montage' => 'Schiene oder Stange', 'Pflege' => '30° Feinwäsche'],
            ],
            ['gardinen', 'p-tuell-gold', 'store-tuell-goldschimmer', false, 0, null,
                'Store Tüll mit Goldschimmer', 'Altın Işıltılı Tül',
                'Feiner Tüll mit dezentem Goldfaden — filtert Licht weich, ohne den Raum zu verdunkeln.',
                'İnce dokulu, hafif altın iplikli tül — odayı karartmadan ışığı yumuşakça süzer.',
                ['Material' => '100% Polyester', 'Lichtdurchlässigkeit' => 'transparent', 'Aufhängung' => 'Faltenband oder Wellenband', 'Pflege' => '30° Feinwäsche'],
            ],

            // Plissees
            ['plissees', 'p-plissee-weiss', 'plissee-weiss-lichtdurchlaessig', true, 89.00, 'Stück',
                'Plissee Weiß lichtdurchlässig', 'Beyaz Plise — Işık Geçirgen',
                'Von oben und unten verstellbar — die Lösung für Dachfenster und Bäder.',
                'Alttan ve üstten ayarlanabilir — çatı pencereleri ve banyolar için çözüm.',
                ['Material' => 'Polyester, plissiert 20 mm', 'Lichtdurchlässigkeit' => 'lichtdurchlässig', 'Bedienung' => 'Griffbedienung oder Kordel', 'Montage' => 'Bohren oder Klemmträger', 'Sonderformen' => 'Dachschräge, Trapez, Dreieck'],
            ],
            ['plissees', 'p-plissee-sand', 'wabenplissee-sand-thermo', true, 119.00, 'Stück',
                'Wabenplissee Sand Thermo', 'Petek Plise — Kum, Isı Yalıtımlı',
                'Doppelte Wabenstruktur mit Luftpolster: hält im Sommer Hitze draußen, im Winter Wärme drinnen.',
                'Hava yastığı oluşturan çift petek yapısı: yazın ısıyı dışarıda, kışın sıcağı içeride tutar.',
                ['Material' => 'Wabenstruktur, Rückseite reflektierend', 'Lichtdurchlässigkeit' => 'halbtransparent bis verdunkelnd', 'Extra' => 'Hitze- und Kälteschutz', 'Bedienung' => 'Griff, Kordel oder Motor', 'Montage' => 'Bohren oder Klemmträger'],
            ],

            // Rollos
            ['rollos', 'p-rollo-creme', 'seitenzugrollo-creme', true, 59.00, 'Stück',
                'Seitenzugrollo Creme', 'Zincirli Stor — Krem',
                'Schlichtes Kettenzugrollo in warmem Creme — die günstigste Maßlösung fürs Fenster.',
                'Sıcak krem tonunda sade zincirli stor — pencere için en ekonomik ölçüye özel çözüm.',
                ['Material' => '100% Polyester' , 'Lichtdurchlässigkeit' => 'halbtransparent', 'Bedienung' => 'Kettenzug links oder rechts', 'Montage' => 'Wand, Decke oder Klemmträger'],
            ],
            ['rollos', 'p-rollo-verdunkelung', 'verdunkelungsrollo-anthrazit', true, 79.00, 'Stück',
                'Verdunkelungsrollo Anthrazit', 'Karartma Stor — Antrasit',
                'Beschichteter Stoff, der Licht nahezu vollständig aussperrt — für Schlaf- und Kinderzimmer.',
                'Işığı neredeyse tamamen kesen kaplamalı kumaş — yatak ve çocuk odaları için.',
                ['Material' => 'Polyester mit Verdunkelungsbeschichtung', 'Lichtdurchlässigkeit' => 'verdunkelnd', 'Extra' => 'auch mit seitlicher Führungsschiene', 'Bedienung' => 'Kettenzug oder Motor', 'Montage' => 'Wand oder Decke'],
            ],
            ['rollos', 'p-rollo-doppel', 'doppelrollo-greige', false, 94.00, 'Stück',
                'Doppelrollo Greige', 'Zebra Stor — Greige',
                'Zwei Stoffbahnen mit transparenten und deckenden Streifen: Lichtmenge stufenlos regeln.',
                'Şeffaf ve kapalı şeritli iki kumaş katı: ışık miktarını kademesiz ayarlayın.',
                ['Material' => '100% Polyester, gestreift', 'Lichtdurchlässigkeit' => 'stufenlos regelbar', 'Bedienung' => 'Kettenzug', 'Montage' => 'Wand, Decke oder Klemmträger'],
            ],

            // Jalousien
            ['jalousien', 'p-jalousie-holz', 'holzjalousie-50mm-natur', true, 149.00, 'Stück',
                'Holzjalousie 50 mm Natur', 'Ahşap Jaluzi 50 mm — Natürel',
                'Echtholzlamellen mit Leiterband — warm, wertig und stufenlos kippbar.',
                'Şeritli gerçek ahşap lameller — sıcak, kaliteli ve kademesiz açı ayarlı.',
                ['Material' => 'Echtholz (Basswood), lackiert', 'Lamellenbreite' => '50 mm', 'Bedienung' => 'Wendestab und Zugschnur', 'Montage' => 'Wand, Decke oder Nische', 'Hinweis' => 'nicht für Feuchträume'],
            ],
            ['jalousien', 'p-jalousie-alu', 'aluminiumjalousie-25mm', false, 69.00, 'Stück',
                'Aluminiumjalousie 25 mm', 'Alüminyum Jaluzi 25 mm',
                'Schmale Alulamellen: schlank, feuchtraumgeeignet und ideal gegen Bildschirmblendung.',
                'İnce alüminyum lameller: zarif, ıslak hacme uygun ve ekran parlamasına karşı ideal.',
                ['Material' => 'Aluminium', 'Lamellenbreite' => '25 mm', 'Bedienung' => 'Wendestab und Zugschnur', 'Montage' => 'Wand, Decke, Nische oder Klemmträger', 'Extra' => 'für Bad und Küche geeignet'],
            ],

            // Lamellenvorhänge
            ['lamellenvorhaenge', 'p-lamellen-weiss', 'lamellenvorhang-127mm-weiss', false, 0, null,
                'Lamellenvorhang 127 mm Weiß', 'Dikey Lamelli Perde 127 mm — Beyaz',
                'Für breite Fensterfronten und Terrassentüren: drehbar und komplett zur Seite schiebbar.',
                'Geniş pencere cepheleri ve teras kapıları için: dönebilir ve tamamen yana toplanabilir.',
                ['Material' => 'Polyester', 'Lamellenbreite' => '89 oder 127 mm', 'Bedienung' => 'Kette und Schnur oder Motor', 'Montage' => 'Wand oder Decke', 'Extra' => 'auch für schräge Fensterfronten'],
            ],

            // Teppiche
            ['teppiche', 'p-teppich-kelim', 'kelim-teppich-terra', true, 0, null,
                'Kelim-Teppich Terra', 'Kilim — Terra',
                'Flachgewebter Kelim in Terra, Anthrazit und Gold — robust und für Fußbodenheizung geeignet.',
                'Terra, antrasit ve altın tonlarında düz dokuma kilim — dayanıklı, yerden ısıtmaya uygun.',
                ['Material' => 'Wolle-Baumwoll-Mischung', 'Florhöhe' => 'flach gewebt', 'Größen' => '120×180, 160×230, 200×290 cm', 'Extra' => 'für Fußbodenheizung geeignet', 'Pflege' => 'saugen, professionell reinigen'],
            ],
            ['teppiche', 'p-teppich-wolle', 'wollteppich-natur-kurzflor', false, 0, null,
                'Wollteppich Natur Kurzflor', 'Yün Halı — Natürel Kısa Hav',
                'Handgewebte Schurwolle in Naturtönen: warm unter den Füßen, dämpft Trittschall.',
                'Doğal tonlarda el dokuma saf yün: ayak altında sıcak, adım sesini yumuşatır.',
                ['Material' => '100% Schurwolle', 'Florhöhe' => 'ca. 12 mm', 'Größen' => 'Standardmaße und Maßanfertigung', 'Extra' => 'schalldämpfend', 'Pflege' => 'saugen, professionell reinigen'],
            ],
            ['teppiche', 'p-teppich-vintage', 'laeufer-vintage-mass', false, 0, null,
                'Läufer Vintage nach Maß', 'Vintage Yol Halısı — Ölçüye Özel',
                'Flur und Treppe nach Maß: Wunschlänge, eingefasste Kante, gedeckte Vintage-Optik.',
                'Koridor ve merdiven için ölçüye özel: istediğiniz boy, overloklu kenar, mat vintage görünüm.',
                ['Material' => 'Polypropylen-Wolle-Mischung', 'Florhöhe' => 'ca. 8 mm', 'Breiten' => '67, 80, 100 cm', 'Länge' => 'nach Maß, Kante eingefasst', 'Extra' => 'auch für Treppenstufen'],
            ],
        ];

        foreach ($products as $i => [$catSlug, $image, $slug, $featured, $price, $unit, $name, $nameTr, $short, $shortTr, $attrs]) {
            Product::updateOrCreate(['slug' => $slug], [
                'category_id'   => $cats[$catSlug]->id,
                'name'          => $name,
                'name_nl'       => $productNl[$slug][0] ?? null,
                'name_en'       => $productEn[$slug][0] ?? null,
                'name_tr'       => $nameTr,
                'cover'         => $img($image),
                'images'        => [$img($image)],
                'short_desc'    => $short,
                'short_desc_nl' => $productNl[$slug][1] ?? null,
                'short_desc_en' => $productEn[$slug][1] ?? null,
                'short_desc_tr' => $shortTr,
                // Kısa açıklamayı tekrar etmiyoruz — detay sayfasında ikisi üst üste görünür.
                'description'   => 'Dieses Modell fertigen wir nach Maß: Sie bestimmen Breite, Höhe, Farbe, '
                    . 'Lichtdurchlässigkeit und Bedienseite. Die Montage übernehmen unsere eigenen Monteure.' . "\n\n"
                    . 'Der angegebene Preis ist ein Ausgangspreis und hängt von Maß, Stoff und Ausführung ab. '
                    . 'Ihren verbindlichen Festpreis erhalten Sie nach dem kostenlosen Aufmaß — dabei zeigen wir '
                    . 'Ihnen alle Stoffe und Farben anhand von Musterbüchern in Ihren eigenen Räumen.',
                'description_nl' => 'Dit model maken wij op maat: u bepaalt de breedte, hoogte, kleur, '
                    . 'lichtdoorlatendheid en bedieningszijde. De montage doen onze eigen monteurs.' . "\n\n"
                    . 'De vermelde prijs is een vanafprijs en hangt af van de maat, de stof en de uitvoering. '
                    . 'Uw bindende vaste prijs ontvangt u na het gratis inmeten — daarbij laten wij u alle stoffen '
                    . 'en kleuren aan de hand van stalenboeken in uw eigen ruimtes zien.',
                'description_en' => 'We make this model to measure: you choose the width, height, colour, '
                    . 'how much light comes through and the operating side. Fitting is done by our own fitters.' . "\n\n"
                    . 'The price shown is a starting price and depends on the size, fabric and finish. You receive '
                    . 'your binding fixed price after the free measuring visit — where we show you all fabrics and '
                    . 'colours from sample books in your own rooms.',
                'description_tr' => 'Bu modeli ölçüye özel üretiyoruz: eni, boyu, rengi, ışık geçirgenliğini ve '
                    . 'kumanda yönünü siz belirliyorsunuz. Montajı kendi ekibimiz yapıyor.' . "\n\n"
                    . 'Belirtilen fiyat başlangıç fiyatıdır; ölçüye, kumaşa ve uygulamaya göre değişir. '
                    . 'Bağlayıcı sabit fiyatınızı ücretsiz ölçüden sonra alıyorsunuz — o randevuda tüm kumaş ve '
                    . 'renkleri numune kitaplarıyla kendi mekânınızda gösteriyoruz.',
                'price'      => $price,
                'price_unit' => $unit,
                'attributes'    => $attrs,
                'attributes_nl' => $ceviriAttr($attrs, 'nl'),
                'attributes_en' => $ceviriAttr($attrs, 'en'),
                'attributes_tr' => $ceviriAttr($attrs, 'tr'),
                'featured'   => $featured,
                'sira'       => $i + 1,
                'durum'      => true,
            ]);
        }

        /* ---------------- Yapılan işler ---------------- */
        /* Projelerin Hollandacası — slug'a göre [başlık, tür, özet] */
        $projectNl = [
            'wohnzimmer-wellenvorhang-greige' => ['Woonkamer met golfgordijn', 'Gordijnen',
                'Raampartij van 4,20 m breed met golfgordijn in greige, plafondrails vlak tegen de wand.'],
            'holzjalousien-altbau' => ['Houten jaloezieën in een oud pand', 'Jaloezieën',
                'Zes ramen met houten jaloezieën van 50 mm in de dagkant gemonteerd — lamelkleur afgestemd op de kozijnen.'],
            'dachfenster-wabenplissee' => ['Dakramen met duette-plissé', 'Plisségordijnen',
                'Een warme zolder: vier dakschuintes met isolerend duette-plissé, bediening met een telescoopstok.'],
            'buero-lamellenvorhang' => ['Kantoor met verticale lamellen', 'Verticale lamellen',
                'Twaalf werkplekken, zuidgevel: lamellen van 127 mm tegen spiegeling op beeldschermen, montage in het weekend.'],
            'schlafzimmer-verdunkelung' => ['Slaapkamer volledig verduisterd', 'Rolgordijnen',
                'Verduisterend rolgordijn met zijgeleiding plus een zwaar gordijn — geen lichtkier aan de randen.'],
            'flur-laeufer-nach-mass' => ['Hal met een loper op maat', 'Tapijten',
                'Smalle hal in een oud pand, 9,40 m: loper op maat gesneden en omgezoomd, traptreden passend belegd.'],
        ];

        /* Projelerin İngilizcesi — slug'a göre [başlık, tür, özet] */
        $projectEn = [
            'wohnzimmer-wellenvorhang-greige' => ['Living room with wave curtains', 'Curtains',
                'A 4.20 m wide window front with wave-heading curtains in greige, ceiling track flush to the wall.'],
            'holzjalousien-altbau' => ['Wooden blinds in a period building', 'Venetian Blinds',
                'Six windows with 50 mm wooden blinds fitted in the recess — slat colour matched to the window frames.'],
            'dachfenster-wabenplissee' => ['Roof windows with honeycomb blinds', 'Pleated Blinds',
                'An overheated loft: four sloping windows with thermal honeycomb pleated blinds, operated by telescopic rod.'],
            'buero-lamellenvorhang' => ['Office with vertical blinds', 'Vertical Blinds',
                'Twelve desks, south-facing facade: 127 mm slats against screen glare, fitted over the weekend.'],
            'schlafzimmer-verdunkelung' => ['Fully blacked-out bedroom', 'Roller Blinds',
                'Blackout roller blind with side guide rails plus a heavy curtain — no gap of light at the edges.'],
            'flur-laeufer-nach-mass' => ['Hallway with a made-to-measure runner', 'Rugs',
                'A narrow 9.40 m period hallway: runner cut and bound to measure, stairs covered to match.'],
        ];

        $projects = [
            ['proj-1', 'wohnzimmer-wellenvorhang-greige', 'Wohnzimmer mit Wellenvorhang', 'Dalga Perdeli Salon',
                'Gardinen', 'Fon Perde', 'Amsterdam', true,
                'Fensterfront von 4,20 m Breite mit Wellenband-Vorhang in Greige, Deckenschiene bündig zur Wand.',
                '4,20 m genişliğinde pencere cephesi; greige dalga bantlı fon perde, duvarla hizalı tavan rayı.'],
            ['proj-2', 'holzjalousien-altbau', 'Holzjalousien im Altbau', 'Eski Binada Ahşap Jaluzi',
                'Jalousien', 'Jaluzi', 'Utrecht', true,
                'Sechs Fenster mit 50-mm-Holzjalousien in Nischenmontage — Lamellenfarbe an die Fensterrahmen angepasst.',
                'Nişe monte 50 mm ahşap jaluziyle altı pencere — lamel rengi pencere doğramasına göre seçildi.'],
            ['proj-3', 'dachfenster-wabenplissee', 'Dachfenster mit Wabenplissee', 'Çatı Penceresinde Petek Plise',
                'Plissees', 'Plise', 'Almere', true,
                'Aufgeheiztes Dachgeschoss: vier Dachschrägen mit Thermo-Wabenplissee, Bedienung per Teleskopstab.',
                'Isınan çatı katı: dört çatı eğimine ısı yalıtımlı petek plise, teleskopik çubukla kumanda.'],
            ['proj-4', 'buero-lamellenvorhang', 'Büro mit Lamellenvorhang', 'Ofiste Dikey Lamelli Perde',
                'Lamellenvorhänge', 'Dikey Lamel', 'Rotterdam', false,
                'Zwölf Arbeitsplätze, Südfassade: 127-mm-Lamellen gegen Bildschirmblendung, Montage am Wochenende.',
                'On iki çalışma alanı, güney cephe: ekran parlamasına karşı 127 mm lamel, montaj hafta sonu yapıldı.'],
            ['proj-5', 'schlafzimmer-verdunkelung', 'Schlafzimmer komplett verdunkelt', 'Tam Karartmalı Yatak Odası',
                'Rollos', 'Stor', 'Haarlem', false,
                'Verdunkelungsrollo mit seitlichen Führungsschienen plus schwerer Vorhang — kein Lichtspalt am Rand.',
                'Yan kılavuz raylı karartma storu ve ağır fon perde — kenarda ışık sızıntısı yok.'],
            ['proj-6', 'flur-laeufer-nach-mass', 'Flur mit Läufer nach Maß', 'Ölçüye Özel Yol Halılı Koridor',
                'Teppiche', 'Halı', 'Zaanstad', false,
                'Schmaler Altbauflur, 9,40 m: Läufer auf Maß geschnitten und eingefasst, Treppenstufen passend belegt.',
                'Dar eski bina koridoru, 9,40 m: yol halısı ölçüye göre kesilip overloklandı, merdiven basamakları uyumlu kaplandı.'],
        ];
        foreach ($projects as $i => [$image, $slug, $title, $titleTr, $kind, $kindTr, $loc, $featured, $summary, $summaryTr]) {
            Project::updateOrCreate(['slug' => $slug], [
                'title'      => $title,
                'title_nl'   => $projectNl[$slug][0] ?? null,
                'title_en'   => $projectEn[$slug][0] ?? null,
                'title_tr'   => $titleTr,
                'kind'       => $kind,
                'kind_nl'    => $projectNl[$slug][1] ?? null,
                'kind_en'    => $projectEn[$slug][1] ?? null,
                'kind_tr'    => $kindTr,
                'location'   => $loc,
                'cover'      => $img($image),
                'images'     => [$img($image)],
                'summary'    => $summary,
                'summary_nl' => $projectNl[$slug][2] ?? null,
                'summary_en' => $projectEn[$slug][2] ?? null,
                'summary_tr' => $summaryTr,
                'content'    => $summary . "\n\n"
                    . 'Ablauf wie immer: kostenloses Aufmaß vor Ort, schriftliches Angebot, Fertigung nach Maß und '
                    . 'Montage durch unsere eigenen Monteure.',
                'content_nl' => ($projectNl[$slug][2] ?? '') . "\n\n"
                    . 'De werkwijze is altijd dezelfde: gratis inmeten ter plaatse, een schriftelijke prijsopgave, '
                    . 'productie op maat en montage door onze eigen monteurs.',
                'content_en' => ($projectEn[$slug][2] ?? '') . "\n\n"
                    . 'The process is always the same: free measuring on site, a written quotation, '
                    . 'made-to-measure production and fitting by our own fitters.',
                'content_tr' => $summaryTr . "\n\n"
                    . 'Süreç her zamanki gibi: yerinde ücretsiz ölçü, yazılı teklif, ölçüye özel üretim ve '
                    . 'kendi ekibimizle montaj.',
                'tarih'    => now()->subMonths(($i + 1) * 2)->startOfMonth(),
                'featured' => $featured,
                'sira'     => $i + 1,
                'durum'    => true,
            ]);
        }

        /* ---------------- Rehber yazıları ---------------- */
        /* Yazıların Hollandacası — slug'a göre [kategori, başlık, özet, içerik] */
        $postNl = [
            'vorhang-richtig-ausmessen' => [
                'Advies',
                'Gordijnen goed opmeten — de vier meestgemaakte fouten',
                'Te kort, te smal, de rails op de verkeerde hoogte: wie zelf meet, trapt meestal in dezelfde valkuilen. Waar u op moet letten.',
                "**1. De stoftoeslag voor de plooien vergeten.** Een gordijn heeft 2 tot 2,5 keer de railsbreedte nodig "
                . "voordat het überhaupt plooien vormt. Wie de railsbreedte als stofbreedte bestelt, krijgt een vlak laken.\n\n"
                . "**2. Vanaf het verkeerde punt meten.** De hoogte wordt gemeten vanaf de bovenkant van de rails of "
                . "roede — niet vanaf het kozijn. En de rails hangt idealiter een stukje boven het raam, niet er direct op.\n\n"
                . "**3. Op maar één plek meten.** In oude panden is een dagkant boven vaak 1–2 cm breder dan onder. "
                . "Meet boven, in het midden en onder — en gebruik de kleinste maat.\n\n"
                . "**4. De vloerafstand niet bepalen.** Vloerlange gordijnen moeten óf 1–2 cm boven de vloer zweven óf "
                . "er bewust op rusten. Alles daartussen ziet uit als een vergissing.\n\n"
                . "Bij twijfel: tijdens de gratis inmeetafspraak meten wij zelf — en dan zijn wij ook aansprakelijk voor de maten.",
            ],
            'welcher-sonnenschutz-passt' => [
                'Materiaalkennis',
                'Plissé, rolgordijn of jaloezie — wat past bij welke ruimte?',
                'Alle drie zitten direct tegen het glas en kosten ongeveer hetzelfde. Het verschil zit in wat ze met het licht doen.',
                "**Plisségordijnen** zijn de specialist voor bijzondere vormen. Omdat ze van boven *en* van onder "
                . "verstelbaar zijn, kunt u het midden van een raam vrijlaten — handig in de badkamer en op de begane "
                . "grond. Voor dakschuintes, trapeziums en driehoeken zijn ze meestal de enige nette oplossing. Als "
                . "duette-plissé isoleren ze bovendien.\n\n"
                . "**Rolgordijnen** geven het rustigste beeld: één stofbaan, geen structuur, veel kleurkeuze. Ze regelen "
                . "licht alleen via de hoogte — helemaal of gedeeltelijk dicht. Met een verduisterende coating en "
                . "zijgeleiding wordt een slaapkamer echt donker. Het duo-rolgordijn is het compromis voor overdag.\n\n"
                . "**Jaloezieën** zijn de enige van de drie die licht *richten*: door de lamellen te kantelen stuurt u "
                . "daglicht naar het plafond zonder het uitzicht helemaal te verliezen. Daarom winnen ze op de werkplek "
                . "en in de keuken — aluminium kan daar tegen vocht, echt hout niet.\n\n"
                . "Kort: bijzondere vorm → plissé. Rustig vlak en verduistering → rolgordijn. Lichtwering met uitzicht → jaloezie.",
            ],
            'teppich-pflege-und-groesse' => [
                'Tapijten',
                'De juiste tapijtmaat kiezen — en uw tapijt mooi houden',
                'De meestgemaakte fout bij het kopen van een tapijt is niet de kleur maar de maat. En verder: wat wol echt nodig heeft.',
                "**Maat.** Een te klein tapijt maakt een ruimte onrustig. Vuistregel voor de woonkamer: de voorpoten van "
                . "de bank en de fauteuils horen op het tapijt te staan. In een eethoek moet het tapijt zo groot zijn dat "
                . "de stoelen er bij achteruitschuiven niet met de achterpoten afglijden — meestal 60–70 cm meer dan de "
                . "tafel aan elke zijde. In een hal laat u langs de lange zijden het liefst 10–15 cm vloer vrij.\n\n"
                . "**Nieuwe pool laat los.** Wollen tapijten laten de eerste weken korte vezels los. Dat is normaal en "
                . "geen gebrek — regelmatig stofzuigen en het stopt.\n\n"
                . "**Vlekken direct opnemen, niet wrijven.** Dep met een lichte doek van buiten naar binnen. Wrijven "
                . "duwt de vlek in de pool en maakt de wol klittig.\n\n"
                . "**Indrukken.** Meubelpoten laten sporen achter. Leg een ijsblokje op de plek, laat het smelten en til "
                . "de pool met uw hand op — meestal verdwijnen ze volledig.\n\n"
                . "**Vloerverwarming.** Vlakgeweven tapijten zoals kelims geleiden warmte goed. Heel dikke, hoogpolige "
                . "tapijten isoleren juist en verlagen de warmteafgifte.",
            ],
        ];

        /* Yazıların İngilizcesi — slug'a göre [kategori, başlık, özet, içerik] */
        $postEn = [
            'vorhang-richtig-ausmessen' => [
                'Guide',
                'Measuring curtains correctly — the four most common mistakes',
                'Too short, too narrow, the track at the wrong height: people who measure themselves usually fall into the same traps. What to watch out for.',
                "**1. Forgetting the fullness allowance.** A curtain needs 2 to 2.5 times the track width "
                . "before it will hang in folds at all. Order the track width as the fabric width and you get a flat sheet.\n\n"
                . "**2. Measuring from the wrong point.** The height is measured from the top edge of the track "
                . "or pole — not from the window frame. And ideally the track sits a little above the window, not directly on it.\n\n"
                . "**3. Measuring in only one place.** In older buildings a recess is often 1–2 cm wider at the top "
                . "than at the bottom. Measure at the top, in the middle and at the bottom — and use the smallest figure.\n\n"
                . "**4. Not deciding on the floor gap.** Floor-length curtains should either hover 1–2 cm above the "
                . "floor or deliberately rest on it. Anything in between looks like a mistake.\n\n"
                . "If in doubt: we take the measurements at the free measuring visit — and then we are liable for them.",
            ],
            'welcher-sonnenschutz-passt' => [
                'Materials',
                'Pleated, roller or venetian — which suits which room?',
                'All three sit right against the glass and cost about the same. The difference lies in what they do with the light.',
                "**Pleated blinds** are the specialist for unusual shapes. Because they adjust from the top *and* "
                . "the bottom, you can leave the middle of a window clear — handy in bathrooms and on the ground floor. "
                . "For roof slopes, trapezoids and triangles they are usually the only clean solution. As a honeycomb "
                . "blind they also insulate.\n\n"
                . "**Roller blinds** give the calmest look: one length of fabric, no structure, a wide choice of "
                . "colours. They control light only through height — fully or partly closed. With a blackout coating and "
                . "side guide rails a bedroom really does go dark. The double roller blind is the compromise for daytime.\n\n"
                . "**Venetian blinds** are the only one of the three that *directs* light: by tilting the slats you send "
                . "daylight up to the ceiling without losing the view out completely. That is why they win at a desk and "
                . "in the kitchen — aluminium copes with moisture there, real wood does not.\n\n"
                . "In short: unusual shape → pleated. Calm surface and blackout → roller. Glare protection with a view → venetian.",
            ],
            'teppich-pflege-und-groesse' => [
                'Rugs',
                'Choosing the right rug size — and keeping a rug looking good',
                'The most common mistake when buying a rug is not the colour but the size. Plus: what wool really needs.',
                "**Size.** A rug that is too small makes a room look restless. A rule of thumb for the living room: the "
                . "front legs of the sofa and armchairs should stand on the rug. In a dining area the rug must be big "
                . "enough that the chairs do not slip off it with their back legs when pushed back — usually 60–70 cm "
                . "more than the table on each side. In a hallway, ideally leave 10–15 cm of floor visible along the "
                . "long sides.\n\n"
                . "**New pile sheds.** Wool rugs shed short fibres in the first few weeks. That is normal and not a "
                . "fault — vacuum regularly and it stops.\n\n"
                . "**Blot stains immediately, do not rub.** Dab with a light cloth from the outside in. Rubbing pushes "
                . "the stain into the pile and mats the wool.\n\n"
                . "**Dents.** Furniture legs leave marks. Put an ice cube on the spot, let it melt and lift the pile "
                . "with your hand — they usually disappear completely.\n\n"
                . "**Underfloor heating.** Flat-woven rugs such as kilims conduct heat well. Very thick, high-pile rugs "
                . "insulate instead and reduce the heating output.",
            ],
        ];

        $posts = [
            ['blog-1', 'vorhang-richtig-ausmessen', 'Ratgeber', 'Rehber',
                'Vorhänge richtig ausmessen — die vier häufigsten Fehler',
                'Fon perdeyi doğru ölçmek — en sık yapılan dört hata',
                'Zu kurz, zu schmal, falsche Schienenhöhe: Wer selbst misst, tappt meist in dieselben Fallen. Was Sie beachten sollten.',
                'Kısa kaldı, en yetmedi, ray yüksekliği yanlış: kendi ölçen çoğunlukla aynı tuzaklara düşüyor. Nelere dikkat etmeli?',
                "**1. Die Stoffzugabe für Falten vergessen.** Ein Vorhang braucht das 2- bis 2,5-fache der Schienenbreite, "
                . "damit er überhaupt Falten wirft. Wer die Schienenbreite als Stoffbreite bestellt, bekommt ein flaches Tuch.\n\n"
                . "**2. Ab der falschen Stelle messen.** Die Höhe wird von der Oberkante der Schiene bzw. Stange gemessen — "
                . "nicht vom Fensterrahmen. Und die Schiene sitzt idealerweise ein Stück über dem Fenster, nicht direkt darauf.\n\n"
                . "**3. Nur an einer Stelle messen.** In Altbauten ist eine Nische oben und unten oft 1–2 cm unterschiedlich breit. "
                . "Messen Sie oben, in der Mitte und unten — und verwenden Sie das kleinste Maß.\n\n"
                . "**4. Den Bodenabstand nicht festlegen.** Bodenlange Vorhänge sollen entweder 1–2 cm über dem Boden schweben "
                . "oder bewusst aufliegen. Dazwischen sieht es nach Versehen aus.\n\n"
                . "Im Zweifel: Beim kostenlosen Aufmaßtermin messen wir selbst — und haften dann auch für die Maße.",
                "**1. Pile için kumaş payını atlamak.** Bir fon perdenin pile yapabilmesi için ray genişliğinin 2 – 2,5 katı "
                . "kumaş gerekir. Ray genişliğini kumaş genişliği olarak sipariş eden düz bir bez alır.\n\n"
                . "**2. Yanlış noktadan ölçmek.** Yükseklik, pencere doğramasından değil rayın ya da borunun üst kenarından ölçülür. "
                . "Ayrıca ray, ideal olarak pencerenin tam üstüne değil biraz yukarısına gelir.\n\n"
                . "**3. Tek noktadan ölçmek.** Eski binalarda niş, üstte ve altta sıklıkla 1–2 cm farklı genişliktedir. "
                . "Üstten, ortadan ve alttan ölçün — en küçük ölçüyü kullanın.\n\n"
                . "**4. Yerden yüksekliği belirlememek.** Yere kadar inen perdeler ya zeminden 1–2 cm yukarıda durmalı ya da "
                . "bilinçli olarak yere değmelidir. Arası kaza gibi görünür.\n\n"
                . "Tereddüt varsa: ücretsiz ölçü randevusunda ölçüyü biz alıyoruz — ve ölçüden biz sorumlu oluyoruz."],

            ['blog-2', 'welcher-sonnenschutz-passt', 'Materialkunde', 'Malzeme Bilgisi',
                'Plissee, Rollo oder Jalousie — was passt zu welchem Raum?',
                'Plise, stor mu jaluzi mi — hangi odaya hangisi?',
                'Alle drei sitzen direkt am Glas und kosten ähnlich viel. Der Unterschied liegt darin, was sie mit dem Licht machen.',
                'Üçü de doğrudan camda durur ve benzer fiyattadır. Fark, ışığa ne yaptıklarında.',
                "**Plissee** ist der Spezialist für Sonderformen. Weil es von oben *und* unten verstellbar ist, kann man die "
                . "Mitte eines Fensters freilassen — praktisch im Bad und im Erdgeschoss. Für Dachschrägen, Trapeze und "
                . "Dreiecke ist es meist die einzige saubere Lösung. Als Wabenplissee isoliert es zusätzlich.\n\n"
                . "**Rollo** ist die ruhigste Optik: eine Stoffbahn, keine Struktur, viel Farbauswahl. Es regelt Licht nur "
                . "über die Höhe — ganz oder teilweise zu. Mit Verdunkelungsbeschichtung und seitlichen Führungsschienen "
                . "wird das Schlafzimmer wirklich dunkel. Das Doppelrollo ist der Kompromiss für tagsüber.\n\n"
                . "**Jalousie** ist die einzige der drei, die Licht *lenkt*: Über die Lamellenneigung schicken Sie Tageslicht "
                . "zur Decke, ohne den Blick nach draußen komplett zu verlieren. Deshalb ist sie am Arbeitsplatz und in der "
                . "Küche im Vorteil — Aluminium verträgt dort auch Feuchtigkeit, Echtholz nicht.\n\n"
                . "Kurz: Sonderform → Plissee. Ruhige Fläche und Verdunkelung → Rollo. Blendschutz mit Ausblick → Jalousie.",
                "**Plise**, özel formların uzmanıdır. Alttan *ve* üstten ayarlanabildiği için pencerenin ortası açık "
                . "bırakılabilir — banyoda ve zemin katta çok pratik. Çatı eğimleri, trapez ve üçgen pencerelerde genellikle "
                . "tek düzgün çözümdür. Petek plise olarak ayrıca yalıtım da sağlar.\n\n"
                . "**Stor**, en sakin görünümdür: tek kumaş katı, doku yok, geniş renk seçeneği. Işığı yalnızca yükseklikle "
                . "ayarlar — tamamen ya da kısmen kapalı. Karartma kaplaması ve yan kılavuz raylarıyla yatak odası gerçekten "
                . "kararır. Zebra stor, gündüz için ara çözümdür.\n\n"
                . "**Jaluzi**, üçü içinde ışığı *yönlendiren* tek seçenektir: lamel açısıyla gün ışığını tavana gönderirsiniz, "
                . "dışarı görüşü tamamen kaybetmeden. Bu yüzden çalışma alanında ve mutfakta avantajlıdır — orada alüminyum "
                . "nemi kaldırır, gerçek ahşap kaldırmaz.\n\n"
                . "Kısaca: özel form → plise. Sakin yüzey ve karartma → stor. Manzarayla birlikte parlama koruması → jaluzi."],

            ['blog-3', 'teppich-pflege-und-groesse', 'Teppiche', 'Halı',
                'Teppichgröße richtig wählen — und den Teppich lange schön halten',
                'Halı ölçüsünü doğru seçmek — ve halıyı uzun süre güzel tutmak',
                'Der häufigste Fehler beim Teppichkauf ist nicht die Farbe, sondern die Größe. Dazu: was Wolle wirklich braucht.',
                'Halı alırken en sık yapılan hata renk değil, ölçü. Ayrıca: yünün gerçekten neye ihtiyacı var?',
                "**Größe.** Ein zu kleiner Teppich lässt den Raum unruhig wirken. Als Faustregel im Wohnzimmer: Die "
                . "Vorderbeine von Sofa und Sesseln sollten auf dem Teppich stehen. Im Essbereich muss der Teppich so groß "
                . "sein, dass die Stühle auch beim Zurückschieben nicht mit den Hinterbeinen herunterrutschen — meist "
                . "60–70 cm mehr als der Tisch auf jeder Seite. Im Flur bleiben an den Längsseiten idealerweise 10–15 cm "
                . "Boden sichtbar.\n\n"
                . "**Neuer Flor fusselt.** Bei Wollteppichen lösen sich in den ersten Wochen kurze Fasern. Das ist normal "
                . "und kein Mangel — regelmäßig saugen, dann hört es auf.\n\n"
                . "**Flecken sofort aufnehmen, nicht reiben.** Mit einem hellen Tuch von außen nach innen tupfen. "
                . "Reiben drückt den Fleck in den Flor und filzt die Wolle.\n\n"
                . "**Druckstellen.** Möbelfüße hinterlassen Dellen. Ein Eiswürfel darauf, schmelzen lassen, den Flor mit "
                . "der Hand aufrichten — meist verschwinden sie vollständig.\n\n"
                . "**Fußbodenheizung.** Flach gewebte Teppiche wie Kelim leiten Wärme gut. Sehr dicke Hochflorteppiche "
                . "dämmen dagegen und senken die Heizleistung.",
                "**Ölçü.** Küçük kalan bir halı odayı huzursuz gösterir. Salonda pratik kural: kanepe ve koltukların ön "
                . "ayakları halının üzerinde olmalı. Yemek alanında halı, sandalyeler geri çekildiğinde arka ayakları "
                . "halıdan kaymayacak kadar büyük olmalı — genellikle masanın her yanından 60–70 cm fazlası. Koridorda "
                . "uzun kenarlarda ideal olarak 10–15 cm zemin görünür kalır.\n\n"
                . "**Yeni hav tüylenir.** Yün halılarda ilk haftalarda kısa lifler dökülür. Bu normaldir, ayıp değildir — "
                . "düzenli süpürünce kesilir.\n\n"
                . "**Lekeyi hemen alın, ovmayın.** Açık renk bir bezle dıştan içe doğru bastırarak alın. Ovmak lekeyi "
                . "havın içine iter ve yünü keçeleştirir.\n\n"
                . "**Bası izleri.** Mobilya ayakları çukur bırakır. Üzerine bir buz küpü koyup erimesini bekleyin, havı "
                . "elle kaldırın — çoğunlukla tamamen geçer.\n\n"
                . "**Yerden ısıtma.** Kilim gibi düz dokuma halılar ısıyı iyi iletir. Çok kalın uzun havlı halılar ise "
                . "yalıtım yapıp ısıtma verimini düşürür."],
        ];
        foreach ($posts as $i => [$image, $slug, $cat, $catTr, $title, $titleTr, $summary, $summaryTr, $content, $contentTr]) {
            Post::updateOrCreate(['slug' => $slug], [
                'title'       => $title,
                'title_nl'    => $postNl[$slug][1] ?? null,
                'category_nl' => $postNl[$slug][0] ?? null,
                'summary_nl'  => $postNl[$slug][2] ?? null,
                'content_nl'  => $postNl[$slug][3] ?? null,
                'title_en'    => $postEn[$slug][1] ?? null,
                'category_en' => $postEn[$slug][0] ?? null,
                'summary_en'  => $postEn[$slug][2] ?? null,
                'content_en'  => $postEn[$slug][3] ?? null,
                'title_tr'    => $titleTr,
                'category'    => $cat,
                'category_tr' => $catTr,
                'image'       => $img($image),
                'summary'     => $summary,
                'summary_tr'  => $summaryTr,
                'content'     => $content,
                'content_tr'  => $contentTr,
                'tarih'       => now()->subWeeks(($i + 1) * 3),
                'durum'       => true,
            ]);
        }

        /* ---------------- Müşteri yorumları ----------------
         | ÖNEMLİ: Bunlar örnek metinlerdir. Yayına almadan önce müşterinin
         | gerçek yorumlarıyla değiştirilmeli (uydurma referans yayınlanmamalı).
         */
        /* Yorumların Hollandacası — ada göre */
        $testimonialNl = [
            'Familie V.' => 'Zaterdag ingemeten, drie weken later gemonteerd — precies zoals afgesproken. De golfgordijnen hangen kaarsrecht.',
            'J. de Boer' => 'Onze zolder was in de zomer onbruikbaar. Met de duette-plissés is het nu een paar graden koeler.',
            'S. Yılmaz'  => 'Advies in onze eigen taal, een schriftelijke prijsopgave met vaste prijs, geen verrassingen op de factuur. Graag weer.',
        ];

        /* Yorumların İngilizcesi — ada göre */
        $testimonialEn = [
            'Familie V.' => 'Measured on Saturday, fitted three weeks later — exactly as agreed. The wave curtains hang perfectly straight.',
            'J. de Boer' => 'Our loft was unusable in summer. With the honeycomb blinds it is now several degrees cooler.',
            'S. Yılmaz'  => 'Advice in our own language, a written quotation with a fixed price, no surprises on the invoice. Happy to come back.',
        ];

        $testimonials = [
            ['Familie V.', 'Amsterdam', 5,
                'Aufmaß am Samstag, drei Wochen später montiert — genau wie besprochen. Die Wellenvorhänge hängen millimetergenau.',
                'Cumartesi ölçü alındı, üç hafta sonra monte edildi — tam konuşulduğu gibi. Dalga perdeler milimetrik duruyor.'],
            ['J. de Boer', 'Utrecht', 5,
                'Unser Dachgeschoss war im Sommer nicht nutzbar. Mit den Wabenplissees ist es jetzt mehrere Grad kühler.',
                'Çatı katımız yazın kullanılamıyordu. Petek pliselerle şimdi birkaç derece daha serin.'],
            ['S. Yılmaz', 'Rotterdam', 5,
                'Beratung auf Türkisch, Angebot schriftlich mit Festpreis, keine Überraschung auf der Rechnung. Gerne wieder.',
                'Türkçe danışmanlık, yazılı ve sabit fiyatlı teklif, faturada sürpriz yok. Tekrar çalışırız.'],
        ];
        foreach ($testimonials as [$name, $city, $stars, $comment, $commentTr]) {
            Testimonial::updateOrCreate(['name' => $name], [
                'title'      => $city,
                'title_nl'   => $city,
                'title_en'   => $city,
                'title_tr'   => $city,
                'comment'    => $comment,
                'comment_nl' => $testimonialNl[$name] ?? null,
                'comment_en' => $testimonialEn[$name] ?? null,
                'comment_tr' => $commentTr,
                'stars'      => $stars,
                'durum'      => true,
            ]);
        }
    }
}
