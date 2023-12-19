<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hitrov\OCI\Signer;
use App\Http\Controllers\Controller;
use Nette\Utils\Random;
use Illuminate\Support\Facades\Storage;

class LanguageTranslationController extends Controller
{
    public function LanguageTranslation(Request $request)
    {
        
        $genKey = \Ramsey\Uuid\Uuid::uuid4()->toString();

        $text = $request->input('text');
        $languageCode = $request->input('languageCode');
        $targetLanguageCode = $request->input('targetLanguageCode');
        $newText = str_replace('"', "'", $text);
        

        $signer = new Signer(
            'ocid1.tenancy.oc1..aaaaaaaas3pyu6kxrchyx2tbljml3fzsltiig7e3hpujk5hvqjl4x46qzata',
            'ocid1.user.oc1..aaaaaaaapurduq4fvmy576w6euo5vc5d6pcxcbciq7jgfg7seqdf23zv7h3a',
            '8d:a9:cb:d8:22:45:51:9d:ab:7c:7c:92:8d:a7:e3:94',
            Storage::path('oci_key.pem')
        );
        $curl = curl_init();
        $url = 'https://language.aiservice.eu-frankfurt-1.oci.oraclecloud.com/20221001/actions/batchLanguageTranslation';
        $method = 'POST';

        $body = '{
           "documents":[
              {
                 "key":"'.$genKey.'",
                 "text":"'.$newText.'",
                 "languageCode":"'.$languageCode.'",
                 "targetLanguageCode":"'.$targetLanguageCode.'"
              }
           ]

        }';

        $headers = $signer->getHeaders($url, $method, $body, 'application/json');


        $curlOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ];

        if ($body) {
            $curlOptions[CURLOPT_POSTFIELDS] = $body;
        }

        curl_setopt_array($curl, $curlOptions);
        $response = curl_exec($curl);
        $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($httpStatus != 200) {
            return response()->json([
                'success' => false,
                'request_id' => $genKey,
            ], 500);
        }

        $res = json_decode($response, true);

        return response()->json([
            'success' => true,
            'request_id' => $genKey,
            'response' => [
                'translatedText' => $res['documents'][0]['translatedText']
            ]
        ], 200);
    }
}
