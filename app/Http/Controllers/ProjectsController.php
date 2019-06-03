<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
use App\Project;
use App\User;
use Illuminate\Support\Facades\Redirect;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::orderBy('updated_at', 'desc')->paginate(12);
        return view('projects.projects')->with('projects', $projects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:75',
            'description' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return redirect('projects/create')
            ->withErrors($validator)
            ->withInput();
        }

        $project = new Project;
        $project->title = $request->input('title');
        $project->description = $request->input('description');
        $project->save();
        $user = User::find('id');
        $project->users()->attach($user);
    


        return redirect('/projects')->with('success', 'je project is al gemaakt');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::find($id);
        return view('projects.view')->with('project', $project);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::find($id);
        return view('projects.edit')->with('project', $project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'title' => 'required',
            'description' => 'required'
        ]);

        $project = Project::find($id);
        $project->title = $request->input('title');
        $project->description = $request->input('description');
        $project->save(); 
        
        return redirect('/projects')->with('success', 'Project is bijgewerkt');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        
        $project->delete();
        
        return redirect('/projects')->with('success', 'Project is verwijderd');
    }

    public function addStudentsToProject($id)
    {   
        $project = Project::find($id);
        $students = User::all()->where('role', '=', 'S');
        return view('students.students', compact('project', 'students'));
    }

    public function addStudent(Project $project, User $student){

        //check of de student al gekoppeld is aan het project. 
        //als deze al gekoppeld is hoef ik deze niet nog een keer toe te voegen
        //is deze student nog niet toegevoegd wil ik deze wel koppelen.

        if(!$project->users->contains($student->id)){
            $project->users()->attach($student->id);
        } else {
            return Redirect::back()->withErrors('De student ' . $student->name . ' is al gekoppeld aan het project ' . $project->title);
        }
        return redirect()->back();
    }

    public function studentProjectDelete($id)
    {
        $student = User::find($id);
        $student->projects()->detach($project_id);
    }
}