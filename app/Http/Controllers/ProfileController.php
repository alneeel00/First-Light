<?php

namespace App\Http\Controllers;

use App\Events\PostViewEvent;
use App\Models\Company;
use App\Models\CompanyCategory;
use App\Models\Profile;
use App\Models\Image;
use App\Models\Post;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    public function Download_attachment($employeessname, $filename)
    {
        // return $filename;
        return response()->download(public_path('attachments/Profile/'.$employeessname.'/'.$filename));
    }

    public function create()
    {
    //   return 0;
        return view('Profile.create');
    }

    public function store(Request $request)
    {
        // return $request;
        $this->requestValidate($request);
        // return $request->Upload_Resume;

    //   return  $request->hasFile('Upload_Resume[]');
        foreach($request->File('Upload_Resume') as $file)
        {
            // return $file;
            $name = $file->getClientOriginalName();
            $file->storeAs('attachments/Profile/'.auth()->user()->name, $file->getClientOriginalName(),'upload_attachments');

            // insert in image_table
            $images= new Image();

            $images->filename=$name;
           
            $images->imageable_id = auth()->user()->id;
           
            $images->imageable_type = auth()->user()->name;
            
            $images->save();
           
        }

        // return 3;
        $Profile = Profile::create([
            'user_id' => auth()->user()->id,
            'First_Name' => $request->First_Name,
            'Last_Name' => $request->Last_Name,
            'Street_Address' => $request->Street_Address,
            'Postal' => $request->Postal,
            'Country' => $request->Country,
            'Email' => $request->Email,
            'Phone' => $request->Phone,
            'date' => $request->date,
            'Upload_Resume' => $images->id,
        ]);
        // return $request;
        //logo
     


        if ($Profile) {
            Alert::toast('create Profile!', 'success');
            return redirect()->route('account.index');
        }
        Alert::toast('Post failed to list!', 'warning');
        return redirect()->back();
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);

        event(new PostViewEvent($post));
        $company = $post->company()->first();

        $similarPosts = Post::whereHas('company', function ($query) use ($company) {
            return $query->where('company_category_id', $company->company_category_id);
        })->where('id', '<>', $post->id)->with('company')->take(5)->get();
        return view('post.show')->with([
            'post' => $post,
            'company' => $company,
            'similarJobs' => $similarPosts
        ]);
    }

    public function edit(Post $post)
    {
        return view('post.edit', compact('post'));
    }

    public function update(Request $request, $post)
    {
        $this->requestValidate($request);
        $getPost = Post::findOrFail($post);

        $newPost = $getPost->update($request->all());
        if ($newPost) {
            Alert::toast('Post successfully updated!', 'success');
            return redirect()->route('account.authorSection');
        }
        return redirect()->route('post.index');
    }

    public function destroy(Post $post)
    {
        if ($post->delete()) {
            Alert::toast('Post successfully deleted!', 'success');
            return redirect()->route('account.authorSection');
        }
        return redirect()->back();
    }

    protected function requestValidate($request)
    {
        return $request->validate([
            'First_Name' => 'required|min:3',
            'Last_Name' => 'required',
            'Street_Address' => 'required',
            'Postal' => 'required',
            'Country' => 'required',
            'Email' => 'required|email',
            'Phone' => 'required',
            'date' => 'required',
            'Upload_Resume' => 'required',
        ]);
     
    }
    protected function getFileName($file)
    {
        $fileName = $file->getClientOriginalName();
        $actualFileName = pathinfo($fileName, PATHINFO_FILENAME);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        return $actualFileName . time() . '.' . $fileExtension;
    }
}
