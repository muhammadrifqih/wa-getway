<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('API Documentation') }}
        </h2>
    </x-slot>

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
  "session_id": "session-user-1-abc1234",
  "sender": "628123456789@s.whatsapp.net",
  "message": "Halo Min, barang ready?",
  "timestamp": 1787508732,
  "type": "message",
  "signature": "a3f5c8b..."
}</pre>

                            <div class="bg-indigo-50 border border-indigo-100 p-4 rounded-lg mb-6">
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

// 4. Buat Logika Bot & Balas
if (strtolower($data['message']) === 'ping') {
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

    const { message, sender } = req.body;
    
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
</x-app-layout>
