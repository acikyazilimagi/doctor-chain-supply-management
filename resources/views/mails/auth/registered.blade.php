@extends('mails.layouts.default')

@section('content')
    <p>Sayın Dr. {{ $user->full_name }},</p>
    <p></p>
    <p>Hoş Geldiniz!</p>
    <p></p>
    <p>Öncelikle yaşadığımız bu felaketten ötürü hepimize çok geçmiş olsun.</p>
    <p></p>
    <p>Bu zor günleri birlikte aşmak ve yaralarımızı birlikte sarmak adına ihtiyacımız olan yegane şey hepimizin bildiği üzere dayanışma içerisinde olmak. Bu düşünceden hareket ve güç ile; doktorlarımızın sahadaki ihtiyaçlarının en hızlı ve en doğru şekilde karşılanması için Açık Yazılım Ağı #AYA ekibi olarak medikalihtiyac.org platformunu kurduk.</p>
    <p></p>
    <p>Bu platform üzerinden ilaç, medikal ürün, giyim, barınma vb. tüm ihtiyaçlarınız için buradan istek oluşturabilir, diğer doktorlar tarafından oluşturulan ihtiyaç listelerini de görüntüleyebilirsiniz.</p>
    <p></p>
    <p>Güvenlik dolayısıyla lütfen kayıt linkini tıp doktoru olmayan kişilerle paylaşmayınız. Aksi taktirde sorumluluk sizin üzerinizde olacak ve yasal işlem başlatılacaktır.</p>
    <p></p>
    <p>Bu projeyi geliştirmemizdeki desteğinden ötürü Türk Tabipler Birliği'ne teşekkürlerimizi sunuyoruz.</p>
    <p></p>
    <p>Yaralarımızı hep beraber sarmak ve bir daha yaşanmaması ümidiyle..</p>
    <p></p>
    <p>Açık Yazılım Ağı x TTB</p>
@endsection
