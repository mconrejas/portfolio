<?php

/**
 * Upload document to storage
 * 
 * @param $file
 * @return Illuminate\Support\Facades\Storage $path
 */
if (!function_exists('upload_document')) {
    function upload_document($file)
    {
        $disk = Illuminate\Support\Facades\Storage::disk('public');
        return $disk->put('/uploads', $file);
    }
}

/**
 * Upload media to storage
 * 
 * @param $file
 * @return Illuminate\Support\Facades\Storage $path
 */
if (!function_exists('upload_media')) {
    function upload_media($file)
    {
        $disk = Illuminate\Support\Facades\Storage::disk('public');
        return $disk->put('/medias', $file);
    }
}

/**
 * Get uploaded document from storage
 * 
 * @param $file
 * @return Illuminate\Support\Facades\Storage $file
 */
if (!function_exists('get_document')) {
    function get_document($file)
    {
        $disk = Illuminate\Support\Facades\Storage::disk('public');
        return $disk->get("$file");
    }
}

/**
 * Parse document using third party document parsing API via custom library
 * 
 * @param $document
 * @param $filename
 * @return App\Libraries\Parser json|ApiException
 */
if (!function_exists('parse_document')) {
    function parse_document($document, $filename)
    {
        $parser = new App\Libraries\Parser();
        return $parser->parseBinary($document, $filename);
    }
}

/**
 * Retrieve profile details from parsed document
 * 
 * @param $data
 * @return Array
 */
if (!function_exists('get_profile_details')) {
    function get_profile_details($data)
    {
        $country = $data->Address[0]->CountryCode->IsoAlpha2;
        $state = $data->Address[0]->State;

        return [
            'first_name' => $data->Name->FirstName,
            'last_name' => $data->Name->LastName,
            'address' => $data->Address[0]->Street,
            'city' => $data->Address[0]->City,
            'state' => str_replace("$country-", "", $state),
            'country' => $data->Address[0]->CountryCode->IsoAlpha2,
            'zip' => $data->Address[0]->ZipCode,
            'phone' => count($data->PhoneNumber) > 0 ? $data->PhoneNumber[0]->Number : '',
            'job_title' => $data->JobProfile,
            'objectives' => $data->Objectives,
        ];
    }
}

/**
 * Retrieve technologies from parsed document
 * 
 * @param $data
 * @return Array
 */
if (!function_exists('get_technology_details')) {
    function get_technology_details($data)
    {
        $technologies = [];

        foreach($data->SegregatedSkill as $key => $tech) {
            array_push($technologies, [
                'name' => $tech->Skill,
            ]);
        }

        return $technologies;
    }
}

/**
 * Retrieve employment history from parsed document
 * 
 * @param $data
 * @return Array
 */
if (!function_exists('get_employment_details')) {
    function get_employment_details($data)
    {
        $carbon = new Carbon\Carbon();
        $experiences = [];

        foreach($data->SegregatedExperience as $key => $experience) {
            $start = $carbon::createFromFormat('d/m/Y',$experience->StartDate);
            $end = $carbon::createFromFormat('d/m/Y',$experience->EndDate);

            array_push($experiences, [
                'company' => $experience->Employer->EmployerName,
                'roles' => [$experience->JobProfile->Title],
                'start' => $start,
                'end' => $end,
                'current' => filter_var($experience->IsCurrentEmployer, FILTER_VALIDATE_BOOLEAN),
                'description' => $experience->JobDescription,
            ]);
        }

        return $experiences;
    }
}

/**
 * Retrieve project details from parsed document
 * 
 * @param $data
 * @return Array
 */
if (!function_exists('get_project_details')) {
    function get_project_details($data)
    {
        $projects = [];

        foreach($data->SegregatedExperience as $key => $experience) {
            foreach($experience->Projects as $k => $project) {
                if($project->ProjectName) {
                    array_push($projects, [
                        'title' => $project->ProjectName
                    ]);
                }
            }
        }

        return $projects;
    }
}

/**
 * Convert string to date
 * 
 * @param $string
 * @return String
 */
if (!function_exists('string_to_date')) {
    function string_to_date($string = '')
    {
        if(!$string)
            return $string;

        $date = '';
        $string = substr($string, 3, strlen($string));
        $string = explode('/', $string);

        switch ($string[0]) {
            case '01':
                    $date .= 'January ';
                break;
            case '02':
                    $date .= 'February ';
                break;
            case '03':
                    $date .= 'March ';
                break;
            case '04':
                    $date .= 'April ';
                break;
            case '05':
                    $date .= 'May ';
                break;
            case '06':
                    $date .= 'June ';
                break;
            case '07':
                    $date .= 'July ';
                break;
            case '08':
                    $date .= 'August ';
                break;
            case '09':
                    $date .= 'September ';
                break;
            case '10':
                    $date .= 'October ';
                break;
            case '11':
                    $date .= 'November ';
                break;
            case '12':
                    $date .= 'December ';
                break;
            
            default:
                $date .= '';
                break;
        }

        return $date . $string[1];
    }
}




