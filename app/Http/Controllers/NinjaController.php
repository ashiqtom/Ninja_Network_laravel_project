<?php

namespace App\Http\Controllers;

use App\Models\Dojo;
use Illuminate\Http\Request;
use App\Models\Ninja;

class NinjaController extends Controller
{
    public function index() {
      // route --> /ninjas/
      // $ninjas = Ninja::all();
      $ninjas = Ninja::with('dojo')->orderBy('created_at', 'desc')->paginate(10);

      return view('ninjas.index', ['ninjas' => $ninjas]);
    }

    public function show(Ninja $ninja) {
      // route --> /ninjas/{id}
      $ninja->load('dojo');

      return view('ninjas.show', ['ninja' => $ninja]);
    }

    public function create() {
      // route --> /ninjas/create
      $dojos = Dojo::all();

      return view('ninjas.create', ['dojos' => $dojos]);
    }

    public function store() {
      // --> /ninjas/ (POST)
      $validateted = request()->validate([
        'name' => 'required|string|max:255',
        'skill' => 'required|integer|min:0|max:100',
        'bio' => 'required|string|min:20|max:1000',
        'dojo_id' => 'required|exists:dojos,id'
      ]);  

      // create a ninja record in the database
      Ninja::create($validateted);

      // redirect to the index page
      return redirect()->route('ninjas.index')->with('success', 'Ninja created successfully');  
    }

    public function destroy(Ninja $ninja) {
      // --> /ninjas/{id} (DELETE)
      $ninja->delete();

      return redirect()->route('ninjas.index')->with('success', 'Ninja deleted successfully');  
    }

    // edit() and update() for edit view and update requests
    // we won't be using these routes
}
