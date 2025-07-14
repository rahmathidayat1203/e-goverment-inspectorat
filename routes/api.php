<?php


use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotKnowledgeController;
use App\Http\Controllers\FileSyaratPengajuanController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\JenisSbtController;
use App\Http\Controllers\MutasiAsnController;
use App\Http\Controllers\PengajuanSbtController;
use App\Http\Controllers\RiwayatChatController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifikasiController;
use App\Models\FileSyaratPengajuan;
use App\Models\mutasi_asn;
use PhpParser\Node\Expr\AssignOp\Mul;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('CHATBOTKNOWLEDGE', ChatbotKnowledgeController::class);
Route::apiResource('INSTANSI', InstansiController::class);
Route::apiResource('JENISSBT', JenisSbtController::class);
Route::apiResource('MUTASIASN', MutasiAsnController::class);
Route::apiResource('PENGAJUANSBT', PengajuanSbtController::class);
Route::apiResource('RIWAYATCHATBOT', RiwayatChatController::class);
Route::apiResource('VERIFIKASI', VerifikasiController::class);
Route::post('chat', [ChatbotKnowledgeController::class, 'ask'])->middleware('auth:sanctum');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('pengajuan-form', [PengajuanSbtController::class, 'pengajuanForm'])->middleware('auth:sanctum');
Route::post('cetak-template/{id}', [FileSyaratPengajuanController::class, 'generatePDF']);
Route::get('progress_berkas', [PengajuanSbtController::class, 'progress_berkas'])->middleware('auth:sanctum');
Route::get('profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
Route::get('pengajuan_sbt_all', [PengajuanSbtController::class, 'getAllPengajuan'])->middleware('auth:sanctum');
Route::get('pengajuan_sbt_detail/{id}', [PengajuanSbtController::class, 'get_detail']);

Route::post('/pengajuan/{id}/verify', [PengajuanSbtController::class, 'verifyPengajuan'])->middleware('auth:sanctum');
Route::get('/pengajuan/{id}/progress', [PengajuanSbtController::class, 'getProgress'])->middleware('auth:sanctum');

Route::apiResource('roles', RoleController::class);
Route::apiResource('users', UserController::class);

Route::get('history_chat', [ChatbotKnowledgeController::class, 'history_chat'])->middleware('auth:sanctum');
Route::post("register", [AuthController::class, "register"]);
Route::post("update-profile",[AuthController::class,'update_profile'])->middleware('auth:sanctum');
