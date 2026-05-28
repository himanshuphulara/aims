<?php

use App\Http\Controllers\ASTBController;
use App\Http\Controllers\BackupDatabase;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ChequeController;
use App\Http\Controllers\DBController;
use App\Http\Controllers\MessBillSubCategoryController;
use App\Http\Controllers\MessBillSummaryController;
use App\Http\Controllers\OfficerDetailController;
use App\Http\Controllers\PropertiesController;
use App\Http\Controllers\SyCrController;
use App\Http\Controllers\SyDrController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\AiKnowledgeController;
use App\Http\Controllers\AiSurveyController;
use App\Http\Controllers\PublicAiSurveyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('signin');
});
// Route::get('/signup', function () {
//     if (Auth::check()) {
//         return redirect('/dashboard');
//     }
//     return view('signup');
// });

// Route::post('/signup',[UsersController::class, 'signUp'])->name('signup');
Route::post('/',[UsersController::class, 'signIn'])->name('signin');
Route::get('/logout',[UsersController::class, 'logout'])->name('logout');

Route::middleware(['checkUserAuth'])->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard','dashboard')->name('dashboard')->middleware('permission:dashboard');
        Route::post('/useradd','userAdd')->name('useradd')->middleware('permission:useradd');
        Route::get('/userslist','usersList')->name('userslist')->middleware('permission:userslist');
        Route::get('/useredit/{id}','userEdit')->name('useredit')->middleware('permission:useredit');
        Route::post('/userupdate','userUpdate')->name('userupdate')->middleware('permission:userupdate');
        Route::post('/userpassword','userPassword')->name('userpassword')->middleware('permission:userpassword');
        Route::get('/userdelete/{id}','userDelete')->name('userdelete')->middleware('permission:userdelete');
        Route::get('/profile','userProfile')->name('userprofile');
        Route::post('/userimageupload','userImageUpload')->name('userimageupload');
        Route::post('/profilepassword','profilepassword')->name('profilepassword');
    });

    Route::controller(RolesController::class)->group(function () {
        Route::get('/roles','roles')->name('roles')->middleware('permission:roles');
        Route::post('/roleadd','roleStore')->name('roleadd')->middleware('permission:roleadd');
        Route::post('/roleupdate','roleUpdate')->name('roleupdate')->middleware('permission:roleupdate');
        Route::get('/roledelete/{id}','roleDelete')->name('roledelete')->middleware('permission:roledelete');
        Route::get('/rolegetpermissions/{id}','roleGetPermissions')->name('rolegetpermissions')->middleware('permission:rolegetpermissions');
        Route::post('/rolesetpermissions','roleSetPermissions')->name('rolesetpermissions')->middleware('permission:rolesetpermissions');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::post('/categoryadd','categoryAdd')->name('categoryadd')->middleware('permission:categoryadd');
        Route::get('/categorylist/{pid?}','categoryList')->name('categorylist')->middleware('permission:categorylist');
        Route::post('/categoryupdate','categoryUpdate')->name('categoryupdate')->middleware('permission:categoryupdate');
        Route::get('/categorydelete/{id}/{catname}','categoryDelete')->name('categorydelete')->middleware('permission:categorydelete');
        Route::post('/subcategoryadd','subCategoryAdd')->name('subcategoryadd')->middleware('permission:subcategoryadd');
        Route::get('/subcategorylist/{pid}/{id}','subcategoryList')->name('subcategorylist')->middleware('permission:subcategorylist');
        Route::post('/subcategoryupdate','subcategoryUpdate')->name('subcategoryupdate')->middleware('permission:subcategoryupdate');
        Route::get('/subcategorydelete/{id}/{catname}/{pid}/{mid}','subcategoryDelete')->name('subcategorydelete')->middleware('permission:subcategorydelete');
        //Route::post('/userupdate','userUpdate')->name('userupdate')->middleware('permission:userupdate');
        //Route::post('/userpassword','userPassword')->name('userpassword')->middleware('permission:userpassword');
    });

    Route::controller(UnitController::class)->group(function () {
        Route::get('/units','index')->name('units.index')->middleware('permission:unitslist');
        Route::post('/units','store')->name('units.store')->middleware('permission:unitadd');
        Route::get('/units/{id}/edit','edit')->name('units.edit')->middleware('permission:unitedit');
        Route::post('/unitsupdate','update')->name('units.update')->middleware('permission:unitupdate');
        Route::get('/units/{id}/delete','destroy')->name('units.destroy')->middleware('permission:unitdelete');
    });

    Route::controller(VoucherController::class)->group(function(){
        Route::get('voucher/{id}','getVoucher')->name('voucher')->middleware('permission:voucher');
        Route::get('voucherp/{id}','getVoucherp')->name('voucherp')->middleware('permission:voucherp');
        Route::post('voucheradd','voucherAdd')->name('voucheradd')->middleware('permission:voucheradd');
        Route::get('voucheredit/{id}','voucherEdit')->name('voucheredit')->middleware('permission:voucheredit');
        Route::post('voucherupdate','voucherUpdate')->name('voucherupdate')->middleware('permission:voucherupdate');
        Route::get('voucherdelete/{id}/{cat_id}','voucherDelete')->name('voucherdelete')->middleware('permission:voucherdelete');
        //CRV
        Route::get('crv/{cat_id}','crv')->name('crv')->middleware('permission:crv');
        Route::post('crvadd','crvAdd')->name('crvadd')->middleware('permission:crvadd');
        Route::get('crvedit/{crv_id}','crvEdit')->name('crvedit')->middleware('permission:crvedit');
        Route::post('crvupdate','crvUpdate')->name('crvupdate')->middleware('permission:crvupdate');
        Route::get('crvdelete/{crv_id}','crvDelete')->name('crvdelete')->middleware('permission:crvdelete');
        // Route::get('crvledger/{vocid}','crvLedger')->name('crvledger')->middleware('permission:crvledger');
        // Route::get('crvledger/fetch/{vocid}','crvLedgerData')->name('crvledgerdata');
        Route::get('crvpdf/{crv_id}','crvPdf')->name('crvpdf');
        //new routes for crvledger without ajax and download crv in pdf
        Route::get('crvledger/{vocid}/{fundfor}','crvLedgerPdfData')->name('crvledger');
        Route::post('crvledgerdatadownload/{vocid}','crvLedgerPdfDataDownload')->name('crvledgerdatadownload');

        //NIV
        Route::get('niv/{cat_id}','niv')->name('niv')->middleware('permission:niv');
        Route::post('nivadd','nivAdd')->name('nivadd')->middleware('permission:nivadd');
        Route::get('nivedit/{niv_id}','nivEdit')->name('nivedit')->middleware('permission:nivedit');
        Route::post('nivupdate','nivUpdate')->name('nivupdate')->middleware('permission:nivupdate');
        Route::get('nivdelete/{niv_id}','nivDelete')->name('nivdelete')->middleware('permission:nivdelete');
        // Route::get('nivledger/{vocid}','nivLedger')->name('nivledger')->middleware('permission:nivledger');
        // Route::get('nivledger/fetch/{vocid}','nivLedgerData')->name('nivledgerdata');
        Route::get('nivpdf/{niv_id}','nivPdf')->name('nivpdf');
         //new routes for nivledger without ajax and download niv in pdf
         Route::get('nivledger/{vocid}','nivLedgerPdfData')->name('nivledger');
         Route::post('nivledgerdatadownload/{vocid}','nivLedgerPdfDataDownload')->name('nivledgerdatadownload');
        //CIV
        Route::get('civ/{cat_id}','civ')->name('civ')->middleware('permission:civ');
        Route::post('civadd','civAdd')->name('civadd')->middleware('permission:civadd');
        Route::get('civedit/{civ_id}','civEdit')->name('civedit')->middleware('permission:civedit');
        Route::post('civupdate','civUpdate')->name('civupdate')->middleware('permission:civupdate');
        Route::get('civdelete/{civ_id}','civDelete')->name('civdelete')->middleware('permission:civdelete');
        Route::get('civpdf/{civ_id}','civPdf')->name('civpdf');
         //new routes for civledger without ajax and download civ in pdf
         Route::get('civledger/{vocid}','civLedgerPdfData')->name('civledger');
         Route::post('civledgerdatadownload/{vocid}','civLedgerPdfDataDownload')->name('civledgerdatadownload');

        Route::get('lastmonth','lastMonthBalace');
        Route::post('bankreconciliation','bankReconciliation')->name('bankreconciliation');

        Route::get('fundvouchers/{id}','fundVouchers')->name('fundvouchers');
        Route::get('crvvouchers','crvVouchers')->name('crvvouchers');
        Route::get('nivvouchers','nivVouchers')->name('nivvouchers');
        Route::get('civvouchers','civVouchers')->name('civvouchers');

        Route::post('bbfadd','bbfAdd')->name('bbfadd');
        Route::post('autobbfadd','autoBbfAdd')->name('autobbfadd');
        Route::get('autobbfdata/{cat_id}','getAutoBbfData')->name('autobbfdata');

        Route::post('totalallotmentadd','totalAllotmentAdd')->name('totalallotmentadd');
        Route::delete('totalallotmentdelete/{id}','totalAllotmentDelete')->name('totalallotmentdelete');
        
        Route::post('pcdatransactionadd','pcdaTransactionAdd')->name('pcdatransactionadd');
        Route::delete('pcdatransactiondelete/{id}','pcdaTransactionDelete')->name('pcdatransactiondelete');
        
        Route::get('mer-report','merReport')->name('mer-report');
        Route::get('qab-report','qabReport')->name('qab-report');
        Route::get('qab-category-report','qabCategoryReport')->name('qab-category-report');
        Route::get('debug-vouchers','debugVouchers')->name('debug-vouchers');
        Route::get('debug-cash/{categoryId}', function($categoryId) {
            $controller = new \App\Http\Controllers\VoucherController();
            $category = \App\Models\Category::find($categoryId);
            $fundfor = $category ? $category->name : '';
            $currentMonth = date('Y-m-01');
            $currentMonthEnd = date('Y-m-t');
            
            $paymentBalance = $controller->selectedMonthPaymentBalance($categoryId, $fundfor, $currentMonth, $currentMonthEnd);
            
            return response()->json([
                'category_id' => $categoryId,
                'category_name' => $fundfor,
                'start_date' => $currentMonth,
                'end_date' => $currentMonthEnd,
                'payment_balance' => $paymentBalance
            ]);
        })->name('debug-cash');
        Route::get('test-qab', function() { return "QAB Test Working!"; })->name('test-qab');
        Route::get('simple-qab', function() { 
            return view('reports.qab', [
                'title' => 'Simple QAB Test',
                'qabData' => [
                    [
                        'ser' => 1,
                        'account_name' => 'Cash In Hand',
                        'current_balance' => 5000,
                        'previous_balance' => 3000,
                        'inc_dec' => 2000,
                        'inc_dec_percent' => 66.67,
                        'account_type' => 'Asset'
                    ]
                ],
                'selectedYear' => 2025,
                'selectedQuarter' => 2,
                'publicCategory' => (object)['name' => 'Public Fund'],
                'allCategories' => \App\Models\Category::where('parent_id', 0)->get(),
                'selectedCategoryId' => 1,
                'quarterName' => 'Q2',
                'quarterEndMonth' => 'SEP',
                'financialYear' => 2025,
                'currentQuarter' => 3
            ]);
        })->name('simple-qab');

        Route::post('grandtotal','grandTotal')->name('grandtotal');
    });

    Route::controller(ChequeController::class)->group(function () {
        Route::get('/chequelist/{cat_id}/{start}/{end}','chequeList')->name('chequelist');
        Route::post('/chequeadd','chequeAdd')->name('chequeadd');
        Route::put('/chequeedit','chequeEdit')->name('chequeedit');
        Route::get('/chequedelete/{id}','chequeDelete')->name('chequedelete');
        Route::post('/chequeclear/{id}','chequeClear')->name('chequeclear');
    });
    Route::controller(SyDrController::class)->group(function () {
        Route::get('/sydrlist/{cat_id}/{start}/{end}','sydrList')->name('sydrlist');
        Route::post('/sydradd','sydrAdd')->name('sydradd');
        Route::put('/sydredit','sydrEdit')->name('sydredit');
        Route::get('/sydrdelete/{id}','sydrDelete')->name('sydrdelete');
    });
    Route::controller(SyCrController::class)->group(function () {
        Route::get('/sycrlist/{cat_id}/{start}/{end}','sycrList')->name('sycrlist');
        Route::post('/sycradd','sycrAdd')->name('sycradd');
        Route::put('/sycredit','sycrEdit')->name('sycredit');
        Route::get('/sycrdelete/{id}','sycrDelete')->name('sycrdelete');
    });
    Route::controller(BankController::class)->group(function () {
        Route::get('/stmtlist/{cat_id}/{start}/{end}','stmtList')->name('stmtlist');
        Route::post('/stmtadd','stmtAdd')->name('stmtadd');
        Route::put('/stmtedit','stmtEdit')->name('stmtedit');
        Route::get('/stmtdelete/{id}','stmtDelete')->name('stmtdelete');
    });
    Route::controller(PropertiesController::class)->group(function () {
        Route::get('/propertieslist/{cat_id}/{start}/{end}','propertiesList')->name('propertieslist');
        Route::post('/propertiesadd','propertiesAdd')->name('propertiesadd');
        Route::put('/propertiesedit','propertiesEdit')->name('propertiesedit');
        Route::get('/propertiesdelete/{id}','propertiesDelete')->name('propertiesdelete');
    });

    Route::controller(OfficerDetailController::class)->group(function () {
        Route::get('/officerlist/{cat_id}','officerList')->name('officerlist');
        Route::post('/officeradd','officerAdd')->name('officeradd');
        Route::put('/officeredit','officerEdit')->name('officeredit');
        Route::get('/officerdelete/{id}','officerDelete')->name('officerdelete');
    });
    Route::controller(MessBillSummaryController::class)->group(function () {
        Route::get('/messbilllist/{cat_id}','messBillList')->name('messbilllist');
        Route::post('/messbilladd','messbillAdd')->name('messbilladd');
        Route::get('/messbilledit/{offid}/{catid}/{start}/{end}','messbillEdit')->name('messbilledit');
        Route::put('/messbillupdate','messbillUpdate')->name('messbillupdate');
        Route::get('/messbilldelete/{id}/{catid}/{start}/{end}','messbillDelete')->name('messbilldelete');
        Route::get('officermessbillpdf/{mess_id}/{catid}/{start}/{end}','officerMessbillPdf')->name('officermessbillpdf');
    });
    Route::controller(MessBillSubCategoryController::class)->group(function () {
        Route::get('/messbillcategory/{cat_id}/{start}/{end}','messBillCategory')->name('messbillcategory');
        Route::post('/messbilladdsubcategory','messbillAddSubcategory')->name('messbilladdsubcategory');
        Route::put('/messbillsubcategoryedit','messbillSubCategoryEdit')->name('messbillsubcategoryedit');
        Route::get('/messbillsubcategorydelete/{id}','messbillSubCategoryDelete')->name('messbillsubcategorydelete');
    });

    Route::controller(ASTBController::class)->group(function () {
        Route::get('/getastb/{item_id}/{start}/{end}','getASTB')->name('getastb');
        Route::post('/astbupdate','astbUpdate')->name('astbupdate');
    });

    Route::controller(DBController::class)->group(function () {
        Route::get('/dblist','dbList')->name('dblist');
        Route::get('/backup','backUp')->name('backup');
        Route::get('/deletebackup/{id}','deleteBackup')->name('deletebackup');
        Route::get('/download/{id}','download')->name('download');
    });

    Route::controller(UnitController::class)->group(function () {
        Route::get('/units','index')->name('units.index')->middleware('permission:unitslist');
        Route::post('/units','store')->name('units.store')->middleware('permission:unitadd');
        Route::get('/units/{id}/edit','edit')->name('units.edit')->middleware('permission:unitedit');
        Route::post('/units/update','update')->name('units.update')->middleware('permission:unitupdate');
        Route::get('/units/{id}/delete','destroy')->name('units.destroy')->middleware('permission:unitdelete');
    });

    Route::prefix('ai')->group(function () {
        Route::controller(AiKnowledgeController::class)->group(function () {
            Route::get('/documents', 'documents')->name('ai.documents')->middleware('permission:ai.documents.manage');
            Route::post('/documents', 'storeDocument')->name('ai.documents.store')->middleware('permission:ai.documents.manage');
            Route::post('/documents/{document}/retry', 'retryDocument')->name('ai.documents.retry')->middleware('permission:ai.documents.manage');
            Route::delete('/documents/{document}', 'deleteDocument')->name('ai.documents.delete')->middleware('permission:ai.documents.manage');

            Route::get('/ask', 'askPage')->name('ai.ask.page')->middleware('permission:ai.ask');
            Route::post('/ask', 'ask')->name('ai.ask')->middleware('permission:ai.ask');
            Route::get('/health', 'health')->name('ai.health')->middleware('permission:ai.documents.manage');
            Route::get('/diagnostics', 'diagnostics')->name('ai.diagnostics')->middleware('permission:ai.documents.manage');
        });

        Route::controller(AiSurveyController::class)->group(function () {
            Route::get('/surveys', 'index')->name('ai.surveys')->middleware('permission:ai.surveys.manage');
            Route::post('/surveys/generate/{document}', 'generateFromDocument')->name('ai.surveys.generate')->middleware('permission:ai.surveys.manage');
            Route::get('/surveys/{survey}/edit', 'edit')->name('ai.surveys.edit')->middleware('permission:ai.surveys.manage');
            Route::put('/surveys/{survey}', 'update')->name('ai.surveys.update')->middleware('permission:ai.surveys.manage');
            Route::post('/surveys/{survey}/publish', 'publish')->name('ai.surveys.publish')->middleware('permission:ai.surveys.manage');
            Route::post('/surveys/{survey}/close', 'close')->name('ai.surveys.close')->middleware('permission:ai.surveys.manage');

            Route::get('/feedback', 'feedback')->name('ai.feedback')->middleware('permission:ai.feedback.view');
            Route::get('/feedback/export/{survey}', 'export')->name('ai.feedback.export')->middleware('permission:ai.feedback.view');
        });
    });

    // Route::controller(PermissionsController::class)->group(function () {
    //     Route::get('/permissions','permissions')->name('permissions');
    //     Route::post('/store','store')->name('store');
    //     Route::post('/update','update')->name('update');
    //     Route::get('/delete/{id}','delete')->name('delete');
    // });

});

Route::controller(PublicAiSurveyController::class)->group(function () {
    Route::get('ai/survey/{token}', 'show')->name('ai.survey.public');
    Route::post('ai/survey/{token}', 'submit')->name('ai.survey.public.submit');
});

Route::get('clear',function(){
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
  return 'Config cache has been cleared';
});
