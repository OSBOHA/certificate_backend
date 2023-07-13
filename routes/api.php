<?php

use App\Http\Controllers\API\UserBookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BooksController;
use App\Http\Controllers\API\CertificatesController;
use App\Http\Controllers\API\FQAController;
use App\Http\Controllers\API\GeneralInformationsController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\BookTypeController;
use App\Http\Controllers\API\ThesisController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\BookSectionController;
use App\Http\Controllers\API\BookLevelController;
use App\Http\Controllers\API\EmailVerificationController;
use App\Http\Controllers\API\PDFController;
use App\Http\Controllers\API\LanguageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
Route::post("register", [AuthController::class, "register"]);
Route::post('register-admin', [UserController::class, 'registerAdmin']);
Route::post("login", [AuthController::class, "login"]);
Route::post('password/forgot-password', [AuthController::class, 'sendResetLinkResponse'])->name('passwords.sent');
Route::post('password/reset', [AuthController::class, 'sendResetResponse'])->name('passwords.reset');

//section routes
Route::group(['prefix' => 'section'], function () {
    Route::get('/', [BookSectionController::class, 'index'])->middleware(['auth:api']);
    Route::post('/', [BookSectionController::class, 'store'])->middleware(['auth:api','role:admin']);
    Route::get('/{id}', [BookSectionController::class, 'show'])->middleware(['auth:api','role:admin']);
    Route::patch('/{id}', [BookSectionController::class, 'update'])->middleware(['auth:api','role:admin']);
    Route::delete('/{id}', [BookSectionController::class, 'destroy'])->middleware(['auth:api','role:admin']);
});
 
//types routes
Route::group(['prefix' => 'types'], function () {
    Route::get('/', [BookTypeController::class, 'index'])->middleware(['auth:api']);
    Route::post('/', [BookTypeController::class, 'store'])->middleware(['auth:api','role:admin']);
    Route::get('/{id}', [BookTypeController::class, 'show'])->middleware(['auth:api','role:admin']);
    Route::patch('/{id}', [BookTypeController::class, 'update'])->middleware(['auth:api','role:admin']);
    Route::delete('/{id}', [BookTypeController::class, 'destroy'])->middleware(['auth:api','role:admin']);
});

//types routes
Route::group(['prefix' => 'languages'], function () {
    Route::get('/', [LanguageController::class, 'index'])->middleware(['auth:api']);
    Route::post('/', [LanguageController::class, 'store'])->middleware(['auth:api','role:admin']);
    Route::get('/{id}', [LanguageController::class, 'show'])->middleware(['auth:api','role:admin']);
    Route::patch('/{id}', [LanguageController::class, 'update'])->middleware(['auth:api','role:admin']);
    Route::delete('/{id}', [LanguageController::class, 'destroy'])->middleware(['auth:api','role:admin']);
});

//level routes
Route::group(['prefix' => 'level'], function () {
    Route::get('/', [BookLevelController::class, 'index'])->middleware(['auth:api']);
    Route::post('/', [BookLevelController::class, 'store'])->middleware(['auth:api','role:admin']);
    Route::get('/{id}', [BookLevelController::class, 'show'])->middleware(['auth:api','role:admin']);
    Route::patch('/{id}', [BookLevelController::class, 'update'])->middleware(['auth:api','role:admin']);
    Route::delete('/{id}', [BookLevelController::class, 'destroy'])->middleware(['auth:api','role:admin']);
});
 //user book routes
    Route::group(['prefix' => 'userbook'], function () {
        Route::get('/', [UserBookController::class, 'index'])->middleware(['auth:api']);
        Route::post('/', [UserBookController::class, 'store'])->middleware(['auth:api']);
        Route::get('/status/{status}',[UserBookController::class ,'getUserBookByStatus'])->middleware(['auth:api','verified' ,"role:admin|book-quality"]);
        Route::patch('/status/{id}',[UserBookController::class ,'changeStatus'])->middleware(['auth:api','verified' ,"role:admin"]);
        Route::get('/last-achievement',[UserBookController::class,"lastAchievement"])->middleware(['auth:api','verified' ]);
        Route::get('/finished-achievement',[UserBookController::class,"finishedAchievement"])->middleware(['auth:api','verified' ]);
        Route::get('/count',[UserBookController::class,"checkOpenBook"])->middleware(['auth:api','verified' ]);
        Route::get('/certificate/{id}',[UserBookController::class,"checkCertificate"])->middleware(['auth:api','verified' ]);
        Route::get('/statistics/{id}',[UserBookController::class,"getStatistics"])->middleware(['auth:api','verified' ]);
        Route::get('/general-statistics/',[UserBookController::class,"getGeneralstatistics"]);
        Route::get('/by-book-id/{bookId}',[UserBookController::class,"getByBookID"])->middleware(['auth:api','verified' ]);
        Route::get('/stage-status/{id}',[UserBookController::class,"getStageStatus"])->middleware(['auth:api','verified' ]);
        Route::get('/{id}', [UserBookController::class, 'show'])->middleware(['auth:api','verified' ]);
        Route::patch('/{id}', [UserBookController::class, 'update'])->middleware(['auth:api']);
        Route::delete('/{id}', [UserBookController::class, 'destroy'])->middleware(['auth:api']);
        Route::post('/review',[UserBookController::class,"review"])->middleware(['auth:api','verified' ]);
        Route::get('/ready/to',[UserBookController::class,"readyToAudit"])->middleware(['auth:api','verified' ]);

    });

