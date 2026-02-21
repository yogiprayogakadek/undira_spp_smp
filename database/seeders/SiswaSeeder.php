<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Nama depan laki-laki khas NTT/Indonesia
        $namaDepanL = [
            'Adrianus', 'Benediktus', 'Bonefasius', 'Daniel', 'Darius',
            'Emanuel', 'Fabianus', 'Gregorius', 'Hendrikus', 'Ignatius',
            'Kornelius', 'Laurensius', 'Martinus', 'Nikolaus', 'Oktavianus',
            'Petrus', 'Reinaldy', 'Sebastianus', 'Titus', 'Valentinus',
            'Wilfridus', 'Yohanes', 'Zefanias', 'Aloysius', 'Bernadus',
            'Donatus', 'Florianus', 'Hilarius', 'Isidorus', 'Josephus',
            'Maximilianus', 'Paskalis', 'Rafael', 'Silvester', 'Teodorus',
        ];

        // Nama depan perempuan khas NTT/Indonesia
        $namaDepanP = [
            'Agustina', 'Bernadeta', 'Cicilia', 'Delfia', 'Elfridis',
            'Florentina', 'Gratia', 'Helena', 'Imelda', 'Juliana',
            'Katarina', 'Lucia', 'Maria', 'Natalia', 'Oktavia',
            'Patrisia', 'Regina', 'Stefania', 'Teresa', 'Ursula',
            'Veronica', 'Wahyuni', 'Yuliana', 'Zelda', 'Anastasia',
            'Benedikta', 'Christina', 'Dorothea', 'Ethelbertha', 'Felisitas',
            'Herminia', 'Inviolata', 'Klara', 'Leonarda', 'Magdalena',
        ];

        // Nama belakang / marga NTT
        $namaBelakang = [
            'Bera', 'Dhae', 'Meo', 'Ngongo', 'Pati', 'Raga', 'Soi',
            'Talo', 'Wake', 'Wolo', 'Kaka', 'Ledo', 'Mosa', 'Nono',
            'Pado', 'Rana', 'Siga', 'Tua', 'Ule', 'Wea', 'Deo',
            'Fera', 'Goa', 'Holo', 'Ino', 'Jawa', 'Kara', 'Lewa',
        ];

        // Tempat lahir di NTT / Nagekeo
        $tempatLahir = [
            'Mauponggo', 'Bajawa', 'Ende', 'Kupang', 'Ruteng',
            'Borong', 'Maumere', 'Larantuka', 'Mbay', 'Aegela',
            'Tonggo', 'Danga', 'Nangaroro', 'Boawae', 'Nggela',
        ];

        $agama = ['Katolik', 'Islam', 'Kristen Protestan'];
        $agamaPeluang = array_merge(
            array_fill(0, 70, 'Katolik'),
            array_fill(0, 20, 'Islam'),
            array_fill(0, 10, 'Kristen Protestan')
        );

        $tahunAjaran = ['2023', '2024', '2025'];

        // Ambil semua kelas yang sudah di-seed
        $kelas = DB::table('kelas')->orderBy('id')->get();

        $nisCounter = 2500001;

        foreach ($kelas as $k) {
            // Jumlah siswa per kelas: antara 30-40
            $total = rand(30, 40);

            // Tentukan jumlah laki-laki & perempuan (campuran)
            $jumlahL = intval($total * (rand(45, 55) / 100));
            $jumlahP = $total - $jumlahL;

            $siswas = [];

            // Generate laki-laki
            $usedNamaL = [];
            for ($i = 0; $i < $jumlahL; $i++) {
                do {
                    $depan = $namaDepanL[array_rand($namaDepanL)];
                    $belakang = $namaBelakang[array_rand($namaBelakang)];
                    $nama = $depan . ' ' . $belakang;
                } while (in_array($nama, $usedNamaL));
                $usedNamaL[] = $nama;

                $siswas[] = [
                    'kelas_id'      => $k->id,
                    'nama_lengkap'  => $nama,
                    'nis'           => (string) $nisCounter++,
                    'tempat_lahir'  => $tempatLahir[array_rand($tempatLahir)],
                    'tanggal_lahir' => $this->randomTanggalLahir(),
                    'agama'         => $agamaPeluang[array_rand($agamaPeluang)],
                    'jenis_kelamin' => 'laki-laki',
                    'email'         => null,
                    'no_telp'       => '08' . rand(100000000, 999999999),
                    'alamat'        => 'Desa ' . $tempatLahir[array_rand($tempatLahir)] . ', Kab. Nagekeo',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }

            // Generate perempuan
            $usedNamaP = [];
            for ($i = 0; $i < $jumlahP; $i++) {
                do {
                    $depan = $namaDepanP[array_rand($namaDepanP)];
                    $belakang = $namaBelakang[array_rand($namaBelakang)];
                    $nama = $depan . ' ' . $belakang;
                } while (in_array($nama, $usedNamaP));
                $usedNamaP[] = $nama;

                $siswas[] = [
                    'kelas_id'      => $k->id,
                    'nama_lengkap'  => $nama,
                    'nis'           => (string) $nisCounter++,
                    'tempat_lahir'  => $tempatLahir[array_rand($tempatLahir)],
                    'tanggal_lahir' => $this->randomTanggalLahir(),
                    'agama'         => $agamaPeluang[array_rand($agamaPeluang)],
                    'jenis_kelamin' => 'perempuan',
                    'email'         => null,
                    'no_telp'       => '08' . rand(100000000, 999999999),
                    'alamat'        => 'Desa ' . $tempatLahir[array_rand($tempatLahir)] . ', Kab. Nagekeo',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }

            // Acak urutan (campur L & P)
            shuffle($siswas);

            DB::table('siswa')->insert($siswas);
        }
    }

    private function randomTanggalLahir(): string
    {
        // Rentang usia siswa SMP: 11-16 tahun
        $tahun = rand(2009, 2013);
        $bulan = rand(1, 12);
        $hari  = rand(1, 28);

        return sprintf('%04d-%02d-%02d', $tahun, $bulan, $hari);
    }
}
