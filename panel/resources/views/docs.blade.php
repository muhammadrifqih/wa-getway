@if(auth()->check())
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('API Documentation') }}
        </h2>
    </x-slot>
@else
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>API Documentation - {{ config('app.name', 'Laravel') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <!-- Guest Navigation -->
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="/">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>
                        <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                    </div>
                </div>
            </div>
        </nav>
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    API Documentation
                </h2>
            </div>
        </header>
@endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-2xl font-bold mb-4">Panduan Lengkap WhatsApp Gateway</h3>
                    <p class="mb-8 text-gray-600">Selamat datang di Dokumentasi Resmi WhatsApp Gateway. Panduan ini akan membantu Anda memahami cara menggunakan panel kami dari awal hingga melakukan integrasi API.</p>

                    <!-- Section 1: Devices -->
                    <div class="mb-8 border-b border-gray-100 pb-6">
                        <div class="flex items-center mb-2">
                            <div class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-3">1</div>
                            <h4 class="text-xl font-semibold text-gray-800">Menghubungkan Perangkat WhatsApp</h4>
                        </div>
                        <p class="text-sm text-gray-700 mb-3 ml-11">Sebelum bisa mengirim atau menerima pesan, Anda harus menyambungkan nomor WhatsApp Anda terlebih dahulu ke dalam sistem.</p>
                        <ol class="list-decimal pl-16 mb-4 text-sm text-gray-600 space-y-1">
                            <li>Buka menu <strong>Devices</strong> di navigasi atas.</li>
                            <li>Klik tombol <strong>"Add Device"</strong> dan masukkan nama perangkat (misal: "CS Admin 1").</li>
                            <li>Tunggu beberapa saat hingga <strong>QR Code</strong> muncul di layar.</li>
                            <li>Buka aplikasi WhatsApp di HP Anda > Pilih <strong>Perangkat Taut</strong> (Linked Devices) > <strong>Tautkan Perangkat</strong>.</li>
                            <li>Scan QR Code tersebut. Jika berhasil, status perangkat akan berubah menjadi <span class="text-green-600 font-semibold">Connected</span>.</li>
                        </ol>
                        <div class="ml-11 bg-yellow-50 border-l-4 border-yellow-400 p-3 text-sm text-yellow-800">
                            <strong>Catatan:</strong> Jika Anda memiliki lebih dari satu perangkat yang "Connected", sistem API secara otomatis akan memilih perangkat pertama Anda, kecuali Anda menentukan ID perangkat di parameter pengiriman.
                        </div>
                    </div>

                    <!-- Section 2: API Keys -->
                    <div class="mb-8 border-b border-gray-100 pb-6">
                        <div class="flex items-center mb-2">
                            <div class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-3">2</div>
                            <h4 class="text-xl font-semibold text-gray-800">Membuat API Key</h4>
                        </div>
                        <p class="text-sm text-gray-700 mb-3 ml-11">Untuk alasan keamanan, semua komunikasi dengan server kami harus diotentikasi menggunakan <strong>API Key</strong> (Kunci API).</p>
                        <ol class="list-decimal pl-16 mb-4 text-sm text-gray-600 space-y-1">
                            <li>Buka menu <strong>API Keys</strong>.</li>
                            <li>Klik tombol <strong>"Create API Key"</strong> dan beri nama kunci tersebut (misal: "Server Toko Online").</li>
                            <li>Kunci API yang terbentuk berawalan <code class="bg-gray-100 px-1 rounded">wa_live_...</code>. <strong>Salin dan simpan kunci ini dengan baik!</strong></li>
                        </ol>
                        <p class="text-sm text-gray-700 ml-11">Setiap mengirim permintaan (request) ke API, Anda wajib menyertakan kunci ini di bagian <strong>Header</strong> HTTP: <br>
                        <code class="bg-gray-100 px-2 py-1 rounded inline-block mt-2 font-mono text-indigo-700">Authorization: Bearer KUNCI_API_ANDA</code></p>
                    </div>

                    <!-- Section 3: Sending Messages -->
                    <div class="mb-8 border-b border-gray-100 pb-6">
                        <div class="flex items-center mb-2">
                            <div class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-3">3</div>
                            <h4 class="text-xl font-semibold text-gray-800">Mengirim Pesan melalui API</h4>
                        </div>
                        
                        <div class="ml-11">
                            <div class="bg-gray-900 text-green-400 p-3 rounded text-sm font-mono mb-4 w-max">
                                POST {{ url('/api/v1/messages') }}
                            </div>
                            
                            <h5 class="font-medium text-gray-800 mb-2">Parameter JSON:</h5>
                            <table class="min-w-full divide-y divide-gray-200 mb-4 border rounded overflow-hidden">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Parameter</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Wajib</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 text-sm">
                                    <tr>
                                        <td class="px-4 py-2 font-mono text-red-600">target</td>
                                        <td class="px-4 py-2 text-red-500 font-bold">Ya</td>
                                        <td class="px-4 py-2 text-gray-600">Nomor WhatsApp tujuan (Contoh: 0812... / 62812...).</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2 font-mono text-red-600">message</td>
                                        <td class="px-4 py-2 text-red-500 font-bold">Ya</td>
                                        <td class="px-4 py-2 text-gray-600">Isi pesan teks. (Berfungsi sebagai keterangan/caption jika mengirim file).</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2 font-mono text-gray-600">device_id</td>
                                        <td class="px-4 py-2 text-gray-400">Tidak</td>
                                        <td class="px-4 py-2 text-gray-600">ID Device jika Anda memiliki lebih dari 1 nomor pengirim aktif.</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2 font-mono text-indigo-600">media_url</td>
                                        <td class="px-4 py-2 text-gray-400">Tidak</td>
                                        <td class="px-4 py-2 text-gray-600">URL lengkap untuk mengirim Foto/Video/File (Contoh: https://web.com/foto.jpg).</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2 font-mono text-indigo-600">media_mimetype</td>
                                        <td class="px-4 py-2 text-gray-400">Tidak</td>
                                        <td class="px-4 py-2 text-gray-600">Sangat disarankan! (image/jpeg, image/png, video/mp4, application/pdf).</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="bg-blue-50 border border-blue-100 p-4 rounded-lg mb-4">
                                <h5 class="font-bold text-blue-800 mb-2">💡 Tips Format Tampilan Media</h5>
                                <p class="text-sm text-blue-700 mb-2">Sistem Gateway mendeteksi tipe file menggunakan parameter <code>media_mimetype</code>:</p>
                                <ul class="list-disc pl-5 text-sm text-blue-700 space-y-1">
                                    <li>Untuk menampilkan <strong>Foto/Gambar langsung</strong>: gunakan <code>"image/jpeg"</code> atau <code>"image/png"</code></li>
                                    <li>Untuk menampilkan <strong>Video langsung</strong>: gunakan <code>"video/mp4"</code></li>
                                    <li>Untuk mengirim <strong>Pesan Suara/Musik</strong>: gunakan <code>"audio/mpeg"</code></li>
                                    <li>Untuk mengirim <strong>Dokumen File Asli</strong> (termasuk PDF/Excel/Word): gunakan <code>"application/pdf"</code> atau format dokumen lainnya. File yang tidak terdeteksi sebagai gambar/video akan otomatis dikirim sebagai dokumen lampiran.</li>
                                </ul>
                            </div>

                            <h5 class="font-medium text-gray-800 mb-2">Contoh cURL (Pesan Teks Standar):</h5>
                            <pre class="bg-gray-100 p-3 rounded text-sm overflow-x-auto text-gray-800 border border-gray-200 mb-4">
curl -X POST {{ url('/api/v1/messages') }} \
-H "Content-Type: application/json" \
-H "Accept: application/json" \
-H "Authorization: Bearer wa_live_KUNCI_API_ANDA_DISINI" \
-d '{
    "target": "08123456789",
    "message": "Halo, ini pesan percobaan dari API!"
}'</pre>
                            
                            <h5 class="font-medium text-gray-800 mb-2">Contoh cURL (Mengirim Gambar/Video/PDF):</h5>
                            <pre class="bg-gray-100 p-3 rounded text-sm overflow-x-auto text-gray-800 border border-gray-200">
curl -X POST {{ url('/api/v1/messages') }} \
-H "Content-Type: application/json" \
-H "Accept: application/json" \
-H "Authorization: Bearer wa_live_KUNCI_API_ANDA_DISINI" \
-d '{
    "target": "08123456789",
    "message": "Cek foto produk terbaru kami! 📸",
    "media_url": "https://picsum.photos/400/300",
    "media_mimetype": "image/jpeg"
}'</pre>
                            
                            <h5 class="font-medium text-gray-800 mb-2 mt-4">Contoh cURL (Mengirim Peta / Share Lokasi):</h5>
                            <pre class="bg-gray-100 p-3 rounded text-sm overflow-x-auto text-gray-800 border border-gray-200">
curl -X POST {{ url('/api/v1/messages') }} \
-H "Content-Type: application/json" \
-H "Accept: application/json" \
-H "Authorization: Bearer wa_live_KUNCI_API_ANDA_DISINI" \
-d '{
    "target": "08123456789",
    "latitude": -6.2088,
    "longitude": 106.8456,
    "location_name": "Monas",
    "location_address": "Jakarta Pusat"
}'</pre>
                        </div>
                    </div>

                    <!-- Section 4: Webhooks -->
                    <div class="mb-4">
                        <div class="flex items-center mb-2">
                            <div class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-3">4</div>
                            <h4 class="text-xl font-semibold text-gray-800">Menerima Pesan (Webhooks & Auto-Reply)</h4>
                        </div>
                        <div class="ml-11">
                            <p class="text-sm text-gray-700 mb-3">Gateway dapat meneruskan semua <strong>Pesan Masuk</strong> dari pelanggan langsung ke server pribadi Anda secara <i>real-time</i>.</p>
                            
                            <ol class="list-decimal pl-5 mb-4 text-sm text-gray-600 space-y-1">
                                <li>Buka menu <strong>Webhooks</strong>.</li>
                                <li>Masukkan <strong>URL Webhook</strong> aplikasi Anda (contoh: <code>https://toko.com/webhook</code>) dan centang <strong>Enable Webhook</strong>.</li>
                                <li>Gunakan <strong>Webhook Secret</strong> untuk memvalidasi (otentikasi) HTTP POST yang datang dari sistem kami.</li>
                            </ol>

                            <h5 class="font-medium text-gray-800 mb-2">Format Data JSON (Dikirim oleh Gateway ke Server Anda):</h5>
                            <pre class="bg-gray-100 p-3 rounded text-sm overflow-x-auto text-gray-800 border border-gray-200 mb-4">
{
    "session_id": "session-user-1-abc12345",
    "sender": "6281234567890@s.whatsapp.net",
    "message": "Isi pesan yang dikirim oleh pelanggan",
    "timestamp": 1690000000,
    "type": "message",
    "media_base64": "data:image/jpeg;base64,... (Muncul jika ada gambar/media)",
    "location_data": {
        "latitude": -6.2088,
        "longitude": 106.8456,
        "name": "Monas",
        "address": "Jakarta"
    },
    "signature": "a1b2c3d4e5f6..."
}
</pre>
                <div class="mt-4">
                    <h4 class="font-medium text-gray-900 mb-2">Penjelasan Field:</h4>
                    <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                        <li><code>type</code>: Jenis pesan (<code>message</code>, <code>image</code>, <code>video</code>, <code>audio</code>, <code>document</code>, <code>location</code>).</li>
                        <li><code>media_base64</code>: Jika klien mengirim gambar/video, file tersebut akan disandikan menjadi teks Base64 String yang bisa langsung Anda simpan.</li>
                        <li><code>location_data</code>: Menampilkan Titik Koordinat GPS jika klien Anda mengirim Share Lokasi.</li>
                        <li><code>signature</code>: Hash keamanan HMAC-SHA256 yang dibuat dari seluruh isi <i>payload</i> menggunakan rahasia Webhook Anda.</li>
                    </ul>
                </div>

                            <div class="bg-indigo-50 border border-indigo-100 p-4 rounded-lg mb-6 mt-6">
                                <h5 class="font-bold text-indigo-800 mb-2">⚡ Fitur Balasan Cepat (Auto-Reply)</h5>
                                <p class="text-sm text-indigo-700 mb-2">Alih-alih memanggil API pengiriman pesan secara terpisah, server Webhook Anda dapat merespons (HTTP 200) dengan mencetak JSON sederhana untuk <strong>membalas otomatis (bot)</strong>:</p>
                                <pre class="bg-white p-2 rounded border border-indigo-200 text-sm text-gray-800">
{
  "reply": "Barang selalu ready, kak! Silakan diorder."
}</pre>
                            </div>

                            <h5 class="font-medium text-gray-800 mb-2 border-t pt-4">Contoh Kode Cara Mengaturnya (Menerima Webhook):</h5>
                            
                            <!-- PHP Example -->
                            <p class="text-sm text-gray-700 font-semibold mt-3 mb-1">Contoh menggunakan PHP Native:</p>
                            <pre class="bg-gray-800 text-green-400 p-3 rounded text-sm overflow-x-auto border border-gray-700 mb-4">
&lt;?php
// 1. Ambil Webhook Secret dari header
$secret = $_SERVER['HTTP_X_WEBHOOK_SECRET'] ?? '';

// 2. Validasi (Otentikasi)
if ($secret !== 'SECRET_WEBHOOK_ANDA_DARI_DASHBOARD') {
    http_response_code(401);
    die(json_encode(["error" => "Unauthorized"]));
}

// 3. Baca Pesan Masuk
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$pesanTeks = $data['message'] ?? '';
$pengirim = $data['sender'] ?? '';

// 4. Tangkap Gambar / Lokasi (Jika Ada)
if (isset($data['media_base64'])) {
    // Simpan Base64 ke dalam File atau Database
    $base64 = $data['media_base64'];
}

if (isset($data['location_data'])) {
    $lat = $data['location_data']['latitude'];
    $lng = $data['location_data']['longitude'];
}

// 5. Buat Logika Bot & Balas
if (strtolower($pesanTeks) === 'ping') {
    header('Content-Type: application/json');
    echo json_encode(["reply" => "Pong! Bot aktif."]);
    exit;
}
?&gt;</pre>

                            <!-- Node.js Example -->
                            <p class="text-sm text-gray-700 font-semibold mb-1">Contoh menggunakan Node.js (Express):</p>
                            <pre class="bg-gray-800 text-green-400 p-3 rounded text-sm overflow-x-auto border border-gray-700">
app.post('/webhook', (req, res) => {
    const secret = req.headers['x-webhook-secret'];
    
    // Validasi
    if (secret !== 'SECRET_WEBHOOK_ANDA_DARI_DASHBOARD') {
        return res.status(401).json({ error: 'Unauthorized' });
    }

    const { message, sender, media_base64, location_data } = req.body;
    
    if (location_data) {
        console.log(`Titik GPS dikirim: ${location_data.latitude}, ${location_data.longitude}`);
    }

    if (media_base64) {
        console.log(`Menerima File Gambar Base64`);
    }
    
    // Logika Bot
    if (message.toLowerCase() === 'halo') {
        return res.json({ reply: 'Halo juga! Ada yang bisa dibantu?' });
    }
    
    res.json({ success: true });
});</pre>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@if(auth()->check())
</x-app-layout>
@else
    </body>
</html>
@endif
