<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\HttpResponse;

class DocumentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Upload resume or portfolio to local file storage and save to database
     * Allowed file type: pdf|doc|docx|txt|html|rtf
     * 
     * @param Illuminate\Foundation\Http\FormRequest
     * @return App\Models\User 
     */
    public function save(\App\Http\Requests\ValidFile $request)
    {
        /**
         * Set user into the variable
         */
        $user = auth()->user();
        
        /**
         * Get file details from request
         */
        $file = $request->file('file');

        /**
         * Upload file
         */
        $path = upload_document($file);

        /**
         * Get filename
         */
        $filename = basename($path);

        /** 
         * Save document via Laravel Eloquent Relation
         */
        $user->document()->updateOrCreate(
            ['user_id' => $user->id], 
            ['file' => $filename]
        );

        /**
         * Get actual file
         */ 
        $document = get_document($path);

        /**
         * Parse file into JSON using third party API
         */
        $parsed_document = parse_document($document, $filename);

        /**
         * Return an error if parsed document returned with an error
         */
        if($parsed_document instanceof HttpResponse)
            return response('Unable to parse selected document.', 404);

        /**
         * Get profile, technologies, employments history, projects from parsed JSON resume/portfolio
         */ 
        $profile = get_profile_details($parsed_document);
        $technologies = get_technology_details($parsed_document);
        $employments = get_employment_details($parsed_document);
        $projects = get_project_details($parsed_document);

        /**
         * Save profile, technologies, employments history, projects via Laravel Eloquent Relation
         */
        if($profile)
            $user->profile()->updateOrCreate(['user_id' => $user->id], $profile);
            
        if($technologies)
            $user->technologies()->createMany($technologies);
            
        if($employments)
            $user->employments()->createMany($employments);
            
        if($projects)
            $user->projects()->createMany($projects);

        /**
         * Return User Object along with it's related models
         */
        return response($user->load(['document', 'profile', 'technologies', 'employments', 'projects']));
    }
}
