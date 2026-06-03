<?php

use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TarifSpp;
use App\Models\TagihanSpp;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('kenaikan kelas page is restricted to admin only', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $bendahara = User::factory()->create(['role' => 'bendahara']);

    // Admin can access
    $response = $this->actingAs($admin)->get('/kenaikan-kelas');
    $response->assertStatus(200);

    // Bendahara is blocked
    $response = $this->actingAs($bendahara)->get('/kenaikan-kelas');
    $response->assertStatus(403);
});

test('can fetch active students by class id', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $kelas = Kelas::create(['nama' => '7 A', 'tingkat' => 7]);
    
    $siswa1 = Siswa::create([
        'kelas_id' => $kelas->id,
        'nama_lengkap' => 'Siswa Aktif',
        'nis' => '123456',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2010-01-01',
        'agama' => 'Islam',
        'jenis_kelamin' => 'laki-laki',
        'no_telp' => '0812345678',
        'alamat' => 'Jl. Merdeka',
        'status' => 'aktif',
    ]);

    $siswa2 = Siswa::create([
        'kelas_id' => $kelas->id,
        'nama_lengkap' => 'Siswa Lulus',
        'nis' => '123457',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2010-01-01',
        'agama' => 'Islam',
        'jenis_kelamin' => 'laki-laki',
        'no_telp' => '0812345678',
        'alamat' => 'Jl. Merdeka',
        'status' => 'lulus',
    ]);

    $response = $this->actingAs($admin)->getJson('/kenaikan-kelas/siswa?kelas_id=' . $kelas->id);
    
    $response->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment(['nama_lengkap' => 'Siswa Aktif']);
});

test('can promote students and auto generate bills', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $kelasAsal = Kelas::create(['nama' => '7 A', 'tingkat' => 7]);
    $kelasTujuan = Kelas::create(['nama' => '8 A', 'tingkat' => 8]);

    // Create Tarif for Grade 8
    TarifSpp::create([
        'tingkat' => 8,
        'tahun_ajaran' => '2026/2027',
        'nominal' => 200000,
        'keterangan' => 'Tarif Grade 8 T.A 2026/2027',
    ]);

    $siswa = Siswa::create([
        'kelas_id' => $kelasAsal->id,
        'nama_lengkap' => 'Promoted Student',
        'nis' => '123456',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2010-01-01',
        'agama' => 'Islam',
        'jenis_kelamin' => 'laki-laki',
        'no_telp' => '0812345678',
        'alamat' => 'Jl. Merdeka',
        'status' => 'aktif',
    ]);

    $response = $this->actingAs($admin)->post('/kenaikan-kelas/proses', [
        'kelas_asal_id' => $kelasAsal->id,
        'tipe_proses' => 'naik',
        'kelas_tujuan_id' => $kelasTujuan->id,
        'tahun_ajaran_baru' => '2026/2027',
        'siswa_ids' => [$siswa->id]
    ]);

    $response->assertRedirect(route('kenaikan-kelas.index'));
    
    // Check student has class updated
    $this->assertDatabaseHas('siswa', [
        'id' => $siswa->id,
        'kelas_id' => $kelasTujuan->id,
        'status' => 'aktif'
    ]);

    // Check SPP bills are generated (12 months)
    $this->assertDatabaseCount('tagihan_spp', 12);
    $this->assertDatabaseHas('tagihan_spp', [
        'siswa_id' => $siswa->id,
        'nominal' => 200000,
        'tahun' => 2026,
    ]);
});

test('can graduate students', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $kelasAsal = Kelas::create(['nama' => '9 A', 'tingkat' => 9]);

    $siswa = Siswa::create([
        'kelas_id' => $kelasAsal->id,
        'nama_lengkap' => 'Graduated Student',
        'nis' => '123456',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2010-01-01',
        'agama' => 'Islam',
        'jenis_kelamin' => 'laki-laki',
        'no_telp' => '0812345678',
        'alamat' => 'Jl. Merdeka',
        'status' => 'aktif',
    ]);

    $response = $this->actingAs($admin)->post('/kenaikan-kelas/proses', [
        'kelas_asal_id' => $kelasAsal->id,
        'tipe_proses' => 'lulus',
        'siswa_ids' => [$siswa->id]
    ]);

    $response->assertRedirect(route('kenaikan-kelas.index'));

    $this->assertDatabaseHas('siswa', [
        'id' => $siswa->id,
        'kelas_id' => null,
        'status' => 'lulus'
    ]);
});

