<?php

use Illuminate\Support\Facades\Route;

use Halim\EKlaim\Controllers\SitbController;
use Halim\EKlaim\Controllers\KlaimController;
use Halim\EKlaim\Controllers\PatientController;
use Halim\EKlaim\Controllers\DiagnosisController;
use Halim\EKlaim\Controllers\GroupKlaimController;
use Halim\EKlaim\Controllers\ProceduresController;
use App\Http\Controllers\v2\KlaimController as CustomKlaimController;

Route::as("e-klaim.")->middleware('api')->prefix('eklaim')->group(function () {
    Route::post('/new', [CustomKlaimController::class, 'new'])->name('new.claim');                          // =====> method : new_claim
    Route::post('/send', [KlaimController::class, 'sendBulk'])->name('send.claim');                         // =====> method : send_claim
    Route::post('/final', [KlaimController::class, 'final'])->name('final.claim');                          // =====> method : claim_final
    // Route::post('/pull', [PullKlaimController::class, 'handle'])->name('pull.claim');                       // =====> method : pull_claim -- [ method sudah ditutup (Manual Web Service 5.8.3b) ]
    Route::post('/{sep}', [CustomKlaimController::class, 'set'])->name('set.claim.data');                   // =====> method : set_claim_data
    Route::get('/get/number', [KlaimController::class, 'generateNumber'])->name('get.claim.number');        // =====> method : generate_claim_number
    Route::get('/{sep}', [KlaimController::class, 'get'])->name('get.claim.data');                          // =====> method : get_claim_data
    Route::get('/{sep}/status', [KlaimController::class, 'getStatus'])->name('get.claim.status');           // =====> method : get_claim_status
    Route::get('/{sep}/re-edit', [KlaimController::class, 'reEdit'])->name('reedit.claim');                 // =====> method : reedit_claim
    Route::get('/{sep}/send', [KlaimController::class, 'send'])->name('send.claim.individual');             // =====> method : send_claim_individual
    Route::get('/{sep}/print', [KlaimController::class, 'print'])->name('print.claim');                     // =====> method : claim_print
    Route::delete('/{sep}', [KlaimController::class, 'delete'])->name('delete.claim');                      // =====> method : delete_claim

    Route::as('sitb.')->prefix('sitb')->group(function () {
        Route::post('/validate', [SitbController::class, 'validateSitb'])->name('validate');                // =====> method : sitb_validate
        Route::get('/invalidate/{sep}', [SitbController::class, 'inValidateSitb'])->name('invalidate');     // =====> method : sitb_invalidate
    });

    Route::as('patient.')->prefix('patient')->group(function () {
        Route::post('/{no_rekam_medis}', [PatientController::class, 'update'])->name('update');             // =====> method : update_patient
        Route::delete('/{no_rekam_medis}', [PatientController::class, 'delete'])->name('delete');           // =====> method : delete_patient
    });

    Route::as('group.')->prefix('group')->group(function () {
        Route::post('/stage/1', [GroupKlaimController::class, 'stage1'])->name('stage.1');                  // =====> method : grouper, stage:  1
        Route::post('/stage/2', [GroupKlaimController::class, 'stage2'])->name('stage.2');                  // =====> method : grouper, stage:  2
    });

    // Route::as('covid19.')->prefix('covid19')->group(function () {
    //     Route::post('/status', [Covid19Controller::class, 'handle'])->name('status');                       // =====> method : search_diagnosis
    // });

    Route::as('diagnosis.')->prefix('diagnosis')->group(function () {
        Route::post('/search', [DiagnosisController::class, 'search'])->name('search');                     // =====> method : search_diagnosis
        Route::post('/search/ina', [DiagnosisController::class, 'searchIna'])->name('search.ina');          // =====> method : search_diagnosis_inagrouper
    });

    Route::as('procedures.')->prefix('procedures')->group(function () {
        Route::post('/search', [ProceduresController::class, 'search'])->name('search');                    // =====> method : search_procedures
        Route::post('/search/ina', [ProceduresController::class, 'searchIna'])->name('search.ina');         // =====> method : search_procedures_inagrouper
    });

    // Route::as('file.')->prefix('file')->group(function () {
    //     Route::post('/{sep}', [NewKlaimController::class, 'get'])->name('get');                             // =====> method : file_get
    //     Route::post('/upload', [NewKlaimController::class, 'upload'])->name('upload');                      // =====> method : file_upload
    //     Route::post('/delete', [NewKlaimController::class, 'delete'])->name('delete');                      // =====> method : file_delete
    // });
});
