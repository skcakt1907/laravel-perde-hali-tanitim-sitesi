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
        $img = fn (string $name) => url('img/demo/' . $name . '.jpg');

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
            'calisma_saatleri_tr' => "Pzt–Cum 09:00–18:00\nCmt 10:00–16:00 (randevu ile)",

            'hero_gorsel' => url('img/demo/hero.jpg'),
            'hero_baslik' => "Maßgefertigte Fensterdekoration\nfür Ihr Zuhause",
            'hero_metin'  => 'Von Plissees und Rollos bis zu Vorhängen und handverlesenen Teppichen: '
                . 'Wir messen kostenlos bei Ihnen aus, beraten Sie in Ruhe und montieren fachgerecht.',
            'hero_baslik_tr' => "Eviniz için\nölçüye özel pencere dekorasyonu",
            'hero_metin_tr'  => 'Plise ve stordan fon perdeye, özenle seçilmiş halılara kadar: '
                . 'Ücretsiz yerinde ölçü alıyor, acele etmeden danışmanlık veriyor ve ustaca monte ediyoruz.',

            'istatistik_yil'      => '15',
            'istatistik_pencere'  => '12.000',
            'istatistik_musteri'  => '3.400',
            'istatistik_bolge'    => '100',

            'hakkimizda_gorsel' => url('img/demo/about.jpg'),
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
                . "Feste Preise im schriftlichen Angebot\nBeratung auf Deutsch, Niederländisch und Türkisch",
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
                . "Yazılı teklifte sabit fiyat\nAlmanca, Hollandaca ve Türkçe danışmanlık",

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
                'name' => 'Gardinen & Vorhänge', 'name_tr' => 'Fon Perde & Tül',
                'description' => 'Vorhänge, Schals und Tüll in Faltenband, Ösen oder Wellenform — vom leichten Store bis zum schweren Verdunkelungsstoff.',
                'description_tr' => 'Pileli, kuş gözlü ya da dalga formunda fon perde, yan perde ve tül — hafif tülden ağır karartma kumaşına.',
            ],
            [
                'slug' => 'plissees', 'icon' => 'bi-layers', 'image' => $img('kat-plissee'),
                'name' => 'Plissees', 'name_tr' => 'Plise Perde',
                'description' => 'Der Allrounder für Dachfenster und schwierige Formen: von oben und unten verstellbar, auch als Wabenplissee mit Isolierwirkung.',
                'description_tr' => 'Çatı pencereleri ve zor formlar için ideal: alttan ve üstten ayarlanabilir, yalıtım sağlayan petek plise seçeneğiyle.',
            ],
            [
                'slug' => 'rollos', 'icon' => 'bi-window-sidebar', 'image' => $img('kat-rollo'),
                'name' => 'Rollos', 'name_tr' => 'Stor Perde',
                'description' => 'Klare Linien, viel Stoffauswahl: Seitenzug- und Kettenzugrollos, Doppelrollos und komplette Verdunkelung fürs Schlafzimmer.',
                'description_tr' => 'Net çizgiler, geniş kumaş seçeneği: zincir mekanizmalı storlar, zebra (çift) storlar ve yatak odası için tam karartma.',
            ],
            [
                'slug' => 'jalousien', 'icon' => 'bi-list', 'image' => $img('kat-jalousien'),
                'name' => 'Jalousien', 'name_tr' => 'Jaluzi',
                'description' => 'Licht dosieren statt aussperren: Holz-, Bambus- und Aluminiumlamellen in 25 bis 50 mm, stufenlos kippbar.',
                'description_tr' => 'Işığı kesmek yerine ayarlamak: 25–50 mm ahşap, bambu ve alüminyum lameller, kademesiz açı ayarı.',
            ],
            [
                'slug' => 'lamellenvorhaenge', 'icon' => 'bi-distribute-vertical', 'image' => $img('kat-lamellen'),
                'name' => 'Lamellenvorhänge', 'name_tr' => 'Dikey Lamelli Perde',
                'description' => 'Für breite Fensterfronten und Terrassentüren: vertikale Lamellen, die sich drehen und komplett zur Seite schieben lassen.',
                'description_tr' => 'Geniş pencere cepheleri ve teras kapıları için: dönebilen ve tamamen yana toplanabilen dikey lameller.',
            ],
            [
                'slug' => 'teppiche', 'icon' => 'bi-grid-3x3', 'image' => $img('kat-teppiche'),
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
                'name_tr'       => $nameTr,
                'cover'         => $img($image),
                'images'        => [$img($image)],
                'short_desc'    => $short,
                'short_desc_tr' => $shortTr,
                // Kısa açıklamayı tekrar etmiyoruz — detay sayfasında ikisi üst üste görünür.
                'description'   => 'Dieses Modell fertigen wir nach Maß: Sie bestimmen Breite, Höhe, Farbe, '
                    . 'Lichtdurchlässigkeit und Bedienseite. Die Montage übernehmen unsere eigenen Monteure.' . "\n\n"
                    . 'Der angegebene Preis ist ein Ausgangspreis und hängt von Maß, Stoff und Ausführung ab. '
                    . 'Ihren verbindlichen Festpreis erhalten Sie nach dem kostenlosen Aufmaß — dabei zeigen wir '
                    . 'Ihnen alle Stoffe und Farben anhand von Musterbüchern in Ihren eigenen Räumen.',
                'description_tr' => 'Bu modeli ölçüye özel üretiyoruz: eni, boyu, rengi, ışık geçirgenliğini ve '
                    . 'kumanda yönünü siz belirliyorsunuz. Montajı kendi ekibimiz yapıyor.' . "\n\n"
                    . 'Belirtilen fiyat başlangıç fiyatıdır; ölçüye, kumaşa ve uygulamaya göre değişir. '
                    . 'Bağlayıcı sabit fiyatınızı ücretsiz ölçüden sonra alıyorsunuz — o randevuda tüm kumaş ve '
                    . 'renkleri numune kitaplarıyla kendi mekânınızda gösteriyoruz.',
                'price'      => $price,
                'price_unit' => $unit,
                'attributes' => $attrs,
                'featured'   => $featured,
                'sira'       => $i + 1,
                'durum'      => true,
            ]);
        }

        /* ---------------- Yapılan işler ---------------- */
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
                'title_tr'   => $titleTr,
                'kind'       => $kind,
                'kind_tr'    => $kindTr,
                'location'   => $loc,
                'cover'      => $img($image),
                'images'     => [$img($image)],
                'summary'    => $summary,
                'summary_tr' => $summaryTr,
                'content'    => $summary . "\n\n"
                    . 'Ablauf wie immer: kostenloses Aufmaß vor Ort, schriftliches Angebot, Fertigung nach Maß und '
                    . 'Montage durch unsere eigenen Monteure.',
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
                'title_tr'   => $city,
                'comment'    => $comment,
                'comment_tr' => $commentTr,
                'stars'      => $stars,
                'durum'      => true,
            ]);
        }
    }
}
