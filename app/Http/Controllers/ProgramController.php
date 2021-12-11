<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Edulevel;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $program = Program::all();
        $program = Program::with('edulevel')->Paginate(5);
        // return $program;
        return view('program/index', compact('program'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $edulevels = Edulevel::all();
        return view('program/create', compact('edulevels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'edulevel_id' => 'required'
    ],
    [
        'name.required' => 'Nama Porgram tidak boleh kosong',
        'edulevel_id.required' => 'Jenjang tidak boleh kosong'
    ]);
    
    // cara 1
    // $program = new Program;
    // $program->name = $request->name;
    // $program->edulevel_id = $request->edulevel_id;
    // $program->student_price = $request->student_price;
    // $program->student_max = $request->student_max;
    // $program->info = $request->info;
    // $program->save();

    // cara 2 
    // $program = Program::create([
    //     'name' => $request->name,
    //     'edulevel_id' => $request->edulevel_id,
    //     'student_price' => $request->student_price,
    //     'student_max' => $request->student_max,
    //     'info' => $request->info
    // ]);

    // cara 3 cepat, syarat : nama table dan deklarasinya harus sama
    Program::create($request->all());



     return redirect('program')->with('status', 'Program Berhasil Ditambah');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function show(Program $program)
    {   
        $program = $program->makeHidden(['edulevel_id']);
        // return $program;
        return view('program/show', compact('program'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function edit(Program $program)
    {
        $edulevels = Edulevel::all();
        return view('program.edit', compact('program', 'edulevels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Program $program)
    {
        $request->validate([
            'name' => 'required|min:3',
            'edulevel_id' => 'required'
    ],
    [
        'name.required' => 'Nama Porgram tidak boleh kosong',
        'edulevel_id.required' => 'Jenjang tidak boleh kosong'
    ]);

    // cara 1
    // $program->name = $request->name;
    // $program->edulevel_id = $request->edulevel_id;
    // $program->student_price = $request->student_price;
    // $program->student_max = $request->student_max;
    // $program->info = $request->info;
    // $program->save();

    // cara 2
    Program::where('id', $program->id)
      ->update([    
            'name' => $request->name,
            'edulevel_id' => $request->edulevel_id,
            'student_price' => $request->student_price,
            'student_max' => $request->student_max,
            'info' => $request->info
        ]);

    return redirect('program')->with('status', 'Program Berhasil Di Edit');

        return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function destroy(Program $program)
    {
        $program->delete();
        return redirect('program')->with('status', 'Program Berhasil Di Hapus');
    }
    public function trash()
    {
        $program = Program::onlyTrashed()->get();
        return view('program.trash', compact('program'));
    }
    public function restore($id= null)
    {
        if($id != null)
        {
            $program = Program::onlyTrashed()
                       -> where('id',$id)
                       -> restore();
        } else{
            $program = Program::onlyTrashed()->restore();
        }

        return view('program.trash')->with('status', 'Program Berhasil direstore');
    }
    public function delete()
    {

    }
}
