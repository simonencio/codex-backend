<?php

namespace App\Http\Resources\v1\Collection;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class SerieTvCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        $tmp = parent::toArray($request);
        $tmp = array_map(array($this, 'getCampi'), $tmp);
        return $tmp;
    }

    protected function getCampi($item)
    {
        // Use the formatId function to get the formatted ID
        $formattedId = $this->formatId($item["idSerieTv"]);
        $folderName = "ID" . $formattedId; // Construct the folder name using the formatted ID
        $versionFolder = "V1";

        // Retrieve the files for the series
        $filmati = Storage::files("SerieTv/Filmati/$versionFolder/$folderName"); // Path for video files
        $locandine = Storage::files("SerieTv/Locandine/$versionFolder/$folderName"); // Path for poster images

        return [
            'idSerieTv' => $item["idSerieTv"],
            'idCategoria' => $item["idCategoria"],
            'nome' => $item["nome"],
            'descrizione' => $item["descrizione"],
            'totaleStagioni' => $item["totaleStagioni"],
            'NumeroEpisodi' => $item["NumeroEpisodi"],
            'regista' => $item["regista"],
            'attori' => $item["attori"],
            'annoInizio' => $item["annoInizio"],
            'annoFine' => $item["annoFine"],
            'Filmati' => $filmati, // Include the video files
            'Locandine' => $locandine, // Include the poster images
        ];
    }

    // Function to format the ID
    protected function formatId($id)
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
