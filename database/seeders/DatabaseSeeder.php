<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Badge;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use App\Models\Forum;
use App\Models\ForumComment;
use App\Models\ForumLike;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users (3 main roles, and extra students)
        $admin = User::create([
            'name' => 'Administrator UnitySpace',
            'email' => 'admin@unityspace.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'faculty' => 'Ilmu Komputer',
            'department' => 'Informatika',
            'religion' => 'Islam',
            'points' => 500,
        ]);

        $moderator = User::create([
            'name' => 'Ahmad Moderator',
            'email' => 'moderator@unityspace.ac.id',
            'password' => Hash::make('password'),
            'role' => 'moderator',
            'faculty' => 'Ilmu Sosial dan Politik',
            'department' => 'Sosiologi',
            'religion' => 'Kristen',
            'points' => 300,
        ]);

        $mahasiswa = User::create([
            'name' => 'Budi Mahasiswa',
            'email' => 'mahasiswa@unityspace.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'faculty' => 'Ekonomi dan Bisnis',
            'department' => 'Manajemen',
            'religion' => 'Islam',
            'points' => 150,
        ]);

        $student2 = User::create([
            'name' => 'Clara Indah',
            'email' => 'clara@unityspace.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'faculty' => 'Hukum',
            'department' => 'Ilmu Hukum',
            'religion' => 'Katolik',
            'points' => 240,
        ]);

        $student3 = User::create([
            'name' => 'Dewa Putu',
            'email' => 'dewa@unityspace.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'faculty' => 'Teknik',
            'department' => 'Teknik Sipil',
            'religion' => 'Hindu',
            'points' => 90,
        ]);

        $student4 = User::create([
            'name' => 'Evelyn Wijaya',
            'email' => 'evelyn@unityspace.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'faculty' => 'Kedokteran',
            'department' => 'Pendidikan Dokter',
            'religion' => 'Buddha',
            'points' => 320,
        ]);

        // 2. Seed Badges
        $badge1 = Badge::create([
            'name' => 'Active Volunteer',
            'description' => 'Telah mendaftar dan aktif berpartisipasi dalam minimal 3 kegiatan kemanusiaan lintas agama.',
            'image' => 'volunteer.png',
            'point_requirement' => 60,
            'badge_type' => 'volunteer',
        ]);

        $badge2 = Badge::create([
            'name' => 'Harmony Contributor',
            'description' => 'Aktif berkontribusi membangun diskusi yang inklusif di forum UnitySpace.',
            'image' => 'contributor.png',
            'point_requirement' => 100,
            'badge_type' => 'contributor',
        ]);

        $badge3 = Badge::create([
            'name' => 'Peace Ambassador',
            'description' => 'Menjadi duta perdamaian dengan pencapaian poin tinggi dan memiliki tingkat toleransi yang sangat baik.',
            'image' => 'ambassador.png',
            'point_requirement' => 200,
            'badge_type' => 'ambassador',
        ]);

        // Award some badges to students
        $student2->badges()->attach($badge1, ['earned_at' => Carbon::now()->subDays(5)]);
        $student2->badges()->attach($badge2, ['earned_at' => Carbon::now()->subDays(2)]);
        $student4->badges()->attach($badge1, ['earned_at' => Carbon::now()->subDays(10)]);
        $student4->badges()->attach($badge2, ['earned_at' => Carbon::now()->subDays(4)]);
        $student4->badges()->attach($badge3, ['earned_at' => Carbon::now()]);

        // 3. Seed Tolerance Quiz
        $quiz = Quiz::create([
            'title' => 'Self-Assessment Toleransi & Kebinekaan Kampus',
            'description' => 'Evaluasi tingkat toleransi, empati, dan pemahaman keberagaman Anda secara objektif. Tes ini menggunakan skala Likert (1 - 5) dan dirancang untuk membantu Anda merefleksikan nilai-nilai inklusivitas.',
        ]);

        $questionsData = [
            [
                'text' => 'Saya bersedia berteman erat dan bekerja sama dalam kelompok belajar dengan mahasiswa yang berbeda agama.',
                'dimension' => 'Hubungan Sosial'
            ],
            [
                'text' => 'Saya merasa tidak nyaman jika harus tinggal satu kost atau bertetangga dengan orang yang berbeda keyakinan.',
                'dimension' => 'Kenyamanan Pribadi' // Reverse-scored question! (But in our controller scoring, we will handle this)
            ],
            [
                'text' => 'Setiap kelompok agama di kampus memiliki hak yang sama untuk menyelenggarakan ibadah dan merayakan hari besar keagamaan secara aman.',
                'dimension' => 'Hak Beragama'
            ],
            [
                'text' => 'Saya bersedia menghadiri acara bakti sosial atau diskusi kemanusiaan yang diinisiasi oleh organisasi keagamaan lain.',
                'dimension' => 'Interaksi Lintas Iman'
            ],
            [
                'text' => 'Saya percaya bahwa hanya penganut agama saya sendiri yang memiliki moralitas yang baik dan dapat dipercaya sepenuhnya.',
                'dimension' => 'Stereotip Sosial' // Reverse-scored
            ],
            [
                'text' => 'Saat membantu orang lain yang sedang tertimpa musibah, latar belakang agama mereka sama sekali tidak mempengaruhi kemauan saya untuk menolong.',
                'dimension' => 'Kemanusiaan Universal'
            ],
            [
                'text' => 'Saya mendukung pembangunan atau keberadaan tempat ibadah berbagai agama di lingkungan kampus/sekitar.',
                'dimension' => 'Dukungan Fasilitas'
            ],
            [
                'text' => 'Saya merasa tersinggung atau emosional ketika mendengar perbedaan pandangan tentang nilai keagamaan dalam diskusi akademis.',
                'dimension' => 'Kematangan Berpikir' // Reverse-scored
            ],
            [
                'text' => 'Perbedaan latar belakang suku, ras, dan agama di kalangan mahasiswa merupakan kekuatan sosial kampus yang harus disyukuri.',
                'dimension' => 'Perspektif Keberagaman'
            ],
            [
                'text' => 'Saya bersedia mendengarkan penjelasan teman tentang keyakinannya demi memahami sudut pandang mereka, tanpa berniat mendebat atau menghakimi.',
                'dimension' => 'Komunikasi Dialogis'
            ],
        ];

        // Options details
        // 1=Sangat Tidak Setuju, 2=Tidak Setuju, 3=Ragu-ragu/Netral, 4=Setuju, 5=Sangat Setuju
        // For reverse-scored questions: 1=5 pts, 2=4 pts, 3=3 pts, etc.
        // We will seed standard options for all, and handle reverse points in controller! Or seed specific options with correct point values. Let's seed options for each question:
        foreach ($questionsData as $index => $qData) {
            $question = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_text' => $qData['text'],
                'dimension' => $qData['dimension'],
            ]);

            $isReverse = in_array($index + 1, [2, 5, 8]); // 2nd, 5th, 8th questions are reverse-scored

            $options = [
                ['text' => 'Sangat Tidak Setuju', 'pts' => $isReverse ? 5 : 1],
                ['text' => 'Tidak Setuju', 'pts' => $isReverse ? 4 : 2],
                ['text' => 'Netral / Ragu-ragu', 'pts' => 3],
                ['text' => 'Setuju', 'pts' => $isReverse ? 2 : 4],
                ['text' => 'Sangat Setuju', 'pts' => $isReverse ? 1 : 5],
            ];

            foreach ($options as $opt) {
                QuizAnswer::create([
                    'quiz_question_id' => $question->id,
                    'option_text' => $opt['text'],
                    'points_value' => $opt['pts'],
                ]);
            }
        }

        // 4. Seed Forums & Comments
        $f1 = Forum::create([
            'user_id' => $mahasiswa->id,
            'title' => 'Indahnya Saling Berbagi di Bulan Suci Ramadan dan Paskah yang Beriringan',
            'content' => 'Halo teman-teman! Kemarin saya sangat tersentuh melihat mahasiswa muslim dan kristen di fakultas kami berkolaborasi membagikan takjil gratis dan dilanjutkan dengan makan malam bersama menyambut Paskah. Hal seperti ini menurut saya sangat positif untuk memupuk kebersamaan lintas iman di kampus kita. Bagaimana pengalaman bertoleransi di fakultas kalian?',
            'category' => 'Toleransi',
            'likes_count' => 3,
            'reports_count' => 0,
        ]);

        $f2 = Forum::create([
            'user_id' => $student2->id,
            'title' => 'Bagaimana Cara Menyikapi Candaan Berbau SARA di Grup WhatsApp Angkatan?',
            'content' => 'Hai guys, mau minta pendapat dong. Akhir-akhir ini di grup angkatan sering ada candaan/meme yang memojokkan keyakinan tertentu. Kalau ditegur dibilang "baperan" atau "sensitif amat". Tapi kalau didiamkan malah makin menjadi-jadi dan bikin tidak nyaman. Apa solusi terbaik ya agar candaan seperti ini tidak merusak inklusivitas angkatan?',
            'category' => 'Diskusi Sosial',
            'likes_count' => 5,
            'reports_count' => 0,
        ]);

        $f3 = Forum::create([
            'user_id' => $student3->id,
            'title' => 'Kegiatan Bakti Sosial Lintas Agama: Yuk Bergabung Akhir Pekan Ini!',
            'content' => 'Halo semua! Kami dari UKM Peduli Sosial akan mengadakan aksi bersih-bersih tempat ibadah (masjid, gereja, pura) di sekitar kampus hari Sabtu nanti. Kita juga akan mendistribusikan sembako ke warga sekitar yang membutuhkan tanpa memandang latar belakang. Kuota relawan masih terbuka lebar, yuk daftar di menu Event!',
            'category' => 'Event Kampus',
            'likes_count' => 2,
            'reports_count' => 0,
        ]);

        // Forum Comments
        $c1 = ForumComment::create([
            'forum_id' => $f1->id,
            'user_id' => $student2->id,
            'content' => 'Setuju sekali! Di Fakultas Hukum kemarin juga diadakan buka bersama lintas iman, dihadiri oleh dosen dan mahasiswa dari berbagai latar belakang agama. Suasananya sangat hangat dan bersahabat!',
        ]);

        $c2 = ForumComment::create([
            'forum_id' => $f1->id,
            'user_id' => $student3->id,
            'content' => 'Keren banget! Semoga toleransi indah seperti ini terus dijaga, bukan cuma pas momen hari raya saja tapi juga di kehidupan perkuliahan sehari-hari.',
        ]);

        // Reply to c1
        ForumComment::create([
            'forum_id' => $f1->id,
            'user_id' => $mahasiswa->id,
            'parent_id' => $c1->id,
            'content' => 'Betul Clara! Semoga fakultas-fakultas lain juga bisa menginisiasi kegiatan serupa ya.',
        ]);

        $c3 = ForumComment::create([
            'forum_id' => $f2->id,
            'user_id' => $moderator->id,
            'content' => 'Halo Clara. Masalah ini memang cukup sering terjadi. Saran saya, cobalah sampaikan keberatan secara personal (PC) ke admin grup atau ke pelaku candaan tersebut dengan bahasa yang baik dan santun, jelaskan kenapa itu kurang pantas. Jika tidak berhasil, kamu bisa menggunakan fitur Laporan Intoleransi di UnitySpace ini agar tim kami bisa menindaklanjuti secara persuasif.',
        ]);

        ForumComment::create([
            'forum_id' => $f2->id,
            'user_id' => $student4->id,
            'content' => 'Benar kata Kak Ahmad. Diam bukan solusi, menegur di grup kadang memicu debat kusir. Pendekatan personal jauh lebih efektif.',
        ]);

        // Seed Forum Likes
        ForumLike::create(['forum_id' => $f1->id, 'user_id' => $student2->id]);
        ForumLike::create(['forum_id' => $f1->id, 'user_id' => $student3->id]);
        ForumLike::create(['forum_id' => $f1->id, 'user_id' => $student4->id]);

        ForumLike::create(['forum_id' => $f2->id, 'user_id' => $mahasiswa->id]);
        ForumLike::create(['forum_id' => $f2->id, 'user_id' => $student3->id]);
        ForumLike::create(['forum_id' => $f2->id, 'user_id' => $student4->id]);
        ForumLike::create(['forum_id' => $f2->id, 'user_id' => $moderator->id]);
        ForumLike::create(['forum_id' => $f2->id, 'user_id' => $admin->id]);

        // 5. Seed Events
        $e1 = Event::create([
            'user_id' => $admin->id,
            'title' => 'Dialog Interaktif Mahasiswa: Merajut Kebersamaan dalam Kebinekaan',
            'description' => 'Dialog terbuka antar mahasiswa dari berbagai perwakilan organisasi keagamaan kampus untuk membahas tantangan toleransi beragama saat ini, serta merumuskan aksi nyata dalam mencegah polarisasi di kalangan mahasiswa.',
            'category' => 'dialog lintas agama',
            'poster' => null,
            'quota' => 80,
            'date' => Carbon::now()->addDays(5)->toDateString(),
            'time' => '13:30:00',
            'location' => 'Aula Gedung Utama Lantai 3',
            'points_reward' => 25,
        ]);

        $e2 = Event::create([
            'user_id' => $admin->id,
            'title' => 'Relawan UnitySpace: Bersih-Bersih Rumah Ibadah Lintas Agama',
            'description' => 'Aksi gotong royong mahasiswa lintas iman dalam membersihkan masjid, gereja, dan pura yang terletak di sekitar area kampus guna menyebarkan pesan harmoni dan kebersamaan.',
            'category' => 'volunteer',
            'poster' => null,
            'quota' => 40,
            'date' => Carbon::now()->addDays(12)->toDateString(),
            'time' => '07:30:00',
            'location' => 'Titik Kumpul Lapangan Rektorat',
            'points_reward' => 30,
        ]);

        $e3 = Event::create([
            'user_id' => $moderator->id,
            'title' => 'Bakti Sosial & Buka Bersama Lintas Iman di Panti Asuhan Kasih Bunda',
            'description' => 'Kegiatan sosial berbagi santunan, makanan, dan hiburan edukatif bagi anak-anak panti asuhan, dikelola secara kolaboratif oleh gabungan UKM Keagamaan kampus.',
            'category' => 'bakti sosial',
            'poster' => null,
            'quota' => 50,
            'date' => Carbon::now()->subDays(6)->toDateString(), // past event
            'time' => '16:00:00',
            'location' => 'Panti Asuhan Kasih Bunda, Sleman',
            'points_reward' => 20,
        ]);

        $e4 = Event::create([
            'user_id' => $admin->id,
            'title' => 'Seminar Nasional: Peran Mahasiswa dalam Mewujudkan Kampus Inklusif & Bebas Kekerasan SARA',
            'description' => 'Menghadirkan narasumber dari Kementerian Agama RI dan Komnas HAM untuk membahas kerangka akademis dan praktis dalam menciptakan lingkungan kampus yang aman, toleran, dan terbebas dari segala bentuk diskriminasi.',
            'category' => 'seminar',
            'poster' => null,
            'quota' => 150,
            'date' => Carbon::now()->addDays(2)->toDateString(),
            'time' => '09:00:00',
            'location' => 'Auditorium Kampus Pusat',
            'points_reward' => 15,
        ]);

        // Event registrations
        EventRegistration::create(['event_id' => $e1->id, 'user_id' => $mahasiswa->id, 'status' => 'registered']);
        EventRegistration::create(['event_id' => $e1->id, 'user_id' => $student2->id, 'status' => 'registered']);
        EventRegistration::create(['event_id' => $e1->id, 'user_id' => $student3->id, 'status' => 'registered']);

        EventRegistration::create(['event_id' => $e2->id, 'user_id' => $student2->id, 'status' => 'registered']);
        EventRegistration::create(['event_id' => $e2->id, 'user_id' => $student4->id, 'status' => 'registered']);

        EventRegistration::create(['event_id' => $e3->id, 'user_id' => $mahasiswa->id, 'status' => 'attended']);
        EventRegistration::create(['event_id' => $e3->id, 'user_id' => $student2->id, 'status' => 'attended']);
        EventRegistration::create(['event_id' => $e3->id, 'user_id' => $student4->id, 'status' => 'attended']);

        // 6. Seed Articles
        Article::create([
            'user_id' => $admin->id,
            'title' => 'Membangun Toleransi Sejak dari Kampus: Tips Praktis untuk Mahasiswa',
            'category' => 'toleransi',
            'thumbnail' => null,
            'content' => '<p>Kampus adalah miniatur Indonesia. Di sinilah ribuan mahasiswa dengan latar belakang budaya, suku, ras, dan agama yang berbeda bertemu dan berinteraksi. Membangun iklim toleransi bukan hanya tugas rektorat, melainkan tanggung jawab bersama seluruh sivitas akademika, terutama mahasiswa.</p>
            <p>Berikut beberapa tips praktis yang bisa kamu terapkan sehari-hari:</p>
            <ul>
                <li><strong>Hindari Stereotip:</strong> Jangan menilai seseorang hanya berdasarkan label agamanya. Kenali mereka secara personal.</li>
                <li><strong>Buka Dialog Damai:</strong> Jangan takut berdiskusi tentang perbedaan dengan sikap saling menghargai. Fokuslah mencari titik persamaan kemanusiaan.</li>
                <li><strong>Kolaborasi Lintas Iman:</strong> Bergabunglah dalam kepanitiaan sosial atau unit kegiatan mahasiswa yang anggotanya majemuk. Kebersamaan dalam aksi nyata akan meruntuhkan sekat kecurigaan.</li>
            </ul>
            <p>Mari jadikan kampus kita ruang aman bagi semua keyakinan, sesuai dengan semangat semboyan Bhinneka Tunggal Ika!</p>',
        ]);

        Article::create([
            'user_id' => $admin->id,
            'title' => 'Menilik SDG 16: Bagaimana Kampus Berperan dalam Perdamaian dan Keadilan?',
            'category' => 'SDGs',
            'thumbnail' => null,
            'content' => '<p>SDG 16 bertujuan untuk mempromosikan masyarakat yang damai dan inklusif untuk pembangunan berkelanjutan, menyediakan akses keadilan bagi semua, dan membangun institusi yang efektif, akuntabel, dan inklusif di semua tingkatan.</p>
            <p>Di lingkup perguruan tinggi, implementasi SDG 16 diwujudkan melalui:</p>
            <ol>
                <li><strong>Kebijakan Anti Kekerasan & Diskriminasi:</strong> Adanya regulasi yang tegas melindungi mahasiswa dari perundungan, kekerasan seksual, dan diskriminasi SARA.</li>
                <li><strong>Pendidikan Inklusif:</strong> Kurikulum yang mengintegrasikan nilai-nilai perdamaian, hak asasi manusia, dan resolusi konflik.</li>
                <li><strong>Platform Suara Mahasiswa:</strong> Ruang bagi mahasiswa untuk berekspresi, berdiskusi secara demokratis, dan menyampaikan keluhan/laporan secara aman dan terlindungi.</li>
            </ol>
            <p>UnitySpace hadir sebagai salah satu kontribusi nyata civitas akademika dalam mewujudkan ekosistem kampus yang damai dan inklusif sesuai target-target global SDG 16.</p>',
        ]);

        Article::create([
            'user_id' => $moderator->id,
            'title' => 'Pentingnya Memahami Hak Asasi Manusia dalam Hubungan Antarumat Beragama',
            'category' => 'hak asasi manusia',
            'thumbnail' => null,
            'content' => '<p>Kebebasan beragama dan berkeyakinan adalah salah satu pilar utama Hak Asasi Manusia (HAM) yang dijamin secara universal dalam Deklarasi Universal HAM (DUHAM) Pasal 18 serta Konstitusi UUD 1945 Pasal 29.</p>
            <p>Memahami hubungan beragama dalam perspektif HAM berarti menyadari bahwa:</p>
            <ul>
                <li>Setiap individu memiliki kedaulatan penuh atas keyakinan batinnya tanpa boleh diintervensi oleh pihak mana pun.</li>
                <li>Negara dan masyarakat berkewajiban untuk menghormati, melindungi, dan memenuhi hak tersebut secara adil.</li>
                <li>Kebebasan mengekspresikan agama dibatasi oleh keharusan menghormati hak dan kebebasan orang lain serta ketertiban umum.</li>
            </ul>
            <p>Dengan menempatkan HAM sebagai fondasi interaksi, gesekan sosial akibat perbedaan tafsir atau doktrin keagamaan dapat diminimalisir dan diselesaikan melalui musyawarah yang beradab.</p>',
        ]);
    }
}