// Books routes
Route::group(['prefix' => 'books'], function () {
    Route::get('/', [BooksController::class, 'index'])->middleware(['auth:api','verified' ]);
    Route::post('/', [BooksController::class, 'store'])->middleware(['auth:api']);
    Route::get('/user',[BooksController::class ,'getBooksForUser'])->middleware(['auth:api','verified' ]);
    Route::get('/user/{id}',[BooksController::class ,'getOpenBook'])->middleware(['auth:api','verified' ]);
    Route::get('/check-achievement/{id}',[BooksController::class ,'checkAchievement'])->middleware(['auth:api','verified' ]);
    Route::get('/{id}', [BooksController::class, 'show'])->middleware(['auth:api','verified' ]);
    Route::patch('/{id}', [BooksController::class, 'update'])->middleware(['auth:api','role:admin|book-quality']);
    Route::delete('/{id}', [BooksController::class, 'destroy'])->middleware(['auth:api','role:admin']);
    Route::get('/user-book/{id}',[BooksController::class ,'getUserBook'])->middleware(['auth:api','verified' ]);
    Route::get('/check-achievement/{id}',[BooksController::class ,'checkAchievement'])->middleware(['auth:api','verified' ]);
    Route::get('/by-name/{name}',[BooksController::class ,'bookByName'])->middleware(['auth:api','verified' ]);


});


//Users routes

Route::group(['prefix' => 'users'], function () { 
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store'])->middleware(['auth:api','role:user|admin','verified' ]);
    Route::get('/statistics', [UserController::class, 'getUserStatistics'])->middleware(['auth:api','verified' ]);
    Route::get('/image', [UserController::class, 'image']);
    Route::get('/{id}', [UserController::class, 'show'])->middleware(['auth:api','verified' ]);
     Route::patch('/activate/{id}',[UserController::class, 'activeUser'])->middleware(['auth:api']);
    Route::patch('/{id}', [UserController::class, 'update'])->middleware(['auth:api']);
    // Route::delete('/{id}', [UserController::class, 'destroy'])->middleware(['auth:api' ]);
    Route::get('/list/un-active', [UserController::class, 'listUnactiveUser'])->middleware(['auth:api' ,'role:admin|user_accept']);
    Route::get('/list/un-active-reviwers-auditors', [UserController::class, 'listUnactiveReviwers'])->middleware(['auth:api' ,'role:admin']);
    Route::post('/upload-user_book',[UserController::class, 'uploaduser_book'])->middleware((['auth:api']));
    Route::post('/update-info',[UserController::class, 'updateInfo'])->middleware((['auth:api',"isActive"]));
    Route::post('/deactive-user',[UserController::class, 'deactivate'])->middleware((['auth:api']));

});


//thesis routes
Route::group(['prefix' => 'thesises'], function () {
    Route::get('/image', [ThesisController::class, 'image']);
    Route::get('/', [ThesisController::class, 'index']);
    Route::post('/', [ThesisController::class, 'store'])->middleware(['auth:api','verified' ]);
    Route::get('final-degree/{id}',[ThesisController::class,"finalDegree"])->middleware(['auth:api','verified' ]);
    Route::get('by-status/{status}',[ThesisController::class,"getByStatus"])->middleware(['auth:api','verified' ]);
    Route::get('/photo-count/{id}', [ThesisController::class, 'getThesisPhotosCount']);
    Route::get('/{id}', [ThesisController::class, 'show'])->middleware(['auth:api','verified' ]);
    Route::patch('update-photo/{id}',[ThesisController::class,"updatePhoto"])->middleware(['auth:api','verified' ]);
    Route::patch('review-thesis/{id}',[ThesisController::class,"reviewThesis"])->middleware(['auth:api','verified' ]);
    Route::patch('/{id}', [ThesisController::class, 'update'])->middleware(['auth:api','verified' ]);
    Route::delete('/photo/{id}', [ThesisController::class, 'deletePhoto'])->middleware(['auth:api','verified' ]);
    Route::delete('/{id}', [ThesisController::class, 'destroy'])->middleware(['auth:api','verified' ]);
    Route::patch('add-degree/{id}',[ThesisController::class,"addDegree"])->middleware(['auth:api','role:admin|auditor|super_auditer']);

    Route::post('update-photo',[ThesisController::class,"updatePicture"])->middleware(['auth:api','verified' ]);
    Route::post('upload/{id}',[ThesisController::class,"uploadPhoto"])->middleware(['auth:api','verified' ]);
    Route::get('user_book_id/{user_book_id}&{status?}',[ThesisController::class,"getByUserBook"])->middleware(['auth:api','verified' ]);
    Route::get('book/{book_id}',[ThesisController::class,"getByBook"])->middleware(['auth:api','verified' ]);
    Route::post('/review',[ThesisController::class,"review"])->middleware(['auth:api','verified' ]);
 

});

