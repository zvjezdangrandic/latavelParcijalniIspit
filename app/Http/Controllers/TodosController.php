<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class TodosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
    */
     public function index()
    {
        $todos = Todo::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
       // $todos = Todo::all();
         return view('home', compact('todos'));

       // return view('home')->with('todos', $todos);
    }

    /**
     */
    public function create()
    {
        return view('add_todo');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'nullable',
        ]);

        $todo = new Todo;
        $todo->title = $request->input('title');
        $todo->description = $request->input('description');

        if ($request->has('completed')) {
            $todo->completed = true;
        }

        $todo->user_id = Auth::user()->id;
        $todo->save();

        return back()->with('success', 'item created successfully!');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory
     */
    public function show($id)
    {
        $todo = Todo::where('id', $id)->where('user_id', Auth::user()->id)->first();
        return view('delete_todo', compact('todo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *      *
     *
     *      */
    public function edit($id)
    {
        $todo = Todo::where('id', $id)->where('user_id', Auth::user()->id)->first();
        return view('edit_todo', compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'nullable',
        ]);

        $todo = Todo::find($id);
        $todo->title = $request->input('title');
        $todo->description = $request->input('description');

        if ($request->has('completed')) {
            $todo->completed = true;
        }else {
            $todo->completed = false;
        }

        $todo->save();

        return back()->with('success', 'item updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $todo = Todo::where('id', $id)->where('user_id', Auth::user()->id)->first();
        $todo->delete();
        return redirect()->route('todo.index')->with('success', 'Item deleted successfully!');
    }

}