test('admin can update unpaid spp bill nominal', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $kelas = Kelas::create(['nama' => '7 A', 'tingkat' => 7]);
    $siswa = Siswa::create([
        'kelas_id' => $kelas->id,
        'nama_lengkap' => 'Student',
        'nis' => '123456',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2010-01-01',
        'agama' => 'Islam',
        'jenis_kelamin' => 'laki-laki',
        'no_telp' => '0812345678',
        'alamat' => 'Jl. Merdeka',
        'status' => 'aktif',
    ]);
    $tarif = TarifSpp::create([
        'tingkat' => 7,
        'tahun_ajaran' => '2026/2027',
        'nominal' => 200000,
        'keterangan' => 'Tarif 7',
    ]);
    $tagihan = TagihanSpp::create([
        'siswa_id' => $siswa->id,
        'tarif_spp_id' => $tarif->id,
        'bulan' => 7,
        'tahun' => 2026,
        'nominal' => 200000,
        'status' => 'belum_bayar',
    ]);

    $response = $this->actingAs($admin)->put('/tagihan-spp/update/' . $tagihan->id, [
        'nominal' => 150000,
    ]);

    $response->assertRedirect(route('tagihan-spp.index'));
    
    $this->assertDatabaseHas('tagihan_spp', [
        'id' => $tagihan->id,
        'nominal' => 150000,
    ]);
});

test('cannot update paid or partially paid spp bill nominal', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $kelas = Kelas::create(['nama' => '7 A', 'tingkat' => 7]);
    $siswa = Siswa::create([
        'kelas_id' => $kelas->id,
        'nama_lengkap' => 'Student',
        'nis' => '123456',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2010-01-01',
        'agama' => 'Islam',
        'jenis_kelamin' => 'laki-laki',
        'no_telp' => '0812345678',
        'alamat' => 'Jl. Merdeka',
        'status' => 'aktif',
    ]);
    $tarif = TarifSpp::create([
        'tingkat' => 7,
        'tahun_ajaran' => '2026/2027',
        'nominal' => 200000,
        'keterangan' => 'Tarif 7',
    ]);
    $tagihan = TagihanSpp::create([
        'siswa_id' => $siswa->id,
        'tarif_spp_id' => $tarif->id,
        'bulan' => 7,
        'tahun' => 2026,
        'nominal' => 200000,
        'status' => 'lunas',
    ]);

    $response = $this->actingAs($admin)->put('/tagihan-spp/update/' . $tagihan->id, [
        'nominal' => 150000,
    ]);

    $response->assertSessionHas('error');
    
    $this->assertDatabaseHas('tagihan_spp', [
        'id' => $tagihan->id,
        'nominal' => 200000,
    ]);
});

test('spp generation redirects with student id and filters listing', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $kelas = Kelas::create(['nama' => '7 A', 'tingkat' => 7]);
    
    $siswa1 = Siswa::create([
        'kelas_id' => $kelas->id,
        'nama_lengkap' => 'Siswa Satu',
        'nis' => '123456',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2010-01-01',
        'agama' => 'Islam',
        'jenis_kelamin' => 'laki-laki',
        'no_telp' => '0812345678',
        'alamat' => 'Jl. Merdeka',
        'status' => 'aktif',
    ]);
    
    $siswa2 = Siswa::create([
        'kelas_id' => $kelas->id,
        'nama_lengkap' => 'Siswa Dua',
        'nis' => '123457',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2010-01-01',
        'agama' => 'Islam',
        'jenis_kelamin' => 'laki-laki',
        'no_telp' => '0812345678',
        'alamat' => 'Jl. Merdeka',
        'status' => 'aktif',
    ]);

    $tarif = TarifSpp::create([
        'tingkat' => 7,
        'tahun_ajaran' => '2026/2027',
        'nominal' => 200000,
        'keterangan' => 'Tarif 7',
    ]);

    // Request generate for Siswa Satu
    $response = $this->actingAs($admin)->post('/tagihan-spp/generate', [
        'siswa_id' => $siswa1->id,
        'tahun_ajaran' => '2026/2027',
    ]);

    // Assert redirect has siswa_id query param
    $response->assertRedirect(route('tagihan-spp.index', ['siswa_id' => $siswa1->id]));

    // Also request generate for Siswa Dua
    $this->actingAs($admin)->post('/tagihan-spp/generate', [
        'siswa_id' => $siswa2->id,
        'tahun_ajaran' => '2026/2027',
    ]);

    // Assert that when calling the index with ajax and siswa_id, it returns ONLY that student's bills
    $ajaxResponse = $this->actingAs($admin)->getJson('/tagihan-spp?siswa_id=' . $siswa1->id, [
        'HTTP_X-Requested-With' => 'XMLHttpRequest'
    ]);

    $ajaxResponse->assertStatus(200);
    $data = $ajaxResponse->json('data');
    
    // Total bills should be 12 (since each student gets 12 months)
    $this->assertCount(12, $data);
    foreach ($data as $row) {
        $this->assertEquals('Siswa Satu', $row['siswa_nama']);
    }
});

