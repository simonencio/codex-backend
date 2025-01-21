<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Episodio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UploadController extends Controller
{
    public function indexFilm(Request $request)
    {
        // Authorization checks
        if (Gate::allows('creare')) {
            if (Gate::allows('Amministratore')) {
                // Proceed with the file upload logic
                $rit = array();

                // Ensure the film ID is provided in the request
                $filmId = $request->input('idFilm'); // Changed to 'idFilm'
                if (empty($filmId)) {
                    $rit["data"] = false;
                    $rit["message"] = "Film ID is required.";
                    return json_encode($rit);
                }

                // Define the base path for storage
                $basePath2 = public_path('storage/Film/V1/');
                $basePath = storage_path('app/public/Film/V1/');

                // Create a new directory for the film ID
                $filmPath = $basePath . 'ID_' . $this->formatId($filmId);
                if (!file_exists($filmPath)) {
                    mkdir($filmPath, 0755, true);
                }

                // Check for the video file upload
                if ($request->hasFile('videoFile')) {
                    $videoFile = $request->file('videoFile');
                    // Move the uploaded video file to the new directory and rename it to filmato.mp4
                    $videoFile->move($filmPath, 'filmato.mp4');

                    $rit["message"] = $basePath . " - " . $basePath2 . " - Trailer OK";

                    // Check for the film.mp4 file upload
                    if ($request->hasFile('film')) {
                        $filmFile = $request->file('film');
                        // Move the uploaded film file to the new directory and rename it to film.mp4
                        $filmFile->move($filmPath, 'film.mp4');

                        $rit["message"] .= " - Film OK";

                        // Check for the poster image upload
                        if ($request->hasFile('locandina')) {
                            $posterFile = $request->file('locandina');
                            // Move the uploaded poster file to the same directory and rename it to locandina.jpg
                            $posterFile->move($filmPath, 'locandina.jpg');

                            $rit["data"] = true; // Indicate success for film upload
                            $rit["message"] .= " - Locandina OK";
                        } else {
                            $rit["message"] = "No locandina file uploaded.";
                        }
                    } else {
                        $rit["message"] = "No film file uploaded.";
                    }
                } else {
                    $rit["data"] = false;
                    $rit["message"] = "No video file uploaded.";
                }




                return json_encode($rit);
            } else {
                abort(403, "PE_0001"); // User is not an administrator
            }
        } else {
            abort(403, "PE_0002"); // User does not have permission to create
        }
    }
    public function indexSerieTv(Request $request)
    {
        // Authorization checks
        if (Gate::allows('creare')) {
            if (Gate::allows('Amministratore')) {
                // Initialize the response array
                $rit = array();

                // Ensure the series ID is provided in the request
                $serieTvId = $request->input('idSerieTv'); // Changed to 'idSerieTv'
                if (empty($serieTvId)) {
                    $rit["data"] = false;
                    $rit["message"] = "Series ID is required.";
                    return json_encode($rit);
                }

                // Define the base path for storage
                $basePath = storage_path('app/public/SerieTv/V1/');

                // Create a new directory for the series ID
                $serieTvPath = $basePath . 'ID_' . $this->formatId($serieTvId);
                if (!file_exists($serieTvPath)) {
                    mkdir($serieTvPath, 0755, true); // Create the directory if it doesn't exist
                }

                // Create an empty directory in the Episodi folder with the same ID
                $episodiBasePath = public_path('storage/Episodi/V1/');
                $episodiPath = $episodiBasePath . 'ID_' . $this->formatId($serieTvId);
                if (!file_exists($episodiPath)) {
                    mkdir($episodiPath, 0755, true); // Create the directory if it doesn't exist
                }

                // Check for the video file upload
                if ($request->hasFile('videoFile')) {
                    $videoFile = $request->file('videoFile');
                    // Move the uploaded video file to the new directory and rename it to filmato.mp4
                    $videoFile->move($serieTvPath, 'filmato.mp4');
                    if ($request->hasFile('locandina')) {
                        $posterFile = $request->file('locandina');
                        // Move the uploaded poster file to the same directory and rename it to locandina.jpg
                        $posterFile->move($serieTvPath, 'locandina.jpg');
                        $rit["data"] = true;
                    } else {
                        $rit["message"] = "No locandina file uploaded.";
                    }
                } else {
                    $rit["data"] = false;
                    $rit["message"] = "No video file uploaded.";
                }

                // Check for the poster image upload


                return json_encode($rit);
            } else {
                abort(403, "PE_0001"); // User is not an administrator
            }
        } else {
            abort(403, "PE_0002"); // User does not have permission to create
        }
    }
    public function indexEpisodi(Request $request)
    {
        // Authorization checks
        if (Gate::allows('creare')) {
            if (Gate::allows('Amministratore')) {
                // Initialize the response array
                $rit = array();

                // Ensure the episode ID and series ID are provided in the request
                $episodioId = $request->input('idEpisodio');
                $idSerieTv = $request->input('idSerieTv'); // Get the series ID from the request
                if (empty($episodioId) || empty($idSerieTv)) {
                    $rit["data"] = false;
                    $rit["message"] = "Episode ID and Series ID are required.";
                    return response()->json($rit);
                }

                // Define the base path for storage
                $basePath = storage_path('app/public/Episodi/V1/');

                // Create a directory for the series ID
                $seriePath = $basePath . 'ID_' . $this->formatId($idSerieTv);
                if (!file_exists($seriePath)) {
                    mkdir($seriePath, 0755, true);
                }

                // Check for the video file upload
                if ($request->hasFile('videoFile')) {
                    $videoFile = $request->file('videoFile');

                    // Move the uploaded video file to the series directory with the original name
                    $videoFile->move($seriePath, $videoFile->getClientOriginalName());
                    $rit["data"] = true;
                    $rit["message"] = "File uploaded successfully.";
                } else {
                    $rit["data"] = false;
                    $rit["message"] = "No video file uploaded.";
                }

                return response()->json($rit);
            } else {
                abort(403, "PE_0001"); // User is not an administrator
            }
        } else {
            abort(403, "PE_0002"); // User does not have permission to create
        }
    }

    // Helper function to format the SerieTv ID
    private function formatId($id)
    {
        if ($id < 10) {
            return '0000' . $id;
        } elseif ($id < 100) {
            return '000' . $id;
        } elseif ($id < 1000) {
            return '00' . $id;
        } elseif ($id < 10000) {
            return '0' . $id;
        } else {
            return (string)$id;
        }
    }
}
