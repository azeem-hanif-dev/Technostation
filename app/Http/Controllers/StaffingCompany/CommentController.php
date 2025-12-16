<?php

namespace App\Http\Controllers\StaffingCompany;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\StaffingCompany\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::all()->sortByDesc('created_at');

        return view('StaffingCompany.Comments.index')->with('comments',$comments);
    }

    public function create()
    {
        $translations = __('Staffing_Company/common');
        return view('StaffingCompany.Comments.create',compact('translations'));
    }

    public function store(CommentRequest $request)
    {
        Comment::create(['comment' => $request->comment]);
        return response()->json([
            'message'=> __('Staffing_Company/Comment/crud.create_comment'),
            'status'=>'200',
        ]);

        // $this->sendResponse([],'Comment Created Successfully',200);
    }

    public function show(Comment $comment)
    {
        return response()->json($comment);
    }

    public function view(Comment $comment)
    {
        $translations = __('Staffing_Company/common');
        return view('StaffingCompany.Comments.show',compact('comment','translations'));
    }

    public function edit(Comment $comment)
    {
        $translations = __('Staffing_Company/common');
        return view('StaffingCompany.Comments.update',compact('comment','translations'));
    }

    public function update(Request $request, Comment $comment)
    {
        $comment->update(['comment' => $request->name]);
          return response()->json([
            'message'=> __('Staffing_Company/Comment/crud.update_comment'),
            'status'=>'200',
        ]);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return redirect()->back()->with('message', 'Comment Deleted Successfully!');
    }
}
