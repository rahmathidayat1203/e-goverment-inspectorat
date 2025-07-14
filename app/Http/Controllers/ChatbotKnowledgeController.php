<?php

namespace App\Http\Controllers;

use App\Models\chatbot_knowledge;
use App\Models\riwayat_chat;
use Exception;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ChatbotKnowledgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        try {
            $perpage = $request->get('per_page', 10);
            $chatbot_knowledges = chatbot_knowledge::paginate($perpage);

            return response()->json([
                'success' => true,
                'massage' => 'data fetch successfully',
                'data' => $chatbot_knowledges
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'massage' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'pertanyaan' => 'required',
                'jawaban' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'data' => $validator->errors()
                ], 403);
            }
            $input = $request->all();
            $input['created_by'] = 1;

            chatbot_knowledge::create($input);
            return response()->json([
                'success' => false,
                'message' => 'created successfully',
                'data' => null,
            ]);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json([
                'success' => false,
                'massage' => $e->getMessage(),
                'data' => null,
            ], 500);

            Log::info($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $chatbot_knowledge = chatbot_knowledge::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'fetch product by id succesfully',
                'data' => $chatbot_knowledge,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'massage' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(chatbot_knowledge $chatbot_knowledge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'pertanyaan' => 'required',
                'jawaban' => 'required',

            ]);
            $input = $request->all();
            $input['created_by'] = 1;
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'data' => $validator->errors()
                ], 403);
            }
            $input = $request->all();
            $input['created_by'] = 1;
            $chatbot_knowledge = chatbot_knowledge::findOrFail($id);
            $chatbot_knowledge->update($input);
            return response()->json([
                'success' => true,
                'message' => 'product update succesfully',
                'data' => $chatbot_knowledge,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'massage' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $chatbot_knowledge  = chatbot_knowledge::findOrFail($id);
            $chatbot_knowledge->delete();
            return response()->json([
                'success' => true,
                'message' => 'product deleted succesfully by id' . $chatbot_knowledge->id,
                'data' => $chatbot_knowledge,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'massage' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }



    /**
     * Handle chatbot query
     */
    private function jaccardSimilarity($str1, $str2)
    {
        // Preprocess strings
        $str1 = $this->preprocessText($str1);
        $str2 = $this->preprocessText($str2);

        // Tokenize strings into words
        $set1 = array_unique(explode(' ', Str::lower($str1)));
        $set2 = array_unique(explode(' ', Str::lower($str2)));

        // Remove empty elements
        $set1 = array_filter($set1);
        $set2 = array_filter($set2);

        // Calculate intersection and union
        $intersection = array_intersect($set1, $set2);
        $union = array_unique(array_merge($set1, $set2));

        // Avoid division by zero
        if (count($union) == 0) {
            return 0;
        }

        return count($intersection) / count($union);
    }

    /**
     * Text preprocessing
     */
    private function preprocessText($text)
    {
        // Remove punctuation
        $text = preg_replace("/[^\w\s]/", "", $text);

        // Convert to lowercase
        $text = Str::lower($text);

        return $text;
    }

    /**
     * Handle chatbot query
     */
    // public function ask(Request $request)
    // {
    //     try {
    //         // Validasi input
    //         $validated = $request->validate([
    //             'question' => 'required|string|max:500',
    //             'show_all' => 'sometimes|boolean'
    //         ]);

    //         $question = $validated['question'];
    //         $showAll = $validated['show_all'] ?? false;

    //         // Ambil hanya kolom yang diperlukan
    //         $knowledge = chatbot_knowledge::select(['pertanyaan', 'jawaban'])
    //             ->get()
    //             ->toArray();

    //         Log::info('Sending to Flask:', [
    //             'question' => $question,
    //             'knowledge_base_count' => count($knowledge),
    //         ]);

    //         // Kirim request dengan timeout
    //         $response = Http::withHeaders([
    //             'Accept' => 'application/json',
    //             'Content-Type' => 'application/json',
    //         ])
    //             ->timeout(30)  // timeout 30 detik
    //             ->retry(3, 100) // retry 3 kali dengan jeda 100ms
    //             ->post('http://127.0.0.1:5000/calculate-similarity', [
    //                 'question' => $question,
    //                 'knowledge_base' => $knowledge
    //             ]);

    //         // Handle error response
    //         if ($response->failed()) {
    //             throw new \Exception('Error from similarity service: ' . $response->body());
    //         }

    //         $data = $response->json();

    //         if (!isset($data['status']) || $data['status'] !== 'success') {
    //             throw new \Exception('Invalid response format');
    //         }

    //         $threshold = $data['threshold'] ?? 0.7;  // default threshold
    //         $bestMatch = null;
    //         $allMatches = [];

    //         // Cek similarity score
    //         if (isset($data['best_match']) && ($data['best_match']['similarity_score'] ?? 0) >= $threshold) {
    //             $bestMatch = $data['best_match'];

    //             // Simpan riwayat hanya jika memenuhi threshold
    //             riwayat_chat::create([
    //                 'id_user' => auth()->id() ?? 1,  // Ambil ID user yang login
    //                 'pertanyaan' => $question,
    //                 'jawaban' => $bestMatch['jawaban'],
    //                 'similarity_score' => $bestMatch['similarity_score'],
    //                 'created_by' => auth()->id() ?? 1
    //             ]);
    //         }

    //         // Format default answer
    //         $defaultAnswer = [
    //             "jawaban" => "Maaf, saya belum mengetahui jawaban untuk pertanyaan tersebutt                                                  cc    .",
    //             "similarity_score" => 0
    //         ];

    //         return response()->json([
    //             'status' => $bestMatch ? 'success' : 'not_found',
    //             'user_question' => $question,
    //             'best_match' => $bestMatch ?? $defaultAnswer,
    //             'threshold' => $threshold,
    //             'all_matches' => $showAll ? ($data['all_matches'] ?? []) : null,
    //         ]);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Pertanyaan harus diisi dan maksimal 500 karakter'
    //         ], 422);
    //     } catch (\Exception $e) {
    //         Log::error('Error in ask(): ' . $e->getMessage() . "\n" . $e->getTraceAsString());

    //         return response()->json([
    //             'status' => 'error',
    //             'user_question' => $request->question ?? null,
    //             'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan dalam memproses pertanyaan.'
    //         ], 500);
    //     }
    // }

    public function ask(Request $request)
    {
        try {
            // Cek apakah tidak ada input 'question'
            if (!$request->has('question')) {
                $user = $request->user();

                $riwayatSemua = riwayat_chat::where('id_user', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

                if ($riwayatSemua->isNotEmpty()) {
                    $riwayat = $riwayatSemua->first(); // ambil riwayat terbaru sebagai best match

                    $bestMatch = [
                        'pertanyaan' => $riwayat->pertanyaan,
                        'jawaban' => $riwayat->jawaban,
                        'similarity_score' => $riwayat->similarity_score
                    ];

                    $allMatches = $riwayatSemua->map(function ($item) {
                        return [
                            'pertanyaan' => $item->pertanyaan,
                            'jawaban' => $item->jawaban,
                            'similarity_score' => $item->similarity_score
                        ];
                    });
                } else {
                    $bestMatch = [
                        'jawaban' => "Belum ada riwayat pertanyaan.",
                        'similarity_score' => 0
                    ];

                    $allMatches = null;
                }

                return response()->json([
                    'status' => $riwayatSemua->isNotEmpty() ? 'history' : 'not_found',
                    'user_question' => null,
                    'best_match' => $bestMatch,
                    'threshold' => null,
                    'all_matches' => $allMatches
                ]);
            }


            // Validasi input
            $validated = $request->validate([
                'question' => 'required|string|max:500',
                'show_all' => 'sometimes|boolean'
            ]);

            $question = $validated['question'];
            $showAll = $validated['show_all'] ?? false;

            // Ambil data knowledge base
            $knowledge = chatbot_knowledge::select(['pertanyaan', 'jawaban'])
                ->get()
                ->toArray();

            Log::info('Sending to Flask:', [
                'question' => $question,
                'knowledge_base_count' => count($knowledge),
            ]);

            // Kirim ke Flask
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
                ->timeout(30)
                ->retry(3, 100)
                ->post('http://127.0.0.1:5000/calculate-similarity', [
                    'question' => $question,
                    'knowledge_base' => $knowledge
                ]);

            if ($response->failed()) {
                throw new \Exception('Error from similarity service: ' . $response->body());
            }

            $data = $response->json();

            if (!isset($data['status']) || $data['status'] !== 'success') {
                throw new \Exception('Invalid response format');
            }

            $threshold = $data['threshold'] ?? 0.7;
            $bestMatch = null;

            if (isset($data['best_match']) && ($data['best_match']['similarity_score'] ?? 0) >= $threshold) {
                $bestMatch = $data['best_match'];

                riwayat_chat::create([
                    'id_user' => auth()->id() ?? 1,
                    'pertanyaan' => $question,
                    'jawaban' => $bestMatch['jawaban'],
                    'similarity_score' => $bestMatch['similarity_score'],
                    'created_by' => auth()->id() ?? 1
                ]);
            }

            $defaultAnswer = [
                "jawaban" => "Maaf, saya belum mengetahui jawaban untuk pertanyaan tersebut.",
                "similarity_score" => 0
            ];

            return response()->json([
                'status' => $bestMatch ? 'success' : 'not_found',
                'user_question' => $question,
                'best_match' => $bestMatch ?? $defaultAnswer,
                'threshold' => $threshold,
                'all_matches' => $showAll ? ($data['all_matches'] ?? []) : null
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'user_question' => null,
                'best_match' => null,
                'threshold' => null,
                'all_matches' => null,
                'message' => 'Pertanyaan harus diisi dan maksimal 500 karakter'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in ask(): ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return response()->json([
                'status' => 'error',
                'user_question' => $request->question ?? null,
                'best_match' => null,
                'threshold' => null,
                'all_matches' => null,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan dalam memproses pertanyaan.'
            ], 500);
        }
    }




    // public function get_riwayat_chat(Request $request){
    //    try {
    //     $user = $request->user();
    //     $riwayat_chat = riwayat_chat::where()->get();

    //    } catch (\Exception $e) {
    //     return response()->json([
    //         'status' => 'error',
    //         'message' => $e->getMessage(),
    //         'data' => null
    //     ], 500);
    //    } 
    // }
    /**
     * Get confidence level based on similarity score
     */
    private function getConfidenceLevel($score)
    {
        if ($score >= 0.8) return 'Very High';
        if ($score >= 0.6) return 'High';
        if ($score >= 0.4) return 'Medium';
        if ($score >= 0.3) return 'Low';
        return 'Very Low';
    }

    public function history_chat(Request $request){
        $user = $request->user();

        try {
            $history_chat = riwayat_chat::where('id_user','=',$user->id)->get();

            return response()->json([
                'success' => true,
                'message' => 'success',
                'data' => $history_chat
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'failed',
                'data' => null
            ]);
        }
    }
}
