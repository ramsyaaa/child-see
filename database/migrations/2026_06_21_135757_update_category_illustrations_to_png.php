<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $map = [
        'tunagrahita'              => 'Tunagrahita.png',
        'tunanetra-totally-blind'  => 'Tunanetra totally blind.png',
        'disleksia'                => 'diseleksia.png',
        'slow-learner'             => 'Slow learner.png',
        'tunadaksa'                => 'Tunadaksa.png',
        'down-syndrome'            => 'Down syndrome.png',
        'prader-willie-syndrome'   => 'Prader Willie syndrome.png',
        'fragile-x-syndrome'       => 'Fragile x syndrome.png',
        'williams-syndrome'        => 'William syndrome.png',
        'disgrafia'                => 'disgrafia.png',
        'diskalkulia'              => 'diskalkulia.png',
        'tunanetra-low-vision'     => 'Tunanetra low vision.png',
        'tunarungu'                => 'Tunarungu.png',
        'tunawicara'               => 'tunawicara.png',
        'autism'                   => 'autism.png',
        'adhd'                     => 'adhd.png',
        'gangguan-emosi-perilaku'  => 'Gangguan emosi dan tingkah laku.png',
        'mdvi'                     => 'Mdvi.png',
        'tunaganda'                => 'Tunaganda.png',
    ];

    private array $oldValues = [];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->map as $slug => $file) {
            $current = DB::table('categories')->where('slug', $slug)->value('result_illustration');
            $this->oldValues[$slug] = $current;

            DB::table('categories')
                ->where('slug', $slug)
                ->update(['result_illustration' => 'assets/img/hasil-analisa/' . $file]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $jpgMap = [
            'tunagrahita'              => 'Tunagrahita.jpg',
            'tunanetra-totally-blind'  => 'Tunanetra totally blind.jpg',
            'disleksia'                => 'Diseleksia.jpg',
            'slow-learner'             => 'Slow learner.jpg',
            'tunadaksa'                => 'Tunadaksa.jpg',
            'down-syndrome'            => 'Down syndrome_.jpg',
            'prader-willie-syndrome'   => 'Prader Willie syndrome_.jpg',
            'fragile-x-syndrome'       => 'Fragile x syndrome_.jpg',
            'williams-syndrome'        => 'William syndrome.jpg',
            'disgrafia'                => 'Disgrafia.jpg',
            'diskalkulia'              => 'Diskalkulia.jpg',
            'tunanetra-low-vision'     => 'Tunanetra low vision.jpg',
            'tunarungu'                => 'Tunarungu.jpg',
            'tunawicara'               => null,
            'autism'                   => 'Autism.jpg',
            'adhd'                     => 'Adhd.jpg',
            'gangguan-emosi-perilaku'  => 'Gangguan emosi dan tingkah laku.jpg',
            'mdvi'                     => 'Mdvi.jpg',
            'tunaganda'                => 'Tunaganda.jpg',
        ];

        foreach ($jpgMap as $slug => $file) {
            DB::table('categories')
                ->where('slug', $slug)
                ->update(['result_illustration' => $file ? 'assets/img/hasil-analisa/' . $file : null]);
        }
    }
};