//questions routes
Route::group(['prefix' => 'questions'], function () {
    Route::get('/', [QuestionController::class, 'index'])->middleware(['auth:api']);
    Route::post('/', [QuestionController::class, 'store'])->middleware(['auth:api']);
    Route::get('status/{status}',[QuestionController::class,"getByStatus"])->middleware(['auth:api','verified' ]);
    Route::get('user-book/{id}',[QuestionController::class,"getUserBookQuestions"])->middleware(['auth:api','verified' ]);
    Route::get('/{id}', [QuestionController::class, 'show'])->middleware(['auth:api']);
    Route::patch('/{id}', [QuestionController::class, 'update'])->middleware(['auth:api', 'verified' ]);
    Route::delete('/{id}', [QuestionController::class, 'destroy'])->middleware(['auth:api','verified' ]);
    Route::patch('add-degree/{id}',[QuestionController::class,"addDegree"])->middleware(['auth:api','role:admin|auditor|super_auditer']);
    Route::get('book/{book_id}',[QuestionController::class,"getByBook"])->middleware(['auth:api','verified' ]);
    Route::get('final-degree/{id}',[QuestionController::class,"finalDegree"])->middleware(['auth:api']);
    Route::get('user_book_id/{user_book_id}',[QuestionController::class,"getByUserBook"])->middleware(['auth:api']);
    Route::get('status/{status}',[QuestionController::class,"getByStatus"])->middleware(['auth:api']);
    Route::post('/review',[QuestionController::class,"review"])->middleware(['auth:api']);
    Route::patch('review-question/{id}',[QuestionController::class,"reviewQuestion"])->middleware(['auth:api','verified' ]);

});

//certificates routes
Route::group(['prefix' => 'certificates'], function () {
    Route::get('/', [CertificatesController::class, 'index'])->middleware(['auth:api','role:admin']);
    Route::post('/', [CertificatesController::class, 'store']);
    Route::get('/user', [CertificatesController::class, 'getUserCertificates'])->middleware(['auth:api','verified' ]);
    Route::get('/{id}', [CertificatesController::class, 'show'])->middleware(['auth:api','verified' ]);
    Route::get('/full-certificate/{user_book_id}', [CertificatesController::class, 'fullCertificate'])->middleware(['auth:api','verified' ]);
    Route::patch('/{id}', [CertificatesController::class, 'update'])->middleware(['auth:api','role:admin|reviewer|super_reviewer']);
    Route::delete('/{id}', [CertificatesController::class, 'destroy'])->middleware(['auth:api','role:admin']);
    
    // generate PDF
    Route::get('/generate-pdf/{user_book_id}', [PDFController::class, 'generatePDF']);

});


//general informations routes
Route::group(['prefix' => 'general-informations'], function () {
    Route::get('/', [GeneralInformationsController::class, 'index'])->middleware(['auth:api','role:super_reviewer|reviewer|admin']);
    Route::post('/', [GeneralInformationsController::class, 'store'])->middleware(['auth:api']);
    Route::get('/user_book_id/{user_book_id}', [GeneralInformationsController::class, 'getByUserBookId'])->middleware(['auth:api']);
    Route::get('/{id}', [GeneralInformationsController::class, 'show'])->middleware(['auth:api']);
    Route::patch('/{id}', [GeneralInformationsController::class, 'update'])->middleware(['auth:api']);
    Route::delete('/{id}', [GeneralInformationsController::class, 'destroy'])->middleware(['auth:api']);
    Route::patch('add-degree/{id}',[GeneralInformationsController::class,"addDegree"])->middleware(['auth:api','role:admin|auditor|super_auditer']);
    Route::get('book/{book_id}',[GeneralInformationsController::class,"getByBook"])->middleware(['auth:api','verified' ]);
    Route::get('final-degree/{id}',[GeneralInformationsController::class,"finalDegree"])->middleware(['auth:api']);
    Route::get('user_book_id/{user_book_id}',[GeneralInformationsController::class,"getByUserBook"])->middleware(['auth:api']);
     Route::get('status/{status}',[GeneralInformationsController::class,"getByStatus"])->middleware(['auth:api']);
     Route::post('/review',[GeneralInformationsController::class,"review"])->middleware(['auth:api']);
     Route::patch('review-general-informations/{id}',[GeneralInformationsController::class,"reviewGeneralInformations"])->middleware(['auth:api','verified' ]);



});


// fqa routes
Route::group(['prefix' => 'fqa'], function () {
    Route::get('/', [FQAController::class, 'index']);
    Route::post('/', [FQAController::class, 'store'])->middleware(['auth:api','role:admin']);
    Route::get('/{id}', [FQAController::class, 'show']);
    Route::patch('/{id}', [FQAController::class, 'update'])->middleware(['auth:api','role:admin']);
    Route::delete('/{id}', [FQAController::class, 'destroy'])->middleware(['auth:api','role:admin']);
});



Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:api');


