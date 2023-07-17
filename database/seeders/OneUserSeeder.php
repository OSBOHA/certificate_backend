<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Book;
use App\Models\Thesis;
use App\Models\Question;
use App\Models\Quotation;
use App\Models\GeneralInformations;
use App\Models\Certificates;
use App\Models\UserBook;




class OneUserSeeder extends Seeder
{
    public function run()
    {
        ## Role ##
        $admin = Role::create(['name' => 'admin']);
        $reviewer = Role::create(['name' => 'reviewer']);
        $auditor = Role::create(['name' => 'auditor']);
        $user = Role::create(['name' => 'user']);

        ## category ##
        $cat = 1;
        while ($cat <= 10) {
            DB::table('section')->insert([
                'name' => 'cat' . $cat,
            ]);
        $cat++;
        }

        ## type ##
        DB::table('level')->insert([
            'name' => 'بسيط'
        ]);
        DB::table('level')->insert([
            'name' => 'متوسط'
        ]);
        DB::table('level')->insert([
            'name' => 'متقدم'
        ]);

        ## users ##
        $admin = User::factory()->create()->assignRole('admin');
        $reviewer = User::factory()->create()->assignRole('reviewer');
        $auditor = User::factory()->create()->assignRole('auditor');
        $user = User::factory()->create()->assignRole('user');

        ## Book ##
        $book = Book::create([
            'name' => Str::random(10) ,
            'pages' => rand(100, 1000),
            'start_page'=>1,
            "end_page"=>300,    
            'section_id' => rand(1, 10),
            'level_id' =>rand(1, 3),
            'language_id'=>1
        ]);

        ## User_Book ##
        $user_book = UserBook::create([
            'status' => 'finished',
            'user_id' => $user->id,
            'book_id' => $book->id,
            'reviews' => 'ما أصله؟ خلافاَ للاعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد، مما يجعله أكثر من عام في القدم. قام البروفيسور "ريتشارد ماك لين'
        ]);

        ## Thesis ##
        $nThesis = rand(8, 12);
        for ($j=0; $j < $nThesis ; $j++) {
            Thesis::create([
                'thesis_text' => 'ما أصله؟ خلافاَ للاعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد، مما يجعله أكثر من عام في القدم. قام البروفيسور "ريتشارد ماك لينتوك" وهو بروفيسور اللغة اللاتينية في جامعة هامبدن-سيدني في فيرجينيا بالبحث عن أصول كلمة لاتينية غامضة في نص لوريم إيبسوم وهي "consectetur"، وخلال تتبعه لهذه الكلمة فيالأدب اللاتيني اكتشف المصدر الغير قابل للشك. فلقد اتضح أن كلمات نص لوريم إيبسوم تأتي من الأقسام من كتاب "حول أقاصي الخير والشر" للمفكر والذي كتبه في عام 45 قبل الميلاد. هذا الكتاب هو بمثابة مقالة علمية مطولة في نظرية الأخلاق، وكان له شعبية كبيرة في عصر النهضة. السطر الأول من لوريم إيبسوم " يأتي من سطر في القسم هذا الكتاب. للمهتمين قمنا بوضع نص لوريم إبسوم القياسي والمُستخدم منذ القرن الخامس عشر في الأسفل. وتم أيضاً توفير الأقسام "حول أقاصي الخير واللمؤلفه بصيغها الأصلية، مرفقة بالنسخ الإنكليزية لها والتي قام بترجمتها هـ.راكهام ما فائدته؟ هناك حقيقة مثبتة منذ زمن طويل وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على الشكل الخارجي للنص أو شكل توضع الفقرات في الصفحة التي يقرأها. ولذلك يتم استخدام طريقة لوريم إيبسوم لأنها تعطي توزيعاَ طبيعياَ -إلى حد ما- للأحرف عوضاً عن استخدام "هنا يوجد محتوى نصي، هنا يوجد محتوى نصي" فتجعلها تبدو (أي الأحرف) وكأنها نص مقروء. العديد من برامح النشر المكتبي وبرامح تحرير صفحات الويب تستخدم لوريم إيبسوم بشكل افتراضي كنموذج عن النص، وإذا قمت بإدخال "lorem ipsum" في أي محرك بحث ستظهر العديد من المواقع الحديثة العهد في نتائج البحث. على مدى السنين ظهرت نسخ جديدة ومختلفة من نص لوريم إيبسوم، أحياناً عن طريق الصدفة، وأحياناً عن عمد كإدخال بعض العبارات الفكاهية إليها.ما أصله؟ خلافاَ للاعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد، مما يجعله أكثر من عام في القدم. قام البروفيسور "ريتشارد ماك لينتوك" وهو بروفيسو',
                'starting_page'	=> strval(rand(1, floor($book->pages/2))),
                'ending_page'	=> rand( floor($book->pages/2)+1, $book->pages),
                'reviews' => 'ما أصله؟ خلافاَ للاعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد، مما يجعله أكثر من عام في القدم. قام البروفيسور "ريتشارد ماك لين',
                'status'	=> 'audited',
                'degree'	=> rand(70, 100),
                'reviewer_id' => $reviewer->id,
                'auditor_id' => $auditor->id,
                'user_book_id'=>$user_book->id,
            ]);
        }

        ## Questions ##
        $nQuestions = rand(5, 12);
        for ($j=0; $j < $nQuestions ; $j++) {
            Question::create([
                'question' => 'ما أصله؟ خلافاَ للاعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد، مما يجعله أكثر من عام في القدم. قام البروفيسور "ريتشارد ماك لينتوك" وهو بروفيسور اللغة اللاتينية في جامعة هامبدن-',
                'reviews' => 'ما أصله؟ خلافاَ للاعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد، مما يجعله أكثر من عام في القدم. قام البروفيسور "ريتشارد ماك لين',
                'starting_page'	=> strval(rand(1, floor($book->pages/2))),
                'ending_page'	=> rand( floor($book->pages/2)+1, $book->pages),
                'status'	=> $s = 'audited',
                'degree'	=> rand(70, 100),
                'reviewer_id' => $reviewer->id,
                'auditor_id' => $auditor->id,
                'user_book_id'=>$user_book->id,
            ]);
        }

        ## Quotation ##
        $question_ids = Question::pluck('id');
        foreach ($question_ids as $question_id) {
            Quotation::create([
                'text' => 'ما أصله؟ خلافاَ للاعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد، مما يجعله أكثر من عام في القدم. قام البروفيسور "ريتشارد ماك لينتوك" وهو بروفيسور اللغة اللاتينية في جامعة هامبدن-',
                'question_id' => $question_id,
            ]);
        }

        ## general_informations ##
        $general_informations = GeneralInformations::create([
            'general_question' => 'ما أصله؟ خلافاَ للاعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد، مما يجعله أكثر من عام في القدم. قام البروفيسور "ريتشارد ماك لينتوك" وهو بروفيسور اللغة اللاتينية في جامعة هامبدن-',
            'summary' => 'ما أصله؟ خلافاَ للاعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد، مما يجعله أكثر من عام في القدم. قام البروفيسور "ريتشارد ماك لينتوك" وهو بروفيسور اللغة اللاتينية في جامعة هامبدن-سيدني في فيرجينيا بالبحث عن أصول كلمة لاتينية غامضة في نص لوريم إيبسوم وهي "consectetur"، وخلال تتبعه لهذه الكلمة فيالأدب اللاتيني اكتشف المصدر الغير قابل للشك. فلقد اتضح أن كلمات نص لوريم إيبسوم تأتي من الأقسام من كتاب "حول أقاصي الخير والشر" للمفكر والذي كتبه في عام 45 قبل الميلاد. هذا الكتاب هو بمثابة مقالة علمية مطولة في نظرية الأخلاق، وكان له شعبية كبيرة في عصر النهضة. السطر الأول من لوريم إيبسوم " يأتي من سطر في القسم هذا الكتاب. للمهتمين قمنا بوضع نص لوريم إبسوم القياسي والمُستخدم منذ القرن الخامس عشر في الأسفل. وتم أيضاً توفير الأقسام "حول أقاصي الخير واللمؤلفه بصيغها الأصلية، مرفقة بالنسخ الإنكليزية لها والتي قام بترجمتها هـ.راكهام ما فائدته؟ هناك حقيقة مثبتة منذ زمن طويل وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على الشكل الخارجي للنص أو شكل توضع الفقرات في الصفحة التي يقرأها. ولذلك يتم استخدام طريقة لوريم إيبسوم لأنها تعطي توزيعاَ طبيعياَ -إلى حد ما- للأحرف عوضاً عن استخدام "هنا يوجد محتوى نصي، هنا يوجد محتوى نصي" فتجعلها تبدو (أي الأحرف) وكأنها نص مقروء. العديد من برامح النشر المكتبي وبرامح تحرير صفحات الو',
            'reviews' => 'ما أصله؟ خلافاَ للاعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له جذور في الأدب اللاتيني الكلاسيكي منذ العام 45 قبل الميلاد، مما يجعله أكثر من عام في القدم. قام البروفيسور "ريتشارد ماك لين',
            'status'	=> 'audited',
            'degree'	=> rand(70, 100),
            'reviewer_id' => $reviewer->id,
            'auditor_id' => $auditor->id,
            'user_book_id'=>$user_book->id,
            ]);


        ## certificates ##
        $general_summary_grade = GeneralInformations::where('user_book_id' ,1)->pluck('degree')->first();

        $thesis_grade = Thesis::where('user_book_id' , $user_book->id)->avg('degree');
        $check_reading_grade = Question::where('user_book_id' , $user_book->id)->avg('degree');
        $final_grade = ($general_summary_grade + $thesis_grade + $check_reading_grade)/3;

        $certificates = Certificates::create([
            'final_grade' => $final_grade,
            'general_summary_grade' => 99,
            'thesis_grade' => round( $thesis_grade),
            'check_reading_grade'	=> round( $check_reading_grade),
            'user_book_id'=>$user_book->id,
        ]);

    }
}
