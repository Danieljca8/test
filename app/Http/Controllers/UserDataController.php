<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Carbon;

class UserDataController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://test.drogueriahofmann.cl/']);
    }

    public function index()
    {
        try {
            // Realizar la solicitud GET a la API para obtener la lista de usuarios
            $response = $this->client->get('usuarios/ListTableUsers');
            $users = json_decode($response->getBody()->getContents(), true);

            // Obtener opciones para el campo 'Code' desde la API GetUsers
            $responseCodes = $this->client->get('usuarios/GetUsers');
            $codes = json_decode($responseCodes->getBody()->getContents(), true);

            // Formatear datos de 'GetUsers' para usar en el select
            $codeOptions = [];
            foreach ($codes as $code) {
                $codeOptions[$code['code']] = $code['code'] . ' - ' . $code['name'];
            }

            // Devolver la vista con los datos para mostrar en la tabla y opciones del select
            return view('users.index', compact('users', 'codeOptions'));

        } catch (\Exception $e) {
            // Manejar errores según sea necesario
            return view('error')->with('message', 'Error al obtener los datos de la API.');
        }
    }
    public function updateUser(Request $request)
    {
        try {
            // Validar los datos recibidos del formulario
            $request->validate([
                'id' => 'required|integer',
                'code' => 'required|string',
                'amount' => 'required|integer',
                'date' => 'required|date',
            ]);

            // Obtener los datos del formulario
            $id = $request->input('id');
            $code = $request->input('code');
            $amount = $request->input('amount');
            $date = Carbon::parse($request->input('date'))->format('Y-m-d\TH:i:s.v\Z'); // Formatear la fecha

            // Objeto JSON a enviar a la API SendUser
            $userData = [
                "id" => (int) $id,
                "code" => $code,
                "amount" => (int) $amount,
                "date" => $date,
                "github" => 'https://github.com/danieljca88'
            ];

            // Convertir el array a JSON
            $jsonUserData = json_encode($userData);

            // Imprimir el JSON para inspección
            echo $jsonUserData;

            // Enviar datos a la API SendUser en formato JSON utilizando Guzzle
            $response = $this->client->post('usuarios/SendUser', [
                'json' => json_decode($jsonUserData, true) // Convertir JSON de nuevo a array asociativo
            ]);
            // Manejar la respuesta de la API según sea necesario
            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {
                // Devolver una respuesta JSON si es necesario
                return response()->json(['mensaje' => 'Status '.$statusCode.' Datos enviados exitosamente.'], 200);
            } else {
                return response()->json(['error' => 'Error al enviar los datos a la API.'], 500);
            }

        } catch (\Exception $e) {
            // Manejar errores de excepción
            return response()->json(['error' => 'Error al enviar los datos: ' . $e->getMessage()], 500);
        }
    }

}
