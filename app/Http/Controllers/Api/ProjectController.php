<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    
    public function createProject(Request $request)
    {
        //validation
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'duration' => 'required'
        ]);
        //student_id create data
        $student_id = Auth()->user()->id;
        $project = new Project();
        $project -> student_id = $student_id;
        $project->name = $request->name;
        $project->description = $request->description;
        $project->duration = $request->duration;
        $project->save();
        //send response
        return response()->json([
            'status' => 1,
            'message' => 'Project has been created'
        ]);

    }

    public function listProject()
    {
        $student_id = Auth()->user()->id;
        $projects = Project::where('student_id',$student_id)->get();
        //send response
        return response()->json([
            'status' => 1,
            'message' => 'List Projects',
            'data'=>$projects
        ]);
    }

    public function singleProject($id)
    {
        if(Project::where('id',$id)->exists())
        {
            $details = Project::find($id);
            return response()->json([
                'status' => 1,
                'message' => 'Project Detail',
                'data'=>$details
            ]);
        }
        else{
            return response()->json([
                'status' => 0,
                'message' => 'Project not found',
            ]);
        }
    }

    public function deleteProject($id)
    {
        $student_id = Auth()->user()->id;
        if(Project::where([
            "id"=>$id,
            "student_id"=>$student_id
        ])->exists()){
            $project = Project::where([
                "id"=>$id,
                "student_id"=>$student_id
            ])->first();
            $project->delete();
            return response()->json([
                'status' => 1,
                'message' => 'Project has been deleted successfully',
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'message' => 'Project not found',
            ]);
        }
    }
}
