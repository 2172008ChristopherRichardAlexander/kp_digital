<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;

use App\Batch;
use App\Http\Requests;
use App\Http\Resources\Batch as BatchResource;
use Carbon\Carbon;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($jenis)
    {
        $matchThese = ['jenis_batch' => $jenis];
        // Get Batch
        $batch = Batch::where($matchThese)->orderBy('id_batch', 'desc')->get();
        // Return collection of Batch as a resource
        return BatchResource::collection($batch);
    }

    public function aktif($jenis)
    {
        $matchThese = ['jenis_batch' => $jenis, 'status_batch' => 1];
        // Get Batch
        $batch = Batch::where($matchThese)->get();
        // Return collection of Batch as a resource
        return BatchResource::collection($batch);
    }

    public function belumAktif($jenis)
    {
        $matchThese = ['jenis_batch' => $jenis, 'status_batch' => 2];
        // Get Batch
        $batch = Batch::where($matchThese)->get();
        // Return collection of Batch as a resource
        return BatchResource::collection($batch);
    }

    public function newest($jenis)
    {
        $matchThese = ['status_batch' => 1, 'jenis_batch' => $jenis];
        // Get Last Batch
        $batch = Batch::where($matchThese)->orderBy('id_batch', 'desc')->first();
        // Return collection of Batch as a resource
        return $batch;
    }

    public function latest($jenis)
    {
        $matchTheseOne = ['jenis_batch' => $jenis];
        $status_batch = [0, 1];
        // $matchTheseTwo = ['status_batch' => 0, 'status_batch' => 1];
        // Get Lastest Batch
        // $batch = Batch::where($matchTheseOne)->orWhere($matchTheseTwo)->orderBy('id_batch', 'desc')->first();
        $batch = Batch::where($matchTheseOne)->wherein('status_batch', $status_batch)->orderBy('id_batch', 'desc')->first();
        // Return collection of Batch as a resource
        return $batch;
    }

    public function pengajuan(Request $request)
    {
        $matchThese = ['jenis_batch' => $request->input('jenis_batch'), 'status_batch' => 1];
        $batch = Batch::where($matchThese)->orderBy('id_batch', 'desc')->first();
        if (!$batch) {
            $matchThese = ['batch.jenis_batch' => $request->input('jenis_batch'), 'semester.status_semester' => 1];
            $batch = Batch::select('batch.*')->join('semester', 'semester.id_semester', '=', 'batch.id_semester')->where($matchThese)->orderBy('id_batch', 'desc')->first();
        }
        return $batch;
    }

    // ! LIST BATCH
    // ? Jenis, Status,
    public function listBatch(Request $request)
    {
        $matchThese = ['jenis_batch' => $request->input('jenis_batch')];
        if ($request->input('status_batch')) {
            $status_batch = explode(",", $request->input('status_batch'));
            $batch = Batch::where($matchThese)->whereIn('status_batch', $status_batch)->orderBy('id_batch', 'desc')->get();
        } else {
            $batch = Batch::where($matchThese)->orderBy('id_batch', 'desc')->get();
        }
        return BatchResource::collection($batch);
    }



    // ! LIST BATCH SEMESTER
    // ? Jenis, Status, Semester
    public function listBatchSemester(Request $request)
    {
        $matchThese = ['jenis_batch' => $request->input('jenis_batch'), 'id_semester' => $request->input('id_semester')];
        if ($request->input('status_batch')) {
            $status_batch = explode(",", $request->input('status_batch'));
            $batch = Batch::where($matchThese)->whereIn('status_batch', $status_batch)->orderBy('id_batch', 'desc')->get();
        } else {
            $batch = Batch::where($matchThese)->orderBy('id_batch', 'desc')->get();
        }
        return BatchResource::collection($batch);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Menentukan Perintah Insert("Post") atau Update("Put")
        $batch = $request->isMethod('put') ? Batch::find($request->id_batch) : new Batch;

        $batch->nama_batch = $request->input('nama_batch');
        $batch->tanggal_mulai_batch = $request->input('tanggal_mulai_batch');
        $batch->tanggal_selesai_batch = $request->input('tanggal_selesai_batch');
        $batch->jenis_batch = $request->input('jenis_batch');
        $batch->minimal_bimbingan = $request->input('minimal_bimbingan');
        $batch->status_batch = $request->input('status_batch');
        $batch->id_jurusan = $request->input('id_jurusan');
        $batch->id_semester = $request->input('id_semester');

        $batch->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get Batch
        $batch = Batch::where('id_batch', $id)->first();

        // Return single Batch as a Resource
        return new BatchResource($batch);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function edit(Batch $batch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Batch $batch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Batch $batch)
    {
        //
    }

    public function aktifasiBatch()
    {
        // ! Non-Aktifkan Batch yang aktif (1) ketika tanggal selesai < tanggal sekarang (jika bertepatan dengan hari ini tidak di Non-Aktifkan)
        Batch::where("status_batch", 1)->whereDate('tanggal_selesai_batch', '<', Carbon::now()->startOfDay())->update(["status_batch" => 0]);
        // ! Aktifkan Batch yang belum aktif (2) ketika tanggal mulai <= tanggal sekarang (jika bertepatan dengan hari ini di Aktifkan)
        Batch::where("status_batch", 2)->whereDate('tanggal_mulai_batch', '<=', Carbon::now()->startOfDay())->update(["status_batch" => 1]);
        return ['message' => 'Batch berhasil di check'];
    }
}