test('spp listing can be filtered by class and is sorted by class name when showing all', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $kelasA = Kelas::create(['nama' => '7 A', 'tingkat' => 7]);
    $kelasB = Kelas::create(['nama' => '7 B', 'tingkat' => 7]);
    
    $siswaA = Siswa::create([
        'kelas_id' => $kelasA->id,
        'nama_lengkap' => 'Siswa A Kelas A',
        'nis' => '123456',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2010-01-01',
        'agama' => 'Islam',
        'jenis_kelamin' => 'laki-laki',
        'no_telp' => '0812345678',
        'alamat' => 'Jl. Merdeka',
        'status' => 'aktif',
    ]);
    
    $siswaB = Siswa::create([
        'kelas_id' => $kelasB->id,
        'nama_lengkap' => 'Siswa B Kelas B',
        'nis' => '123457',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2010-01-01',
        'agama' => 'Islam',
        'jenis_kelamin' => 'laki-laki',
        'no_telp' => '0812345678',
        'alamat' => 'Jl. Merdeka',
        'status' => 'aktif',
    ]);

    $tarif = TarifSpp::create([
        'tingkat' => 7,
        'tahun_ajaran' => '2026/2027',
        'nominal' => 200000,
        'keterangan' => 'Tarif 7',
    ]);

    // Create 1 bill for Siswa A and 1 for Siswa B
    TagihanSpp::create([
        'siswa_id' => $siswaA->id,
        'tarif_spp_id' => $tarif->id,
        'bulan' => 7,
        'tahun' => 2026,
        'nominal' => 200000,
        'status' => 'belum_bayar',
    ]);
    
    TagihanSpp::create([
        'siswa_id' => $siswaB->id,
        'tarif_spp_id' => $tarif->id,
        'bulan' => 7,
        'tahun' => 2026,
        'nominal' => 200000,
        'status' => 'belum_bayar',
    ]);

    // Request index filtered by Kelas B
    $responseB = $this->actingAs($admin)->getJson('/tagihan-spp?kelas_id=' . $kelasB->id, [
        'HTTP_X-Requested-With' => 'XMLHttpRequest'
    ]);
    $responseB->assertStatus(200);
    $dataB = $responseB->json('data');
    $this->assertCount(1, $dataB);
    $this->assertEquals('Siswa B Kelas B', $dataB[0]['siswa_nama']);

    // Request index without filters (shows all, sorted by class)
    $responseAll = $this->actingAs($admin)->getJson('/tagihan-spp', [
        'HTTP_X-Requested-With' => 'XMLHttpRequest'
    ]);
    $responseAll->assertStatus(200);
    $dataAll = $responseAll->json('data');
    $this->assertCount(2, $dataAll);
    
    // Sort ordering: Kelas 7 A (Siswa A) first, then Kelas 7 B (Siswa B)
    $this->assertEquals('Siswa A Kelas A', $dataAll[0]['siswa_nama']);
    $this->assertEquals('Siswa B Kelas B', $dataAll[1]['siswa_nama']);
});

test('spp listing can be filtered by tingkat', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $kelas7 = Kelas::create(['nama' => '7 A', 'tingkat' => 7]);
    $kelas8 = Kelas::create(['nama' => '8 A', 'tingkat' => 8]);
    
    $siswa7 = Siswa::create([
        'kelas_id' => $kelas7->id,
        'nama_lengkap' => 'Siswa Kelas 7',
        'nis' => '123456',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2010-01-01',
        'agama' => 'Islam',
        'jenis_kelamin' => 'laki-laki',
        'no_telp' => '0812345678',
        'alamat' => 'Jl. Merdeka',
        'status' => 'aktif',
    ]);
    
    $siswa8 = Siswa::create([
        'kelas_id' => $kelas8->id,
        'nama_lengkap' => 'Siswa Kelas 8',
        'nis' => '123457',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2010-01-01',
        'agama' => 'Islam',
        'jenis_kelamin' => 'laki-laki',
        'no_telp' => '0812345678',
        'alamat' => 'Jl. Merdeka',
        'status' => 'aktif',
    ]);

    $tarif7 = TarifSpp::create([
        'tingkat' => 7,
        'tahun_ajaran' => '2026/2027',
        'nominal' => 200000,
        'keterangan' => 'Tarif 7',
    ]);
    $tarif8 = TarifSpp::create([
        'tingkat' => 8,
        'tahun_ajaran' => '2026/2027',
        'nominal' => 250000,
        'keterangan' => 'Tarif 8',
    ]);

    TagihanSpp::create([
        'siswa_id' => $siswa7->id,
        'tarif_spp_id' => $tarif7->id,
        'bulan' => 7,
        'tahun' => 2026,
        'nominal' => 200000,
        'status' => 'belum_bayar',
    ]);
    
    TagihanSpp::create([
        'siswa_id' => $siswa8->id,
        'tarif_spp_id' => $tarif8->id,
        'bulan' => 7,
        'tahun' => 2026,
        'nominal' => 250000,
        'status' => 'belum_bayar',
    ]);

    // Request index filtered by tingkat 7
    $response = $this->actingAs($admin)->getJson('/tagihan-spp?tingkat=7', [
        'HTTP_X-Requested-With' => 'XMLHttpRequest'
    ]);
    $response->assertStatus(200);
    $data = $response->json('data');
    $this->assertCount(1, $data);
    $this->assertEquals('Siswa Kelas 7', $data[0]['siswa_nama']);
});
